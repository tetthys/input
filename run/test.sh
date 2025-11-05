#!/usr/bin/env bash
set -euo pipefail

echo "🧪 Running Pest tests (interactive) inside PHP 8.3 container..."

if ! command -v docker &>/dev/null; then
  echo "❌ Docker not found. Please install Docker first."
  exit 1
fi

export COMPOSE_PROJECT_NAME="tetthys_pest413"

# Optional target (e.g., ./run/test.sh tests/Feature)
TEST_TARGET="${1:-}"

# Ensure image exists
docker compose build php >/dev/null

# Interactive run so Pest output (colors, progress, summary) shows properly
if [ -z "$TEST_TARGET" ]; then
  docker compose run --rm -it php bash -lc "
    set -euo pipefail
    echo '▶ Executing all Pest tests (auto scan)...'
    ./vendor/bin/pest --colors=always
  "
else
  docker compose run --rm -it php bash -lc "
    set -euo pipefail
    echo '▶ Executing Pest tests in target: $TEST_TARGET'
    ./vendor/bin/pest --colors=always $TEST_TARGET
  "
fi

echo "✅ Pest tests finished successfully."
