import { ref } from 'vue'
import axios from 'axios'
import type { Conversation } from '@/types'

export function useAIAgent() {
  const toggling = ref(false)
  const error = ref<string | null>(null)

  async function toggleAI(conversation: Conversation) {
    toggling.value = true
    error.value = null

    try {
      const { data } = await axios.patch(
        route('api.conversations.toggle-ai', conversation.id),
        {
          ai_enabled: !conversation.ai_enabled,
        }
      )

      return data.data
    } catch (e) {
      error.value = 'Gagal mengubah status AI'
      console.error('Failed to toggle AI:', e)
      throw e
    } finally {
      toggling.value = false
    }
  }

  return {
    toggling,
    error,
    toggleAI,
  }
}
