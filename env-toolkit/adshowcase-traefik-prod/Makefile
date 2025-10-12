# ===== Makefile (SERVER: TEST/PROD) =====

include mk/common.mk
COMPOSE_FILE := docker-compose.yml
ENV_FILE := .env

.PHONY: init-perms recreate up stop ps logs into-container root-into-container down dump-docker-config traefik-logs \
        composer-install-PROD composer-update-PROD composer-require-PROD \
        migrate migrate-create

init-perms:
	@ chmod +x $(TOOLKIT_SH)

recreate:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) build --force-rm --no-cache

up: init-perms
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) up --detach

stop:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) stop

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

dump-docker-config:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) config | ( if command -v yq &> /dev/null; then yq; else cat; fi )

traefik-logs:
	@ echo "Traefik (server) corre dentro del stack con TLS (Let's Encrypt)."

# ===== Composer (server) =====
composer-install-PROD:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) run --rm composer install --no-dev

composer-update-PROD:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) run --rm composer update --no-dev $(NAME)

composer-require-PROD:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) run --rm composer require $(NAME)

# ===== DB (Yii2) =====
migrate:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate"

migrate-create:
	@ $(COMPOSE_BIN) $(COMPOSE_ARGS) exec $(APP_CONTAINER) /bin/bash -c "php yii migrate/create $(NAME)"