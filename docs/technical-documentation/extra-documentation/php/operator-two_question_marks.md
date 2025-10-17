- El operador ?? se llama "operador de fusión de null" (null coalescing operator)
  - Sirve para comprobar si una variable existe y no es null, y si no lo está, devolver un valor por defecto.
  - Ejemplo

    $nombre = $_GET['nombre'] ?? 'Invitado';

    - Si $_GET['nombre'] existe y no es null, se usa su valor.
    - Si no existe o es null, se usa 'Invitado'.