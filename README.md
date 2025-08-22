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

-----------------------------------------------------------------------------------------------------------------------------

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

# Módulo Detalle del Producto - Plan de desarrollo (Anime Shop)

## 1️⃣ Funcionalidades principales
1. **Mostrar información completa del producto**
   - Nombre, precio, disponibilidad, SKU, marca, categoría.
   - Descripción corta y descripción detallada.
2. **Galería de imágenes**
   - Imagen principal ampliable.
   - Miniaturas para cambiar imagen.
   - Zoom al pasar el cursor (opcional).
3. **Opciones de compra**
   - Botón "Agregar al carrito".
   - Botón "Agregar al wishlist".
4. **Estado de stock**
   - Mostrar si hay unidades disponibles o si está agotado.
5. **Información extra**
   - Opiniones y calificaciones de otros usuarios.
   - Productos relacionados o recomendados.
6. **Datos estructurados (SEO)**
   - Implementar metaetiquetas y JSON-LD para mejorar posicionamiento en buscadores.

---

## 2️⃣ Backend: dividido en partes pequeñas
| Paso  | Funcionalidad                | Descripción |
|-------|------------------------------|-------------|
| **B1** | **Ruta y controlador**        | Acción `GET /product/{id}/{slug}` que reciba ID y slug para mostrar el detalle. |
| **B2** | **Modelo y repositorio**      | Método `getProductById($id)` que retorne toda la información del producto (uniones con categorías, marcas, stock). |
| **B3** | **Control de errores**        | Si el producto no existe, mostrar página 404 personalizada. |
| **B4** | **Galería de imágenes**       | Método para obtener imágenes adicionales del producto desde tabla `product_images`. |
| **B5** | **Opiniones de usuarios**     | Método `getReviewsByProductId($id)` para mostrar reseñas. |
| **B6** | **Productos relacionados**    | Método `getRelatedProducts($categoryId, $excludeId)` para sugerencias. |
| **B7** | **Datos SEO**                 | Generar metaetiquetas dinámicas (title, description, og:image, etc.). |

---

## 3️⃣ Frontend: dividido en partes pequeñas
| Paso  | Funcionalidad                | Descripción |
|-------|------------------------------|-------------|
| **F1** | **Estructura básica HTML**    | Maquetar nombre, precio, botones y descripción. |
| **F2** | **Galería de imágenes**       | Imagen principal + miniaturas; clic en miniatura cambia la imagen. |
| **F3** | **Zoom o modal de imagen**    | Ampliar imagen al hacer clic o pasar el cursor. |
| **F4** | **Botones de acción**         | "Agregar al carrito" y "Agregar al wishlist" con eventos JavaScript/AJAX. |
| **F5** | **Estado de stock**           | Mostrar disponibilidad visualmente (verde: disponible, rojo: agotado). |
| **F6** | **Opiniones de usuarios**     | Lista de reseñas con nombre, fecha, comentario y calificación en estrellas. |
| **F7** | **Productos relacionados**    | Carrusel o grid con productos similares. |
| **F8** | **Responsive design**         | Adaptar la vista a móviles y tablets. |

---

## 4️⃣ Orden recomendado para desarrollo
1. **Backend base**: ruta, controlador y método `getProductById`.
2. **Maquetado básico** (HTML/CSS) con datos estáticos.
3. **Integrar datos reales** desde la base de datos.
4. **Agregar galería de imágenes**.
5. **Botones de acción funcionales** (carrito y wishlist).
6. **Opiniones y calificaciones**.
7. **Productos relacionados**.
8. **Mejoras visuales y optimizaciones SEO**.

--------------------------------------------------------------------------------------------------------------------

# Módulo Wishlist - Estado actual (Anime Shop MVP)

## ✅ Funcionalidades esenciales implementadas

### Backend
| Funcionalidad | Estado |
|---------------|--------|
| Tabla `wishlist` con campos `id`, `user_id`, `product_id`, `created_at` | ✅ Hecha |
| `WishlistModel` y `WishlistRepository` con métodos `addItem`, `removeItem`, `getItems`, `exists` | ✅ Hecho |
| Controladores: agregar (`add`), eliminar (`remove`), listar (`list`) | ✅ Hecho |
| Migración de localStorage al iniciar sesión | ✅ Hecho |

### Frontend
| Funcionalidad | Estado |
|---------------|--------|
| Botón de wishlist en detalle del producto con AJAX | ✅ Hecho |
| Animación del corazón al agregar/eliminar | ✅ Hecho |
| Vista `/wishlist` mostrando productos o mensaje de lista vacía | ✅ Hecho |
| Persistencia para invitados usando `localStorage` | ✅ Hecho |
| Contador de wishlist en el header actualizado en tiempo real | ✅ Hecho |

