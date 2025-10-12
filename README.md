# AdShowcase — Bootstrap (infra)
- **Traefik Global** (puerto 80)
- **Stack local** (MariaDB + PHP-FPM + Nginx + Composer)
- **Stack server** (test/prod) con Traefik TLS

## Requisitos
Añade a `/etc/hosts`:
127.0.0.1  localhost.adshowcase.com

## Uso (local)
1) Sitúate en `~/AdShowcase` con tu **código Yii2** en la raíz (o añádelo después).
2) `make up`
3) Abre `http://localhost.adshowcase.com`
