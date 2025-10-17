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
                            <th>Estado</th>
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
                                    <td data-label="ID"><?= htmlspecialchars($product['product_id']) ?></td>
                                    <td data-label="Nombre"><?= htmlspecialchars($product['name']) ?></td>
                                    <td data-label="Precio">$<?= htmlspecialchars($product['price']) ?></td>
                                    <td data-label="Precio Oferta">
                                        <?= $product['price_discounted'] ? '$' . htmlspecialchars($product['price_discounted']) : '-' ?>
                                    </td>
                                    <td data-label="Stock"><?= htmlspecialchars($product['stock']) ?></td>
                                    <td data-label="Vendidos"><?= htmlspecialchars($product['sold_count']) ?></td>
                                    <td data-label="Oferta">
                                        <?= $product['is_on_sale'] ? '<span title="En oferta">🔥</span>' : '-' ?>
                                    </td>
                                    <td data-label="Preventa">
                                        <?= $product['is_preorder'] ? '<span title="Preventa">🕓</span>' : '-' ?>
                                    </td>
                                    <td data-label="Imagen">
                                        <?php if (!empty($product['image'])): ?>
                                            <img class="product-thumbnail" src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="Miniatura">
                                        <?php else: ?>
                                            <span class="no-image">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Categoría"><?= htmlspecialchars($product['category_name'] ?? 'Sin categoría') ?></td>
                                    <td data-label="Marca"><?= htmlspecialchars($product['brand_name'] ?? 'Sin marca') ?></td>
                                    <td data-label="Creado"><?= date('d/m/Y', strtotime($product['created_at'])) ?></td>
                                    <td data-label="Estado" class="status-cell <?= $product['is_active'] ? '' : 'inactive-text' ?>">
                                        <?= $product['is_active'] ? 'Activo' : 'Inactivo' ?>
                                    </td>
                                    <td data-label="Acciones">
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

                <?php
                    include __DIR__ . '/../../../view/admin/components/pagination.php';
                ?>

            </div>
        </main>
    </div>
    <script async src="/anime-shop/public/assets/js/components/modal-delete.js" type="module"></script>
</body>
</html>
