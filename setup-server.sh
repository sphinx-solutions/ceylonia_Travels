#!/usr/bin/env bash
set -euo pipefail

# Ceyloania Travels — production/test setup (SQLite + seed)
# Run from Laravel root, e.g.:
#   cd /home/tharushafdo/web/ceyloniatravels.mihimatha.com/public_html
#   bash setup-server.sh

APP_URL_DEFAULT="https://ceyloniatravels.mihimatha.com"

echo "==> Creating .env if missing"
if [ ! -f .env ]; then
  cp .env.example .env
fi

echo "==> Writing production-oriented .env values"
php -r '
$path = ".env";
$env = file_get_contents($path);
$replacements = [
  "/^APP_ENV=.*/m" => "APP_ENV=production",
  "/^APP_DEBUG=.*/m" => "APP_DEBUG=false",
  "/^APP_URL=.*/m" => "APP_URL=https://ceyloniatravels.mihimatha.com",
  "/^SITE_NAME=.*/m" => "SITE_NAME=\"Ceyloania Travels\"",
  "/^DB_CONNECTION=.*/m" => "DB_CONNECTION=sqlite",
  "/^FILESYSTEM_DISK=.*/m" => "FILESYSTEM_DISK=public",
  "/^SESSION_DRIVER=.*/m" => "SESSION_DRIVER=database",
  "/^CACHE_STORE=.*/m" => "CACHE_STORE=database",
  "/^QUEUE_CONNECTION=.*/m" => "QUEUE_CONNECTION=database",
];
foreach ($replacements as $pattern => $value) {
  if (preg_match($pattern, $env)) {
    $env = preg_replace($pattern, $value, $env);
  } else {
    $env .= PHP_EOL . $value;
  }
}
// Comment out MySQL vars so SQLite is used cleanly
$env = preg_replace("/^DB_HOST=/m", "# DB_HOST=", $env);
$env = preg_replace("/^DB_PORT=/m", "# DB_PORT=", $env);
$env = preg_replace("/^DB_DATABASE=/m", "# DB_DATABASE=", $env);
$env = preg_replace("/^DB_USERNAME=/m", "# DB_USERNAME=", $env);
$env = preg_replace("/^DB_PASSWORD=/m", "# DB_PASSWORD=", $env);
file_put_contents($path, $env);
'

echo "==> Ensuring SQLite database file exists"
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite || true

echo "==> Generating app key (if empty)"
php artisan key:generate --force

echo "==> Storage link + writable permissions"
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache database || true
php artisan storage:link --force || true

echo "==> Migrate + seed"
php artisan migrate --force
php artisan db:seed --force

echo "==> Publish Filament / TinyEditor assets"
php artisan filament:assets || true
php artisan vendor:publish --provider="AmidEsfahani\FilamentTinyEditor\TinyeditorServiceProvider" --tag=public --force || true

echo "==> Optimize caches"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "Done."
echo "Site:  ${APP_URL_DEFAULT}"
echo "Admin: ${APP_URL_DEFAULT}/admin"
echo "Login: admin@ceyloania.test / password"
echo ""
echo "IMPORTANT: Point the domain document root to the /public folder"
echo "  e.g. .../ceyloniatravels.mihimatha.com/public_html/public"
echo "If the panel already points at public_html, add a public_html/index.php that boots public/index.php"
echo "  or symlink/move as your host requires."
