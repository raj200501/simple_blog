#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"
SEED_DATA="${SEED_DATA:-true}"

"$ROOT_DIR/scripts/bootstrap.sh"

php "$ROOT_DIR/scripts/migrate.php"

if [ "$SEED_DATA" = "true" ]; then
  php "$ROOT_DIR/scripts/seed.php"
fi

echo "Starting Simple Blog at http://$HOST:$PORT"
exec php -S "$HOST:$PORT" -t "$ROOT_DIR/public"
