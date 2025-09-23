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
        $this->currentPage = min($page, $this->totalPages > 0 ? $this->totalPages : 1);
    }

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

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }
}
