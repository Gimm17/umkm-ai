<p align="center">
  <img src="https://raw.githubusercontent.com/Gimm17/umkm-ai/main/public/logo.png" alt="UmkmAI Logo" width="120" />
</p>

<h1 align="center">UmkmAI 🤖</h1>

<p align="center">
  <strong>One dashboard, all channels, powered by AI — free & self-hosted</strong>
</p>

<p align="center">
  <a href="https://github.com/Gimm17/umkm-ai/blob/main/LICENSE"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT License"></a>
  <a href="https://github.com/Gimm17/umkm-ai/actions"><img src="https://github.com/Gimm17/umkm-ai/actions/workflows/ci.yml/badge.svg" alt="CI Status"></a>
  <a href="https://github.com/Gimm17/umkm-ai/stargazers"><img src="https://img.shields.io/github/stars/Gimm17/umkm-ai?style=social" alt="GitHub Stars"></a>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Vue-3-42b883?logo=vue.js" alt="Vue 3">
  <img src="https://img.shields.io/badge/AI-Claude-orange?logo=anthropic" alt="Claude AI">
  <a href="https://github.com/Gimm17/umkm-ai/blob/main/README.id.md"><img src="https://img.shields.io/badge/🇮🇩-Bahasa Indonesia-red" alt="Bahasa Indonesia"></a>
</p>

<p align="center">
  <em>Helping Indonesian small businesses manage WhatsApp & Instagram messages with AI auto-reply, order detection, and a unified inbox — all in one place.</em>
</p>

---

## ✨ Features

- 💬 **Unified Inbox** — WhatsApp Business + Instagram DM in one beautiful dashboard
- 🤖 **AI Auto-Reply** — powered by Claude (Anthropic), responds to customers automatically based on your business context
- 📦 **Auto Order Detection** — AI recognizes purchase intent and logs orders automatically
- 🔄 **Real-time Updates** — live message streaming via WebSockets (Soketi)
- 🏠 **Self-hosted** — your data, your server, full control
- 🆓 **100% Open Source** — MIT License, free forever
- 🌐 **Multi-channel** — extensible architecture to support future channels

---

## 🚀 Quick Start (5 minutes with Docker)

**Prerequisites:** Docker & Docker Compose installed

```bash
# 1. Clone the repository
git clone https://github.com/Gimm17/umkm-ai.git
cd umkm-ai

# 2. Copy environment file and fill in your API keys
cp .env.example .env

# 3. Start all services
docker compose up -d

# 4. Run migrations & seed
docker compose exec app php artisan migrate --seed

# 5. Open your browser
open http://localhost:8000
```

> **That's it!** Visit `http://localhost:8000` and log in with the default credentials from the seed.

---

## ⚙️ Configuration

After running the app, fill in these keys in your `.env` file:

| Variable | Description |
|---|---|
| `ANTHROPIC_API_KEY` | Your Claude API key from [console.anthropic.com](https://console.anthropic.com) |
| `WHATSAPP_PHONE_NUMBER_ID` | From Meta Business Suite |
| `WHATSAPP_ACCESS_TOKEN` | WhatsApp Cloud API token |
| `INSTAGRAM_ACCESS_TOKEN` | Instagram Graph API token |

See [`.env.example`](.env.example) for the full list with descriptions.

---

## 🏗️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 12, PHP 8.3 |
| **Frontend** | Vue 3 (Composition API), Inertia.js |
| **Styling** | Tailwind CSS v4, shadcn-vue |
| **AI** | Anthropic Claude API |
| **Queue** | Laravel Horizon + Redis |
| **Real-time** | Laravel Echo + Soketi |
| **Database** | MySQL 8 (production), SQLite (testing) |
| **DevOps** | Docker Compose, GitHub Actions |

---

## 📁 Project Structure

```
umkm-ai/
├── app/
│   ├── Http/Controllers/      # Thin controllers
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic
│   │   ├── AIAgentService.php          # Claude integration
│   │   ├── WhatsAppService.php         # WhatsApp Cloud API
│   │   ├── InstagramService.php        # Instagram Graph API
│   │   ├── OrderExtractorService.php   # Auto order detection
│   │   └── ContextBuilderService.php   # AI context builder
│   └── Jobs/
│       ├── ProcessIncomingMessage.php
│       └── SendAIReply.php
├── resources/js/
│   └── Pages/                 # Vue pages (Inertia)
│       ├── Dashboard.vue
│       ├── Inbox/
│       └── Orders/
├── docker-compose.yml
└── .github/workflows/ci.yml
```

---

## 🔌 Webhook Setup

### WhatsApp
1. Go to [Meta Developer Console](https://developers.facebook.com)
2. Add webhook URL: `https://yourdomain.com/webhook/whatsapp`
3. Verify token: value of `WHATSAPP_VERIFY_TOKEN` in your `.env`
4. Subscribe to: `messages`, `messaging_postbacks`

### Instagram
1. Add webhook URL: `https://yourdomain.com/webhook/instagram`
2. Verify token: value of `INSTAGRAM_VERIFY_TOKEN` in your `.env`
3. Subscribe to: `messages`, `messaging_postbacks`

---

## 🧪 Running Tests

```bash
# PHP tests
php artisan test

# Or with Docker
docker compose exec app php artisan test
```

---

## 🤝 Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for how to get started.

**Looking for a good first issue?** Check the [`good first issue`](https://github.com/Gimm17/umkm-ai/labels/good%20first%20issue) label.

---

## 📄 License

[MIT](LICENSE) — free to use, modify, and distribute.

---

## 🙏 Acknowledgements

- [Anthropic](https://www.anthropic.com) for the Claude API
- [Laravel](https://laravel.com) & [Vue.js](https://vuejs.org) communities
- Every UMKM owner in Indonesia 🇮🇩 who inspired this project

---

<p align="center">
  Made with ❤️ for Indonesian small businesses (UMKM) 🇮🇩
  <br>
  If this project helped you, please ⭐ star it on GitHub!
</p>
