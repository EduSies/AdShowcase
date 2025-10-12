COMPOSE_FILE := docker-compose.yml
PROJECT := traefik-adshowcase

.PHONY: up down logs net status

net:
	@if ! docker network ls --format '{{.Name}}' | grep -q '^traefik_proxy$$'; then \
		echo ">> Creando red externa 'traefik_proxy'"; docker network create traefik_proxy; \
	else echo ">> Red 'traefik_proxy' ya existe"; fi
up: net
	@ docker compose -f $(COMPOSE_FILE) -p $(PROJECT) up -d
down:
	@ docker compose -f $(COMPOSE_FILE) -p $(PROJECT) down
logs:
	@ docker compose -f $(COMPOSE_FILE) -p $(PROJECT) logs -f
status:
	@ docker compose -f $(COMPOSE_FILE) -p $(PROJECT) ps