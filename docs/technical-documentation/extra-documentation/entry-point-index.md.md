- Para el PHP public\index.php que es el punto de entrada de la aplicacion hay un comentario que encierra el texto con dos asterisco y a continuacion te explico su significado.
  - Es solo decorativo o estético. Es más fácil de identificar al leer el código, especialmente si hay muchos comentarios.
  - No hay un estándar oficial que diga que debes usar ** … ** en comentarios de línea.
  - Es válido y común en proyectos grandes para destacar comentarios importantes, pero estrictamente no es obligatorio.
  - Los ** dentro de // son estéticos, no funcionales.

- Del codigo del PHP public\index.php

  use Core\App;

  $app = new App();
  $app->run();

  1. use Core\App; → importa la clase App del namespace Core.
  2. $app = new App(); →
    - Crea un objeto $app.
    - Dentro de __construct() se crea un $router.
  3. $app->run(); →
    - Llama a $router->handleRequest().
    - El router analiza la URL y ejecuta el controlador y acción adecuados.

  Todo esto es un patrón muy común en frameworks: un punto de entrada único (index.php) que instancia y arranca la aplicación.