# Root Makefile — ejecutar desde ~/AdShowcase
# Un solo `make up` arranca global-traefik (80) + local
# ENV=local (por defecto) | ENV=server
ENV ?= local

SHELL := /bin/bash
TG_DIR := env-toolkit/global-traefik
LOCAL_DIR := env-toolkit/adshowcase-traefik
SERVER_DIR := env-toolkit/adshowcase-traefik-prod
COMPOSER_DEV_JSON ?= composer-dev.json
COMPOSER_DEV_LOCK ?= composer-dev.lock
UP_AFTER_BUILD ?= 1
YII_APP_BASIC_VERSION ?= ^2.0
RBAC_AFTER_BUILD ?= 1

TARGETS := \
  stop ps logs recreate dump-docker-config traefik-logs \
  into-container root-into-container \
  migrate migrate-create \
  composer-install-PROD composer-update-PROD composer-require-PROD \
  composer-install-DEV  composer-update-DEV  composer-require-DEV

.PHONY: help env up down down-all $(TARGETS) local-% server-% build rbac-migrate

help:
	@echo "Uso:"
	@echo "  make build                      # build php (Yii2 horneado) + sync + normaliza PHP>=8.2; al final ejecuta composer-install-DEV y luego (opcional) up"
	@echo "                                   # desactiva el auto-up con: UP_AFTER_BUILD=0 make build"
	@echo "  make up                         # (ENV=local) levanta global-traefik + stack local"
	@echo "  SKIP_TRAEFIK_GLOBAL=1 make up   # salta traefik global si ya tienes :80 ocupado"
	@echo "  ENV=server make up              # levanta sólo el stack de server (test/prod)"
	@echo "  make down                       # para el stack según ENV"
	@echo "  make down-all                   # para local + global-traefik"
	@echo "  make logs / ps                  # logs o listado de servicios (LOCAL por defecto)"
	@echo "  make migrate / migrate-create   # migraciones de Yii2 (LOCAL)"
	@echo ""
	@echo "Composer (DEV, sobre composer-dev.json/lock):"
	@echo "  make composer-install-DEV       # instala dependencias (usa composer-dev.json / composer-dev.lock)"
	@echo "  make composer-require-DEV NAME='vendor/paquete:^version'  # añade dependencia en DEV"
	@echo "  make composer-update-DEV [NAME=vendor/paquete]            # update selectivo o completo en DEV"
	@echo "  (si falta composer-dev.json: cd env-toolkit/adshowcase-traefik && make composer-init-DEV)"
	@echo ""
	@echo "Composer (PROD / server):"
	@echo "  make composer-install-PROD      # install en el stack de server"
	@echo "  make composer-require-PROD NAME='vendor/paquete:^version'"
	@echo "  make composer-update-PROD [NAME=vendor/paquete]"
	@echo ""
	@echo "Atajos útiles:"
	@echo "  make traefik-logs               # logs del traefik global (:80)"
	@echo "  local-<target> / server-<target># ejecuta un target directamente en LOCAL/SERVER"
	@echo ""
	@echo "Variables:"
	@echo "  ENV=local|server                # entorno objetivo (por defecto: local)"
	@echo "  NAME=vendor/paquete[:^version]  # usado por composer-require/update"

env:
	@echo "ENV = $(ENV)"

ifeq ($(ENV),local)
up:
	@$(MAKE) -C $(TG_DIR) up
	@$(MAKE) -C $(LOCAL_DIR) up
down:
	@$(MAKE) -C $(LOCAL_DIR) down
else ifeq ($(ENV),server)
up:
	@$(MAKE) -C $(SERVER_DIR) up
down:
	@$(MAKE) -C $(SERVER_DIR) down
else
up:
	$(error ENV debe ser 'local' o 'server' (valor actual: '$(ENV)'))
down:
	$(error ENV debe ser 'local' o 'server' (valor actual: '$(ENV)'))
endif

down-all:
	@$(MAKE) -C $(LOCAL_DIR) down || true
	@$(MAKE) -C $(TG_DIR) down || true

