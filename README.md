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