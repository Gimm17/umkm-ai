import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import type { Conversation, FilterOptions } from '@/types'

export function useInbox() {
  const conversations = ref<Conversation[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const filter = ref<FilterOptions>({
    channel: 'all',
    status: 'all',
    search: '',
  })

  const filteredConversations = computed(() => {
    let result = conversations.value

    // Filter by channel
    if (filter.value.channel && filter.value.channel !== 'all') {
      result = result.filter(c => c.channel === filter.value.channel)
    }

    // Filter by status
    if (filter.value.status && filter.value.status !== 'all') {
      result = result.filter(c => c.status === filter.value.status)
    }

    // Filter by search
    if (filter.value.search) {
      const search = filter.value.search.toLowerCase()
      result = result.filter(c =>
        c.contact?.name.toLowerCase().includes(search)
      )
    }

    // Sort by last message
    return result.sort((a, b) =>
      new Date(b.last_message_at).getTime() - new Date(a.last_message_at).getTime()
    )
  })

  const unreadCount = computed(() =>
    conversations.value.filter(c => c.unread_count > 0).length
  )

  const needsHumanCount = computed(() =>
    conversations.value.filter(c => c.status === 'needs_human').length
  )

  async function loadConversations() {
    loading.value = true
    error.value = null

    try {
      const { data } = await axios.get(route('api.conversations.index'))
      conversations.value = data.data
    } catch (e) {
      error.value = 'Gagal memuat percakapan'
      console.error('Failed to load conversations:', e)
    } finally {
      loading.value = false
    }
  }

  function selectConversation(conversationId: number) {
    router.visit(route('inbox.show', conversationId))
  }

  function updateFilter(newFilter: Partial<FilterOptions>) {
    filter.value = { ...filter.value, ...newFilter }
  }

  return {
    conversations,
    loading,
    error,
    filter,
    filteredConversations,
    unreadCount,
    needsHumanCount,
    loadConversations,
    selectConversation,
    updateFilter,
  }
}
