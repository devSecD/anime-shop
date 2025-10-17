- Sobre string: $item['name'] de la vista app\view\cart\index.php
    - Eso se llama named arguments (argumentos nombrados), una funcionalidad de PHP 8 en adelante.
    - Sintaxis válida desde PHP 8:
    - Ventaja: puedes pasar los parámetros en cualquier orden usando el nombre del parámetro.
    - Ejemplo usando varios parámetros con named arguments:

        htmlspecialchars(
            string: $item['name'],
            flags: ENT_QUOTES,
            encoding: 'UTF-8'
        );

- El atributo en una etiqueta de formulario HTML novalidate es para poner validaciones personalizadas y no sujetarse a las que trae HTML muy funcional para tener validaciones personalizadas con Javascript

- Para las imagenes en HTML
  - loading="eager"
    - Controla cuándo se carga la imagen.
    - Valores posibles:

      - eager → la imagen se carga inmediatamente, tan pronto como el navegador la encuentra en el HTML.
      - lazy → la imagen se carga solo cuando entra en la ventana visible (viewport), útil para mejorar performance y evitar    cargar imágenes que el usuario aún no ve.
  - decoding="async"
    - Controla cómo el navegador decodifica la imagen antes de mostrarla.
    - Valores posibles:
      - sync → decodificación sincrónica: el navegador espera a decodificar antes de continuar, puede bloquear el render.
      - async → decodificación asíncrona: el navegador sigue renderizando la página mientras decodifica la imagen en segundo plano.
      - auto → el navegador decide automáticamente.

- Atributo tabindex de HTML
  - El atributo tabindex controla el orden de tabulación de los elementos cuando el usuario navega usando la tecla Tab.
  - Se puede usar en cualquier elemento que pueda recibir foco o que quieras que reciba foco programáticamente
  - Incluso se puede aplicar a elementos semánticos como <section>, <article>, <div>, <span>, etc., para mejorar accesibilidad o control de foco dinámico.

  | Valor de tabindex | Comportamiento                                     |
  | ----------------- | -------------------------------------------------- |
  | `-1`              | No enfocable con Tab, sí con JS                    |
  | `0`               | Enfocable con Tab siguiendo el orden del DOM       |
  | `>0`              | Enfocable con Tab según el orden numérico indicado |

  - Tip de accesibilidad:
    - Usa tabindex="-1" para elementos que quieras controlar solo con JS, pero evita abusar para no romper la navegación con teclado.
  
- data-label en celdas <td>
  - Definición:
    El atributo data-label es un atributo de datos personalizado (data-*) utilizado para asociar una etiqueta descriptiva a una celda (<td>).
    En HTML no tiene un significado semántico propio, pero se usa comúnmente en el diseño responsive para mostrar el nombre de la columna cuando la tabla se adapta a pantallas pequeñas.
  - Propósito:
    Permite mantener la legibilidad de las tablas en dispositivos móviles, donde la estructura cambia de una vista tabular a un formato tipo “lista”.
    El valor del atributo data-label suele corresponder al encabezado (<th>) original de la tabla.