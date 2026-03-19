<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  enabled: boolean
  typing?: boolean
}>()

const statusConfig = computed(() => {
  if (props.typing) {
    return {
      label: 'AI mengetik...',
      icon: '⌨️',
      bgClass: 'bg-gradient-to-r from-purple-500 to-indigo-500',
      textClass: 'text-white',
      showPulse: true,
    }
  }

  if (props.enabled) {
    return {
      label: 'AI Aktif',
      icon: '✨',
      bgClass: 'bg-teal-100 dark:bg-teal-900/30',
      textClass: 'text-teal-700 dark:text-teal-400',
      showPulse: false,
    }
  }

  return {
    label: 'AI Mati',
    icon: '🤖',
    bgClass: 'bg-gray-100 dark:bg-gray-800',
    textClass: 'text-gray-600 dark:text-gray-400',
    showPulse: false,
  }
})
</script>

<template>
  <span
    :class="[
      'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium transition-all',
      statusConfig.bgClass,
      statusConfig.textClass,
    ]"
  >
    <!-- Pulse animation untuk AI typing -->
    <span
      v-if="statusConfig.showPulse"
      class="relative flex h-2 w-2"
    >
      <span
        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"
      ></span>
      <span
        class="relative inline-flex rounded-full h-2 w-2 bg-white"
      ></span>
    </span>

    <!-- Icon -->
    <span>{{ statusConfig.icon }}</span>

    <!-- Label -->
    <span>{{ statusConfig.label }}</span>
  </span>
</template>
