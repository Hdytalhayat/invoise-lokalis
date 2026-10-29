#!/bin/sh
set -e

cd /var/www/html

# Install PHP dependencies on first run (named volume may be empty)
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Ensure writable directories exist with correct permissions
mkdir -p \
    writable/cache \
    writable/debugbar \
    writable/logs \
    writable/session \
    writable/uploads \
    public/uploads/qris
chown -R www-data:www-data writable public/uploads 2>/dev/null || true
chmod -R 775 writable public/uploads

# Wait for the database to be ready
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
php -r '
$host = getenv("DB_HOST") ?: "db";
$port = (int) (getenv("DB_PORT") ?: 3306);
for ($i = 0; $i < 60; $i++) {
    $fp = @fsockopen($host, $port, $errno, $errstr, 2);
    if ($fp) {
        fclose($fp);
        echo "Database is up.\n";
        exit(0);
    }
    sleep(1);
}
fwrite(STDERR, "Database not reachable.\n");
exit(1);
'

# Run migrations (idempotent)
echo "Running migrations..."
php spark migrate || echo "Migration step finished with warnings."

exec "$@"