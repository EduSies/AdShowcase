# AdShowcase — Bootstrap (infra)

Infraestructura Docker para levantar rápidamente el entorno de AdShowcase (MariaDB + PHP-FPM + Nginx + Traefik) tanto en local como en servidor.

- **Traefik global** (puerto 80)
- **Stack local** (MariaDB + PHP-FPM + Nginx + Composer)
- **Stack server** (test/prod) con Traefik TLS

---

## 1. Requisitos previos

Antes de clonar y arrancar el proyecto, asegúrate de tener instalado:

- **Git**
- **Docker** y **Docker Compose** (Docker Desktop en Windows/macOS)
- **make** (en macOS y Linux viene por defecto; en Windows se puede usar WSL o Git Bash)

Además, añade la entrada siguiente a tu fichero `hosts` para poder acceder al dominio local:

```text
127.0.0.1  localhost.adshowcase.com
```

En macOS / Linux el fichero suele estar en `/etc/hosts`.  
En Windows, normalmente se encuentra en `C:\Windows\System32\drivers\etc\hosts`.

---

## 2. Clonar el repositorio

Clona el repositorio de AdShowcase en tu máquina:

```bash
git clone https://github.com/EduSies/AdShowcase.git
cd AdShowcase
```

---

## 3. Configuración inicial (`.env`)

El proyecto utiliza **phpdotenv** para cargar variables de entorno.

1. Si existe un fichero de plantilla `.env.example`, cópialo a `.env`:

   ```bash
   cp .env.example .env
   ```

2. Edita `.env` y revisa, como mínimo:

   - `APP_ID` y `APP_NAME` (identificador y nombre de la app).
   - Variables de la base de datos (host, usuario, contraseña, nombre de la BD).
   - Cualquier otra variable marcada como obligatoria en la plantilla.

Si no existe `.env.example`, el proyecto debería funcionar con los valores por defecto incluidos en `config/params.php` y `config/db.php`, pero es recomendable disponer de un `.env` para adaptar el entorno local si es necesario.

---

## 4. Primer arranque del entorno local

La primera vez es recomendable **construir las imágenes** y, después, levantar el stack.

Desde la raíz del proyecto (`AdShowcase/`), ejecuta:

1. Construir imagen PHP + dependencias y preparar el stack:

   ```bash
   make build
   ```

2. Levantar los contenedores:

   ```bash
   make up
   ```

El primer `make up`:

- Levanta los contenedores definidos en `docker-compose.yml` (MariaDB, PHP-FPM, Nginx, Traefik, etc.).
- Si la imagen `adshowcase-php` no existe todavía, Docker la construye automáticamente.

Es posible que la **primera vez** veas un aviso tipo:

```text
pull access denied for adshowcase-php, repository does not exist or may require 'docker login'
```

En este caso, Docker intenta primero buscar la imagen en un registro remoto y, al no encontrarla, pasa a **construirla localmente** (lo verás en el log como pasos de `Building` y `naming to docker.io/library/adshowcase-php`). Mientras al final termine indicando que el contenedor `php-adshowcase` se ha arrancado correctamente, el proceso es el esperado.

---

## 5. Ejecutar migraciones de base de datos

Una vez los contenedores estén arriba, lanza las migraciones de Yii2 desde el contenedor `php` usando el `Makefile` del proyecto.

Para aplicar todas las migraciones (incluyendo las de RBAC y las propias de AdShowcase), ejecuta:

```bash
make migrate
```

Según la configuración actual del `Makefile`, este comando:

- Se conecta al contenedor PHP.
- Ejecuta `php yii migrate --interactive=0` contra la base de datos local.
- Deja el esquema listo (usuarios de prueba, catálogos iniciales, RBAC, etc.), siempre que existan migraciones de *seed* en el proyecto.

> Si en tu versión del proyecto existe un objetivo `make build` que ya ejecuta las migraciones, basta con hacer primero `make build` y luego `make up`. En caso de duda, `make migrate` después de `make up` es la opción más clara.

---

## 6. Acceder a la aplicación

Con los contenedores en marcha y las migraciones aplicadas, abre un navegador y accede a:

```text
http://localhost.adshowcase.com
```

Deberías ver la pantalla de inicio de AdShowcase (página pública o pantalla de login, según la configuración actual del proyecto).

---

## 7. Comandos útiles

Desde la raíz del proyecto (`AdShowcase/`):

- **Levantar el entorno**

  ```bash
  make build   # construir imágenes (primera vez o tras cambios en Dockerfile)
  make up      # levantar contenedores
  ```

### Descripción de los comandos `make`

- `make help`  
  Muestra un resumen de uso y los comandos más habituales, tal como están definidos en el propio Makefile.

- `make env`  
  Imprime el valor actual de la variable `ENV` (`local` por defecto, o `server` para el stack de servidor).

