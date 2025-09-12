# Feature: URL amigable post-checkout (Mercado Pago Pro)

## 1. Contexto

Actualmente, después de completar un pago con Mercado Pago Pro, la URL de redirección contiene **muchos parámetros técnicos** y no es amigable ni para el usuario ni para SEO.
Ejemplo de URL actual (proporcionada por ngrok):

```text
https://url-ngrok/anime-shop/public/payment/result?collection_id=1324724684&collection_status=approved&payment_id=1324724684&status=approved&external_reference=7&payment_type=debit_card&merchant_order_id=33880675373&preference_id=2525379108-d046f293-694a-4886-8ed4-dd052c9c096e&site_id=MLM&processing_mode=aggregator&merchant_account_id=null
```

Se desea simplificarla a una URL amigable, mostrando **solo los datos necesarios** (`status`, `payment_id`, `external_reference`) para la vista de resultado del pago.

---

## 2. Objetivo

* Mejorar la experiencia del usuario con URLs claras y simples.
* Facilitar la compartición de enlaces de confirmación de pago.
* Mantener compatibilidad con la lógica actual de Mercado Pago Pro.
* Preparar la tienda para futuras mejoras de SEO y usabilidad.

---

## 3. Requerimientos técnicos

### 3.1 Rutas

Agregar la siguiente ruta en el router (`Router.php` o archivo equivalente):

```php
'payment/result/{status}/{payment_id}/{external_reference}' => [
    'controller' => 'Payment\ResultController',
    'action' => 'index'
],
```

* `{status}` → estado del pago (`approved`, `pending`, `failure`).
* `{payment_id}` → identificador del pago en Mercado Pago.
* `{external_reference}` → referencia interna de la orden en Anime Shop.

---

### 3.2 Generación de URL amigable en la vista

En la vista, la URL se construirá usando los parámetros GET:

```php
payment/result/{$_GET['status']}/{$_GET['payment_id']}/{$_GET['external_reference']}
```

Esto permitirá que los enlaces sean limpios y consistentes.

---

### 3.3 Redirección desde parámetros GET actuales

Para que la transición de la URL actual a la amigable funcione automáticamente:

```php
if (isset($_GET['status'], $_GET['payment_id'], $_GET['external_reference'])) {
    $url = UrlHelper::base_url(
        "payment/result/{$_GET['status']}/{$_GET['payment_id']}/{$_GET['external_reference']}"
    );
    header("Location: $url");
    exit;
}
```

* `UrlHelper::base_url()` devuelve la URL base actual (ngrok o dominio en producción).
* Garantiza que cualquier acceso directo con los parámetros completos sea redirigido automáticamente a la URL amigable.

---

## 4. Lógica del `ResultController`

El controlador `Payment\ResultController@index` deberá:

```text
1. Validar que los parámetros 'status', 'payment_id' y 'external_reference' existen y son válidos.
2. Consultar en la base de datos la orden correspondiente (external_reference) para mostrar el resumen del pedido.
3. Mostrar un mensaje según el estado del pago:
   - approved → "Pago aprobado, gracias por tu compra."
   - pending → "Pago pendiente, en cuanto se confirme se actualizará el estado."
   - failure → "Pago rechazado, intenta nuevamente."
4. Manejar casos donde la orden no exista o los parámetros sean inválidos (mostrar error amigable).
```

---

## 5. Consideraciones

* Validar que los parámetros nunca estén vacíos ni manipulados maliciosamente.
* Asegurar compatibilidad con futuras actualizaciones de Mercado Pago Pro.
* Mantener consistencia de rutas y enlaces internos en toda la tienda.
* Preparar la URL para uso en email de confirmación o redes sociales.
* Registrar logs de redirección y errores para depuración futura.

---

## 6. Beneficios esperados

* URLs más limpias y fáciles de leer, recordar y compartir.
* Mejor experiencia post-checkout del usuario.
* Base sólida para futuras mejoras de SEO y usabilidad.

---

## 7. Pendientes / Futuras mejoras

* Agregar manejo de errores avanzado si el pago falla o los parámetros son incorrectos.
* Validar seguridad: impedir que usuarios manipulen parámetros para acceder a pedidos ajenos.
* Considerar compatibilidad con múltiples métodos de pago futuros.
* Posible integración de tracking y analytics en la URL amigable.
