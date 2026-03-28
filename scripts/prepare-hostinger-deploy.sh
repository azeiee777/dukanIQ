#!/usr/bin/env bash
set -euo pipefail

echo "📦 Preparing Hostinger deploy package..."

# Clean previous deploy artifacts
rm -rf .deploy
mkdir -p .deploy/laravel_app
mkdir -p .deploy/public_html

echo "📁 Copying Laravel app files..."
rsync -a \
  --exclude='.git' \
  --exclude='.github' \
  --exclude='.deploy' \
  --exclude='node_modules' \
  --exclude='tests' \
  --exclude='.env' \
  --exclude='storage/app' \
  --exclude='storage/framework' \
  --exclude='storage/logs' \
  --exclude='public/build' \
  --exclude='public/storage' \
  --exclude='scripts' \
  . .deploy/laravel_app/

echo "📁 Copying public files..."
rsync -a \
  public/ .deploy/public_html/

# Copy built frontend assets into public_html
if [ -d "public/build" ]; then
  echo "🎨 Copying built frontend assets..."
  rsync -a public/build/ .deploy/public_html/build/
fi

echo "✅ Deploy package ready!"
ls -la .deploy/