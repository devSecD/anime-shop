## Comportamiento de sesiones con Mercado Pago en local

- **Problema**: Al usar `ngrok` como túnel, Mercado Pago redirige a una URL distinta de `localhost`.
- **Efecto**: La sesión PHP no se comparte entre `localhost` y la URL pública de ngrok → el carrito no se borra al finalizar el checkout.
- **Solución temporal**: Aceptar este comportamiento en local.
- **En producción**: No habrá problema, ya que todas las redirecciones apuntarán al mismo dominio.
