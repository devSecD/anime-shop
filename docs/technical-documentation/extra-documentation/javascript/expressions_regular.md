- Desglose paso a paso de la expresion regular usada en public\assets\js\util\validation.js de la funcion isValidEmail

  - ^[^\s@]+
    - Empieza desde el inicio ^.
    - [^\s@]+ → uno o más caracteres que no sean espacio ni @.
    - Esto captura la parte local del email antes de la arroba (usuario).

  - @
    - Debe haber exactamente un @ después de la parte local.

  - [^\s@]+\.
    - Uno o más caracteres que no sean espacio ni @, seguido de un . literal.
    - Esto captura la parte del dominio (ej. gmail.).

  - [^\s@]+$

    - Uno o más caracteres que no sean espacio ni @ hasta el final $.
    - Esto captura la extensión del dominio (ej. com, org, net).