import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Conversation } from '@/types'

export const useInboxStore = defineStore('inbox', () => {
  const conversations = ref<Conversation[]>([])
  const activeConversationId = ref<number | null>(null)

  const activeConversation = computed(() =>
    conversations.value.find(c => c.id === activeConversationId.value)
  )

  const unreadCount = computed(() =>
    conversations.value.filter(c => c.unread_count > 0).length
  )

  const needsHumanCount = computed(() =>
    conversations.value.filter(c => c.status === 'needs_human').length
  )

  function setConversations(data: Conversation[]) {
    conversations.value = data
  }

  function setActiveConversation(id: number | null) {
    activeConversationId.value = id
  }

  function updateConversation(id: number, updates: Partial<Conversation>) {
    const index = conversations.value.findIndex(c => c.id === id)
    if (index !== -1) {
      conversations.value[index] = {
        ...conversations.value[index],
        ...updates,
      }
    }
  }

  function markAsRead(conversationId: number) {
    const conversation = conversations.value.find(c => c.id === conversationId)
    if (conversation) {
      conversation.unread_count = 0
    }
  }

  return {
    conversations,
    activeConversationId,
    activeConversation,
    unreadCount,
    needsHumanCount,
    setConversations,
    setActiveConversation,
    updateConversation,
    markAsRead,
  }
})
