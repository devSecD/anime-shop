- La funcion nl2br (usado en la vista del detalle del producto) Inserta un salto de línea HTML en cada nueva línea.
Puede existir dos casos para que funcione correctamente:

  1. Tener explicitamente el salto de linea como en el siguiente ejemplo:
    - 
      <?php
        echo nl2br("foo isn't\n bar");
      ?>

  2. Tenerlo implicitamente como en el siguiente ejemplo que es una multilinea:
    - 
    $texto = "Línea 1
    Línea 2
    Línea 3";

  En ambos caos mete un <br> o <br />

  - Los saltos de linea pueden ser \n, \r, \r\n o uno implicito como el multilinea del ejemplo