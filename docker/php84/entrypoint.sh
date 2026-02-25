#!/bin/sh
set -eu

log() {
    printf '%s\n' "[php-entrypoint] $*"
}

APP_DIR="/var/www/html"
if [ -n "${APP_PATH:-}" ] && [ -d "/var/www/html/${APP_PATH}" ]; then
    APP_DIR="/var/www/html/${APP_PATH}"
fi

run_step() {
    # Usage: run_step "description" command...
    DESC="$1"
    shift

    log "${DESC}"
    if "$@"; then
        return 0
    fi

    log "WARN: step failed: ${DESC}"
    if [ "${FAIL_ON_INIT_ERROR:-0}" = "1" ]; then
        log "FAIL_ON_INIT_ERROR=1, exiting"
        exit 1
    fi
    return 0
}

# Support both layouts:
# - Laravel app mounted at ${APP_DIR} (has composer.json)
# - Laravel app is nested at ${APP_DIR}/app (has app/composer.json)
if [ ! -f "${APP_DIR}/composer.json" ] && [ -f "${APP_DIR}/app/composer.json" ]; then
    APP_DIR="${APP_DIR}/app"
fi

if [ -f "${APP_DIR}/composer.json" ]; then

    cd "${APP_DIR}"

    if [ "${SKIP_COMPOSER_INSTALL:-0}" != "1" ] && [ ! -f "vendor/autoload.php" ]; then
        run_step "composer install" composer install --no-interaction --prefer-dist --optimize-autoloader
    fi

    # 2) Laravel app key (only if missing)
    if [ "${SKIP_KEY_GENERATE:-0}" != "1" ] && [ -f "artisan" ] && [ -f ".env" ]; then
        if ! grep -q '^APP_KEY=base64:' .env; then
            run_step "php artisan key:generate" php artisan key:generate --force --no-interaction
        fi
        run_step "php artisan optimize:clear" php artisan optimize:clear --no-interaction
    fi

    # 2.1) Optional migrations/seed (disabled by default)
    if [ "${RUN_MIGRATIONS:-0}" = "1" ] && [ -f "artisan" ]; then
        run_step "php artisan migrate" php artisan migrate --force --no-interaction
    fi
    if [ "${RUN_SEEDERS:-0}" = "1" ] && [ -f "artisan" ]; then
        run_step "php artisan db:seed" php artisan db:seed --force --no-interaction
    fi

    # 3) Node deps (only if not installed) + build (only if missing)
    if [ -f "package.json" ]; then
        if [ "${SKIP_NPM_INSTALL:-0}" != "1" ] && [ ! -d "node_modules" ]; then
            if [ -f "package-lock.json" ]; then
                run_step "npm ci" npm ci
            else
                run_step "npm install" npm install
            fi
        fi

        if [ "${SKIP_NPM_BUILD:-0}" != "1" ] && [ ! -f "public/build/manifest.json" ]; then
            run_step "npm run build" npm run build
        fi
    fi
else
    log "No composer.json in ${APP_DIR}, skipping app init"
fi

log "Starting main process: $*"
if [ "$#" -eq 0 ]; then
    # Keep container running by default (same as base image CMD)
    set -- php-fpm
fi

exec docker-php-entrypoint "$@"

