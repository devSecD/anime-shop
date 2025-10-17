- String() es un constructor global nativo de JavaScript.
  - Cuando lo llamas como función (sin new), actúa como conversor de tipo y devuelve la representación en texto del valor que recibe.
  - Ejemplos:

    String(123);           // "123"
    String(true);          // "true"
    String(false);         // "false"
    String(null);          // "null"
    String(undefined);     // "undefined"
    String([1, 2, 3]);     // "1,2,3"
    String({a: 1});        // "[object Object]"

  - Usar String(value) es la forma más segura y clara, porque no lanza error con null ni undefined.
  - En resumen

    | Aspecto               | Detalle                                              |
    | --------------------- | ---------------------------------------------------- |
    | **Función**           | `String()`                                           |
    | **Tipo de operación** | Conversión o casteo explícito a cadena               |
    | **Devuelve**          | Representación en texto del valor                    |
    | **Seguro para**       | Cualquier tipo de dato, incluso `null` o `undefined` |
    | **Equivalente a**     | `value + ""`, pero más legible                       |