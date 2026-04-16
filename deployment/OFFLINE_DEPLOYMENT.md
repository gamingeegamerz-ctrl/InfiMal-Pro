# INFIMAL Offline Laravel Deployment (Network-Restricted)

Use this flow when the server cannot download Composer dependencies.

## 1) Local build (machine with internet)

From project root:

```bash
composer install --no-dev --optimize-autoloader
zip -qr vendor.zip vendor
```

Or use the helper:

```bash
./scripts/package_vendor.sh
```

## 2) Upload package

Upload `vendor.zip` to the server project root (same folder as `artisan`).

## 3) Extract and verify runtime

```bash
unzip -oq vendor.zip -d .
test -f vendor/autoload.php && echo "vendor autoload OK"
```

## 4) Restore Laravel runtime caches

```bash
php artisan config:clear
php artisan route:clear
php artisan route:cache
php artisan optimize
```

## 5) Run deploy helper (optional)

The deploy helper now supports offline deployments automatically:

```bash
SKIP_TESTS=1 OFFLINE_VENDOR_ZIP=vendor.zip ./scripts/deploy.sh
```

The script will:
- use `vendor/autoload.php` if already present,
- else extract `vendor.zip` if available,
- else fallback to Composer install.

## 6) Smoke test checklist

- Open dashboard.
- Send a test email.
- Trigger tracking events (open/click/bounce/complaint/reply).
- Verify analytics update for the sending user.
