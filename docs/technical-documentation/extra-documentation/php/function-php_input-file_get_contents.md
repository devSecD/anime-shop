- php://input y file_get_contents

  1. Que es y para que sirve php://input ? 
    - Es un flujo de lectura (stream) interno de PHP que te permite acceder directamente al cuerpo “raw” (crudo) de una petición HTTP. En otras palabras: php://input es una fuente de datos de solo lectura que contiene exactamente lo que el cliente (navegador, AJAX, API, etc.) envió en el body de la petición.
  2. Que es y para que sirve file_get_contents ? 
    - Es una función de PHP que lee el contenido de un archivo o flujo (como si fuera un “lector de texto”).
  3. Y que hacen conjuntamente php://input y file_get_contents ?
    - Leer el cuerpo completo de la petición HTTP (tal cual fue enviado) como un string.