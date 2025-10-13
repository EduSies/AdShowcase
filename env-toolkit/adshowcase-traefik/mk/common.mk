# ===== env-toolkit/mk/common.mk =====
COMPOSE_PROJECT ?= adshowcase
COMPOSE_FILE ?= docker-compose.yml
ENV_FILE ?= .env
APP_CONTAINER ?= php
COMPOSE_BIN ?= docker
COMPOSE_SUB ?= compose
COMPOSE_ENVARG := $(if $(wildcard $(ENV_FILE)),--env-file $(ENV_FILE),)
COMPOSE_ARGS := $(COMPOSE_SUB) -f $(COMPOSE_FILE) -p $(COMPOSE_PROJECT) $(COMPOSE_ENVARG)
TOOLKIT_SH := scripts/docker.sh
COMPOSE_FILE_LOCAL := $(CURDIR)/$(LOCAL_DIR)/docker-compose.yml
COMPOSE_FILE_SERVER := $(CURDIR)/$(SERVER_DIR)/docker-compose.yml
SHELL := /bin/bash
COMPOSER_DEV_JSON ?= composer-dev.json
COMPOSER_DEV_LOCK ?= composer-dev.lock