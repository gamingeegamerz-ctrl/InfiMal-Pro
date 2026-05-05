#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="${PROJECT_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
OUTPUT_ZIP="${OUTPUT_ZIP:-vendor.zip}"

cd "$PROJECT_DIR"

echo "[package-vendor] Building production vendor/ locally"
"$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

if [[ ! -f vendor/autoload.php ]]; then
  echo "[package-vendor] ERROR: vendor/autoload.php was not generated" >&2
  exit 1
fi

echo "[package-vendor] Creating $OUTPUT_ZIP"
rm -f "$OUTPUT_ZIP"
zip -qr "$OUTPUT_ZIP" vendor

echo "[package-vendor] Done: $(pwd)/$OUTPUT_ZIP"
