#!/usr/bin/env bash
set -euo pipefail

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
cd "$ROOT"
touch .gitignore

for pattern in ".env" "/backups" "*.sql.gz"; do
  if ! grep -Fqx "$pattern" .gitignore; then
    printf '\n%s\n' "$pattern" >> .gitignore
  fi
done

echo ".gitignore sudah memuat .env, /backups, dan *.sql.gz. Periksa juga aturan Laravel bawaan sebelum commit."
