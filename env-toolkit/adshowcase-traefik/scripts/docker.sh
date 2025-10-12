#!/usr/bin/env bash
    set -euo pipefail
    CMD="${1:-}"
    ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
    dotenv() { local f="$1"; [[ -f "$f" ]] && export $(grep -E '^[A-Za-z_][A-Za-z0-9_]*=' "$f" | sed 's/#.*//' | xargs); }
    case "$CMD" in
      net)
        if [[ -f "$ROOT_DIR/.env" ]]; then
          dotenv "$ROOT_DIR/.env"
          LOCAL="${LOCAL_HOST:-localhost.adshowcase.com}"
          if ! grep -q "$LOCAL" /etc/hosts 2>/dev/null; then
            echo "[env-toolkit] Añade a /etc/hosts:  127.0.0.1   ${LOCAL}"
          fi
          if ! docker network ls --format '{{.Name}}' | grep -q '^traefik_proxy$'; then
            echo "[env-toolkit] Falta la red 'traefik_proxy'. Ejecuta:  cd ~/AdShowcase/env-toolkit/global-traefik && make up"
          fi
        fi;;
      up)
        if [[ -f "$ROOT_DIR/.env" ]]; then
          dotenv "$ROOT_DIR/.env"; LOCAL="${LOCAL_HOST:-localhost.adshowcase.com}"
          echo "[env-toolkit] Local arriba en: http://${LOCAL}  (via Traefik Global)"
        fi;;
      stop) echo "[env-toolkit] Servicios detenidos.";;
      down) echo "[env-toolkit] Servicios eliminados.";;
      *) echo "Uso: $0 {net|up|stop|down}"; exit 1;;
    esac