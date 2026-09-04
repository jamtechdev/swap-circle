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

echo "==> Remove Vite HMR pointer (would blank auth UI on prod/staging)"
rm -f public/hot

echo "==> Symlink static assets for SiteGround nginx docroot (public_html = project root)"
ln -sfn public/build build
ln -sfn public/uploads uploads

echo "==> PHP dependencies"
composer install --no-dev --optimize-autoloader

echo "==> Frontend build (auth/landing/portal CSS+JS)"
if command -v npm >/dev/null 2>&1; then
  npm ci --omit=dev 2>/dev/null || npm ci
  npm run build
else
  echo "WARN: npm not found — ensure public/build was committed or built elsewhere"
fi

if [[ ! -f public/build/manifest.json ]]; then
  echo "ERROR: public/build/manifest.json missing — auth pages will break"
  exit 1
fi
if ! grep -q 'auth.css' public/build/manifest.json; then
  echo "ERROR: auth.css not in Vite manifest — run npm run build"
  exit 1
fi

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

echo "==> Verify SC-07 security headers on login"
curl -sI "https://staging.swapcircle.trade/login" | grep -iE 'strict-transport-security|x-frame-options|content-security-policy|x-content-type-options|referrer-policy' || true

echo "==> Verify login is branded (not blank Vite failure)"
curl -s "https://staging.swapcircle.trade/login" | grep -qi 'Welcome Back' && echo "login copy OK" || echo "WARN: login copy missing — check Vite build + APP_ENV"

echo "==> Verify branded 404"
curl -s "https://staging.swapcircle.trade/this-route-should-404-sc13" | grep -qi 'Page not found' && echo "404 OK" || echo "WARN: branded 404 missing"

echo "Done. Hard-refresh browser: Ctrl+Shift+R"
echo "Confirm .env: APP_ENV=production APP_DEBUG=false API_URL=https://staging.swapcircle.trade/api/ SESSION_SECURE_COOKIE=true"
