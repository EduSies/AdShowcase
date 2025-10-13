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
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm php composer install --no-progress --no-interaction

composer-update-PROD:
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm php composer update $(NAME)

composer-require-PROD:
	@test -n "$(NAME)" || (echo "Uso: make composer-require-PROD NAME=vendor/paquete:^version"; exit 1)
	@docker compose -f "$(COMPOSE_FILE_SERVER)" -p "$(COMPOSE_PROJECT)" \
	  run --rm php composer require $(NAME)

composer-install-DEV:
	@# 1) Comprobar que existe composer-dev.json (o abortar)
	@test -f "$(COMPOSER_DEV_JSON)" || { echo "ERROR: falta $(COMPOSER_DEV_JSON). Ejecuta: make composer-init-DEV"; exit 2; }
	@echo ">> composer install (usando $(COMPOSER_DEV_JSON) / $(COMPOSER_DEV_LOCK))"
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  php bash -lc "set -euo pipefail; cd /var/www/html; \
	    rm -f composer.lock; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer install --no-progress --no-interaction; \
	    mv -f composer.lock $(COMPOSER_DEV_LOCK)"

composer-update-DEV:
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  php bash -lc "set -euo pipefail; cd /var/www/html; \
	    rm -f composer.lock; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer update $${NAME:-}; \
	    mv -f composer.lock $(COMPOSER_DEV_LOCK)"

composer-require-DEV:
	@docker compose -f $(LOCAL_DIR)/docker-compose.yml -p adshowcase run --rm \
	  --user "$(shell id -u):$(shell id -g)" \
	  -e COMPOSER=$(COMPOSER_DEV_JSON) \
	  php bash -lc "set -euo pipefail; cd /var/www/html; \
	    rm -f composer.lock; \
	    if [ -f $(COMPOSER_DEV_LOCK) ]; then cp $(COMPOSER_DEV_LOCK) composer.lock; fi; \
	    composer require $(NAME); \
	    mv -f composer.lock $(COMPOSER_DEV_LOCK)"

# ===== DB (Yii2) =====
migrate:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate"

migrate-create:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate/create $(NAME)"