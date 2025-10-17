# Generación y Expiración de Tokens en Anime Shop

Este documento explica el uso de funciones nativas de PHP para la
**generación segura de tokens** y la **definición de expiración**.

------------------------------------------------------------------------

## 🔐 Generación del token

``` php
$token = bin2hex(random_bytes(32));
```

### ➤ `random_bytes(32)`

-   Genera una **secuencia de bytes aleatorios** (críptograficamente
    seguros).\
-   El número `32` indica **cuántos bytes** quieres.\
-   32 bytes = **256 bits** de entropía.

### ➤ `bin2hex(...)`

-   Convierte los bytes binarios (que pueden ser caracteres no
    imprimibles) en una cadena **hexadecimal legible**.\
-   El resultado tiene **64 caracteres hexadecimales** (`2 * 32 = 64`).

### ⚡ Ejemplo de token generado

    f7a9b7e4c0a14de5c9b84a7fb1e44c97a1f2c4f14ef7d1c2a9c8a0b9e2f6a4b1

### 🔑 ¿Por qué hacerlo así?

-   Seguridad criptográfica garantizada.\
-   Compatible para guardar en DB y enviar en URLs.\
-   Ideal para:
    -   Resets de contraseña.\
    -   Confirmación de email.\
    -   Tokens de sesión temporales.

------------------------------------------------------------------------

## ⏳ Expiración del token

``` php
$expiresAt = (new \DateTime())->add(new \DateInterval('PT1H'))->format('Y-m-d H:i:s');
```

### ➤ Explicación paso a paso

-   `(new \DateTime())` → fecha y hora actual.\
-   `add(new \DateInterval('PT1H'))` → suma 1 hora.\
-   `format('Y-m-d H:i:s')` → lo convierte a string estilo SQL
    (`2025-10-01 16:15:00`).

### 📌 `'PT1H'` explicado

-   **P** = Period (obligatorio).\
-   **T** = Time (marca que sigue un intervalo de tiempo).\
-   **1H** = 1 hora.

### Ejemplos útiles de intervalos

  Intervalo   Significado
  ----------- ---------------------
  `PT30M`     30 minutos
  `PT1H`      1 hora
  `PT2H`      2 horas
  `P1D`       1 día
  `P7D`       7 días (una semana)
  `P1M`       1 mes
  `P1Y`       1 año
  `P1DT12H`   1 día y 12 horas

------------------------------------------------------------------------

## ✅ Resumen

-   `bin2hex(random_bytes(32))` → Token seguro de 64 caracteres
    hexadecimales.\
-   `'PT1H'` → Intervalo de 1 hora para definir la expiración del token.