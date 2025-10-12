# ===== env-toolkit/mk/common.mk =====
PROJECT_NAME ?= adshowcase
COMPOSE_FILE ?= docker-compose.yml
ENV_FILE ?= .env
APP_CONTAINER ?= php
COMPOSE_BIN ?= docker
COMPOSE_SUB ?= compose
COMPOSE_ENVARG := $(if $(wildcard $(ENV_FILE)),--env-file $(ENV_FILE),)
COMPOSE_ARGS := $(COMPOSE_SUB) -f $(COMPOSE_FILE) -p $(PROJECT_NAME) $(COMPOSE_ENVARG)
TOOLKIT_SH := scripts/docker.sh
