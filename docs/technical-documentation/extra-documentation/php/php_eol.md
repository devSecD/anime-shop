- PHP_EOL es una constante predefinida de PHP que representa el carácter de salto de línea (end of line = fin de línea) adecuado para el sistema operativo donde se está ejecutando el script.
  - Por qué existe
      Porque no todos los sistemas operativos representan el fin de línea igual.
      Por ejemplo:

        1. En Linux y macOS, una línea termina con \n.
        2. En Windows, termina con \r\n.
  - Uso típico:
    - Archivos
    - Logs
    - Correos
    - Salida en consola