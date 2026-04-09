# InfiMal

InfiMal is a Laravel 12 email marketing SaaS with strict paid-access enforcement, per-user data isolation, SMTP-based delivery, queue-backed campaign sending, and first-party analytics tracking.

## Core platform behavior

- Users register as **unpaid** and cannot access any dashboard feature until a verified payment activates an account.
- Every core data model is scoped by `user_id` for private campaigns, subscribers, SMTP accounts, analytics, and messages.
- Campaign sends are queued into `email_jobs` and processed by Laravel workers.
- Tracking endpoints persist opens, clicks, and bounces into dedicated tables.
- SMTP credentials are encrypted in the database.

## Required environment variables

Copy `.env.example` and configure at minimum:

```env
APP_NAME=InfiMal
APP_ENV=production
APP_URL=https://your-domain.example
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=infimal
DB_USERNAME=infimal
DB_PASSWORD=secret
QUEUE_CONNECTION=database
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYPAL_MODE=live
PAYPAL_WEBHOOK_ID=
```

## Deployment steps

### Option A (recommended): one command deploy script

Run the bundled script from the project root:

```bash
bash scripts/deploy.sh
```

The script installs Composer dependencies, creates `.env` if missing, generates `APP_KEY` when needed, runs migrations, executes Unit/Feature tests, and warms config/route/view caches.

### Option B: manual deployment commands

1. `composer install --no-interaction --prefer-dist --optimize-autoloader`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `php artisan test --testsuite=Unit,Feature`
6. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
7. Run a queue worker on the server:
   - `php artisan queue:work --queue=default,emails --tries=3 --sleep=3`
8. Configure your web server to serve `public/` and your scheduler if you add recurring jobs.

### Composer network troubleshooting

If `composer install` fails with GitHub `CONNECT tunnel failed, response 403` errors, your server/proxy is blocking GitHub package downloads. Allow outbound access to:

- `github.com`
- `api.github.com`
- `codeload.github.com`

You can also configure an authenticated proxy in Composer (if your infrastructure requires one) before running deployment.

## PayPal flow

- Billing starts from `/billing`.
- InfiMal creates the PayPal order on the backend.
- PayPal redirects back to `/payment/success`.
- The backend verifies the approved order, captures it server-side, and only then activates the user and license.
- Optional webhook verification is supported via `PAYPAL_WEBHOOK_ID`.

## Queue and sending notes

- Add at least one SMTP account before sending campaigns.
- Campaign send actions create queued `email_jobs` records per active subscriber.
- Start a queue worker in production or no emails will leave the queue.
