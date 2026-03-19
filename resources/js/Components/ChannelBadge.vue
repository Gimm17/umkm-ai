<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  channel: 'whatsapp' | 'instagram'
  size?: 'sm' | 'md' | 'lg'
}>()

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-2 py-0.5 text-xs'
    case 'lg':
      return 'px-4 py-2 text-base'
    default:
      return 'px-3 py-1 text-sm'
  }
})

const channelConfig = computed(() => {
  switch (props.channel) {
    case 'whatsapp':
      return {
        label: 'WhatsApp',
        icon: '📱',
        bgClass: 'bg-green-100 dark:bg-green-900/30',
        textClass: 'text-green-700 dark:text-green-400',
      }
    case 'instagram':
      return {
        label: 'Instagram',
        icon: '📸',
        bgClass: 'bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30',
        textClass: 'text-purple-700 dark:text-purple-400',
      }
  }
})
</script>

<template>
  <span
    :class="[
      'inline-flex items-center gap-1.5 rounded-full font-medium transition-colors',
      sizeClasses,
      channelConfig.bgClass,
      channelConfig.textClass,
    ]"
  >
    <span class="text-base">{{ channelConfig.icon }}</span>
    <span>{{ channelConfig.label }}</span>
  </span>
</template>