$(TARGETS):
ifeq ($(ENV),local)
	@$(MAKE) -C $(LOCAL_DIR) $@ NAME="$(NAME)"
else ifeq ($(ENV),server)
	@$(MAKE) -C $(SERVER_DIR) $@ NAME="$(NAME)"
else
	$(error ENV debe ser 'local' o 'server')
endif

local-%:
	@$(MAKE) -C $(LOCAL_DIR) $* NAME="$(NAME)"
server-%:
	@$(MAKE) -C $(SERVER_DIR) $* NAME="$(NAME)"

build:
	@echo ">> 1/6 Build de imágenes (php)"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase build php

	@echo ">> 2/6 Sincronizar Yii2 desde la imagen (si existe /opt/yii2-template)"
	@bash -lc "set -euo pipefail; \
	  if docker compose -f '$(LOCAL_DIR)/docker-compose.yml' -p adshowcase run --rm php bash -lc 'test -d /opt/yii2-template'; then \
	    ( cd '$(LOCAL_DIR)' && docker compose -f docker-compose.yml -p adshowcase run --rm php bash -lc 'cd /opt/yii2-template && tar cf - .' ) | tar -x -k -f - ; \
	  else \
	    echo '   (fallback) creando scaffold con Composer dentro de php...)'; \
	    docker compose -f '$(LOCAL_DIR)/docker-compose.yml' -p adshowcase run --rm --user $$(id -u):$$(id -g) \
	      -e COMPOSER_HOME=/var/www/html/runtime/composer -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	      php bash -lc 'set -e; mkdir -p runtime/composer; composer create-project yiisoft/yii2-app-basic:$(YII_APP_BASIC_VERSION) /var/www/html/_yii_scaffold --prefer-dist --no-interaction --no-progress --no-install --no-scripts'; \
	    rsync -av --ignore-existing \
	      --exclude='.git' --exclude='.gitignore' --exclude='vendor' --exclude='runtime' --exclude='web/assets' \
	      _yii_scaffold/ .; \
	    rm -rf _yii_scaffold; \
	  fi"

	@echo ">> 3/6 Purgar docker-compose* del scaffold en raíz (para evitar 7.4)"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e PURGE_MODE=delete \
	  php /bin/bash /var/www/html/env-toolkit/scripts/purge_scaffold_compose.sh

	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER_DEV_JSON="$(COMPOSER_DEV_JSON)" \
	  -e COMPOSER_DEV_LOCK="$(COMPOSER_DEV_LOCK)" \
	  -e YII_APP_BASIC_VERSION="$(YII_APP_BASIC_VERSION)" \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  -e HOME=/tmp \
	  php bash -lc 'set -e; mkdir -p /var/www/html/runtime/composer; /bin/bash /var/www/html/env-toolkit/scripts/normalize_composer_php82.sh'

	@echo ">> 5/6 Permisos de runtime y web/assets"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm php \
	  bash -lc 'mkdir -p runtime web/assets runtime/composer && chown -R www-data:www-data runtime web/assets && chmod -R u+rwX,g+rwX runtime web/assets'

	@echo ">> 6/6 Composer install (delegado a composer-install-DEV)"
	@$(MAKE) composer-install-DEV

	@if [ "$(RBAC_AFTER_BUILD)" != "0" ]; then \
	  echo ">> RBAC: ejecutando migraciones (@yii/rbac/migrations)"; \
	  docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase up -d db php; \
	  docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase exec -T php \
	    php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0; \
	  $(MAKE) migrate ENV=$(ENV); \
	fi

	@echo ">> Build listo. Luego: make up && open http://localhost.adshowcase.com"
	@if [ "$(UP_AFTER_BUILD)" != "0" ]; then \
	  echo ">> Auto-up: lanzando 'make up' (ENV=$(ENV))"; \
	  $(MAKE) up ENV=$(ENV) SKIP_TRAEFIK_GLOBAL=$(SKIP_TRAEFIK_GLOBAL); \
	else \
	  echo ">> UP_AFTER_BUILD=0 ⇒ no se lanza 'make up' automáticamente"; \
	fi

rbac-migrate:
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase exec -T php \
	  php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0