<section class="cart-section">
    <?php /** @var array $items  @var float $total */ ?>
    <h2 class="cart-title">Tu carrito</h2>

    <?php if (!$items): ?>
        <p class="cart-empty">No tienes productos añadidos. <a href="/anime-shop/public/catalog">Explorar catálogo</a></p>
    <?php else: ?>

        <div class="cart-table-container">
            <table class="table-cart">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($items as $pid => $item): ?>
                    <tr>
                        <td class="product-info">
                            <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img">
                            <span class="product-name"><?= htmlspecialchars(string: $item['name']) ?></span>
                        </td>
                        <td>
                            <input type="number" min="1" value="<?= $item['qty'] ?>" data-id="<?= $pid ?>" class="cart-qty-input">
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        <td><button data-id="<?= $pid ?>" class="btn-remove cart-remove">&times;</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <p>Total: <strong id="cart-total"><?= number_format($total, 2) ?></strong></p>
            <div class="cart-actions">
                <a href="/anime-shop/public/catalog" class="btn btn-secondary">Seguir comprando</a>
                <a href="/anime-shop/public/checkout" class="btn btn-primary">Ir a pagar</a>
            </div>
        </div>

        <?php if (in_array('cart', $assets)): ?>
            <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
        <?php endif; ?>

    <?php endif; ?>
</section>