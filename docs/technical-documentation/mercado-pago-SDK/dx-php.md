# Documentación de `dx-php` en Anime Shop

## Contexto del proyecto

**Anime Shop** es una tienda en línea con temática **Otaku**, que vende productos relacionados con anime, manga y cultura japonesa. Para procesar pagos de manera segura y confiable, se integró **dx-php**.

## Instalación de `dx-php`

Se instaló mediante Composer, el manejador de dependencias de PHP, con la siguiente línea en el `composer.json`:

```json
{
  "require": {
    "mercadopago/dx-php": "^3.5"
  }
}
```

### Explicación:

- `"mercadopago/dx-php"`: es la librería oficial de Mercado Pago para PHP.
- `"^3.5"`: indica que se instalará la versión 3.5 o cualquier versión compatible superior que no rompa la compatibilidad (ej. 3.5.x, 3.6, etc.).
- Composer genera automáticamente un autoload (`vendor/autoload.php`) que permite usar dx-php en cualquier archivo de tu proyecto.

## Ventajas de usar dx-php

1. Integración directa con la API de Mercado Pago.
2. Manejo seguro de pagos sin exponer datos sensibles.
3. Documentación y soporte oficial.
4. Compatible con PHP 7.4+ y versiones modernas.
5. Facilita la implementación de múltiples métodos de pago (tarjeta, Pix, transferencia, etc.).

## Recomendaciones

- Mantener tu `Access Token` en variables de entorno o archivos de configuración fuera del repositorio.
- Verificar siempre el estado del pago antes de confirmar pedidos.
- Revisar la documentación oficial para funcionalidades avanzadas como suscripciones, webhooks y manejo de errores.

## Recursos adicionales

- [Documentación oficial de Mercado Pago](https://www.mercadopago.com.ar/developers/es/guides)
- [Repositorio de dx-php en GitHub](https://github.com/mercadopago/dx-php)
- [Composer](https://getcomposer.org/)

Esta documentación sirve como guía inicial para integrar y usar **dx-php** en **Anime Shop** de manera clara y mantenible.