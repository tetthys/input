#!/usr/bin/env bash
set -euo pipefail

echo "📦 Setting up PHP 8.3 + Pest 4.1.3 environment..."

if ! command -v docker &>/dev/null; then
  echo "❌ Docker not found. Please install Docker first."
  exit 1
fi

export COMPOSE_PROJECT_NAME="tetthys_pest413"

# 1️⃣ Build the image
docker compose build --pull php

# 2️⃣ Run composer and pest installation inside the container
docker compose run --rm php bash -lc "
  set -euo pipefail &&
  echo '→ Installing base dependencies...' &&
  composer install --no-interaction --no-progress --prefer-dist &&
  echo '→ Removing PHPUnit...' &&
  composer remove phpunit/phpunit --no-interaction --ansi || true &&
  echo '→ Installing Pest 4.1.3 with all dependencies...' &&
  composer require pestphp/pest:4.1.3 --dev --with-all-dependencies --no-interaction --prefer-dist --ansi &&
  echo '→ Initializing Pest configuration...' &&
  ./vendor/bin/pest --init &&
  echo '→ Dumping optimized autoload...' &&
  composer dump-autoload -o &&
  echo '✅ Pest 4.1.3 environment ready.'
"

echo "✅ Installation complete. Run './run/test.sh' to execute tests."
