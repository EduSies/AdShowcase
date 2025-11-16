# ===== Makefile (LOCAL) =====
include mk/common.mk

.PHONY: init-perms recreate up stop ps logs into-container root-into-container down dump-docker-config traefik-logs \
        composer-install-PROD composer-update-PROD composer-require-PROD \
        composer-install-DEV composer-update-DEV composer-require-DEV \
        migrate migrate-create

init-perms:
	@ chmod +x $(TOOLKIT_SH)

recreate:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) build --force-rm --no-cache

up: init-perms
	@ bash $(TOOLKIT_SH) net
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) up --detach
	@ bash $(TOOLKIT_SH) up

stop:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) stop
	@ bash $(TOOLKIT_SH) stop

ps:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) ps

logs:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) logs --follow

into-container:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash

root-into-container:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec --user 0 $(APP_CONTAINER) /bin/bash

down:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) down
	@ bash $(TOOLKIT_SH) down

dump-docker-config:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) config | ( if command -v yq &> /dev/null; then yq; else cat; fi )

traefik-logs:
	@ echo "Traefik corre como 'global-traefik'. Mira: cd ~/AdShowcase/env-toolkit/global-traefik && make logs"

# ===== Composer =====
composer-install-PROD:
	@rm -rf "$(ROOT_DIR)/vendor" || true
	@rm -f  "$(ROOT_DIR)/composer.lock" || true
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    composer install --no-progress --no-interaction'

composer-update-PROD:
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    composer update $${NAME:-}'

composer-require-PROD:
	@test -n "$(NAME)" || (echo "Uso: make composer-require-PROD NAME=vendor/paquete:^version"; exit 1)
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    composer require $(NAME)'

composer-install-DEV:
	@rm -rf "$(ROOT_DIR)/vendor" || true
	@rm -f  "$(ROOT_DIR)/$(COMPOSER_DEV_LOCK)" || true
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@test -f "$(ROOT_DIR)/$(COMPOSER_DEV_JSON)" || { echo "ERROR: falta $(COMPOSER_DEV_JSON) en $(ROOT_DIR)."; exit 2; }
	@echo ">> composer install (usando $(COMPOSER_DEV_JSON) / $(COMPOSER_DEV_LOCK))"
	@cd "$(CURDIR)" && docker compose -f docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer install --no-progress --no-interaction; \
	    if [ -f composer.lock ]; then mv -f composer.lock $(COMPOSER_DEV_LOCK); fi'

composer-update-DEV:
	@test -f "$(ROOT_DIR)/$(COMPOSER_DEV_JSON)" || { echo "ERROR: falta $(COMPOSER_DEV_JSON) en $(ROOT_DIR)."; exit 2; }
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@cd "$(CURDIR)" && docker compose -f docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    rm -f composer.lock; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer update $${NAME:-}; \
	    if [ -f composer.lock ]; then mv -f composer.lock $(COMPOSER_DEV_LOCK); fi'

composer-require-DEV:
	@test -f "$(ROOT_DIR)/$(COMPOSER_DEV_JSON)" || { echo "ERROR: falta $(COMPOSER_DEV_JSON) en $(ROOT_DIR)."; exit 2; }
	@mkdir -p "$(ROOT_DIR)/runtime/composer" || true
	@cd "$(CURDIR)" && docker compose -f docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  -e COMPOSER_HOME=/var/www/html/runtime/composer \
	  -e COMPOSER_CACHE_DIR=/var/www/html/runtime/composer \
	  php bash -lc 'set -euo pipefail; cd /var/www/html; \
	    mkdir -p runtime/composer; \
	    rm -f composer.lock; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer require $(NAME); \
	    if [ -f composer.lock ]; then mv -f composer.lock $(COMPOSER_DEV_LOCK); fi'

# ===== DB (Yii2) =====
migrate:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate"

migrate-create:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate/create $(NAME)"