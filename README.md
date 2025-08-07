# Proyecto Vanilla

Este es un proyecto de ejemplo con HTML, CSS y Javascript puro (sin framework), creado como ejercicio final del curso de Git.

## Tecnologias usadas

- HTML5
- CSS3
- Javascript

## Estructura del proyecto

```
anime-shop/
├── app/
│   ├── controllers/
│   │   ├── product/
│   │   │   ├── ListController.php
│   │   │   ├── ShowController.php
│   │   │   └── ...
│   │   ├── user/
│   │   ├── cart/
│   │   └── ...
│   ├── models/
│   │   ├── product/
│   │   │   ├── Product.php
│   │   │   └── ProductRepository.php
│   │   ├── user/
│   │   ├── order/
│   │   └── ...
│   ├── views/
│   │   ├── product/
│   │   │   ├── list.php
│   │   │   ├── show.php
│   │   │   └── ...
│   │   ├── user/
│   │   ├── cart/
│   │   └── ...
│   ├── config/
│   │   ├── database.php
│   │   └── app.php
│   ├── helpers/
│   │   ├── AuthHelper.php
│   │   ├── ValidationHelper.php
│   │   └── ...
│   └── core/
│       ├── Controller.php       # Clase base para todos los controladores
│       ├── Model.php            # Clase base para todos los modelos
│       ├── View.php             # Clase base para renderizar vistas
│       ├── Router.php           # Sistema de rutas personalizado (si aplica)
│       └── App.php              # Inicializador del framework/MVC
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── index.php                # Punto de entrada a la aplicación
├── .htaccess                    # Reescritura de URLs para routing amigable
└── README.md                    # Documentación del proyecto
```

## Captura de pantalla

