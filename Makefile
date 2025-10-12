# Root Makefile — ejecutar desde ~/AdShowcase
# Un solo `make up` arranca global-traefik (80) + local
# ENV=local (por defecto) | ENV=server
ENV ?= local

TG_DIR     := env-toolkit/global-traefik
LOCAL_DIR  := env-toolkit/adshowcase-traefik
SERVER_DIR := env-toolkit/adshowcase-traefik-prod-addon

TARGETS := \
	stop ps logs recreate down dump-docker-config traefik-logs \
	into-container root-into-container \
	migrate migrate-create \
	composer-install-PROD composer-update-PROD composer-require-PROD \
	composer-install-DEV  composer-update-DEV  composer-require-DEV

.PHONY: help env up down down-all $(TARGETS) local-% server-%

help:
	@echo "Uso:"
	@echo "  make up                     # global-traefik + local (ENV=local)"
	@echo "  ENV=server make up          # solo server (test/prod)"
	@echo "  make down                   # para el stack según ENV"
	@echo "  make down-all               # para local + global-traefik"
	@echo "  make logs / ps / migrate... # actúan sobre LOCAL por defecto"

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