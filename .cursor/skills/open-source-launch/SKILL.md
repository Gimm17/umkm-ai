---
name: open-source-launch
description: >
  Panduan strategi peluncuran dan pertumbuhan GitHub stars untuk project
  UmkmAI sebagai open-source. Gunakan skill ini saat menulis README,
  membuat dokumentasi, menyiapkan GitHub repository, atau merencanakan
  strategi promosi project ke komunitas developer.
---

# Open Source Launch Strategy (UmkmAI)

## 🎯 Target
- **5.000 GitHub stars** dalam 6-12 bulan
- Masuk **GitHub Trending** minimal sekali
- Diterima di program **Claude for Open Source** dari Anthropic

---

## 📄 README — Komponen Wajib (Urutan Penting!)

```markdown
# UmkmAI 🤖

> Satu dashboard, semua channel, dibantu AI — gratis & self-hosted

[Demo GIF di sini — WAJIB ADA, ini paling penting]

[![Stars](badge)] [![License: MIT](badge)] [![Laravel](badge)] [![Vue](badge)]

## ✨ Fitur
- 🟢 **WhatsApp & Instagram** dalam satu inbox
- 🤖 **AI Auto-Reply** powered by Claude — jawab pelanggan otomatis
- 📦 **Deteksi Order** — AI kenali intent beli dan catat otomatis
- 🏠 **Self-hosted** — data kamu, servermu
- 🆓 **100% Open Source** — MIT License

## 🚀 Quick Start (5 menit)
[Instruksi Docker — HARUS semudah mungkin]

## 📸 Screenshots
[3-4 screenshot UI yang bagus]

## 🏗️ Tech Stack
...

## 🤝 Contributing
...
```

### ⚠️ Rules README:
- **Demo GIF adalah prioritas #1** — tanpa demo visual, orang tidak akan star
- Quick Start MAKSIMAL 5 langkah — kalau lebih, sederhanakan
- Tulis dalam **English** (utama) — jangkauan global
- Tambahkan badge `🇮🇩 Tersedia dalam Bahasa Indonesia` di bagian atas
- Hindari jargon teknis di bagian intro

---

## 🎬 Cara Buat Demo GIF yang Bagus

Tools: **ScreenToGif** (Windows) atau **Kap** (Mac)

Skenario demo (30-45 detik):
1. Buka inbox — tampil percakapan dari WA & IG
2. Klik percakapan — tampil chat
3. Pelanggan kirim pesan "mau pesan 2 kaos size M"
4. AI auto-reply dalam 2 detik
5. Badge "Order Detected" muncul
6. Geser ke tab Orders — orderan sudah tercatat

**Tips:** Record di resolusi 1280x800, compress dengan tools online

---

## 🏷️ GitHub Repository Setup

### Topics (Wajib semua ini):
```
laravel, vue, whatsapp, instagram, ai-agent, umkm, indonesia,
self-hosted, open-source, chatbot, omnichannel, anthropic, claude-ai
```

### Repository Description:
```
🤖 AI-powered omnichannel inbox for Indonesian SMBs. WhatsApp + Instagram + AI auto-reply. Self-hosted & open source.
```

### File yang Wajib Ada:
```
README.md           ← Bahasa Inggris
README.id.md        ← Bahasa Indonesia
LICENSE             ← MIT
CONTRIBUTING.md     ← Panduan kontribusi
.github/
  ISSUE_TEMPLATE/
    bug_report.md
    feature_request.md
  PULL_REQUEST_TEMPLATE.md
docker-compose.yml  ← One-command setup
.env.example        ← Tanpa nilai sensitif
```

---

## 📢 Strategi Promosi — Timeline

### Minggu Pertama Setelah Launch:
- [ ] Post di **r/selfhosted** (Reddit) — komunitas besar, suka self-hosted tools
- [ ] Post di **r/laravel** dan **r/vuejs**
- [ ] Submit ke **Hacker News** — "Show HN: UmkmAI – open source AI inbox for small businesses"
- [ ] Post di **X/Twitter** — tag @AnthropicAI, @laravelphp, komunitas developer Indonesia
- [ ] Submit ke **Product Hunt** — siapkan hunter, tagline, dan media

### Komunitas Indonesia:
- [ ] **Discord Laravel Indonesia**
- [ ] **Telegram PHP Indonesia**
- [ ] **Facebook Group: WPU / Programmer Indonesia**
- [ ] **LinkedIn** — post artikel tentang problem yang diselesaikan

### Setelah 100 Stars:
- [ ] Submit ke **awesome-selfhosted** (GitHub list 200k+ stars)
- [ ] Submit ke **awesome-laravel**
- [ ] Tulis artikel di **Dev.to** atau **Medium**
- [ ] Buat video demo di **YouTube** (bahasa Indonesia)

---

## 🤝 Komunitas & Kontribusi

### Label Issue yang Wajib Ada:
```
good first issue    ← Untuk pemula — jangan sepelekan ini!
help wanted
bug
enhancement
documentation
question
```

### Isi Issue "good first issue" yang Bagus:
- Fix typo di README
- Translate README ke bahasa lain
- Tambah unit test untuk 1 method
- Tambah screenshot ke dokumentasi
- Buat badge baru

### CONTRIBUTING.md — Poin Penting:
1. Cara setup development environment lokal
2. Cara menjalankan tests
3. Code style yang digunakan (Laravel Pint, ESLint)
4. Alur Pull Request
5. Commit message convention

---

## 📊 Metrik yang Perlu Dipantau

| Metrik | Target 1 Bulan | Target 6 Bulan |
|--------|----------------|----------------|
| GitHub Stars | 100 | 5.000 |
| Forks | 20 | 500 |
| Contributors | 3 | 20 |
| Open Issues | < 10 | < 30 |

---

## 🏆 Cara Masuk GitHub Trending

GitHub Trending diurutkan berdasarkan **star velocity** (stars per hari), bukan total stars.

Strategi untuk masuk trending:
1. Koordinasi launch — ajak teman-teman star di hari yang sama
2. Post di banyak platform di **hari yang sama**
3. Waktu terbaik launch: **Selasa-Kamis pagi WIB** (saat developer Asia Tenggara aktif)
4. Minimal **50 stars dalam 24 jam pertama** untuk masuk trending harian

---

## 📝 Template Post untuk Launch

### Reddit r/selfhosted:
```
Title: UmkmAI – Open source AI-powered inbox for small businesses (WhatsApp + Instagram + auto-reply)

Hey r/selfhosted! I built an open-source tool to help small business owners
manage WhatsApp and Instagram messages in one place, with AI auto-reply powered by Claude.

Key features:
• Unified inbox for WhatsApp Business + Instagram DM
• AI auto-replies based on your product catalog
• Automatic order detection from chat
• 100% self-hosted, MIT license
• Docker one-command setup

[GitHub link] | [Demo GIF]

Would love feedback from the community!
```

### Hacker News:
```
Show HN: UmkmAI – Self-hosted AI inbox for small businesses in Indonesia

I built this after seeing small merchants struggle to manage hundreds of
WhatsApp and Instagram messages daily without any staff...
[cerita problem, solusi, tech stack, link]
```
