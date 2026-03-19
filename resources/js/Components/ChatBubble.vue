<script setup lang="ts">
import { computed } from 'vue'
import type { Message } from '@/types'

const props = defineProps<{
  message: Message
}>()

const bubbleConfig = computed(() => {
  if (props.message.direction === 'inbound') {
    return {
      alignment: 'justify-start',
      bgClass: 'bg-white dark:bg-gray-800',
      textClass: 'text-gray-900 dark:text-gray-100',
      roundedClass: 'rounded-l-sm rounded-2xl rounded-tr-2xl',
      shadowClass: 'shadow-sm',
      showLabel: false,
    }
  }

  if (props.message.sent_by === 'ai') {
    return {
      alignment: 'justify-end',
      bgClass: 'bg-gradient-to-br from-purple-600 to-indigo-600',
      textClass: 'text-white',
      roundedClass: 'rounded-r-sm rounded-2xl rounded-tl-2xl',
      shadowClass: 'shadow-md',
      showLabel: true,
      label: 'AI 🤖',
    }
  }

  // Human outbound
  return {
    alignment: 'justify-end',
    bgClass: 'bg-gradient-to-br from-coral-500 to-coral-600',
    textClass: 'text-white',
    roundedClass: 'rounded-r-sm rounded-2xl rounded-tl-2xl',
    shadowClass: 'shadow-md',
    showLabel: true,
    label: 'Kamu 👤',
  }
})

const formattedTime = computed(() => {
  const date = new Date(props.message.created_at)
  return date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
  })
})
</script>

<template>
  <div :class="['flex w-full', bubbleConfig.alignment]">
    <div
      :class="[
        'max-w-[80%] px-4 py-3',
        bubbleConfig.bgClass,
        bubbleConfig.textClass,
        bubbleConfig.roundedClass,
        bubbleConfig.shadowClass,
      ]"
    >
      <!-- Label untuk pesan outbound -->
      <div
        v-if="bubbleConfig.showLabel"
        class="mb-1 text-xs font-semibold opacity-80"
      >
        {{ bubbleConfig.label }}
      </div>

      <!-- Message content -->
      <div class="text-sm leading-relaxed whitespace-pre-wrap break-words">
        {{ message.content }}
      </div>

      <!-- Timestamp -->
      <div
        :class="[
          'mt-1 text-xs opacity-60',
          message.direction === 'inbound' ? 'text-right' : 'text-left',
        ]"
      >
        {{ formattedTime }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-gradient-to-br.from-coral-500 {
  background: linear-gradient(to bottom right, #FF6B6B, #FF8E8E);
}
</style>
