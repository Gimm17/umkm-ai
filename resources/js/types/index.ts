// Domain Types
export interface Contact {
  id: number
  channel: 'whatsapp' | 'instagram'
  channel_id: string
  name: string
  created_at: string
  updated_at: string
}

export interface Conversation {
  id: number
  contact_id: number
  contact?: Contact
  channel: 'whatsapp' | 'instagram'
  status: 'open' | 'closed' | 'needs_human'
  ai_enabled: boolean
  last_message_at: string
  unread_count: number
  created_at: string
  updated_at: string
}

export interface Message {
  id: number
  conversation_id: number
  direction: 'inbound' | 'outbound'
  content: string
  message_type: 'text' | 'image' | 'audio'
  sent_by?: 'human' | 'ai' | null
  created_at: string
}

export interface Order {
  id: number
  conversation_id: number
  items: OrderItem[]
  total_estimate: number
  shipping_address?: string
  status: 'detected' | 'confirmed' | 'cancelled'
  created_at: string
}

export interface OrderItem {
  name: string
  qty: number
  price: number
}

// UI State Types
export interface FilterOptions {
  channel?: 'whatsapp' | 'instagram' | 'all'
  status?: 'open' | 'needs_human' | 'all'
  search?: string
}

export interface InboxState {
  conversations: Conversation[]
  activeConversationId: number | null
  filter: FilterOptions
  loading: boolean
}

export interface ChatState {
  messages: Message[]
  sending: boolean
  isAITyping: boolean
  error: string | null
  orders: Order[]
}

// API Response Types
export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface ApiResponse<T> {
  data: T
  message?: string
}
