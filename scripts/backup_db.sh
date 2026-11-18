#!/usr/bin/env bash
set -euo pipefail

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
ENV_FILE="$ROOT/.env"
MYSQL_CNF="${MYSQL_CNF:-$HOME/.my.cnf}"

if [[ ! -f "$ENV_FILE" ]]; then
  echo ".env tidak ditemukan di $ROOT" >&2
  exit 1
fi
if [[ ! -r "$MYSQL_CNF" ]]; then
  echo "File kredensial MySQL tidak dapat dibaca: $MYSQL_CNF" >&2
  echo "Siapkan ~/.my.cnf dan chmod 600 ~/.my.cnf sesuai docs/BACKUP_SOP.md." >&2
  exit 1
fi

DB_NAME="${DB_DATABASE:-$(grep -E '^DB_DATABASE=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\"' | tr -d "'")}"
if [[ -z "$DB_NAME" || ! "$DB_NAME" =~ ^[A-Za-z0-9_]+$ ]]; then
  echo "DB_DATABASE pada .env kosong atau tidak valid." >&2
  exit 1
fi

BACKUP_DIR="${BACKUP_DIR:-$ROOT/backups}"
mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"
STAMP="$(date +%Y%m%d_%H%M)"
OUT="$BACKUP_DIR/${DB_NAME}_${STAMP}.sql.gz"

mysqldump --defaults-extra-file="$MYSQL_CNF" --single-transaction --routines --triggers "$DB_NAME" | gzip -9 > "$OUT"
gzip -t "$OUT"
chmod 600 "$OUT"

echo "Backup berhasil: $OUT"
echo "Salin file ini ke drive bersama di luar server/repo. Jangan commit dump database."
