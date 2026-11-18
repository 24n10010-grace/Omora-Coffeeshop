#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Pemakaian: bash scripts/restore_db.sh <file-backup.sql.gz>" >&2
  exit 2
fi

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
ENV_FILE="$ROOT/.env"
MYSQL_CNF="${MYSQL_CNF:-$HOME/.my.cnf}"
BACKUP_FILE="$1"

[[ -f "$ENV_FILE" ]] || { echo ".env tidak ditemukan." >&2; exit 1; }
[[ -r "$MYSQL_CNF" ]] || { echo "Kredensial MySQL tidak dapat dibaca: $MYSQL_CNF" >&2; exit 1; }
[[ -f "$BACKUP_FILE" ]] || { echo "File backup tidak ditemukan: $BACKUP_FILE" >&2; exit 1; }
gzip -t "$BACKUP_FILE"

DB_NAME="${DB_DATABASE:-$(grep -E '^DB_DATABASE=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\"' | tr -d "'")}"
if [[ -z "$DB_NAME" || ! "$DB_NAME" =~ ^[A-Za-z0-9_]+$ ]]; then
  echo "DB_DATABASE pada .env kosong atau tidak valid." >&2
  exit 1
fi

echo "PERINGATAN: restore akan mengimpor isi backup ke database '$DB_NAME'."
echo "Gunakan database kosong/khusus pengujian; script ini tidak menghapus database yang sudah ada."
read -r -p "Ketik RESTORE untuk melanjutkan: " CONFIRM
[[ "$CONFIRM" == "RESTORE" ]] || { echo "Dibatalkan."; exit 1; }

gunzip -c "$BACKUP_FILE" | mysql --defaults-extra-file="$MYSQL_CNF" "$DB_NAME"
echo "Restore selesai ke database: $DB_NAME"
