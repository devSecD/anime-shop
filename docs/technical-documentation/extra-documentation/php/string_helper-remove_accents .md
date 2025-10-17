- Para el metodo "removeAccents" del helper StringHelper.
  - $transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
    1. \Transliterator es una clase de PHP que forma parte de la extensión intl, usada para transformar cadenas de texto siguiendo reglas de transliteración.
    - create('NFD; [:Nonspacing Mark:] Remove; NFC') define una secuencia de reglas de Unicode:
      1. NFD → Normalización de forma descompuesta:
        - Convierte caracteres acentuados en letra base + marca de acento.
        - Ejemplo: á → a + ́ (la tilde se separa de la letra).
      2. [:Nonspacing Mark:] Remove → Elimina marcas que no ocupan espacio, es decir, los acentos, tildes, diéresis, etc.
      3. NFC → Normalización de forma compuesta:
        - Combina nuevamente los caracteres en su forma estándar sin los acentos.
    - Resultado: Obtienes un objeto $transliterator que puede convertir cualquier cadena con acentos a su forma “limpia”.
  2. $transliterator->transliterate($text);
     - Llama al método transliterate() del objeto $transliterator.
     - Qué hace: Aplica la regla definida en create() sobre la cadena $text.
     - Ejemplo:

        $text = "Canción ünica";
        $clean = $transliterator->transliterate($text);
        // Resultado: "Cancion unica"
     - Muy útil porque funciona con cualquier acento o carácter Unicode y no solo con las letras del español.
  3. $chars = [ 'á'=>'a', 'é'=>'e', ... ]; y return strtr($text, $chars);
    - Aquí se define un arreglo de reemplazo manual como fallback si Transliterator no está disponible.
    - strtr($text, $chars) reemplaza cada carácter que coincida con una clave del array por su valor correspondiente.
    - Ejemplo:

        $text = "Canción ünica";
        $chars = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'ñ'=>'n', 'ü'=>'u'
        ];
        $clean = strtr($text, $chars);
        // Resultado: "Cancion unica"

    - Es menos flexible que Transliterator, pero suficiente para español básico.