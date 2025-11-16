#!/usr/bin/env bash
set -euo pipefail
cd /var/www/html

DEV_JSON="${COMPOSER_DEV_JSON:-composer-dev.json}"
DEV_LOCK="${COMPOSER_DEV_LOCK:-composer-dev.lock}"

# Si no existe composer-dev.json, copiar desde composer.json si existe
if [[ ! -f "$DEV_JSON" && -f "composer.json" ]]; then
  cp composer.json "$DEV_JSON"
fi

# Quitar platform.php heredado (si existiera)
composer config --no-plugins --no-scripts --unset platform.php || true
COMPOSER="$DEV_JSON" composer config --no-plugins --no-scripts --unset platform.php || true

# Usar la versión EXACTA del PHP del contenedor (8.2.x)
PHPV="$(php -r 'echo PHP_VERSION;')"
# Requerir PHP >=8.2 mediante 'composer require' (no 'composer config')
if [[ -f "composer.json" ]]; then
  COMPOSER="composer.json" composer require "php:${PHPV}" --no-update --no-interaction || true
fi
if [[ -f "$DEV_JSON" ]]; then
  COMPOSER="$DEV_JSON" composer require "php:${PHPV}" --no-update --no-interaction || true
fi

# Fijar platform.php de ambos JSON a la versión exacta del contenedor (8.2.x)
composer config --no-plugins --no-scripts platform.php "$PHPV" || true
COMPOSER="$DEV_JSON" composer config --no-plugins --no-scripts platform.php "$PHPV" || true

# (Opcional) Actualizar la versión de Yii2 si se define YII_APP_BASIC_VERSION
if [[ -n "${YII_APP_BASIC_VERSION:-}" ]]; then
  for f in composer.json "$DEV_JSON"; do
    if [[ -f "$f" ]]; then
      # Sustituye la constraint de yiisoft/yii2 por el valor de YII_APP_BASIC_VERSION (ej: ^2.0 o 2.0.53)
      sed -i -E 's/"yiisoft\/yii2"[[:space:]]*:[[:space:]]*"[^\"]+"/"yiisoft\/yii2": "'"$YII_APP_BASIC_VERSION"'"/g' "$f" || true
    fi
  done
fi

# Limpiar locks para que el primer install resuelva ya con >=8.2
rm -f composer.lock "$DEV_LOCK" || true

echo "   (composer.json y $DEV_JSON => php=$PHPV; platform.php=$PHPV; yii2=${YII_APP_BASIC_VERSION}; locks borrados...)"