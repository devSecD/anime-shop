<?php
namespace Core;

class Paginator
{
    private int $currentPage;
    private int $perPage;
    private int $totalItems;
    private int $totalPages;

    public function __construct(int $totalItems, int $perPage = 10, ?int $currentPage = null)
    {
        $this->totalItems = $totalItems;
        $this->perPage = $perPage;
        $this->totalPages = (int) ceil($totalItems / $perPage);

        // Detectar página actual
        $page = $currentPage ?? ($_GET['page'] ?? 1);
        $page = is_numeric($page) && $page > 0 ? (int) $page : 1;

        // Limitar al máximo de páginas disponibles
        // Asegura que la página actual no sea mayor que la última disponible, usando 1 si no hay páginas
        $this->currentPage = min($page, $this->totalPages > 0 ? $this->totalPages : 1);
    }

    /**
     * Calcula el desplazamiento (offset) para la consulta SQL
     * en función de la página actual y la cantidad de elementos por página.
     *
     * Este valor se utiliza típicamente en sentencias SQL con LIMIT y OFFSET
     * para obtener el subconjunto de registros correspondiente a la página actual.
     *
     * @return int Número de registros a omitir antes de comenzar a recuperar los resultados.
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Verifica si existe una página anterior disponible.
     *
     * Devuelve true cuando la página actual es mayor que 1,
     * lo que indica que se puede retroceder en la paginación.
     *
     * @return bool true si hay una página anterior, false en caso contrario.
     */
    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Verifica si existe una página siguiente disponible.
     *
     * Devuelve true cuando la página actual es menor que el total de páginas,
     * lo que indica que se puede avanzar en la paginación.
     *
     * @return bool true si hay una página siguiente, false en caso contrario.
     */
    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }
}
