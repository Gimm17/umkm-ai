<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useInboxStore } from '@/stores/inbox'
import { router } from '@inertiajs/vue3'

const page = usePage()
const inboxStore = useInboxStore()

const appName = computed(() => page.props.appName as string || 'UmkmAI')

const navigation = [
  { name: 'Inbox', route: 'inbox.index', icon: '💬' },
  { name: 'Orders', route: 'orders.index', icon: '🛒' },
  { name: 'Settings', route: 'settings.index', icon: '⚙️' },
]
</script>

<template>
  <Head :title="`${appName} - Inbox`" />

  <div class="flex h-screen bg-gradient-to-br from-[#FFF5F5] via-[#F0FFF4] to-[#EFF6FF]">
    <!-- Sidebar -->
    <aside
      class="w-full md:w-80 lg:w-96 flex flex-col bg-gradient-to-b from-[#FFF5F5] to-[#F8FFFC] border-r border-[#FFE8D6] transition-all duration-300"
    >
      <!-- Logo & Header -->
      <div class="px-6 py-4 border-b border-[#FFE8D6]">
        <div class="flex items-center gap-3">
          <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-[#FF6B6B] to-[#4ECDC4] flex items-center justify-center text-white font-bold text-xl shadow-lg">
            🤖
          </div>
          <div>
            <h1 class="text-xl font-bold bg-gradient-to-r from-[#FF6B6B] to-[#4ECDC4] bg-clip-text text-transparent">
              UmkmAI
            </h1>
            <p class="text-xs text-gray-500">AI untuk UMKM-mu 🚀</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <div class="px-4 py-3 border-b border-[#FFE8D6]">
        <nav class="space-y-1">
          <a
            v-for="item in navigation"
            :key="item.route"
            :href="route(item.route)"
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all',
              page.url.startsWith(item.route)
                ? 'bg-gradient-to-r from-[#FF6B6B] to-[#FF8E8E] text-white shadow-md'
                : 'text-gray-600 hover:bg-white/60',
            ]"
          >
            <span class="text-lg">{{ item.icon }}</span>
            <span>{{ item.name }}</span>
          </a>
        </nav>
      </div>

      <!-- Slot untuk sidebar content (filter & list) -->
      <div class="flex-1 overflow-hidden">
        <slot name="sidebar" />
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
      <!-- Slot untuk main content (chat view) -->
      <slot />
    </main>
  </div>
</template>
