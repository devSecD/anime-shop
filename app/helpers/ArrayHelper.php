<?php
namespace App\Helpers;

class ArrayHelper
{
    /**
     * Convierte un arreglo indexado a uno asociativo usando
     * las columnas indicadas como clave y valor.
     *
     * @param array $items Arreglo indexado de registros
     * @param string $keyColumn Nombre de la columna para usar como clave
     * @param string $valueColumn Nombre de la columna para usar como valor
     * @return array Arreglo asociativo resultante
     */
    public static function toAssocArray(array $items, string $keyColumn, string $valueColumn): array
    {
        $assoc = [];
        foreach ($items as $item) {
            if (isset($item[$keyColumn]) && isset($item[$valueColumn])) {
                $assoc[$item[$keyColumn]] = $item[$valueColumn];
            }
        }
        return $assoc;
    }

    /**
     * Filtra un array de arrays excluyendo aquellos donde el valor en $keyField
     * coincida con cualquiera de los valores de $excludeValues.
     *
     * @param array $array Array de arrays asociativos.
     * @param string $keyField Nombre de la clave a evaluar.
     * @param string|array $excludeValues Valor o lista de valores a excluir.
     * @return array Array filtrado.
     */
    public static function excludeByKeyValues(array $array, string $keyField, $excludeValues): array
    {
        if (!is_array($excludeValues)) {
            $excludeValues = [$excludeValues];
        }

        return array_filter($array, function($item) use ($keyField, $excludeValues) {
            return !in_array($item[$keyField] ?? null, $excludeValues, true);
        });
    }

}
