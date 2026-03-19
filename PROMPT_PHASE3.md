# PROMPT PHASE 3 — Frontend MVP dengan MCP Stitch
# Copy-paste seluruh isi file ini ke Cursor

---

Phase 2 selesai. Sekarang kita masuk Phase 3 - Frontend MVP.

Sebelum nulis kode apapun, lakukan langkah ini berurutan:

## 🎨 DESIGN LANGUAGE — Wajib Dipahami Dulu

UmkmAI bukan aplikasi korporat yang dingin — ini untuk UMKM Indonesia yang hangat, penuh semangat, dan optimis.

**Vibe:** Cerah, hangat, penuh harapan — seperti pagi hari di toko yang ramai pelanggan.

### Color Palette:
```
--color-primary:       #FF6B6B   /* coral hangat — energi & semangat */
--color-primary-soft:  #FFE8E8   /* coral muda — background card aktif */
--color-secondary:     #4ECDC4   /* teal segar — aksi & highlight */
--color-accent:        #FFE66D   /* kuning cerah — notifikasi & badge */

/* Pastel Gradient Background */
--gradient-hero:    linear-gradient(135deg, #FFF5F5 0%, #F0FFF4 50%, #EFF6FF 100%)
--gradient-card:    linear-gradient(145deg, #FFFFFF 0%, #FFF9F0 100%)
--gradient-sidebar: linear-gradient(180deg, #FFF5F5 0%, #F8FFFC 100%)

/* Surfaces */
--color-bg:         #FFFAF7     /* krem lembut — background utama */
--color-surface:    #FFFFFF     /* putih bersih — kartu */
--color-border:     #FFE8D6     /* peachy border lembut */

/* Text */
--color-text:       #2D3748     /* abu tua yang hangat */
--color-text-muted: #A0AEC0     /* abu lembut */

/* Channel Colors */
--color-whatsapp:   #25D366
--color-instagram:  linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888)

/* AI Badge */
--color-ai:         linear-gradient(135deg, #667eea 0%, #764ba2 100%)
```

### Typography:
- Font utama: **Plus Jakarta Sans** (friendly, modern, Indonesian-friendly)
- Heading: bold, coral atau teal
- Body: regular, warm grey

### Karakter Visual:
- Sudut rounded: `border-radius: 16px` untuk card, `24px` untuk modal
- Shadow: soft drop shadow `0 4px 20px rgba(255, 107, 107, 0.08)`
- Ilustrasi: emoji besar sebagai ilustrasi kosong (🛍️ 💬 🤖)
- Microinteraction: hover card naik sedikit (translateY -2px)

---

## LANGKAH 1 — Baca Rules
Baca file berikut dulu:
- `.cursor/skills/vue-frontend/SKILL.md`
- `.cursor/skills/ai-agent/SKILL.md`

## LANGKAH 2 — Generate Design via Stitch MCP
Gunakan Stitch MCP untuk generate design semua halaman berikut SEBELUM coding.
Ikuti urutan ini:

### Screen 1 — Layout Utama + Inbox
```
Generate halaman Inbox utama untuk aplikasi UmkmAI — dashboard AI omnichannel untuk UMKM Indonesia.

Style: LIGHT theme dengan pastel gradient yang hangat dan cerah.
Vibe: Seperti toko UMKM yang hangat, optimis, penuh semangat — bukan korporat dingin.

Warna:
- Background utama: gradient lembut dari #FFF5F5 ke #F0FFF4 ke #EFF6FF
- Sidebar: gradient #FFF5F5 ke #F8FFFC
- Kartu: putih bersih dengan shadow coral lembut
- Aksen utama: coral #FF6B6B
- Aksen sekunder: teal #4ECDC4
- Border: peachy #FFE8D6

Layout:
- Sidebar kiri 320px: gradient pastel, search bar rounded dengan border peach,
  filter chip horizontal (Semua / WhatsApp / Instagram / Perlu Dibalas) dengan style pill aktif coral
- Kartu percakapan: rounded-2xl, shadow lembut, hover naik sedikit,
  avatar bulat dengan ring warna channel, nama kontak bold coral,
  preview pesan abu lembut, timestamp kecil, badge channel berwarna
- Main area: empty state dengan emoji besar 💬 dan teks hangat "Hai! Pilih percakapan untuk mulai membalas 👋"
- Topbar: logo UmkmAI dengan gradient coral-teal, tagline kecil "AI untuk UMKM-mu 🚀"

Font: Plus Jakarta Sans, rounded dan friendly
Border radius: 16px card, 24px modal, pill untuk badge
```

### Screen 2 — Chat View (Inbox/Show)
```
Generate halaman Chat aktif UmkmAI dengan design DNA pastel hangat dari screen sebelumnya.

Spesifikasi:
- Header chat: gradient coral-to-teal lembut, avatar dengan ring warna channel,
  nama kontak putih bold, badge channel pill, toggle AI switch (ON=teal cerah, OFF=abu)
- Area chat background: gradient #FFFAF7 sangat lembut, ada pattern titik-titik halus
- Bubble inbound (pelanggan): putih bersih, shadow lembut, sudut kiri flat, timestamp abu
- Bubble outbound human: gradient coral #FF6B6B ke #FF8E8E, teks putih, sudut kanan flat, label "Kamu 👤"
- Bubble outbound AI: gradient ungu #667eea ke #764ba2, teks putih, label "AI 🤖", ada sparkle icon ✨
- Badge order: pill kuning #FFE66D dengan emoji 🛒 "Order Terdeteksi!"
- Indikator AI mengetik: 3 titik animasi dengan warna gradient
- Banner mode manual: background oranye pastel #FFF3E0, icon ✋, teks "Mode Manual Aktif"
- Form reply: rounded-2xl, border peach, tombol kirim coral dengan icon pesawat kertas ✈️
```

