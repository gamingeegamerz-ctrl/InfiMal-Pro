#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="${PROJECT_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
ENV_FILE="${ENV_FILE:-.env}"
OFFLINE_VENDOR_ZIP="${OFFLINE_VENDOR_ZIP:-vendor.zip}"
SKIP_TESTS="${SKIP_TESTS:-1}"

cd "$PROJECT_DIR"

echo "[deploy] Working directory: $PROJECT_DIR"

if [[ -f vendor/autoload.php ]]; then
  echo "[deploy] vendor/autoload.php already present; skipping dependency install"
elif [[ -f "$OFFLINE_VENDOR_ZIP" ]]; then
  echo "[deploy] Using offline package $OFFLINE_VENDOR_ZIP"
  unzip -oq "$OFFLINE_VENDOR_ZIP" -d "$PROJECT_DIR"
else
  echo "[deploy] No packaged vendor found; attempting composer install"
  "$COMPOSER_BIN" install --no-interaction --prefer-dist --optimize-autoloader
fi

if [[ ! -f vendor/autoload.php ]]; then
  echo "[deploy] ERROR: vendor/autoload.php missing after dependency step" >&2
  exit 1
fi

if [[ ! -f "$ENV_FILE" ]]; then
  echo "[deploy] $ENV_FILE not found; creating from .env.example"
  cp .env.example "$ENV_FILE"
fi

if ! grep -qE "^APP_KEY=base64:" "$ENV_FILE"; then
  echo "[deploy] Generating APP_KEY"
  "$PHP_BIN" artisan key:generate --force
fi

echo "[deploy] Running database migrations"
"$PHP_BIN" artisan migrate --force

if [[ "$SKIP_TESTS" != "1" ]]; then
  echo "[deploy] Running tests"
  "$PHP_BIN" artisan test --testsuite=Unit,Feature
else
  echo "[deploy] SKIP_TESTS=1, skipping test suite during deployment"
fi

echo "[deploy] Restoring Laravel runtime caches"
"$PHP_BIN" artisan config:clear
"$PHP_BIN" artisan route:clear
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan optimize

echo "[deploy] Done"
