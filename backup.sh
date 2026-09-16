#!/usr/bin/env bash
# Nightly Postgres backup.
#   0 3 * * * /path/to/frasear/scripts/backup.sh >> /var/log/frasear-backup.log 2>&1

set -euo pipefail

COMPOSE_CMD="${COMPOSE_CMD:-podman-compose}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/frasear}"
KEEP_DAYS=14
COMPOSE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TIMESTAMP="$(date +%Y-%m-%d_%H%M%S)"
FILENAME="${BACKUP_DIR}/frasear_${TIMESTAMP}.dump"

# shellcheck source=/dev/null
set -a; source "${COMPOSE_DIR}/.env"; set +a

mkdir -p "$BACKUP_DIR"

# -Fc (custom format) so pg_restore can do selective restores later.
$COMPOSE_CMD -f "${COMPOSE_DIR}/compose.yml" exec -T postgres \
    pg_dump -U "$DB_USERNAME" -Fc "$DB_DATABASE" > "$FILENAME"

find "$BACKUP_DIR" -name 'frasear_*.dump' -mtime +"$KEEP_DAYS" -delete

# --- OFF-SITE COPY: uncomment one before you have real users ---
# A backup that only exists on the same disk as the database is not a backup.
#
# rclone copy "$FILENAME" remote:frasear-backups/
# restic -r s3:s3.amazonaws.com/your-bucket backup "$FILENAME"

echo "Backup complete: $FILENAME"