### Screen 3 — Design System Components
```
Generate halaman design system / component library UmkmAI dengan style pastel hangat yang sama.

Komponen yang perlu ditampilkan:
- ChannelBadge WhatsApp: pill hijau #25D366, icon WA, label "WhatsApp"
- ChannelBadge Instagram: pill gradient ungu-pink, icon IG, label "Instagram"
- ChannelBadge needs_human: pill oranye #FF9500, icon ✋, label "Perlu Dibalas"
- ChatBubble inbound: putih, shadow lembut, avatar kiri
- ChatBubble human: coral gradient, label "Kamu"
- ChatBubble AI: ungu gradient, label "AI ✨", badge sparkle
- AIStatusBadge ON: teal #4ECDC4, dot animasi pulse, label "AI Aktif"
- AIStatusBadge OFF: abu, label "AI Mati"
- OrderDetectedBanner: kuning pastel, emoji 🛒, nama produk + jumlah
- EmptyState: emoji besar 🛍️, teks hangat, tombol coral
```

## LANGKAH 3 — Extract Design DNA & Buat Tailwind Config
Setelah semua screen di-generate Stitch, extract design DNA lalu buat:

```
Extract design DNA dari semua screen UmkmAI yang sudah dibuat.
Generate sebagai:
1. CSS custom properties (--color-*, --gradient-*, --radius-*, --shadow-*)
2. Tailwind v4 theme extension config
3. Plus Jakarta Sans font import
Simpan ke file resources/css/design-tokens.css
```

## LANGKAH 4 — Convert Design ke Vue Components
Setelah design DNA di-extract, buat semua komponen Vue berdasarkan design Stitch.
Ikuti SEMUA aturan di `.cursor/skills/vue-frontend/SKILL.md`:

### 4a. Design Tokens CSS
Buat `resources/css/design-tokens.css`:
```css
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
  /* Colors */
  --color-primary: #FF6B6B;
  --color-primary-soft: #FFE8E8;
  --color-secondary: #4ECDC4;
  --color-accent: #FFE66D;

  /* Gradients */
  --gradient-hero: linear-gradient(135deg, #FFF5F5 0%, #F0FFF4 50%, #EFF6FF 100%);
  --gradient-card: linear-gradient(145deg, #FFFFFF 0%, #FFF9F0 100%);
  --gradient-sidebar: linear-gradient(180deg, #FFF5F5 0%, #F8FFFC 100%);
  --gradient-primary: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
  --gradient-ai: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

  /* Surfaces */
  --color-bg: #FFFAF7;
  --color-surface: #FFFFFF;
  --color-border: #FFE8D6;

  /* Text */
  --color-text: #2D3748;
  --color-text-muted: #A0AEC0;

  /* Channels */
  --color-whatsapp: #25D366;
  --color-instagram-start: #f09433;
  --color-instagram-end: #bc1888;

  /* Radius */
  --radius-sm: 8px;
  --radius-md: 16px;
  --radius-lg: 24px;
  --radius-pill: 9999px;

  /* Shadow */
  --shadow-card: 0 4px 20px rgba(255, 107, 107, 0.08);
  --shadow-hover: 0 8px 30px rgba(255, 107, 107, 0.15);

  /* Font */
  --font-base: 'Plus Jakarta Sans', sans-serif;
}
```

### 4b. Types & Interfaces
Buat file `resources/js/types/index.ts` berisi semua interface TypeScript.

### 4c. Composables
Buat `resources/js/composables/useInbox.ts` — filter, search, load conversations.
Buat `resources/js/composables/useMessages.ts` — send, realtime Echo, isAITyping.
Buat `resources/js/composables/useAIAgent.ts` — toggle AI on/off.

### 4d. Pinia Store
Buat `resources/js/stores/inbox.ts` — global state conversations & activeConversationId.

### 4e. Komponen Atom (Pakai CSS variables dari design tokens)
Buat `resources/js/Components/ChannelBadge.vue`
Buat `resources/js/Components/ChatBubble.vue`
Buat `resources/js/Components/AIStatusBadge.vue`
Buat `resources/js/Components/OrderDetectedBanner.vue`

### 4f. Layout & Halaman
Buat `resources/js/Layouts/InboxLayout.vue` — sidebar + main, mobile responsive.
Buat `resources/js/Pages/Inbox/Index.vue` — list percakapan + filter.
Buat `resources/js/Pages/Inbox/Show.vue` — chat view + toggle AI + reply form.

## LANGKAH 5 — Checklist Final
- [ ] Semua komponen pakai `<script setup lang="ts">`
- [ ] Tidak ada Options API
- [ ] Tidak ada hardcode URL — pakai `route()` dari Ziggy
- [ ] Semua komponen UI base pakai shadcn-vue
- [ ] Warna pakai CSS variables dari design-tokens.css, bukan hardcode hex
- [ ] Echo listener di-cleanup di `onUnmounted`
- [ ] Pinia hanya untuk global state
- [ ] Mobile-first di semua komponen
- [ ] Font Plus Jakarta Sans terload
- [ ] Hover effect pada kartu percakapan (translateY -2px)
