- Intl de Javascript (abreviatura de Internationalization) es un objeto global incorporado en JavaScript que agrupa herramientas para manejar formatos internacionales como:
Números y monedas
  - Fechas y horas
  - Texto en distintos idiomas
  - Comparación de cadenas
  - Unidades de medida
  - Formatos regionales

  - En resumen:
    - Intl = conjunto de herramientas para mostrar valores (números, fechas, etc.) en el formato correcto según la región o idioma.

- Intl.supportedValuesOf(). Esta función es más reciente (introducida en ECMAScript 2022) y sirve para consultar qué valores soporta el entorno actual (navegador o Node.js) para ciertas categorías de internacionalización.
  - Sintaxis:

    Intl.supportedValuesOf(key)
  
    - Parámetro key

      Es una cadena que indica qué tipo de valores quieres listar.
      Puede ser uno de los siguientes:

      - 'calendar' → tipos de calendario soportados
      - 'collation' → métodos de ordenación de cadenas
      - 'currency' → códigos de monedas
      - 'numberingSystem' → sistemas numéricos
      - 'timeZone' → zonas horarias válidas
      - 'unit' → unidades (como "meter", "liter", "celsius", etc.)