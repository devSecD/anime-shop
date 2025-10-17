- Funcion nativa de PHP urlencode() codifica una cadena de texto para que pueda ser transmitida de forma segura dentro de una URL.
  - Su objetivo es convertir caracteres especiales (como espacios, acentos, signos de puntuación, etc.) en un formato compatible con las reglas de las URLs.
  - ¿Para qué sirve? Sirve para preparar datos que serán incluidos como parte de una URL, por ejemplo:
    - Parámetros en una query string (?name=valor).
    - Datos enviados mediante GET.
    - Enlaces generados dinámicamente con valores que podrían tener caracteres especiales.
  - ¿Por qué es necesario? En las URLs solo se permiten ciertos caracteres (letras, números y algunos símbolos como -, _, ., ~).
  Cualquier otro carácter —por ejemplo, espacios o acentos— puede romper la URL o ser interpretado incorrectamente por el navegador o el servidor.
  - Ejemplo práctico
    $nombre = "Camiseta edición limitada";
    $url = "https://anime-shop.com/product?name=" . urlencode($nombre);

    echo $url;

    Salida:

    https://anime-shop.com/product?name=Camiseta+edici%C3%B3n+limitada
    
  - ¿Cuándo usarlo?
    - Construyes URLs dinámicas con datos que vienen de variables:

      $query = "SELECT * FROM productos WHERE categoria = 'manga y anime'";
      $link = "https://anime-shop.com/search?q=" . urlencode($query);

    - Envías datos por método GET:

      header("Location: search.php?keyword=" . urlencode($keyword));

    - Generas enlaces con parámetros que pueden tener espacios o acentos, por ejemplo nombres de productos o categorías.