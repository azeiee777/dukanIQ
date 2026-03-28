#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DEPLOY_ROOT="$ROOT_DIR/.deploy"
APP_STAGE="$DEPLOY_ROOT/laravel_app"
PUBLIC_STAGE="$DEPLOY_ROOT/public_html"

rm -rf "$DEPLOY_ROOT"
mkdir -p "$APP_STAGE" "$PUBLIC_STAGE"

rsync -a "$ROOT_DIR"/ "$APP_STAGE"/ \
  --exclude ".deploy" \
  --exclude ".git" \
  --exclude ".github" \
  --exclude ".idea" \
  --exclude ".vscode" \
  --exclude "docs" \
  --exclude "node_modules" \
  --exclude "tests" \
  --exclude ".env" \
  --exclude ".env.*" \
  --exclude ".phpunit.result.cache" \
  --exclude "database/database.sqlite" \
  --exclude "storage" \
  --exclude "bootstrap/cache"

rsync -a "$ROOT_DIR/public"/ "$PUBLIC_STAGE"/

sed -i "s#__DIR__.'/../storage#__DIR__.'/../laravel_app/storage#g" "$PUBLIC_STAGE/index.php"
sed -i "s#__DIR__.'/../vendor/autoload.php'#__DIR__.'/../laravel_app/vendor/autoload.php'#g" "$PUBLIC_STAGE/index.php"
sed -i "s#__DIR__.'/../bootstrap/app.php'#__DIR__.'/../laravel_app/bootstrap/app.php'#g" "$PUBLIC_STAGE/index.php"

tar -czf "$DEPLOY_ROOT/laravel_app.tar.gz" -C "$DEPLOY_ROOT" laravel_app
tar -czf "$DEPLOY_ROOT/public_html.tar.gz" -C "$DEPLOY_ROOT" public_html

printf 'Prepared deploy package in %s\n' "$DEPLOY_ROOT"
