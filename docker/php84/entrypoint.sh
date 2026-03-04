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

if [ ! -f "${APP_DIR}/composer.json" ] && [ -f "${APP_DIR}/app/composer.json" ]; then
    APP_DIR="${APP_DIR}/app"
fi

if [ -f "${APP_DIR}/composer.json" ]; then

    cd "${APP_DIR}"

    if [ "${SKIP_COMPOSER_INSTALL:-0}" != "1" ] && [ ! -f "vendor/autoload.php" ]; then
        run_step "composer install" composer install --no-interaction --prefer-dist --optimize-autoloader
    fi
else
    log "No composer.json in ${APP_DIR}, skipping app init"
fi

if [ -f "package.json" ]; then
        run_step "npm install" npm install
        run_step "npm run build" npm run build-css
    fi

run_step "php src/Сonsole/seederScript.php" php src/Сonsole/seederScript.php

log "Starting main process: $*"
if [ "$#" -eq 0 ]; then
    # Keep container running by default (same as base image CMD)
    set -- php-fpm
fi

exec docker-php-entrypoint "$@"