![demo](https://via.placeholder.com/600x300?text=Demo+del+Proyecto)

## Funcionalidades

- [x] Estructura de una base
- [ ] Añadir lógica en Javascript
- [ ] Mejorar estilos

## Como clonar este repositorio

```bash
git clone https://github.com/devSecD/proyecto-vanilla.git
cd proyecto-vanilla
```

## Autor

Creado por [Dev Sec D](https://github.com/devSecD)

## Licencia

Este proyecto esta bajo licencia MIT.


Flujo del proceso del pago:
1. Agregar al carritos los prodductos (esto desde cualquier pagina donde tenga el boton de "agregar al carrito)
2. Pantalla: carrito donde muestro los siguientes datos:
    * Imagen del producto
    * Nombre dedl producto
    * Cantidad por producto
    * Precio unitario (por producto)
    * Subtotal del producto (cantidad * precio)
    * Boton de elimnar producto
    * Total a pagar de todos los productos
    * Boton de "Seguir comprando"
    *  Boton de "Ir a pagar"

3. Pantalla: resumen del pedido donde muestro los siguientes datos:
    * Imagen del producto
    * Nombre del producto
    * Cantidad por producto
    * Precio unitario (por producto)
    * Subtotal del producto (cantidad * precio)
    * Total a pagar de todos los productos
    * Boton de "Volver al carrito"
    * Boton de "Continuar con la compra"
  
    * Mostrar logo de métodos de pago aceptados (para tranquilizar al usuario).
    * (FEATURE) Mostrar costo de envío si ya se calculó (o “se calculará en siguiente paso”).

4. Pantalla Direccion de envio. Es un formulario con los siguientes datos:
    * Nombre completo
    * Teléfono
    * Correo electrónico
    * Calle y número
    * Colonia
    * Código Postal
    * Ciudad
    * Estado
    * País
    * Notas adicionales
    
    - Boton de "Regresar" (regresa al resumen del pedido)
    - Boton de "Continuar al pago"

5. Pantalla: pago donde muestro los siguientes datos:
    * Dirección de envío
    * Método de pago
    * Resumen del pedido (viene tambien el total a pagar)
    * Boton de "Regresar" (regresa al formulario de la direccion de envio)
    * Boton de "Pagar ahora"

* (FEATURE) Mostrar claramente tiempo estimado de entrega.
* (FEATURE) Mostrar desglose de impuestos + envío + total final.

6. Pantalla que me proporciona Mercado Pago del checkout pro, aqui yo no implemento nada de codigo solo tengo el webhook 
y las redirecciones

1. Pantalla despues de realizar el pago (back urls asi lo reconoce Mercado Pago) que puede ser las siguientes de acuerdo al estatus del pago:
    * Succes
    * Pending
    * Faillure

AL TOTAL SE LE DEBE AGREGAR IMPUESTOS

## 🛠️ Resetear ID AUTO_INCREMENT (para evitar IDs discontinuos)

Si deseas reiniciar el contador de IDs de una tabla (por ejemplo, `orders`) para que el siguiente registro comience desde `1` (o cualquier valor que quieras), usa el siguiente comando SQL:

```sql
ALTER TABLE orders AUTO_INCREMENT = 1;


## ✅ Matriz de pruebas – Checkout y procesamiento de pagos (Mercado Pago)

| #  | Escenario                        | Paso / acción                                               | Esperado                                                                                  | Observado | OK ✔ |
|----|----------------------------------|-------------------------------------------------------------|-------------------------------------------------------------------------------------------|----------|------|
| 1  | Pago exitoso (approved)         | Realizar compra con tarjeta de test que aprueba             | Redirección a `success` en `back_urls` + orden marcada como *paid* en BD                  |          |      |
| 2  | Pago rechazado (rejected)      | Usar tarjeta test que rechaza                               | Redirección a `failure` + orden marcada como *cancelled* o sin cambiar                    |          |      |
| 3  | Pago pendiente                  | Usar tarjeta test que devuelve “pending”                     | Redirección a `pending` + orden marcada como *pending*                                    |          |      |
| 4  | Cancelación manual             | Pagar y luego cancelar desde panel Mercado Pago             | Orden actualiza estatus a *cancelled*                                                     |          |      |
| 5  | Webhook notification (approved)| Simular notificación webhook (approved)                     | Orden actualiza estatus a *paid*                                                          |          |      |
| 6  | Webhook notification (cancelled)| Simular notificación webhook (cancelled)                     | Orden actualiza estatus a *cancelled*                                                     |          |      |
| 7  | Doble notificación webhook     | Simular dos notificaciones seguidas (approved + cancelled)  | Orden queda finalmente en el estatus correcto (*cancelled* si la segunda es cancel)       |          |      |
| 8  | Order inexistente              | Forzar notificación webhook a `order_id` que no existe      | Se registra log, pero no falla ni rompe la aplicación                                     |          |      |
| 9  | Verificación de logs           | Realizar pago normal                                        | Entrada creada en tabla `payment_logs` o logs correctos                                   |          |      |
| 10 | Imágenes de productos          | Confirmar que en vista result se ven nombre + imagen        | Todo cargado correctamente                                                                |          |      |
| 11 | Totales y subtotales           | Confirmar que suma de subtotales == total order             | Correcto                                                                                  |          |      |
| 12 | URL final amigable             | Revisar que la redirección final de MP use URL correcta     | Sin parámetros basura o datos sensibles en la URL final                                   |          |      |

## Notas Técnicas / Recordatorios

### Inicio del endpoint con ngrok para webhook

Para levantar el túnel ngrok y exponer tu endpoint local de webhook:

C:/ngrok.exe start anime-shop-webhook
anime-shop/public/webhook/mercadopago

### Problema con el `order_id` en la preferencia de Mercado Pago

Mercado Pago no devuelve el `order_id` que enviamos en la preferencia,  
por eso se usa el campo `external_reference` para pasar nuestro ID interno de la orden y poder identificarla correctamente.

Referencias útiles:

- [API Pagos Mercado Pago](https://www.mercadopago.com.mx/developers/es/reference/payments/_payments_search/get)  
- [Crear pagos online Mercado Pago](https://www.mercadopago.com.mx/developers/es/reference/orders/online-payments/create/post)  
- [Uso de external_reference](https://www.mercadopago.com.mx/developers/es/docs/checkout-bricks/status-screen-brick/advanced-features/add-external-reference)  
- [StackOverflow (ES) problema con external_reference](https://es.stackoverflow.com/questions/275430/problema-external-reference-mercadopago)


******************************************************************************************************************************************

🎯 Objetivo MVP
* Crear un panel seguro para subir nuevos productos a la tienda.
* Panel accesible solo para tu usuario administrador (puede ser por sesión o autenticación simple).
* Funcionalidad básica para:
    * Añadir producto (nombre, descripción, precio, stock, categoría, marca, imagen, etc.).
    * Editar producto.
    * Listar productos con paginación.
    * Eliminar productos (opcional en MVP).
* Validaciones básicas en frontend y backend.
* Guardar imágenes (local o carpeta pública).


🛠️ Paso a paso inicial para este módulo:
1. Rutas y controladores
    * Crear controlador(s) para Admin\ProductController o similar.
    * Rutas protegidas (middleware o lógica simple que valide que solo tú puedas acceder).

2. Vistas
    * Formulario para crear/editar productos.
    * Tabla/listado de productos existentes.

3. Modelos y repositorios
    * Usar o extender el modelo Product que ya tienes.
    * Añadir métodos para crear, actualizar y borrar productos.

4. Subida de imágenes
    * Manejo básico de upload en PHP, guardando en carpeta pública /public/uploads/.
    * Validar tipo y tamaño del archivo.

5. Seguridad
    * Para el MVP, puedes usar validación simple con sesión y un usuario hardcodeado.
    * Más adelante implementarás roles y usuarios.

******************************************************************************************************************************************

----------------------------------------------------------------------------------------------------------------------------------------

* Empezamos creando el middleware básico que valide que el usuario está logueado y que su role sea 'admin'.
* Creamos el controlador Admin\ProductController con el método index() para listar productos.
* Creamos vistas y formularios con la estructura que usas para el admin (sin CSS, solo marcado básico).
* Implementamos el repositorio y modelo con el flujo ya definido para CRUD básico.
* Creamos los scripts JS y PHP para validaciones reutilizables (o los adaptamos si ya tienes).
* Creacion de tabals necesarios para los roles en Base de Datos

* Agregar la lógica para manejar subida de imágenes.
* Completar carga dinámica de categorías y marcas en el formulario.
* Crear métodos para editar y eliminar productos.
* Agregar validaciones backend y frontend reutilizando tus funciones.
* Estilizar las vistas con tu CSS.

----------------------------------------------------------------------------------------------------------------------------------------


ESTO CADA VEZ QUE EMPIECE UNA NUEVA FUNCIONALIDAD

Mira yo traigo mi propio estilo de trabajo y te doy un contexto general y dime si te sirve que te un ejemplo del contexto:
1. Yo llamo a las vistas desed un controlador y generalmente uso el metodo index para cargar la vista o a veces tiene otro nombre iferente de "index" pero generalmente lleva ese nombre.
2. El css ya traigo el diseño basado en eldiseño del home junto con mis variables de csss que las tengo en un archivo del mismo nombre. Tambien tengo css que reutilizo
3. Los archivos js los voy seperando y encapsulando con ayuda e export y de ahi los importo en donde los requiera usar
4. Para las validaciones tanto en front como en backend uso archivos ya se js o php para tener mis funciones de validaciones y solo invocarlas cuano las use.
5. Recuerda y esto siempre te lo repito y se te olviddda no se porque; el flujo del backend es controladdor -> repository que lleva la logica del negocio -> modelo que solo lleva las consultas SQL
6 Referente a la base de datos. Actualmente solamente tengo una tabla de usuarios la cual tiene la sigueinte estructura (te paso el scrip SQL con el que se creo para amyor entendimiento):

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

Actualmente no tengo una tabla de roles y si me gustaria podedr implementar los roles + middleware mas que naad por seguridad.

7. Yo ya tengo registro y login para usuario promedio que son los que van a ver la tienda online y si deesean comprar productos lom haran. Quiero que esto no se mueva o si se mueve sea lo mas minimo lo digo por la implementacion de roles + midleware

Entonces con todddo esto creo que ya podriamos ir empezanddo esto siempre y cuando no requieres de un ejemplo (algo que ya haya echo por ejeplo registro de usuario, login, etc) para entender mejor el contexto

ESTO CADA VEZ QUE EMPIECE UNA NUEVA FUNCIONALIDAD
