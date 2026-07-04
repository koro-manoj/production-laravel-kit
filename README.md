# Production Laravel Kit

Modular Laravel 11 showcase: branching quiz funnel, Stripe sandbox checkout with database-stored credentials, Filament admin, Sanctum mobile API, and Horizon-backed queues.

## Modules

| Module | Path | Capabilities |
|--------|------|--------------|
| Auth | `app/Domain/Auth/` | Spatie roles: Admin, Clinic, Pharmacy, Patient |
| Integrations | `app/Domain/Integrations/` | Encrypted Stripe credentials in DB |
| Quiz | `app/Domain/Quiz/` | Branching funnel, sessions, Livewire + API |
| Payments | `app/Domain/Payments/` | Products, orders, Stripe Checkout, webhooks |
| Admin | `app/Filament/` | Integrations, quizzes, orders |
| API | `routes/api.php` | Sanctum auth, quiz, checkout endpoints |

## Requirements

- PHP 8.2+ with `pdo`, `mbstring`, `openssl`, `intl` (Filament)
- Composer 2
- Node 20+ (Vite + Tailwind)
- Redis (queues / Horizon)
- PostgreSQL or SQLite (local)

## Setup

```bash
git clone <repo-url> production-laravel-kit
cd production-laravel-kit
composer install --ignore-platform-req=ext-intl   # if intl missing locally
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

## Documentation

- [Architecture](docs/architecture.md)
- [Reliability & debug playbook](docs/reliability-debug-playbook.md)

## Default users

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| clinic@example.com | password | Clinic |
| pharmacy@example.com | password | Pharmacy |
| patient@example.com | password | Patient |

## License

MIT
