import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import type { Message, Order } from '@/types'

export function useMessages(conversationId: number) {
  const messages = ref<Message[]>([])
  const sending = ref(false)
  const isAITyping = ref(false)
  const error = ref<string | null>(null)
  const orders = ref<Order[]>([])

  async function loadMessages() {
    error.value = null

    try {
      const { data } = await axios.get(route('api.conversations.messages.index', conversationId))
      messages.value = data.data
    } catch (e) {
      error.value = 'Gagal memuat pesan'
      console.error('Failed to load messages:', e)
    }
  }

  async function send(content: string) {
    sending.value = true
    error.value = null

    try {
      const { data } = await axios.post(
        route('api.conversations.messages.store', conversationId),
        { content }
      )

      messages.value.push(data.data)
      return data.data
    } catch (e) {
      error.value = 'Gagal mengirim pesan'
      console.error('Failed to send message:', e)
      throw e
    } finally {
      sending.value = false
    }
  }

  function listenRealtime() {
    // Listen for new messages
    window.Echo.private(`conversation.${conversationId}`)
      .listen('.NewMessageReceived', (e: { message: Message }) => {
        messages.value.push(e.message)
      })
      .listen('.AIFailed', (e: { error: string }) => {
        isAITyping.value = false
        error.value = `AI gagal: ${e.error}`
      })
      .listen('.OrderDetected', (e: { order: Order }) => {
        orders.value.push(e.order)
      })

    // Listen for AI typing status
    window.Echo.private(`conversation.${conversationId}`)
      .listen('.AITyping', () => {
        isAITyping.value = true
      })
      .listen('.AIReply', () => {
        isAITyping.value = false
      })
  }

  onMounted(() => {
    loadMessages()
    listenRealtime()
  })

  onUnmounted(() => {
    window.Echo.leave(`conversation.${conversationId}`)
  })

  return {
    messages,
    sending,
    isAITyping,
    error,
    orders,
    send,
  }
}
