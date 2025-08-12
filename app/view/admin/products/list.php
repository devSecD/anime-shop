<?php
    use App\Helpers\SessionHelper;
    SessionHelper::start();
    $user = SessionHelper::getUser() ;
?>
<!DOCTYPE html>
<html lang="es">
    <?php include $html_head ?>
<body>
    <div class="admin-dashboard">
        <?php include $sidebar ?>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Panel de administración: Productos</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <div class="add-product-container">
                <a href="/anime-shop/public/admin/products/create" class="btn-primary add-product-btn">
                    <i class="fa-solid fa-plus"></i> Agregar producto nuevo
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Precio Oferta</th>
                            <th>Stock</th>
                            <th>Vendidos</th>
                            <th>Oferta</th>
                            <th>Preventa</th>
                            <th>Imagen</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="13" class="text-center no-products">No hay productos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['product_id']) ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td>$<?= htmlspecialchars($product['price']) ?></td>
                                    <td>
                                        <?= $product['price_discounted'] ? '$' . htmlspecialchars($product['price_discounted']) : '-' ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['stock']) ?></td>
                                    <td><?= htmlspecialchars($product['sold_count']) ?></td>
                                    <td>
                                        <?= $product['is_on_sale'] ? '<span title="En oferta">🔥</span>' : '-' ?>
                                    </td>
                                    <td>
                                        <?= $product['is_preorder'] ? '<span title="Preventa">🕓</span>' : '-' ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['image'])): ?>
                                            <img class="product-thumbnail" src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="Miniatura">
                                        <?php else: ?>
                                            <span class="no-image">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['category_name'] ?? 'Sin categoría') ?></td>
                                    <td><?= htmlspecialchars($product['brand_name'] ?? 'Sin marca') ?></td>
                                    <td><?= date('d/m/Y', strtotime($product['created_at'])) ?></td>
                                    <td>
                                        <a href="/anime-shop/public/admin/product/update/<?= $product['product_id'] ?>" title="Editar" class="action-edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="/anime-shop/public/admin/products/delete?id=<?= $product['product_id'] ?>" 
                                        data-confirm="¿Estás seguro de que deseas eliminar este producto?" 
                                        class="action-delete btn-confirm-delete" 
                                        title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>

                                <?php include $modalConfirmDelete ?>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <?php if ($currentPage > 1): ?>
                            <a class="pagination-btn" href="?page=<?= $currentPage - 1 ?>">Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a class="pagination-btn <?= $i == $currentPage ? 'active' : '' ?>" href="?page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a class="pagination-btn" href="?page=<?= $currentPage + 1 ?>">Siguiente</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
            <!--
            Campo	            ¿Mostrar?	Comentario
            product_id	        ✅	        Útil como identificador interno. Puede ir oculto o visible.
            name	            ✅	        Nombre del producto (principal).
            price	            ✅	        Precio base (sin descuento).
            price_discounted	✅	        Si existe, mostrar junto al precio o con un ícono de oferta.
            stock	            ✅	        Crítico para administración.
            sold_count	        ✅	        Útil para ver qué tan popular ha sido.
            is_on_sale	        ✅	        Mostrar con ícono o etiqueta si está en oferta.
            is_preorder	        ✅	        Mostrar si está en preventa.
            image	            ✅	        Mostrar miniatura (opcional, mejora visualmente).
            category_id	        ✅	        Mostrar nombre de categoría (usando join).
            brand_id	        ✅	        Mostrar nombre de marca (usando join).
            created_at	        ✅	        Mostrar solo si necesitas control temporal (ordenar por reciente, etc.).
            description	        ❌	        No mostrar en la tabla resumen; es demasiado larga.
            Acciones	        ✅	        Botones para "Editar", "Eliminar", "Ver detalle", etc.
            -->
        </main>
    </div>
    <script async src="/anime-shop/public/assets/js/components/modal-delete.js" type="module"></script>
</body>
</html>
<style>
.pagination-admin {
    margin: 20px auto;
    text-align: center;
}
.pagination-list {
    list-style: none;
    padding: 0;
    display: inline-flex;
    gap: 10px;
}
.pagination-list li a {
    padding: 6px 12px;
    border: 1px solid #ccc;
    text-decoration: none;
    color: #333;
    border-radius: 4px;
}
.page-current {
    background-color: #333;
    color: white;
}

</style>
