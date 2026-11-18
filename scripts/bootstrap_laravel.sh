#!/usr/bin/env bash
set -euo pipefail

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
cd "$ROOT"

if [[ -f artisan && -f composer.json ]]; then
  echo "Skeleton Laravel sudah ada; menjalankan langkah pemulihan/penyelesaian dependensi."
  command -v composer >/dev/null 2>&1 || { echo "Composer 2 belum tersedia." >&2; exit 1; }
  [[ -f .env ]] || cp .env.example .env
  composer install
  php artisan key:generate
  exit 0
fi

if [[ -e artisan || -e composer.json ]]; then
  echo "Ditemukan composer.json atau artisan yang tidak lengkap. Periksa kondisi repository secara manual; script tidak akan menimpa file parsial." >&2
  exit 1
fi

command -v composer >/dev/null 2>&1 || { echo "Composer 2 belum tersedia." >&2; exit 1; }

TMP_DIR="$(mktemp -d)"
cleanup() { rm -rf "$TMP_DIR"; }
trap cleanup EXIT

composer create-project laravel/laravel "$TMP_DIR/laravel" --no-install --no-interaction --no-scripts
rm -f "$TMP_DIR/laravel/.env"
rm -rf "$TMP_DIR/laravel/.git"

# Merge file skeleton ke root dan jangan menimpa file/dokumen yang telah ada.
cp -a -n "$TMP_DIR/laravel/." "$ROOT/"

mkdir -p \
  app/Enums \
  app/Http/Controllers/Api/V1 \
  app/Http/Requests \
  app/Http/Resources \
  app/Services/Inventory \
  database/migrations database/seeders database/factories \
  docs postman scripts \
  tests/Unit/Inventory tests/Feature/Api

for dir in app/Enums app/Http/Controllers/Api/V1 app/Http/Requests app/Http/Resources \
  app/Services/Inventory postman tests/Unit/Inventory tests/Feature/Api; do
  [[ -n "$(find "$dir" -mindepth 1 -maxdepth 1 -not -name .gitkeep -print -quit 2>/dev/null)" ]] || touch "$dir/.gitkeep"
done

if [[ ! -f .env ]]; then
  cp .env.example .env
fi

composer install
php artisan key:generate

echo "Skeleton Laravel sudah ditambahkan secara non-destructive. Lanjutkan dengan docs/ENVIRONMENT.md."
