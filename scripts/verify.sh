#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

export APP_ENV=test
export DB_DRIVER=sqlite
export SQLITE_PATH="$ROOT_DIR/var/simple_blog_verify.sqlite"

"$ROOT_DIR/scripts/bootstrap.sh"

rm -f "$SQLITE_PATH"

php "$ROOT_DIR/scripts/migrate.php"

php "$ROOT_DIR/scripts/run_tests.php"

"$ROOT_DIR/scripts/smoke_test.sh"
