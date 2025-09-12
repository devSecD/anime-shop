# Validación de stock en Checkout

## Descripción
Se implementó una validación en el **flujo de pago** para garantizar la integridad de las órdenes y evitar inconsistencias en el inventario al momento de procesar una compra.

La lógica se ejecuta en el `ResultController`, que es el punto de redirección de Mercado Pago una vez completado un pago. En este punto, antes de disminuir stock o incrementar el contador de ventas de un producto, se valida que la cantidad solicitada no exceda el stock disponible.

---

## Implementación

### Controlador `ResultController`

- En el método `successView`, al procesar una orden con estado **paid**:
  - Se recorren los ítems de la orden.
  - Se valida que la cantidad (`quantity`) sea mayor a 0 y **menor o igual** al stock actual.
  - Solo en ese caso se:
    - Disminuye el stock (`decreaseStock`).
    - Incrementa el contador de ventas (`increaseSoldCount`).

```php
if ($productId && $quantity > 0 && $quantity <= $stock) {
    $productRepo->decreaseStock($productId, $quantity);
    $productRepo->increaseSoldCount($productId, $quantity, $stock);
}
```

---

### Repository `ProductRepository`

En la capa Repository también se validan las operaciones críticas:

```php
public function decreaseStock(int $productId, int $qty, int $stock): bool
{
    if ($qty <= 0 || $qty > $stock) return false;
    return $this->productModel->decreaseStock($productId, $qty);
}

public function increaseSoldCount(int $productId, int $qty, int $stock): bool
{
    if ($qty <= 0 || $qty > $stock) return false;
    return $this->productModel->increaseSoldCount($productId, $qty);
}
```

De esta forma se aplica **defensa en profundidad**: tanto el controlador como el repositorio evitan inconsistencias en los datos.

---

## Nota de seguridad

- Alterar la sesión de PHP desde el cliente no es trivial, lo que ya reduce riesgos.
- Aun así, se implementó validación adicional en el backend para proteger el stock y el conteo de ventas.

---

## Mejora futura (Versión 2)

Actualmente, si se detecta un intento de compra con cantidades mayores al stock, simplemente no se procesa el decremento ni el incremento de ventas.  

Para la **Versión 2** se planea extender esta lógica:
- No permitir que se agregue el producto a la orden si la validación falla.
- Detener la compra completa y mostrar un **mensaje de error general** al usuario.
- O bien, notificar específicamente que un producto no tiene stock suficiente.

📂 Documentado en:  
`docs/FEATURES_V2/validacion_stock_checkout.md`
