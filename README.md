# Proyecto Anime Shop

Tienda en línea bautizada como **Anime-Shop**, actualmente un MVP.

## Contenido

- [Proyecto Anime Shop](#proyecto-anime-shop)
  - [Contenido](#contenido)
  - [Tecnologias usadas](#tecnologias-usadas)
  - [Requisitos](#requisitos)
  - [Arquitectura](#arquitectura)
  - [Estructura del proyecto](#estructura-del-proyecto)
  - [Funcionalidades](#funcionalidades)
  - [Roadmap](#roadmap)
  - [Instalación y uso](#instalación-y-uso)
  - [Base de datos](#base-de-datos)
  - [Features versión 2 (pendientes)](#features-versión-2-pendientes)
  - [Contribuciones](#contribuciones)
  - [Autor](#autor)
  - [Licencia](#licencia)

---

## Tecnologias usadas

- HTML5
- CSS3 Vanilla
- Javascript Vanilla
- PHP Vanilla
- Mysql
- Composer

---

## Requisitos

- Apache >= 2.4.62
- PHP >= 8.3.16
- MySQL >= 8.4.3
- Servidor Apache con mod_rewrite habilitado
- Navegador moderno (Chrome, Firefox, Edge)
- Composer version 2.8.4

---

## Arquitectura

El proyecto está construido con un patrón **MVC + Repository** propio en PHP vanilla, 
con un router personalizado y autoload para mantener la estructura modular y escalable.

---

## Estructura del proyecto

```
anime-shop/
├── app/
│   ├── config/
│   ├── controllers/
│   ├── core/
│   ├── helpers/
│   ├── libs/
│   ├── middleware/
│   ├── models/
│   ├── services/
│   ├── view/
├── public/
│   ├── .htaccess               # Reescritura de URLs para routing amigable
│   ├── index.php               # Punto de entrada a la aplicación
│   ├── assets/
│   │   ├── css/
│   │   └── images/
│   │   ├── js/
├── storage/
├── vendor/
└── README.md                   # Documentación del proyecto
```

---

## Funcionalidades

- [x] Registro de usuario asignandole rol (admin o customer)
- [x] Login de usuario
- [x] Redireccion de login de usuario segun su rol (admin al panel administrativo y customer al home)
- [x] Catalogo de productos
- [x] Badges de oferta y preventa
- [x] Filtros de productos
- [x] Busqueda de productos
- [x] Paginacion de productos
- [x] Lista de deseos (Wishlist)
- [x] Agregar producto a lista de deseos (Wishlist) desde detalle del producto
- [x] Eliminar producto a lista de deseos (Wishlist) desde detalle del producto
- [x] Agregar producto a lista de deseos (Wishlist) como invitado (sin inciiar sesion) desde detalle del producto
- [x] Migrar lista de deseos (Wishlist) como invitado (sin inciiar sesion) y hacerla persistente con base de datos
- [x] Detalle del producto
- [x] Productos relacionados
- [x] Carrito de comprar (implementado con sesiones)
- [x] Agregar producto al carrito
- [x] Eliminar producto al carrito
- [x] Actualizar cantidad de producto en el carrito
- [x] Contador de producto del carrito con icono que lleva a la vista del carrito
- [x] Contador de producto de la lista de deseos con icono que lleva a la vista de la lista de deseos (Wishlist)
- [x] Suscripcion a newsletter
- [x] Realizar compras de productos
- [x] Agregar direccion de envio en el proceso de compra
- [x] Preview de productos que se compraran en el proceso de compras
- [x] Fronted de implementacion de pasarela de pago de MP (Mercado Pago)
- [x] Webhook para recibir proceso de compra desde el fronted de MP (Mercado Pago)
- [x] Actualizacion de stock si la compra fue exitosa
- [x] Guardado de informacion que devuelve la pasarela de pago de MP (Mercado Pago) en Base de Datos
- [x] Pantallas del fronted paara estos estatus de compra; 'pending','paid','cancelled'
- [x] Mostrar productos comprados si el pago procesado por la pasarela de pago MP (Mercado Pago) fue exitosa
- [x] Panel administrativo
- [x] Panel administrativo; listar productos dando opcion de eliminar o actualizar informacion de cada producto
- [x] Panel administrativo; agregar producto
- [x] Panel administrativo; listar ordenes
- [x] Panel administrativo; listar usuarios incluyendo al que este logueado
- [x] Panel administrativo; listar newsletter/suscripciones
- [x] Panel administrativo; configuracion con opciones de modificar nombre del sitio, email de contacto, timezone, currency, logo
- [x] Panel administrativo; pantalla de inicio donde se muestra total de productos, ordenes, suscriptores, en espera y productos recientes
- [x] Layaout base donde se incrusta el contenido principal de cada pagina
- [x] Componente footer
- [x] Componente header

---

## Roadmap

- [x] Casos de prueba completos
- [x] Ajustes de responsive en vistas pendientes
- [x] Refactorizar código donde sea necesario
- [x] Mejoras de UX/UI
- [x] Nuevas features útiles detectadas durante pruebas

---

## Instalación y uso

1. Clona el repositorio:

```bash
git clone https://github.com/devSecD/anime-shop.git
cd anime-shop
```

2. Configura tu servidor Apache y base de datos MySQL.
3. Importa la base de datos desde el archivo SQL proporcionado.
3. Instala composer e instala dependencias del proyecto
```bash
composer install
```

   > 💡 **Notas:**  
   > - Para checar la versión de Composer:  
   >   ```bash
   >   composer -V
   >   ```  
   > - La única dependencia del proyecto es la pasarela de pago:  
   >   `mercadopago/dx-php 3.5.1` (Mercado Pago PHP SDK)

5. Accede a la aplicación en tu navegador: http://localhost/anime-shop/public

## Base de datos
- Documentación completa de tablas, campos y relaciones disponible en [anime_shop_schema.sql](./docs/database/anime_shop_schema.sql).
- Diagrama E-R de la base de datos disponible [dbdiagram.io](https://dbdiagram.io/d/68d47aeed2b621e422cad616).

## Features versión 2 (pendientes)

Documentación completa de las features de la versión 2 disponible [aquí](./docs/FEATURES_V2/bd_anime_shop_v2_features.md).

<!--
## 📸 Capturas

- (Agrega imágenes o GIFs de tu aplicación funcionando: catálogo, carrito, panel admin, etc.)
-->

## Contribuciones

Las contribuciones, issues y solicitudes de mejora son bienvenidas.  
Siéntete libre de abrir un PR para nuevas ideas o correcciones.

## Autor

Creado por [Dev Sec D](https://github.com/devSecD)

## Licencia

Este proyecto esta bajo licencia MIT.