- `make build`  
  Construye (o reconstruye) la imagen PHP (`adshowcase-php`) usando el Dockerfile del proyecto y prepara el stack. Es recomendable ejecutarlo la primera vez y siempre que cambies el `Dockerfile` o las dependencias de PHP/composer.

- `make up`  
  Levanta el entorno según `ENV`:
  - `ENV=local` (por defecto): arranca primero `traefik-adshowcase` (reverse proxy en el puerto 80) y luego el stack local (MariaDB, PHP-FPM, Nginx).
  - `ENV=server`: levanta el stack de servidor (test/prod) definido para despliegues remotos.  
  Puedes usar `SKIP_TRAEFIK_GLOBAL=1` para saltar el arranque de `global-traefik` si ya tienes el puerto 80 ocupado.

- `make down`  
  Detiene y elimina los contenedores del stack correspondiente al `ENV` actual (local o server), manteniendo los volúmenes de datos.

- `make down-all`  
  Detiene tanto el stack local como el `traefik-adshowcase`, útil para “apagar” por completo todo lo relacionado con AdShowcase en la máquina local.

- `make stop`  
  Detiene los servicios del stack local (o server) sin destruir los contenedores, dejando el entorno listo para reanudar con un nuevo `make up`.

- `make ps`  
  Muestra el estado de los contenedores del stack actual (equivalente a `docker compose ps` sobre el entorno AdShowcase).

- `make logs`  
  Muestra los logs en tiempo real del stack actual (similar a `docker compose logs -f`).

- `make recreate`  
  Fuerza la reconstrucción de las imágenes del stack local con `--no-cache` (útil cuando cambian extensiones de PHP o paquetes del sistema y quieres una imagen limpia).

- `make dump-docker-config`  
  Vuelca la configuración renderizada de Docker Compose para depurar qué servicios y parámetros se están usando realmente.

- `make traefik-logs`  
  Muestra los logs de `traefik-adshowcase` (el reverse proxy que expone `http://localhost.adshowcase.com`).

- `make into-container`  
  Abre una shell interactiva dentro del contenedor de la aplicación (usuario no root, entorno similar al de ejecución de PHP) para ejecutar comandos manualmente (`php yii`, `composer`, etc.).

- `make root-into-container`  
  Abre una shell dentro del contenedor de la aplicación como usuario root, útil para tareas puntuales de depuración o instalación de paquetes.

- `make migrate`  
  Ejecuta las migraciones de Yii2 dentro del contenedor PHP contra la base de datos (`php yii migrate --interactive=0`), usando el stack indicado por `ENV` (por defecto, local).

- `make migrate-create NAME=NombreDeMigracion`  
  Crea una nueva migración de Yii2 con el nombre indicado (`php yii migrate/create NombreDeMigracion`) dentro del contenedor de la aplicación.

- `make composer-install-DEV`  
  Ejecuta `composer install` en modo desarrollo usando el fichero de dependencias de desarrollo (`composer-dev.json`/`composer-dev.lock`) dentro del contenedor PHP.

- `make composer-update-DEV`  
  Ejecuta `composer update` en modo desarrollo sobre las dependencias definidas para DEV, actualizando el `composer-dev.lock`.

- `make composer-require-DEV NAME=vendor/paquete`  
  Añade un nuevo paquete de desarrollo con `composer require` y actualiza el `composer-dev.lock` dentro del contenedor.

- `make composer-install-PROD`  
  Ejecuta `composer install` en modo producción (sin dependencias de desarrollo) usando el `composer.json` principal de la aplicación.

- `make composer-update-PROD`  
  Ejecuta `composer update` en modo producción sobre las dependencias del `composer.json` principal.

- `make composer-require-PROD NAME=vendor/paquete`  
  Añade un paquete a las dependencias de producción del proyecto desde dentro del contenedor.

- `make rbac-migrate`  
  Ejecuta exclusivamente las migraciones del paquete `@yii/rbac/migrations` dentro del contenedor PHP, para inicializar o actualizar el esquema de RBAC sin lanzar el resto de migraciones.

---

## 8. Problemas frecuentes

- **No resuelve el dominio `localhost.adshowcase.com`:**  
  Revisa que has añadido correctamente la línea en `/etc/hosts` (o en el fichero `hosts` de Windows) y que no haya espacios o caracteres extraños.

- **Error de conexión a la base de datos:**  
  Comprueba que:
  - El contenedor de MariaDB está levantado (`docker ps`).
  - Las credenciales de la BD en `.env` coinciden con las que espera `docker-compose.yml`.

- **Cambios en migraciones o en el esquema:**  
  Si alteras las migraciones y quieres partir de cero:
  - Para los contenedores (`make down`).
  - Elimina los volúmenes de la base de datos o la BD manualmente.
  - Vuelve a levantar (`make up`) y ejecuta de nuevo `make migrate`.

Con estos pasos, el tutor (o cualquier desarrollador) debería poder pasar de un `git clone` a tener AdShowcase funcionando en `http://localhost.adshowcase.com` sin pasos adicionales.
