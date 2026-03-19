# Contributing to UmkmAI 🤖

Thank you for your interest in contributing to UmkmAI! We welcome contributions from the community — whether it's a bug fix, new feature, documentation improvement, or translation.

---

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Running Tests](#running-tests)
- [Code Style](#code-style)
- [Pull Request Process](#pull-request-process)
- [Commit Message Convention](#commit-message-convention)
- [Good First Issues](#good-first-issues)

---

## Code of Conduct

By participating in this project, you agree to be respectful, inclusive, and constructive. We are building a tool for Indonesian small businesses and want this to be a welcoming community for everyone.

---

## Getting Started

1. [Fork the repository](https://github.com/Gimm17/umkm-ai/fork)
2. Clone your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/umkm-ai.git
   cd umkm-ai
   ```
3. Add the upstream remote:
   ```bash
   git remote add upstream https://github.com/Gimm17/umkm-ai.git
   ```

---

## Development Setup

### Requirements
- PHP 8.3+
- Composer 2+
- Node.js 20+ & npm
- Redis (or Docker)
- MySQL 8 (or Docker)

### Option A — Docker (Recommended)

```bash
# Copy environment
cp .env.example .env
# Edit .env with your API keys

# Start all services
docker compose up -d

# Install PHP dependencies
docker compose exec app composer install

# Generate app key
docker compose exec app php artisan key:generate

# Run migrations with seed data
docker compose exec app php artisan migrate --seed

# Install JS dependencies and build
docker compose exec app npm install && npm run build
```

### Option B — Local (without Docker)

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy and configure environment
cp .env.example .env
# Edit .env: set DB_CONNECTION=sqlite for easy local setup
# Also fill in ANTHROPIC_API_KEY if testing AI features

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Start dev server (in separate terminals):
php artisan serve          # Backend at http://localhost:8000
npm run dev                # Vite frontend watcher
php artisan horizon        # Queue worker (optional for webhook testing)
php artisan reverb:start   # (or soketi) for WebSockets (optional)
```

---

## Running Tests

```bash
# Run all tests
php artisan test

# Run only unit tests
php artisan test --testsuite=Unit

# Run only feature tests
php artisan test --testsuite=Feature

# With coverage (requires Xdebug or PCOV)
php artisan test --coverage

# With Docker
docker compose exec app php artisan test
```

---

## Code Style

We use automated code formatters. Please run these before submitting a PR:

### PHP — Laravel Pint

```bash
# Check
./vendor/bin/pint --test

# Fix
./vendor/bin/pint
```

### JavaScript/Vue — ESLint + Prettier

```bash
# Check
npm run lint

# Fix
npm run lint -- --fix
```

We follow:
- **PHP**: [PSR-12](https://www.php-fig.org/psr/psr-12/) + Laravel conventions
- **Vue**: Composition API with `<script setup>` syntax
- **Controllers**: Keep thin — business logic goes in `app/Services/`
- **Jobs**: All heavy operations (AI calls, message sending) must be queued

---

## Pull Request Process

1. **Branch from `main`**:
   ```bash
   git checkout -b feat/your-feature-name
   ```

2. **Make your changes** with clear, focused commits

3. **Write or update tests** if applicable

4. **Run lint and tests** before pushing:
   ```bash
   ./vendor/bin/pint
   npm run lint -- --fix
   php artisan test
   ```

5. **Push and open a PR**:
   ```bash
   git push origin feat/your-feature-name
   ```

6. In the PR description, title it clearly (see commit convention below) and fill in the **PR template**

7. A maintainer will review within a few days. Be responsive to feedback!

> **Note**: All PRs require CI to pass before merging.

---

## Commit Message Convention

We follow **Conventional Commits**:

```
<type>: <short description>

[optional body]
```

| Type | When to use |
|------|-------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation changes |
| `test` | Adding/fixing tests |
| `refactor` | Code refactoring without feature/fix |
| `chore` | Maintenance, dependency updates |
| `style` | Code style/formatting only |
| `ci` | CI/CD changes |

**Examples:**
```
feat: add webhook handler for WhatsApp message events
fix: correct ORDER_DETECTED parsing for multi-item orders
docs: update docker setup instructions in README
test: add feature tests for AIAgentService
refactor: extract ContextBuilderService from AIAgentService
```

---

## Good First Issues

Never contributed to open source before? Start here:

- 🔤 Fix a typo in README or documentation
- 🌐 Translate README to another language
- ✅ Add a unit test for an existing Service method
- 📸 Add a screenshot to the documentation
- 🏷️ Add a new badge to the README

Check the [`good first issue`](https://github.com/Gimm17/umkm-ai/labels/good%20first%20issue) label on GitHub!

---

## Questions?

Open a [Discussion](https://github.com/Gimm17/umkm-ai/discussions) or [Issue](https://github.com/Gimm17/umkm-ai/issues) — we're happy to help.

Thank you for making UmkmAI better! 🙏
