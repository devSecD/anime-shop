# Diferencia entre `bindValue()` y pasar parámetros directamente en `execute()` (PHP PDO)

## Contexto

En PHP, cuando usamos PDO y preparamos una sentencia SQL con placeholders (`:key`), tenemos varias formas de pasar los valores:

1. Usando `bindValue()` o `bindParam()`.
2. Pasando un arreglo directamente en `execute()`.

Ejemplo:

```php
// Forma 1: bindValue + execute
$stmt->bindValue(':key', $key, PDO::PARAM_STR);
$stmt->execute();

// Forma 2: execute con array
$stmt->execute([':key' => $key]);
```

---

## 1️⃣ `bindValue()`

- Vincula **el valor actual** al placeholder **antes de ejecutar**.
- Permite **especificar el tipo de dato** (`PDO::PARAM_STR`, `PDO::PARAM_INT`, etc.).
- Útil para **reutilizar la misma sentencia** varias veces con diferentes valores.

```php
$stmt = $db->prepare("SELECT value FROM settings WHERE `key` = :key");
$stmt->bindValue(':key', $key, PDO::PARAM_STR);
$stmt->execute();
```

- Si `$key` cambia después de `bindValue()`, el valor enviado será **el original**.

---

## 2️⃣ Pasar parámetros directamente en `execute()`

- Pasa un **arreglo de valores** que PDO reemplaza en los placeholders durante la ejecución.
- PDO **infiera automáticamente** el tipo de datos.
- Menos flexible para reutilización múltiple, pero más compacto.

```php
$stmt = $db->prepare("SELECT value FROM settings WHERE `key` = :key");
$stmt->execute([':key' => $key]);
```

- El valor usado será el que tenga `$key` **al momento de ejecutar**.

---

## 3️⃣ Diferencias clave

| Característica | bindValue | execute con array |
|----------------|-----------|-----------------|
| Momento de asignación | Inmediato (al llamar bindValue) | Durante la ejecución |
| Especificación de tipo | Sí (`PDO::PARAM_STR`, etc.) | Implícita, PDO infiere |
| Reutilización de la sentencia | Sí, se puede llamar execute() varias veces | Menos flexible, hay que pasar array cada vez |
| Código | 2 líneas | Más compacto |
| Seguridad SQL | ✅ | ✅ |

---

## 4️⃣ Cuándo usar

- **bindValue()**: cuando necesites **controlar el tipo de dato** o **ejecutar varias veces** la misma sentencia.
- **execute(array)**: cuando la consulta sea **simple y única**, para un código más limpio.

---

## 5️⃣ Ejemplo práctico de reutilización

```php
$stmt = $db->prepare("SELECT value FROM settings WHERE `key` = :key");

$keys = ['site_name', 'items_per_page', 'currency'];
foreach ($keys as $key) {
    $stmt->bindValue(':key', $key, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $key . ' = ' . $result['value'] . "\n";
}
```

Con `execute(array)`:

```php
foreach ($keys as $key) {
    $stmt->execute([':key' => $key]);
}
```

---

## Resumen rápido

- **Seguridad SQL:** ambas son seguras.
- **bindValue:** control de tipo + reutilización.
- **execute(array):** más compacto para casos simples.
- Elección depende de **contexto y legibilidad**.