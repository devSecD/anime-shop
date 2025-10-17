<?php
namespace App\Helpers;

class StringHelper
{
    public static function trim(?string $value): string
    {
        return trim((string) $value);
    }

    public static function implodeArray(array $items, string $separator): string
    {
        return implode($separator, $items);
    }

    // se usa para nombre de clases siguiendo PSR-1/PSR-12 
    public static function toPascalCase(string $str): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * Convierte un texto en un slug amigable para URL.
     * Ejemplo: "Figura Naruto Shippuden" => "figura-naruto-shippuden"
     *
     * @param string $text
     * @return string
     */
    public static function generateSlug(string $text): string
    {
        // Paso 1: Convertir a minúsculas
        $text = mb_strtolower($text, 'UTF-8');

        // Paso 2: Eliminar acentos y caracteres especiales
        $text = self::removeAccents($text);

        // Paso 3: Reemplazar cualquier caracter no alfanumérico por guiones
        // ^ dentro de corchetes niega el conjunto, es decir, todo lo que NO sea una letra minúscula (a-z) ni un número (0-9)
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        // Paso 4: Eliminar guiones al inicio y al final
        $text = trim($text, '-');

        return $text;
    }

    /**
     * Quita acentos y caracteres especiales latinos
     *
     * @param string $text
     * @return string
     */
    private static function removeAccents(string $text): string
    {
        $transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
        if ($transliterator) {
            return $transliterator->transliterate($text);
        }

        // Si no hay transliterator, fallback manual
        $chars = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'ñ'=>'n', 'ü'=>'u'
        ];
        return strtr($text, $chars);
    }
}