## ❌ Funcionalidades excluidas para MVP / mejoras futuras
| Funcionalidad | Estado |
|---------------|--------|
| Agregar wishlist desde listado/catálogo | ❌ Mejora futura |
| Eliminar múltiples productos a la vez | ❌ Mejora futura |
| Notificaciones de cambios de stock o precio | ❌ Mejora futura |
| Acciones visuales o animaciones avanzadas adicionales | ❌ Mejora futura |

## ✅ Conclusión
El módulo Wishlist esencial está completo y listo para el MVP. Todas las funcionalidades críticas para usuarios registrados e invitados funcionan correctamente.


------------------------------------------------------------------------------------------------------------------------

 Entonces voy a comenzar el modulo del wishlist/lista de deseos para esto te dare el siguiente contexto relevante:

1. Para todas las vistas quiero el mismo diseño que incluyen estos css uno de variables y el otro es un ejemplo de implementacion del diseño con hojas de estilo:

* variables css

:root {
    --primary-color: #0277bd;
    --primary-color-dark: #015a94;
    --secondary-color: #ffc107;
    --accent-color: #d32f2f;
    --text-color: #fff;
    --secondary-text-color: #000;

    /* Background and borders */
    --background-color: #f9f9f9;
    --background-color-secondary: #222021;
    --border-color: #ddd;

    /* Fonts */
    --main-font: 'Poppins', sans-serif;
    --secondary-font: 'Roboto', sans-serif;

    /* Spacing */
    --small-spacing: 8px;
    --medium-spacing: 16px;
    --large-spacing: 32px;

    /* Font size */
    --font-size-title: 2rem;
    --font-size-paragraph: 1rem;
    --font-size-small: 0.875rem;

    /* Bordes y sombras */
    --border-radius: 5px;
    --box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);

    /* Tamaños */
    --max-width: 1200px;

    /*

    Breve documentacion
    * Encabezados y botones                           => var(--primary-color)
    * Llamadas a la accion (CTA), ofertas             => var(--secondary--color)
    * Detalles y resaltes                             => var(--accent-color)
    * Textos principales                              => var(--secondary-text-color)
    * Fondo general                                   => var(--background-color)

    */
}

* Y un ejemplo basado en el catalogo:

/* Ajusta para que no se desborden los contenedores hijos */
.section-catalog-product {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    padding: 1.5rem;
    width: 97%; /* ajuste de ancho */
}
/* Ajusta para que no se desborden los contenedores hijos */

.section-catalog-product .aside-collection-toolbar{
    grid-column: 1 / -1;
}

.section-catalog-product .aside-collection-toolbar{
    display: grid;
    grid-template-columns: 50% 50%;
    justify-items: center;
}

/* filtro para responsive */
.aside-collection-toolbar .aside-filter {
    display: none;
}

#filter-toggle {
    display: none;
}

.close_search {
    display: none;
    font-size: 1.5rem;
    position: fixed;
    top: 0.1rem;
    right: 0.5rem;
    z-index: 5;
    width: 20px;
    height: 20px;
    text-align: center;
}

.close_search a{
    color: var(--accent-color);
}

.filter-menu-container i.fa-filter:hover{
    color: var(--accent-color);
}

.nav-filter {
    position: fixed;
    top: 0rem;
    margin-left: 0rem;
    z-index: 2;
    width: 85%;
    height: 100%;
    background-color: var(--primary-color); /* negro */
    transition: right 0.4s cubic-bezier(0.77, 0.2, 0.05, 1);
    box-shadow: -4px 0 15px var(--accent-color);
    overflow-y: auto;
    padding-top: 100px;
}

.nav-filter::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: var(--secondary-color);
    backdrop-filter: blur(10px);
}
  
.nav-filter ul li {
    margin: 0 15px;
    position: relative; /* para pdoerle aplicar posicion absoluta al a::after */
}
  
