Ruter personalizado

- Basado en un arreglo asociativo.

Cada ruta de la aplicación (URL) está mapeada a:

- Un controlador: la clase PHP que contiene la lógica para esa ruta.
- Una acción: el método de esa clase que se ejecuta cuando se accede a la ruta.
- Esto permite que cada URL tenga un punto de entrada único y controlado.

Claves

1. Clave del arreglo → la ruta URL relativa que el usuario escribe o que se genera en enlaces.
- Ejemplo: 'catalog' → http://tu-sitio/anime-shop/public/catalog
- 'newsletter/subscribe' → http://tu-sitio/anime-shop/public/newsletter/subscribe
2. Valor del arreglo → un sub-arreglo con dos llaves:
- 'controller' → nombre completo de la clase que manejará la ruta.
  - Ejemplo: Home\IndexController significa:
    - Carpeta app/controllers/Home/
    - Archivo IndexController.php
    - Clase IndexController dentro del namespace Home.
- 'action' → método de la clase que se ejecuta.
  - Ejemplo: 'index' significa que se ejecuta $controller->index().
3. Cómo funciona internamente

  1. El router recibe la URL que solicita el usuario.
  2. Busca la clave exacta en el arreglo de rutas.
  3. Si existe:
    - Instancia la clase del controlador indicado (new Home\IndexController()).
    - Llama al método (index() o handle()) correspondiente.
  4. Si no existe:
    - Redirige a una página 404 o muestra un error.