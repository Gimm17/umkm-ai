<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import InboxLayout from '@/Layouts/InboxLayout.vue'
import ChannelBadge from '@/Components/ChannelBadge.vue'
import ChatBubble from '@/Components/ChatBubble.vue'
import AIStatusBadge from '@/Components/AIStatusBadge.vue'
import OrderDetectedBanner from '@/Components/OrderDetectedBanner.vue'
import { useMessages } from '@/composables/useMessages'
import { useAIAgent } from '@/composables/useAIAgent'
import { router } from '@inertiajs/vue3'
import type { Conversation } from '@/types'

const props = defineProps<{
  conversation: Conversation
}>()

const {
  messages,
  sending,
  isAITyping,
  error,
  orders,
  send,
} = useMessages(props.conversation.id)

const { toggling, toggleAI } = useAIAgent()

const replyContent = ref('')
const messagesContainer = ref<HTMLElement | null>(null)

async function handleSendReply() {
  if (!replyContent.value.trim() || sending.value) return

  try {
    await send(replyContent.value)
    replyContent.value = ''
    scrollToBottom()
  } catch (e) {
    console.error('Failed to send reply:', e)
  }
}

async function handleToggleAI() {
  try {
    const updated = await toggleAI(props.conversation)
    props.conversation.ai_enabled = updated.ai_enabled
    props.conversation.status = updated.status
  } catch (e) {
    console.error('Failed to toggle AI:', e)
  }
}

function scrollToBottom() {
  setTimeout(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  }, 100)
}

const filteredOrders = computed(() => {
  return orders.value.filter(o => o.status !== 'cancelled')
})
</script>

<template>
  <Head :title="`${conversation.contact?.name || 'Chat'} - UmkmAI`" />

  <InboxLayout>
    <!-- Chat Header -->
    <div class="bg-gradient-to-r from-[#FF6B6B] to-[#4ECDC4] px-6 py-4 shadow-lg">
      <div class="flex items-center justify-between">
        <!-- Contact Info -->
        <div class="flex items-center gap-4">
          <!-- Back Button (Mobile) -->
          <button
            @click="router.visit(route('inbox.index'))"
            class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/20 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          <!-- Avatar -->
          <div
            :class="[
              'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md',
              conversation.channel === 'whatsapp'
                ? 'bg-[#25D366]'
                : 'bg-gradient-to-br from-purple-500 to-pink-500',
            ]"
          >
            {{ conversation.contact?.name?.charAt(0) || '?' }}
          </div>

          <!-- Name & Channel -->
          <div class="text-white">
            <h2 class="font-bold text-lg">
              {{ conversation.contact?.name || 'Unknown' }}
            </h2>
            <div class="flex items-center gap-2 mt-0.5">
              <ChannelBadge :channel="conversation.channel" size="sm" />
              <span
                v-if="conversation.status === 'needs_human'"
                class="text-xs bg-white/20 px-2 py-0.5 rounded-full"
              >
                ✋ Perlu Dibalas
              </span>
            </div>
          </div>
        </div>

        <!-- AI Toggle -->
        <button
          @click="handleToggleAI"
          :disabled="toggling"
          class="px-4 py-2 rounded-full bg-white/20 hover:bg-white/30 text-white font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <span>{{ conversation.ai_enabled ? '✨' : '🤖' }}</span>
          <span class="hidden sm:inline">{{ conversation.ai_enabled ? 'AI Aktif' : 'AI Mati' }}</span>
        </button>
      </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4" ref="messagesContainer">
      <!-- Order Banners -->
      <div
        v-for="order in filteredOrders"
        :key="order.id"
        class="mb-4"
      >
        <OrderDetectedBanner :order="order" />
      </div>

      <!-- Messages -->
      <ChatBubble
        v-for="message in messages"
        :key="message.id"
        :message="message"
      />

      <!-- AI Typing Indicator -->
      <div
        v-if="isAITyping"
        class="flex items-center gap-3 px-4 py-3 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-2xl rounded-tr-sm w-fit"
      >
        <div class="flex gap-1">
          <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
          <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
          <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
        <span class="text-sm font-medium text-purple-700">AI sedang mengetik...</span>
      </div>

      <!-- Manual Mode Banner -->
      <div
        v-if="!conversation.ai_enabled"
        class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-orange-100 to-amber-100 rounded-2xl border-2 border-orange-200"
      >
        <span class="text-2xl">✋</span>
        <div class="flex-1">
          <p class="font-semibold text-orange-800">Mode Manual Aktif</p>
          <p class="text-sm text-orange-700">AI dimatikan, balas pesan secara manual</p>
        </div>
      </div>
    </div>

    <!-- Reply Form -->
    <div class="px-6 py-4 bg-white/80 backdrop-blur-sm border-t-2 border-[#FFE8D6]">
      <form @submit.prevent="handleSendReply" class="flex gap-3">
        <input
          v-model="replyContent"
          type="text"
          placeholder="Tulis balasan..."
          :disabled="sending"
          class="flex-1 px-4 py-3 rounded-2xl border-2 border-[#FFE8D6] bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        />
        <button
          type="submit"
          :disabled="!replyContent.trim() || sending"
          class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center gap-2"
        >
          <span v-if="!sending">Kirim</span>
          <span v-else class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengirim...
          </span>
          <span v-if="!sending">✈️</span>
        </button>
      </form>

      <!-- Error Message -->
      <div
        v-if="error"
        class="mt-3 px-4 py-2 rounded-lg bg-red-100 text-red-700 text-sm"
      >
        {{ error }}
      </div>
    </div>
  </InboxLayout>
</template>
