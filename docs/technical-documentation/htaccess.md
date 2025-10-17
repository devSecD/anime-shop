# Documentación del archivo `.htaccess`

Este archivo `.htaccess` se utiliza para configurar la reescritura de URLs en el proyecto **anime-shop**, permitiendo un sistema de routing amigable para las URLs.

## Contenido del archivo

```apache
# Reescritura de URLs para routing amigable

RewriteEngine On

RewriteBase /anime-shop/public/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Explicación de cada línea

1. `RewriteEngine On`
   - Activa el módulo de reescritura de Apache. Es necesario para que las reglas de `RewriteRule` funcionen.

2. `RewriteBase /anime-shop/public/`
   - Define la base para todas las reglas de reescritura. Esto es útil cuando tu proyecto no está en el root del servidor sino en un subdirectorio (`anime-shop/public`).

3. `RewriteCond %{REQUEST_FILENAME} !-f`
   - Condición que verifica que la URL solicitada **no sea un archivo real** existente en el servidor.

4. `RewriteCond %{REQUEST_FILENAME} !-d`
   - Condición que verifica que la URL solicitada **no sea un directorio real** existente en el servidor.

5. `RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]`
   - Regla de reescritura que redirige **todas las solicitudes que no sean archivos ni directorios reales** hacia `index.php`, pasando la URL original como parámetro `url`.
   - Flags:
     - `QSA` (Query String Append): conserva los parámetros de la URL original.
     - `L` (Last): indica que esta es la última regla que se debe aplicar si coincide.

## Funcionamiento general

Con esta configuración, cualquier solicitud que no corresponda a un archivo o directorio físico será procesada por `index.php`. Esto permite implementar un **routing amigable**, donde las URLs pueden ser más limpias y descriptivas, por ejemplo:

```
http://tusitio.com/anime-shop/public/product/list
```

En lugar de:

```
http://tusitio.com/anime-shop/public/index.php?url=product/list
```

Esto es especialmente útil en sistemas MVC, donde `index.php` actúa como punto de entrada central para todas las solicitudes.