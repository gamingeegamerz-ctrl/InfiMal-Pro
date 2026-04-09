#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="${PROJECT_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
ENV_FILE="${ENV_FILE:-.env}"

cd "$PROJECT_DIR"

echo "[deploy] Working directory: $PROJECT_DIR"

echo "[deploy] Installing PHP dependencies"
"$COMPOSER_BIN" install --no-interaction --prefer-dist --optimize-autoloader

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

echo "[deploy] Running tests"
"$PHP_BIN" artisan test --testsuite=Unit,Feature

echo "[deploy] Warming Laravel caches"
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "[deploy] Done"
