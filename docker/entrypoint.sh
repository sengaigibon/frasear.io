#!/usr/bin/env bash
set -euo pipefail

# Wait for Postgres to accept connections. On a cold `docker compose up` the
# app container is usually ready before the database finishes initialising,
# and `artisan migrate` would fail on connection refused.
if [[ -n "${DB_HOST:-}" ]]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-5432}..."
    for i in {1..30}; do
        if php -r "
            try {
                new PDO(
                    'pgsql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 5432) . ';dbname=' . getenv('DB_DATABASE'),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD')
                );
                exit(0);
            } catch (Throwable \$e) {
                exit(1);
            }
        " 2>/dev/null; then
            echo "Database is up."
            break
        fi
        if [[ $i -eq 30 ]]; then
            echo "Database did not become ready in time." >&2
            exit 1
        fi
        sleep 2
    done
fi

# --force skips the interactive confirm that artisan shows when APP_ENV=production.
php artisan migrate --force

# Cache config/routes/views for production speed. Done at runtime, not build
# time, because config:cache bakes in env values — baking them into the image
# would freeze whatever placeholder values existed during the build.
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
