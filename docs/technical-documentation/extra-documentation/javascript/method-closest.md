- closest() es un método nativo del DOM que se usa sobre un elemento HTML (nodo tipo Element).
Sirve para buscar el ancestro más cercano (incluyendo el mismo elemento) que coincida con un selector CSS especificado. En palabras simples; Sube por el árbol del DOM hasta encontrar un elemento que cumpla con el selector indicado.
  - En resumen

    | Aspecto            | Descripción                                                                      |
    | ------------------ | -------------------------------------------------------------------------------- |
    | **Método**         | `Element.closest(selector)`                                                      |
    | **Función**        | Buscar el ancestro (o el propio elemento) más cercano que cumpla un selector CSS |
    | **Devuelve**       | El elemento encontrado o `null`                                                  |
    | **Uso típico**     | Delegación de eventos, búsqueda de contenedores, validación de estructuras       |
    | **Compatibilidad** | Amplia (todos los navegadores modernos)                                          |