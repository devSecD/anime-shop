- http_build_query
  - Es una función nativa de PHP que convierte un array asociativo en una cadena de consulta (query string) para URLs.
  - Muy útil cuando quieres enviar datos por GET o construir URLs con parámetros.

  - Ejemplo básico

    $params = [
      'search' => 'anime',
      'page' => 2,
      'sort' => 'popular'
    ];

    $query = http_build_query($params);
    echo $query;

    Salida
    search=anime&page=2&sort=popular