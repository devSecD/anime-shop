<?php

namespace App\Helpers;

class FilterHelper
{
    /**
     * Verifica si un filtro está activo basado en los query params.
     * @param array $queryParams Array con parámetros de consulta (usualmente $_GET)
     * @param string $filterName Nombre del filtro a verificar
     * @param string $filterValue Valor del filtro para comparar
     * @return bool
     */

    public static function isActiveFilter(array $queryParams, string $filterName, string $filterValue  ): bool
    {
        return isset($queryParams[$filterName]) && $queryParams[$filterName] === $filterValue;
    }

    /**
     * Verifica si no hay ningún filtro activo en los query params.
     * @param array $queryParams Array con parámetros de consulta (usualmente $_GET)
     * @param array $filters Lista de filtros a verificar
     * @return bool
     */
    public static function isAllActive(array $queryParams, array $filters): bool
    {
        foreach ($filters as $filterName) {
            if (isset($queryParams[$filterName]) && $queryParams[$filterName] !== '') {
                return false;
            }
        }
        return true;
    }
}