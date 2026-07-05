# Production Laravel Kit

Laravel 11 app with branching quiz funnel, Stripe checkout, Filament admin, Sanctum API, and Horizon queues.

## Modules

Domain-driven layout under `app/Domain/`. Each module owns models, services, and jobs; HTTP adapters live in `app/Http/` and `app/Filament/`.

| Module | Path | Summary |
|--------|------|---------|
| Auth | `app/Domain/Auth/` | Spatie roles + Sanctum API tokens |
| Integrations | `app/Domain/Integrations/` | Encrypted Stripe credentials in DB |
| Quiz | `app/Domain/Quiz/` | Branching funnel, sessions, Livewire + API |
| Payments | `app/Domain/Payments/` | Products, orders, Stripe Checkout, webhooks |
| Admin | `app/Filament/` | Filament CRUD for all domains |
| Queues | `app/Domain/*/Jobs/` + Horizon | Async quiz completion + order receipts |
| API | `routes/api.php` | Sanctum JSON API under `/api/v1/*` |
| Public UI | `app/Http/Livewire/` + `resources/views/` | Landing, quiz, checkout |

### Module points

#### Auth
- **Roles:** Admin, Clinic, Pharmacy, Patient (`RoleName` enum + Spatie Permission)
- **Admin bypass:** `AuthServiceProvider` grants Admin full gate access
- **API:** register, login, logout, me via Sanctum personal access tokens
- **Routes:** `POST /api/v1/auth/register|login`, `GET /api/v1/auth/me` (auth)
- **Seeded users:** four role-based demo accounts (see below)

#### Integrations
- **Purpose:** Store third-party API keys encrypted — never in `.env`
- **Model:** `Integration` with provider enum (`IntegrationProvider::Stripe`)
- **Service:** `IntegrationCredentialService` — encrypt/decrypt using `APP_KEY`
- **Admin:** Filament `IntegrationResource` — paste Stripe test keys in sandbox
- **Consumed by:** Payments module (`StripeCheckoutService`, webhook processor)

#### Quiz
- **Purpose:** Branching health assessment funnel (questions → options → next question or terminal outcome)
- **Models:** `Quiz`, `QuizQuestion`, `QuizOption`, `QuizSession`, `QuizResponse`
- **Service:** `QuizFunnelService` — session lifecycle, answer validation, branch routing
- **Web UI:** Livewire `QuizFunnel` at `/quiz/{slug}` with progress bar
- **API:** start session, fetch current question, submit answer
- **Job:** `ProcessQuizCompletionJob` — queued on terminal outcome
- **Routes:** `GET /quiz/health-assessment` · `POST /api/v1/quizzes/{slug}/sessions` · `POST /api/v1/quiz-sessions/{id}/questions/{id}/answer`
- **Test:** `tests/Feature/QuizFunnelTest` — branching path to outcome

#### Payments
- **Purpose:** Sell consultation packages via Stripe Checkout (sandbox)
- **Models:** `Product`, `Order`, `Payment`, `PaymentWebhookEvent`
- **Services:** `StripeCheckoutService` (session creation), `PaymentWebhookProcessor` (idempotent status updates)
- **Web UI:** Livewire `CheckoutFlow` at `/checkout/{slug}`; success/cancel redirect routes
- **Webhook:** `POST /webhooks/stripe` — signature verify + duplicate event guard
- **Job:** `SendOrderReceiptJob` + `OrderReceiptMail` on successful payment
- **API:** list products, create checkout session (returns Stripe URL)
- **Depends on:** Integrations module for live Stripe keys

#### Admin (Filament)
- **Panel:** `/admin` — single Filament 3 panel (`AdminPanelProvider`)
- **Resources:** Integrations, Quizzes (+ questions/options), Products, Orders
- **Access:** authenticated users with Admin role (or gate bypass)
- **Horizon:** `/horizon` — Admin-only queue dashboard (`HorizonServiceProvider`)

#### Queues & Horizon
- **Driver:** Redis (`QUEUE_CONNECTION=redis`)
- **Jobs:** quiz completion processing, order receipt email
- **Ops:** `php artisan horizon` or Docker `horizon` service
- **Pattern:** dispatch from domain services/controllers — no fat job classes

#### API (Sanctum)
- **Prefix:** `/api/v1/*`
- **Public:** auth register/login
- **Protected:** quiz sessions, products, checkout creation
- **Auth header:** `Authorization: Bearer {token}` · `Accept: application/json`
- **Controllers:** `Api/AuthController`, `Api/QuizController`, `Api/CheckoutController`

#### Public UI
- **Landing:** `/` — Tailwind marketing page (health/clinic vertical)
- **Quiz:** Livewire funnel with step progress and outcome screen
- **Checkout:** product summary → redirect to Stripe Checkout → success/cancel pages
- **Assets:** Vite + Tailwind (`npm run build`)

### Module rules (for contributors)

- New business logic goes in the **correct domain folder** under `app/Domain/` — not in controllers
- Controllers and Livewire components are **thin** — delegate to domain services
- Cross-domain access via **services** (e.g. Payments reads Integrations via `IntegrationCredentialService`)
- Stripe keys only via Integrations — never hardcode or add to `.env`
- New features get Conventional Commits scoped to domain: `feat(quiz): …`, `fix(payments): …`

## Requirements

- PHP 8.2+ with `pdo`, `mbstring`, `openssl`, `intl` (Filament). Homebrew PHP 8.4 is built without `intl` — use PHP 8.2 locally (`brew link php@8.2 --force --overwrite`) or run `composer install` as documented below.
- Composer 2
- Node 20+ (Vite + Tailwind)
- Redis (queues / Horizon)
- PostgreSQL or SQLite (local)

## Setup

```bash
git clone <repo-url> production-laravel-kit
cd production-laravel-kit
composer install --ignore-platform-req=ext-intl   # required on Homebrew PHP 8.4 (no intl)
# Or switch to PHP 8.2: brew link php@8.2 --force --overwrite && composer install
cp .env.example .env
php artisan key:generate
```

### SQLite (fastest local)

```bash
touch database/database.sqlite
# Set in .env:
# DB_CONNECTION=sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

### Docker

```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
```

Visit http://localhost:8080

### Stripe sandbox

1. Log into Filament: `/admin` as `admin@example.com` / `password`
2. Open **Integrations** and replace placeholder Stripe test keys
3. Forward webhooks: `stripe listen --forward-to localhost:8000/webhooks/stripe`

Stripe secrets are **not** stored in `.env` — only in the encrypted `integrations` table.

### Queues & Horizon

```bash
# .env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1

php artisan horizon
```

Horizon UI: `/horizon` (Admin role)

## Demo routes

| URL | Description |
|-----|-------------|
| `/` | Landing page |
| `/quiz/health-assessment` | Branching quiz (Livewire) |
| `/checkout/consultation-package` | Stripe checkout flow |
| `/admin` | Filament panel |
| `/api/v1/*` | Sanctum JSON API |

## API examples

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"Mobile User","email":"mobile@example.com","password":"password","password_confirmation":"password","device_name":"ios"}'

# Start quiz session
curl -X POST http://localhost:8000/api/v1/quizzes/health-assessment/sessions \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## Default users

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| clinic@example.com | password | Clinic |
| pharmacy@example.com | password | Pharmacy |
| patient@example.com | password | Patient |

## Live demo

Pending deployment.

## License

MIT