.nav-filter ul li a {
    display: block;
    color: #fff;
    text-decoration: none;
    padding: 15px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.nav-filter ul li a::after {
    position: absolute;
    right: 0.5rem; /* siempre pegado al borde */
    content: "\25BC";
}
/* filtro para responsive */

.container-product {
    padding: 1rem;
    border: 1px solid rgba(255, 193, 7, 0.5);
    transition: border 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.container-product:hover {
    border: 1px solid transparent;
    box-shadow: 0 4px 10px rgba(255, 193, 7, 0.6);
}

/* Badge visual de productos */
.badge-container {
    position: relative;
    height: 0;
}

.badge {
    position: absolute;
    top: -0.5rem;
    padding: 0.3rem 0.6rem;
    font-size: 0.75rem;
    font-weight: bold;
    color: #fff;
    border-radius: 0.25rem;
    z-index: 1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.badge-sale {
    left: -0.5rem;
    background-color: var(--accent-color); /* Amarillo */
}

.badge-preorder {
    right: 0rem; /* desplaza a la derecha para que no se superponga */
    background-color: #007bff; /* azul */
}

.container-product img {
    width: 100%;
    height: 300px;
    object-fit: contain;
    cursor: pointer;
}

.container-product p {
    margin: 1rem 0rem;
    cursor: pointer;
}

.container-product h5 {
    margin: 0.75rem 0rem;
    cursor: pointer;
}

.container-product p, .container-product h5 {
    text-align: center;
}

.btn-add-to-cart {
    width: 100%;
    padding: 0.7rem;
    background-color: var(--secondary-color);
    color: #333;
    border: 1px solid var(--accent-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    font-size: var(--font-size-paragraph);
    transition: background-color 0.3s ease, border 0.3s ease, color 0.3s ease;
}

.btn-add-to-cart:hover {
    background-color: var(--accent-color);
    color: var(--text-color);
    border: 1px solid var(--secondary-color);
}

.aside-paginated {
    grid-column: 1 / -1; /* Esto sirve para que el ancho ocupa todas las columnas disponibles grid del padre */
}

.page-list {
    display: grid;
    grid-template-columns: repeat(7, auto);
    gap: 0.5rem;
    justify-content: center;
    padding: 1rem;
}

.page-list li a {
    padding: 0.5rem 0.7rem;
    color: #333;
    cursor: unset;
}

.page-list li a.page-current {
    background-color: var(--secondary-color);
}

.page-list li a.page-number, .page-list li a.page-previous, .page-list li a.page-next {
    transition: color 0.3s ease;
}

.page-list li a.page-number:hover, .page-list li a.page-previous:hover, .page-list li a.page-next:hover {
    color: var(--accent-color);
    cursor: pointer;
}

/* remueve estilos por defecto de enlaces */
a {
    text-decoration: none;
    color: inherit;
}

a:visited {
    color: inherit;
}

a:hover,
a:focus {
    text-decoration: none;
    color: inherit;
}

a:active {
    color: inherit;
}
/* remueve estilos por defecto de enlaces */

/* Media queries */
@media (max-width: 1000px) {
    .section-catalog-product {
        width: 100%;
    }
}
@media (max-width: 640px) {
    .section-catalog-product {
        grid-template-columns: 100%;
        padding: 0.5rem;
    }
}

@media (min-width: 641px) and (max-width: 999px) {
    .section-catalog-product {
        grid-template-columns: 50% 50%;
        padding: 1rem;
    }
}

@media (min-width: 1000px) and (max-width: 1279px) {
    .section-catalog-product {
        grid-template-columns: 33% 33% 33%;
        padding: 1.3rem;
    }
}

/* filtro responsive */

@media (max-width: 1000px) {
    .aside-collection-toolbar .aside-filter {
        display: block;
    }
    .nav-filter {
        display: none;
    }
}

/* felchas del menu desplegable de filtros */
@media (min-width: 430px) and (max-width: 500px){
    .nav-filter ul li a::after {
        right: 1rem; /* 430px a 500px => 1rem */
    }
}

@media (min-width: 501px) and (max-width: 640px){
    .nav-filter ul li a::after {
        right: 2rem; /* 501px a 640px => 2rem */
    }
}

@media (min-width: 641px) and (max-width: 800px){
    .nav-filter ul li a::after {
        right: 3rem; /* 641px a 800px => 3rem */
    }
}

@media (min-width: 801px) and (max-width: 900px){
    .nav-filter ul li a::after {
        right: 4rem; /* 801px a 900px => 4rem */
    }
}

@media (min-width: 901px) and (max-width: 1000px){
    .nav-filter ul li a::after {
        right: 5rem; /* 901px a 1000px => 5rem */
    }
}
/* felchas del menu desplegable de filtros */

/* filtro responsive */

2. Siempre se usa MVC + Repository para el backend. La forma de implementarlo es la siguiente:

* Controlador invoca repository. Respository se encarga de la logica del negocio e invoca al modelo. El modelo se encarga exclusivamente de la(s) consulta(s) SQL.