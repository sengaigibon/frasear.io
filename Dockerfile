# syntax=docker/dockerfile:1

# ---------- Stage 1: build frontend assets ----------
# Vite compiles to public/build/. Without this stage the @vite() directive
# in your Blade layouts throws at runtime, since there's no manifest.
FROM node:22-bookworm-slim AS assets

WORKDIR /build

# .npmrc sets ignore-scripts=true; --ignore-scripts here keeps the container
# install consistent with what you get locally.
COPY package.json package-lock.json .npmrc ./
RUN npm ci --ignore-scripts

# Vite needs the config + everything it scans: tailwind.config.js has content
# globs pointing at resources/views, so the Blade files must be present or
# Tailwind will tree-shake away every class it can't see.
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build


# ---------- Stage 2: PHP runtime ----------
FROM php:8.4-fpm-bookworm AS app

# pdo_pgsql for Postgres; zip/intl for general Laravel use; opcache for speed.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        zip \
        intl \
        opcache \
        bcmath \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first, separately from app code, so Docker can cache this
# layer — it only re-runs when composer.json/lock actually change.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# Now the application code.
COPY . .

# Composer/artisan shell out to git for package metadata; the container
# builds as root, which git treats as "dubious ownership" by default.
# Must run before any composer command below.
RUN git config --global --add safe.directory /var/www/html

# Defensive: wipe any cached provider/config manifests that may have leaked
# in from the host via the COPY above (e.g. bootstrap/cache/packages.php
# referencing dev-only packages like Laravel Boost). Must run BEFORE the
# composer install below, since that install triggers `package:discover`,
# which reads this cache before it gets a chance to regenerate it.
RUN rm -f bootstrap/cache/*.php

# podman-compose's build path does not reliably honour .dockerignore /
# .containerignore (a known rough edge distinct from native `podman build`),
# so COPY . . above may have overwritten the clean --no-dev vendor/ from
# step 6 with whatever vendor/ exists on the host (dev packages included).
# Rather than depend on ignore-file support working, force correctness:
# discard whatever vendor/ we now have and reinstall clean from
# composer.lock. This also regenerates the optimized autoloader and runs
# `package:discover` automatically (it's wired to composer's
# post-autoload-dump event) — no separate dump-autoload step needed.
# Composer's own package cache is already warm from step 6, so this hits
# local disk rather than re-downloading over the network.
RUN rm -rf vendor \
    && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Ongoing safety net: fail loudly if a dev-only package ever ends up in a
# --no-dev image, instead of surfacing as a cryptic "class not found" error
# at runtime.
RUN if [ -d vendor/laravel/boost ]; then \
        echo "ERROR: vendor/laravel/boost present in a --no-dev build." >&2; \
        exit 1; \
    fi

# Compiled assets from stage 1.
COPY --from=assets /build/public/build ./public/build

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/99-opcache.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# php-fpm runs as www-data; it needs to write logs, cache and sessions.
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
