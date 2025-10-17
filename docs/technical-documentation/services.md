Servicios

1. Explicacion paso paso del cuerpo del metodo "mapCartItemsToMpItems" del repositorio "MercadoPagoService"
  1. Entrada esperada ($items)
    Se asume que $items es un array (lista) de arrays asociativos, por ejemplo:

    $items = [
      ['name' => 'Figura Naruto', 'qty' => '2', 'price' => '19.99'],
      ['name' => 'Poster',       'qty' => 1,   'price' => 7.5]
    ];

    Cada elemento representa un producto con claves como name, qty, price.
  2. array_map(...) — aplica una función a cada elemento
    array_map toma una función (callback) y un array, y aplica esa función a cada elemento del array, 
    devolviendo un nuevo array con los resultados (mismo orden lógico de iteración).
    - Para cada $item del array original, la arrow function devuelve un nuevo array asociativo con estas claves:
      - 'title' → toma $item['name'] (sin casting explícito; normalmente será string).
      - 'quantity' → (int)$item['qty'] — convierte el valor a entero.
      - 'unit_price' → (float)$item['price'] — convierte el valor a float.
    Resultado intermedio (conceptual) para el ejemplo:
    [
      0 => ['title' => 'Figura Naruto', 'quantity' => 2, 'unit_price' => 19.99],
      1 => ['title' => 'Poster',       'quantity' => 1, 'unit_price' => 7.5],
    ]
  3. array_values(...) — reindexa el array resultante
  array_values toma el array que devolvió array_map y devuelve un nuevo array con índices numéricos 
  consecutivos empezando en 0.
  Esto garantiza que las claves sean [0,1,2,...] (por si el array previo tenía claves “saltadas” o asociativas).
  4. Valor final
  El resultado es un array indexado numéricamente, donde cada elemento es un array asociativo 
  con las tres claves: title, quantity, unit_price.
  Tipo "conceptual": array<int, array<string, mixed>>.

  * Mejoras
    - Avisos si faltan claves
    Si algún $item no tiene 'name', 'qty' o 'price' se disparará un notice de PHP al intentar acceder a $item['key']. 
    Para hacerlo más robusto usa el operador ??:
    
    'title' => $item['name'] ?? '',
    'quantity' => (int)($item['qty'] ?? 0),
    'unit_price' => (float)($item['price'] ?? 0.0),

    - Formato de números
    Si price viene con coma decimal ("19,99"), el cast a float puede comportarse de forma inesperada dependiendo del formato. 
    Es mejor normalizar (str_replace(',', '.', $price)) si hay posibilidad de comas.
    - Alternativa con foreach (más explícita y a veces más legible):

    $result = [];
    foreach ($items as $item) {
        $result[] = [
            'title' => $item['name'] ?? '',
            'quantity' => (int)($item['qty'] ?? 0),
            'unit_price' => (float)($item['price'] ?? 0.0),
        ];
    }
    // $result ya viene indexado numéricamente

    - Complejidad: O(n) — se recorre el array una vez.