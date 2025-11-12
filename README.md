> Implementación deliberadamente simple y repetitiva (sin prepared statements, sin helpers, conexión duplicada) para usar en clase y luego refactorizar.

## Requisitos
- PHP 7+ con mysqli
- MySQL/MariaDB

## Instalación
1. Crear BD e importar `database.sql`.
2. Copiar el contenido del zip en el servidor (colocar la carpeta `imagenes` con permisos de escritura si se va a subir imágenes).
3. Usuario admin: `admin` / `admin`.
4. Usuario Editor: `editor` / `editor`.
