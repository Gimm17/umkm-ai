<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import InboxLayout from '@/Layouts/InboxLayout.vue'
import ChannelBadge from '@/Components/ChannelBadge.vue'
import { useInbox } from '@/composables/useInbox'

const {
  loading,
  filter,
  filteredConversations,
  unreadCount,
  needsHumanCount,
  loadConversations,
  selectConversation,
  updateFilter,
} = useInbox()

const searchQuery = ref('')

onMounted(() => {
  loadConversations()
})

function handleSearch(event: Event) {
  const target = event.target as HTMLInputElement
  searchQuery.value = target.value
  updateFilter({ search: target.value })
}

function handleFilterChange(channel: 'whatsapp' | 'instagram' | 'all') {
  updateFilter({ channel })
}

function getStatusColor(status: string) {
  switch (status) {
    case 'needs_human':
      return 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400'
    case 'closed':
      return 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'
    default:
      return 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
  }
}
</script>

<template>
  <Head title="Inbox" />

  <InboxLayout>
    <template #sidebar>
      <!-- Search Bar -->
      <div class="px-4 py-3">
        <div class="relative">
          <input
            type="text"
            placeholder="Cari percakapan..."
            :value="searchQuery"
            @input="handleSearch"
            class="w-full px-4 py-2.5 pl-10 rounded-2xl border-2 border-[#FFE8D6] bg-white/80 backdrop-blur-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FF6B6B] focus:ring-2 focus:ring-[#FF6B6B]/20 transition-all"
          />
          <svg
            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
        </div>
      </div>

      <!-- Filter Chips -->
      <div class="px-4 pb-3">
        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
          <button
            @click="handleFilterChange('all')"
            :class="[
              'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all',
              filter.channel === 'all'
                ? 'bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white shadow-md'
                : 'bg-white/80 text-gray-600 hover:bg-white hover:text-gray-900',
            ]"
          >
            Semua
          </button>
          <button
            @click="handleFilterChange('whatsapp')"
            :class="[
              'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all',
              filter.channel === 'whatsapp'
                ? 'bg-gradient-to-r from-[#25D366] to-[#128C7E] text-white shadow-md'
                : 'bg-white/80 text-gray-600 hover:bg-white hover:text-gray-900',
            ]"
          >
            📱 WhatsApp
          </button>
          <button
            @click="handleFilterChange('instagram')"
            :class="[
              'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all',
              filter.channel === 'instagram'
                ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-md'
                : 'bg-white/80 text-gray-600 hover:bg-white hover:text-gray-900',
            ]"
          >
            📸 Instagram
          </button>
          <button
            v-if="needsHumanCount > 0"
            @click="updateFilter({ status: 'needs_human' })"
            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-orange-100 text-orange-700 hover:bg-orange-200 transition-all"
          >
            ✋ Perlu Dibalas ({{ needsHumanCount }})
          </button>
        </div>
      </div>

      <!-- Conversation List -->
      <div class="flex-1 overflow-y-auto px-4 pb-4 space-y-2">
        <!-- Loading State -->
        <div
          v-if="loading"
          class="flex items-center justify-center py-8"
        >
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#FF6B6B]"></div>
        </div>

        <!-- Empty State -->
        <div
          v-else-if="filteredConversations.length === 0"
          class="flex flex-col items-center justify-center py-12 text-center"
        >
          <div class="text-6xl mb-4">💬</div>
          <p class="text-gray-600 font-medium">Belum ada percakapan</p>
          <p class="text-sm text-gray-400 mt-1">Percakapan baru akan muncul di sini</p>
        </div>

        <!-- Conversation Cards -->
        <div
          v-for="conversation in filteredConversations"
          :key="conversation.id"
          @click="selectConversation(conversation.id)"
          class="group p-4 rounded-2xl bg-white/80 backdrop-blur-sm border-2 border-[#FFE8D6] shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer"
        >
          <div class="flex items-start gap-3">
            <!-- Avatar -->
            <div
              :class="[
                'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg',
                conversation.channel === 'whatsapp'
                  ? 'bg-gradient-to-br from-[#25D366] to-[#128C7E]'
                  : 'bg-gradient-to-br from-purple-500 to-pink-500',
              ]"
            >
              {{ conversation.contact?.name?.charAt(0) || '?' }}
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <!-- Name & Channel Badge -->
              <div class="flex items-center gap-2 mb-1">
                <h3 class="font-semibold text-gray-900 truncate">
                  {{ conversation.contact?.name || 'Unknown' }}
                </h3>
                <ChannelBadge :channel="conversation.channel" size="sm" />
              </div>

              <!-- Status Badge -->
              <div
                v-if="conversation.status === 'needs_human'"
                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 mb-1"
              >
                ✋ Perlu Dibalas
              </div>

              <!-- Last Message Preview -->
              <p class="text-sm text-gray-500 truncate">
                Terakhir aktif {{ new Date(conversation.last_message_at).toLocaleDateString('id-ID') }}
              </p>

              <!-- Unread Count -->
              <div class="flex items-center justify-between mt-2">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                  <span v-if="conversation.ai_enabled" class="text-teal-500">✨ AI Aktif</span>
                  <span v-else class="text-gray-400">🤖 AI Mati</span>
                </div>
                <div
                  v-if="conversation.unread_count > 0"
                  class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-[#FF6B6B] to-[#FF8E8E] text-white text-xs font-bold flex items-center justify-center"
                >
                  {{ conversation.unread_count }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Main Content Area -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="text-center max-w-md">
        <div class="text-8xl mb-6">💬</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          Hai! Pilih percakapan untuk mulai membalas 👋
        </h2>
        <p class="text-gray-600">
          Pilih percakapan dari sidebar di sebelah kiri untuk melihat detail dan membalas pesan.
        </p>
      </div>
    </div>
  </InboxLayout>
</template>
