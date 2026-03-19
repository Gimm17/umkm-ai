<p align="center">
  <img src="https://raw.githubusercontent.com/Gimm17/umkm-ai/main/public/logo.png" alt="UmkmAI Logo" width="120" />
</p>

<h1 align="center">UmkmAI 🤖</h1>

<p align="center">
  <strong>Satu dashboard, semua channel, dibantu AI — gratis & bisa self-hosted</strong>
</p>

<p align="center">
  <a href="https://github.com/Gimm17/umkm-ai/blob/main/LICENSE"><img src="https://img.shields.io/badge/Lisensi-MIT-green.svg" alt="MIT License"></a>
  <a href="https://github.com/Gimm17/umkm-ai/actions"><img src="https://github.com/Gimm17/umkm-ai/actions/workflows/ci.yml/badge.svg" alt="Status CI"></a>
  <a href="https://github.com/Gimm17/umkm-ai/stargazers"><img src="https://img.shields.io/github/stars/Gimm17/umkm-ai?style=social" alt="GitHub Stars"></a>
  <a href="https://github.com/Gimm17/umkm-ai/blob/main/README.md"><img src="https://img.shields.io/badge/🇬🇧-English-blue" alt="English"></a>
</p>

<p align="center">
  <em>Membantu UMKM Indonesia mengelola pesan WhatsApp & Instagram dengan AI auto-reply, deteksi order otomatis, dan satu inbox terpadu.</em>
</p>

---

## ✨ Fitur Unggulan

- 💬 **Inbox Terpadu** — WhatsApp Business + Instagram DM dalam satu dashboard
- 🤖 **AI Auto-Reply** — powered by Claude (Anthropic), balas pelanggan otomatis berdasarkan konteks bisnis Anda
- 📦 **Deteksi Order Otomatis** — AI mengenali niat beli dan mencatat order secara otomatis
- 🔄 **Update Real-time** — streaming pesan langsung via WebSocket (Soketi)
- 🏠 **Self-hosted** — data Anda, server Anda, kontrol penuh
- 🆓 **100% Open Source** — MIT License, gratis selamanya
- 🌐 **Multi-channel** — arsitektur extensible untuk mendukung channel lain di masa depan

---

## 🚀 Mulai Cepat (5 menit dengan Docker)

**Prasyarat:** Docker & Docker Compose sudah terinstall

```bash
# 1. Clone repository
git clone https://github.com/Gimm17/umkm-ai.git
cd umkm-ai

# 2. Salin file environment dan isi API key Anda
cp .env.example .env

# 3. Jalankan semua layanan
docker compose up -d

# 4. Jalankan migrasi & seed database
docker compose exec app php artisan migrate --seed

# 5. Buka browser
open http://localhost:8000
```

> **Selesai!** Kunjungi `http://localhost:8000` dan login dengan kredensial default dari seed.

---

## ⚙️ Konfigurasi

Setelah aplikasi berjalan, isi konfigurasi berikut di file `.env`:

| Variabel | Keterangan |
|---|---|
| `ANTHROPIC_API_KEY` | API key Claude dari [console.anthropic.com](https://console.anthropic.com) |
| `WHATSAPP_PHONE_NUMBER_ID` | Dari Meta Business Suite |
| `WHATSAPP_ACCESS_TOKEN` | Token WhatsApp Cloud API |
| `INSTAGRAM_ACCESS_TOKEN` | Token Instagram Graph API |

Lihat [`.env.example`](.env.example) untuk daftar lengkap beserta deskripsinya.

---

## 🔌 Setup Webhook

### WhatsApp
1. Buka [Meta Developer Console](https://developers.facebook.com)
2. Tambahkan URL webhook: `https://domain-anda.com/webhook/whatsapp`
3. Verify token: isi dengan nilai `WHATSAPP_VERIFY_TOKEN` di `.env` Anda
4. Subscribe ke event: `messages`, `messaging_postbacks`

### Instagram
1. Tambahkan URL webhook: `https://domain-anda.com/webhook/instagram`
2. Verify token: isi dengan nilai `INSTAGRAM_VERIFY_TOKEN` di `.env` Anda
3. Subscribe ke event: `messages`, `messaging_postbacks`

---

## 🏗️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | Laravel 12, PHP 8.3 |
| **Frontend** | Vue 3 (Composition API), Inertia.js |
| **Styling** | Tailwind CSS v4, shadcn-vue |
| **AI** | Anthropic Claude API |
| **Queue** | Laravel Horizon + Redis |
| **Real-time** | Laravel Echo + Soketi |
| **Database** | MySQL 8 (produksi), SQLite (testing) |
| **DevOps** | Docker Compose, GitHub Actions |

---

## 🧪 Menjalankan Tests

```bash
# PHP tests
php artisan test

# Atau dengan Docker
docker compose exec app php artisan test
```

---

## 🤝 Berkontribusi

Kontribusi sangat disambut! Baca [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan memulai.

**Cari issue yang cocok untuk pemula?** Cek label [`good first issue`](https://github.com/Gimm17/umkm-ai/labels/good%20first%20issue).

---

## 📄 Lisensi

[MIT](LICENSE) — bebas digunakan, dimodifikasi, dan didistribusikan.

---

## 🙏 Terima Kasih

- [Anthropic](https://www.anthropic.com) atas Claude API
- Komunitas [Laravel](https://laravel.com) & [Vue.js](https://vuejs.org)
- Seluruh pelaku UMKM Indonesia 🇮🇩 yang menginspirasi project ini

---

<p align="center">
  Dibuat dengan ❤️ untuk UMKM Indonesia 🇮🇩
  <br>
  Jika project ini membantumu, tolong ⭐ beri bintang di GitHub!
</p>
