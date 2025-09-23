<?php
// pagination.php
// $pagination: array con currentPage, totalPages, hasPrev, hasNext
// $queryParams: array opcional para filtros (ej: ['status' => 'pending'])

$queryString = '';
if (!empty($queryParams) && is_array($queryParams)) {
    $queryString = '&' . http_build_query($queryParams);
}
?>

<?php if ($pagination['totalPages'] > 1): ?>
    <div class="pagination-container">
        <?php if ($pagination['hasPrev']): ?>
            <a class="pagination-btn" href="?page=<?= $pagination['currentPage'] - 1 ?><?= $queryString ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
            <a class="pagination-btn <?= $i == $pagination['currentPage'] ? 'active' : '' ?>"
               href="?page=<?= $i ?><?= $queryString ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($pagination['hasNext']): ?>
            <a class="pagination-btn" href="?page=<?= $pagination['currentPage'] + 1 ?><?= $queryString ?>">Siguiente</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
