---
name: vue-frontend
description: >
  Panduan membangun frontend Vue 3 + Inertia.js untuk project UmkmAI.
  Gunakan skill ini setiap kali membuat komponen Vue, halaman Inertia,
  Pinia store, composable, atau layout UI. Wajib dibaca sebelum menulis
  kode Vue/JavaScript apapun di project UmkmAI.
---

# Vue Frontend — Rules & Best Practices (UmkmAI)

## ⚙️ Stack Frontend
- **Vue 3** — Composition API + `<script setup>`
- **Inertia.js** — SPA tanpa API terpisah
- **Tailwind CSS v4**
- **shadcn-vue** — komponen UI utama
- **Pinia** — state management
- **Laravel Echo + Soketi** — realtime

---

## 📐 Struktur Komponen Wajib

### Selalu Gunakan `<script setup>`
```vue
<!-- ✅ BENAR -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useInbox } from '@/composables/useInbox'

const props = defineProps<{
  conversationId: number
  showAiBadge?: boolean
}>()

const emit = defineEmits<{
  messageSent: [message: Message]
  conversationClosed: []
}>()
</script>

<!-- ❌ SALAH — jangan pakai Options API -->
<script>
export default {
  props: ['conversationId'],
  data() { return {} }
}
</script>
```

---

## 🧩 Composables — Pisahkan Logic dari Template

Setiap domain punya composable tersendiri:

```
composables/
├── useInbox.ts          # List & filter percakapan
├── useMessages.ts       # Kirim & terima pesan, realtime
├── useOrders.ts         # Manajemen order terdeteksi
├── useAIAgent.ts        # Toggle AI, status AI
└── useSettings.ts       # Baca & update konfigurasi
```

### Contoh Composable yang Benar
```typescript
// composables/useMessages.ts
export function useMessages(conversationId: number) {
  const messages = ref<Message[]>([])
  const sending = ref(false)
  const error = ref<string | null>(null)

  async function send(content: string) {
    sending.value = true
    error.value = null
    try {
      const { data } = await axios.post(`/conversations/${conversationId}/messages`, { content })
      messages.value.push(data)
    } catch (e) {
      error.value = 'Gagal mengirim pesan'
    } finally {
      sending.value = false
    }
  }

  function listenRealtime() {
    window.Echo.private(`conversation.${conversationId}`)
      .listen('NewMessageReceived', (e: { message: Message }) => {
        messages.value.push(e.message)
      })
  }

  onMounted(() => listenRealtime())
  onUnmounted(() => window.Echo.leave(`conversation.${conversationId}`))

  return { messages, sending, error, send }
}
```

---

## 🏪 Pinia Store — Hanya untuk Global State

State yang perlu di-share antar komponen:
```typescript
// stores/inbox.ts
export const useInboxStore = defineStore('inbox', () => {
  const conversations = ref<Conversation[]>([])
  const unreadCount = computed(() =>
    conversations.value.filter(c => c.unread_count > 0).length
  )

  function markAsRead(conversationId: number) {
    const conv = conversations.value.find(c => c.id === conversationId)
    if (conv) conv.unread_count = 0
  }

  return { conversations, unreadCount, markAsRead }
})
```

**Jangan pakai Pinia untuk state lokal** — cukup `ref` / `reactive` di dalam komponen.

---

## 🎨 UI Rules — Tailwind + shadcn-vue

### Gunakan shadcn-vue untuk semua komponen base
```vue
<!-- ✅ Pakai shadcn-vue -->
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { ScrollArea } from '@/components/ui/scroll-area'

<!-- ❌ Jangan buat button/input dari scratch -->
<button class="px-4 py-2 bg-blue-500 rounded">...</button>
```

### Color Semantic (pakai CSS variable, bukan hardcode warna)
```vue
<!-- ✅ -->
<div class="bg-background text-foreground border-border">

<!-- ❌ -->
<div class="bg-white text-gray-900 border-gray-200">
```

### Channel Badge — Komponen Wajib Ada
```vue
<!-- Setiap konversasi tampilkan badge channel -->
<ChannelBadge :channel="conversation.channel" />
<!-- Render: 🟢 WhatsApp | 🔵 Instagram -->
```

---

## ⚡ Realtime — Laravel Echo

### Setup di `app.js`
```javascript
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  wsHost: import.meta.env.VITE_PUSHER_HOST,
  wsPort: import.meta.env.VITE_PUSHER_PORT,
  forceTLS: false,
  disableStats: true,
})
```

### Pattern Listen di Composable
```typescript
// Selalu cleanup listener di onUnmounted
onUnmounted(() => {
  window.Echo.leave(`conversation.${conversationId}`)
})
```

---

## 🔄 Inertia.js Rules

### Gunakan `router.visit` bukan `axios` untuk navigasi
```typescript
import { router } from '@inertiajs/vue3'

// Navigasi biasa
router.visit('/inbox')

// Submit form
router.post('/conversations', form, {
  onSuccess: () => toast('Berhasil!'),
  onError: (errors) => console.error(errors),
})
```

### Shared Data — Akses via `usePage()`
```typescript
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const auth = computed(() => page.props.auth) // user yang login
const flash = computed(() => page.props.flash) // flash message
```

---

## 📱 Responsive Rules
- Mobile-first — mulai dari tampilan HP
- Breakpoint utama: `md:` (tablet) dan `lg:` (desktop)
- Chat UI harus bisa dipakai di HP (banyak UMKM akses dari HP)
- Sidebar collapse di mobile → bottom navigation

---

## 🚫 Hal yang DILARANG
- ❌ Options API (`data()`, `methods:`, `computed:`)
- ❌ `document.querySelector` atau manipulasi DOM langsung
- ❌ Hardcode URL — selalu pakai `route()` dari Ziggy
- ❌ Inline style (`style="color: red"`) — pakai Tailwind class
- ❌ Import komponen besar yang tidak dipakai (tree-shake selalu)
- ❌ State management Pinia untuk data yang hanya dipakai 1 komponen
