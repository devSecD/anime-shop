<section class="checkout-section">
    <?php /** @var array $items  @var float $total */ ?>
    <h2 class="checkout-title">Resumen de tu pedido</h2>

    <div class="checkout-table-container">
        <table class="checkout-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $pid => $item): ?>
                    <tr>
                        <td class="product-info">
                            <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img">
                            <span class="product-name"><?= htmlspecialchars($item['name']) ?></span>
                        </td>
                        <td><?= $item['qty'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total:</th>
                    <th>$<?= number_format($total, 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="checkout-actions">
        <a href="/anime-shop/public/cart" class="btn btn-secondary">Volver al carrito</a>
        <a href="/anime-shop/public/checkout/address" class="btn btn-primary">Continuar con la compra</a>
    </div>

    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>

</section>