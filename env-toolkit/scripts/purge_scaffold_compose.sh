#!/usr/bin/env bash
set -euo pipefail
cd /var/www/html

# PURGE_MODE=delete (por defecto) | rename
MODE="${PURGE_MODE:-delete}"

CANDIDATES=(
  "docker-compose.yml"
  "docker-compose.yaml"
  "docker-compose.override.yml"
  "docker-compose.local.yml"
  "docker-compose.dev.yml"
)

for f in "${CANDIDATES[@]}"; do
  if [[ -f "$f" ]]; then
    if [[ "$MODE" == "rename" ]]; then
      mv -f "$f" "$f.scaffold.bak"
      echo "   (renombrado $f → $f.scaffold.bak)"
    else
      rm -f "$f"
      echo "   (eliminado $f)"
    fi
  fi
done

echo "   (purgado compose(s) de raíz completado)"