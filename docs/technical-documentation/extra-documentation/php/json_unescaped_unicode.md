- La bandera (JSON_UNESCAPED_UNICODE) dentro de json_encode() es muy útil cuando trabajas con textos en español, japonés o cualquier idioma con caracteres no ASCII
  - Por defecto, la función json_encode() convierte (escapa) todos los caracteres que no son ASCII en secuencias Unicode.
  - Por ejemplo:
  
    $data = ['mensaje' => 'Confirmación de compra'];
    echo json_encode($data);
    
    Salida por defecto:

    {"mensaje":"Confirmaci\u00f3n de compra"}

    Como ves, las letras con acento (ó) se transforman en su equivalente Unicode \u00f3.

  - Si usas JSON_UNESCAPED_UNICODE
    Cuando agregas la bandera JSON_UNESCAPED_UNICODE, le dices a PHP:

    “No conviertas los caracteres Unicode en secuencias \uXXXX; déjalos tal cual.”

    Ejemplo:

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

    Salida más legible:

    {"mensaje":"Confirmación de compra"}