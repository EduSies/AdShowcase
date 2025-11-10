# Root Makefile — ejecutar desde ~/AdShowcase
# Un solo `make up` arranca global-traefik (80) + local
# ENV=local (por defecto) | ENV=server
ENV ?= local

SHELL := /bin/bash
TG_DIR := env-toolkit/global-traefik
LOCAL_DIR := env-toolkit/adshowcase-traefik
SERVER_DIR := env-toolkit/adshowcase-traefik-prod-addon
COMPOSER_DEV_JSON ?= composer-dev.json
COMPOSER_DEV_LOCK ?= composer-dev.lock

TARGETS := \
	stop ps logs recreate down dump-docker-config traefik-logs \
	into-container root-into-container \
	migrate migrate-create \
	composer-install-PROD composer-update-PROD composer-require-PROD \
	composer-install-DEV  composer-update-DEV  composer-require-DEV

.PHONY: help env up down down-all $(TARGETS) local-% server-% build

help:
	@echo "Uso:"
	@echo "  make build                      # build php (Yii2 horneado) + sync a raíz + composer install (DEV)"
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
	@echo ">> 1/5 Build de imágenes (php)"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase build php

	@echo ">> 2/5 Sincronizar Yii2 desde la imagen (si existe /opt/yii2-template)"
	@bash -lc '\
	  if docker compose -f "$(LOCAL_DIR)/docker-compose.yml" -p adshowcase run --rm php bash -lc "test -d /opt/yii2-template"; then \
	    ( cd "$(LOCAL_DIR)" && docker compose -f docker-compose.yml -p adshowcase run --rm php bash -lc "cd /opt/yii2-template && tar cf - ." ) | tar -x -k -f - ; \
	  else \
	    echo "   (fallback) creando scaffold con Composer dentro de php...)"; \
	    docker compose -f "$(LOCAL_DIR)/docker-compose.yml" -p adshowcase run --rm --user "$$(id -u):$$(id -g)" php \
	      composer create-project yiisoft/yii2-app-basic /var/www/html/_yii_scaffold --prefer-dist --no-interaction --no-progress; \
	    rsync -av --ignore-existing \
	      --exclude=".git" --exclude=".gitignore" --exclude="vendor" --exclude="runtime" --exclude="web/assets" \
	      _yii_scaffold/ .; \
	    rm -rf _yii_scaffold; \
	  fi'

	# Asegurar composer-dev.json (si no existe pero hay composer.json, copiar)
	@if [ ! -f "$(COMPOSER_DEV_JSON)" ] && [ -f composer.json ]; then \
	  cp composer.json "$(COMPOSER_DEV_JSON)"; echo ">> Copiado composer.json → $(COMPOSER_DEV_JSON)"; \
	fi
	@if [ ! -f "$(COMPOSER_DEV_JSON)" ]; then \
	  echo "ERROR: falta $(COMPOSER_DEV_JSON) y composer.json. Crea uno y reintenta."; exit 2; \
	fi

	@echo ">> 3/5 Composer install (usando $(COMPOSER_DEV_JSON) / $(COMPOSER_DEV_LOCK))"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  -e COMPOSER_CACHE_DIR=/tmp/composer-cache \
	  -e HOME=/tmp \
	  php bash -lc "set -euo pipefail; cd /var/www/html; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; else rm -f composer.lock; fi; \
	    composer install --no-progress --no-interaction; \
	    if [ -f composer.lock ]; then mv -f composer.lock $(COMPOSER_DEV_LOCK); fi"

	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm php \
		bash -lc 'mkdir -p runtime web/assets && chown -R www-data:www-data runtime web/assets && chmod -R u+rwX,g+rwX runtime web/assets'

	@echo ">> Build listo. Luego: make up && open http://localhost.adshowcase.com"