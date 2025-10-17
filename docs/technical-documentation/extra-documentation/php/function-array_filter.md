- array_filter() filtra los elementos de un array creando un nuevo arreglo, devolviendo solo los que cumplen cierta condición.

- Es necesario o buena practica castear valores en un bind value donde lleve el PDO::PARAM_* ?
  - No obligatorio, pero sí recomendable cuando quieres seguridad, claridad y compatibilidad.
  - Especialmente útil en IDs, flags, conteos, o valores NULL que van directo a la base.

  - Tip rápido
    - Para strings, normalmente no necesitas castear, pero puedes usar PDO::PARAM_STR para mayor claridad.
    - Para booleanos, siempre conviene usar PDO::PARAM_BOOL o castear con (bool).
    - Para valores NULL, usa PDO::PARAM_NULL.