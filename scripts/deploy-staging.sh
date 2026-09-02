#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "==> Reset local lockfile drift (server should not edit package-lock.json)"
git checkout -- package-lock.json 2>/dev/null || true

echo "==> Pull latest staging"
git fetch origin
git checkout staging
git pull origin staging

echo "==> Symlink static assets for SiteGround nginx docroot (public_html = project root)"
ln -sfn public/build build
ln -sfn public/uploads uploads

echo "==> PHP dependencies"
composer install --no-dev --optimize-autoloader

echo "==> Laravel optimize"
php artisan storage:link --force 2>/dev/null || true
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Verify Vite chunk is served as JavaScript"
curl -sI "https://staging.swapcircle.trade/build/assets/chunk-_FHsfQqc.js" | grep -i content-type || true

echo "Done. Hard-refresh browser: Ctrl+Shift+R"
