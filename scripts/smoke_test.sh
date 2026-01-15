#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
HOST="127.0.0.1"
PORT="8040"
BASE_URL="http://$HOST:$PORT"

export SQLITE_PATH="${SQLITE_PATH:-$ROOT_DIR/var/simple_blog_smoke.sqlite}"

rm -f "$SQLITE_PATH"

php "$ROOT_DIR/scripts/migrate.php"

php -S "$HOST:$PORT" -t "$ROOT_DIR/public" > /tmp/simple_blog_server.log 2>&1 &
SERVER_PID=$!

cleanup() {
  kill "$SERVER_PID" >/dev/null 2>&1 || true
}
trap cleanup EXIT

attempts=0
until curl -s "$BASE_URL/health.php" | grep -q '"status"'; do
  attempts=$((attempts + 1))
  if [ "$attempts" -gt 20 ]; then
    echo "Server did not start in time."
    cat /tmp/simple_blog_server.log
    exit 1
  fi
  sleep 0.2
done

health_json=$(curl -s "$BASE_URL/health.php")
if ! echo "$health_json" | grep -q '"status":"ok"'; then
  echo "Health check failed: $health_json"
  exit 1
fi

curl -s -X POST "$BASE_URL/create.php" \
  -d "title=Smoke+Test+Post" \
  -d "content=This+post+was+created+by+the+smoke+test." \
  > /dev/null

index_html=$(curl -s "$BASE_URL/index.php")
if ! echo "$index_html" | grep -q "Smoke Test Post"; then
  echo "Post not found on index page."
  exit 1
fi

post_id=$(php -r "\$pdo = new PDO('sqlite:' . getenv('SQLITE_PATH')); \$id = \$pdo->query('SELECT id FROM posts ORDER BY id DESC LIMIT 1')->fetchColumn(); echo \$id;")
view_html=$(curl -s "$BASE_URL/view.php?id=$post_id")
if ! echo "$view_html" | grep -q "smoke test"; then
  echo "Post content not found on view page."
  exit 1
fi

echo "Smoke test passed."
