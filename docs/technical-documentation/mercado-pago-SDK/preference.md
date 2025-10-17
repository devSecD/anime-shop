# ¿Qué es una preferencia en Mercado Pago?

En Mercado Pago, una **preferencia** es un **objeto de configuración** que define cómo se presentará y procesará un pago.  

Cuando se crea una preferencia, se envía a Mercado Pago toda la información necesaria para generar el **checkout** de pago:

---

## Contenido típico de una preferencia

- **Items:** Lista de productos o servicios (nombre, precio, cantidad).  
- **URLs de retorno (`back_urls`):** Direcciones a dónde redirigir al comprador según el resultado del pago:  
  - `success`: si el pago fue aprobado.  
  - `failure`: si ocurrió un error.  
  - `pending`: si el pago quedó pendiente.  
- **auto_return:** Define si el comprador regresa automáticamente al sitio después de pagar (ejemplo: `"approved"`).  
- **external_reference:** Referencia externa que vincula el pago con tu sistema, normalmente el **ID de la orden**.  
- **metadata:** Datos adicionales personalizados que quieras asociar al pago (ejemplo: ID de usuario, ID de orden).  

---

## Resultado de la creación de una preferencia

El servicio de Mercado Pago devuelve un objeto `Preference` que incluye, entre otros datos:

- **init_point:** La URL única para iniciar el checkout de Mercado Pago.  
- **sandbox_init_point:** Enlace para pruebas en ambiente sandbox.  
- Los mismos datos de items, back_urls, external_reference y metadata enviados en la petición.

---

## Resumen

En pocas palabras:  
👉 La **preferencia** es el **puente** entre el carrito de compras (o la orden en tu sistema) y el **checkout de Mercado Pago**.  
Permite asociar los productos, la orden y las rutas de retorno con el flujo de pago en línea.