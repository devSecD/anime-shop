- Tabla comparativa entre strlen y mb_strlen

| Función       | Cuenta     | Problema con UTF-8                              |
| ------------- | ---------- | ----------------------------------------------- |
| `strlen()`    | Bytes      | Sí, caracteres especiales cuentan más de 1 byte |
| `mb_strlen()` | Caracteres | No, cuenta correctamente los caracteres Unicode |