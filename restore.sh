#!/usr/bin/env bash
# Restore a dump. Test this against a scratch database NOW, not during an outage.
#   ./restore.sh /var/backups/frasear/frasear_2026-09-10_030000.dump

set -euo pipefail

[[ $# -eq 1 ]] || { echo "Usage: $0 <dump-file>"; exit 1; }

COMPOSE_CMD="${COMPOSE_CMD:-podman-compose}"
DUMP_FILE="$1"
COMPOSE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# shellcheck source=/dev/null
set -a; source "${COMPOSE_DIR}/.env"; set +a

$COMPOSE_CMD -f "${COMPOSE_DIR}/compose.yml" exec -T postgres \
    pg_restore -U "$DB_USERNAME" -d "$DB_DATABASE" --clean --if-exists --no-owner \
    < "$DUMP_FILE"

echo "Restore complete from: $DUMP_FILE"
