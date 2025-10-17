- Para carpetas de subida de archivos por buena practica y seguridad adr permisos 0755

# 📌 Resumen: Crear directorios con permisos `0755` en PHP

## 1️⃣ Concepto general
- En Linux/Unix cada archivo o directorio tiene **permisos** que controlan quién puede **leer, escribir o ejecutar**.  
- Los permisos se representan numéricamente, y `0755` es uno de los más comunes para directorios que deben ser accesibles pero seguros.

### Desglose de `0755`:
| Dígito | Usuario        | Permisos         | Explicación |
|--------|----------------|----------------|------------|
| 7      | Propietario    | rwx            | Leer, escribir y entrar al directorio |
| 5      | Grupo          | r-x            | Leer y entrar, **sin escribir** |
| 5      | Otros          | r-x            | Leer y entrar, **sin escribir** |

> Nota: `x` para directorios significa poder **entrar con `cd`** y acceder a los contenidos.  

## 2️⃣ Uso en PHP
Cuando quieres **crear un directorio solo si no existe**, se usa la función `mkdir()` junto con `is_dir()`:

```php
$uploadDir = __DIR__ . '/uploads';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // crea el directorio con permisos 0755
}
```

### Explicación:
1. `is_dir($uploadDir)` → comprueba si el directorio ya existe.  
2. `mkdir($uploadDir, 0755, true)` → crea el directorio con:
   - `0755` → permisos seguros y estándar.
   - `true` → crea subdirectorios intermedios si no existen.

## 3️⃣ Por qué `0755` es adecuado
- Permite que **el propietario (tu app) pueda escribir** dentro del directorio.  
- Permite que **otros usuarios lean y entren**, pero **no modifiquen** los archivos.  
- Evita problemas de seguridad que habría con `0777` (cualquiera puede escribir).

## 4️⃣ Alternativas según necesidad
| Caso | Permiso recomendado | Comentario |
|------|------------------|------------|
| Directorio público accesible desde la web | 0755 | Propietario puede escribir, otros solo leer/entrar |
| Directorio privado / confidencial | 0700 | Solo el propietario puede leer, escribir o entrar |
| Directorio temporal compartido (`/tmp`) | 1777 | Sticky bit para seguridad en escritura compartida |

💡 **Resumen práctico:**  
Tu código actual:

```php
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
```

✅ Es correcto y seguro para crear directorios en un proyecto PHP donde solo la app necesita escribir y otros usuarios solo leer/entrar.

# 📌 Validaciones de subida de archivos e imágenes en PHP (según OWASP)

## 1️⃣ Archivo requerido

**Qué es:**  
- Asegurarte de que el usuario haya enviado un archivo en el formulario (`<input type="file">`).  
- No se acepta un campo vacío.

**Por qué es importante (seguridad / funcionalidad):**  
- Evita que el backend procese un valor `null` o vacío, lo cual puede causar errores.  
- Evita ataques donde se explote lógica de tu aplicación enviando peticiones vacías o manipuladas.

**Implementación típica en PHP:**
```php
if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
    throw new Exception('Archivo requerido');
}
```

**Estándar:**  
- OWASP recomienda siempre validar que haya un archivo antes de procesarlo para evitar fallos en el flujo.

---

## 2️⃣ Tamaño máximo

**Qué es:**  
- Limitar el tamaño del archivo que se puede subir.  
- Se recomienda un máximo de **5 MB** (`5 * 1024 * 1024 = 5242880 bytes`).

**Por qué es importante:**  
- Evita ataques de **denegación de servicio (DoS)** cargando archivos gigantes.  
- Protege la memoria y almacenamiento del servidor.  
- Protege la aplicación de subidas masivas de archivos.

**Implementación típica:**
```php
$maxSize = 5 * 1024 * 1024; // 5 MB
if ($_FILES['file']['size'] > $maxSize) {
    throw new Exception('Archivo demasiado grande');
}
```

**Estándar:**  
- OWASP: limitar tamaño y número de archivos, definir un máximo razonable según la aplicación.

---

## 3️⃣ Extensión segura

**Qué es:**  
- Comprobar que el archivo tiene una **extensión permitida**, por ejemplo `.jpg`, `.png`, `.pdf`.  

**Por qué es importante:**  
- Evita la subida de archivos ejecutables peligrosos (`.php`, `.js`) que puedan ejecutarse en el servidor.  
- Proporciona un **primer filtro** contra malware o scripts maliciosos.

**Implementación típica:**
```php
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExtensions)) {
    throw new Exception('Extensión no permitida');
}
```

**Estándar:**  
- OWASP recomienda **lista blanca de extensiones**, nunca lista negra, porque la lista negra siempre puede ser evadida.

---

## 4️⃣ Tipo MIME real

**Qué es:**  
- Validar el **tipo MIME del archivo real**, no solo la extensión.  
- Por ejemplo, un `.jpg` podría ser en realidad un `.php` renombrado; esto lo detecta el MIME.

**Por qué es importante:**  
- Evita que archivos peligrosos se suban con extensiones falsas.  
- Mitiga ataques donde el archivo se ejecuta en el servidor.

**Implementación típica:**
```php
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($_FILES['file']['tmp_name']);
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($mimeType, $allowedMimes)) {
    throw new Exception('Tipo MIME no permitido');
}
```

**Estándar:**  
- OWASP: validar **tipo real con `finfo`** y no depender solo de la extensión.

---

## 5️⃣ Validación de que realmente es una imagen (dimensiones válidas)

**Qué es:**  
- Confirmar que el archivo es realmente una **imagen** y opcionalmente que cumpla ciertas **dimensiones mínimas/máximas**.

**Por qué es importante:**  
- Evita subir archivos que **no sean imágenes** aunque pasen la extensión o MIME.  
- Previene ataques donde un archivo malicioso se disfraza de imagen.

**Implementación típica:**
```php
$imageInfo = getimagesize($_FILES['file']['tmp_name']);
if ($imageInfo === false) {
    throw new Exception('Archivo no es una imagen válida');
}

// Opcional: validar dimensiones
$maxWidth = 2000;
$maxHeight = 2000;
if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
    throw new Exception('Dimensiones de la imagen demasiado grandes');
}
```

**Estándar:**  
- OWASP: siempre validar que la imagen sea procesable por la aplicación, y aplicar límites de dimensiones para evitar consumo excesivo de memoria (ataques **zip bombs** o imágenes gigantes).

---

## ✅ Buenas prácticas generales

- Nunca confiar solo en la extensión: siempre validar MIME y contenido real.  
- Usar lista blanca de extensiones y tipos MIME.  
- Limitar tamaño y dimensiones.  
- Crear un directorio seguro para subir archivos (`0755`) y no permitir ejecución allí.  
- Renombrar los archivos subidos para evitar colisiones o ejecución por nombre original.  
- Validar antes de mover el archivo a su destino final (`move_uploaded_file()`).

# 📌 Por qué se usan las extensiones de archivo en minúsculas en PHP

Cuando validas o procesas archivos subidos, es común convertir la extensión a minúsculas usando `strtolower`. Esto no es un estándar oficial, pero es una **buena práctica de seguridad y consistencia**.  

## 1️⃣ Consistencia

- Los sistemas de archivos pueden ser **sensibles a mayúsculas/minúsculas** según el OS:  
  - Linux: `archivo.JPG` ≠ `archivo.jpg`  
  - Windows/macOS: generalmente insensible
- Convertir la extensión a minúsculas permite comparar fácilmente con la **lista blanca** de extensiones.  

**Ejemplo en PHP:**
```php
$ext = pathinfo($filename, PATHINFO_EXTENSION); // "JPG"
$ext = strtolower($ext);                        // "jpg"
if (!in_array($ext, ['jpg','png','gif'])) {
    throw new Exception('Extensión no permitida');
}
```

## 2️⃣ Seguridad

- Un atacante podría intentar evadir la validación usando **mayúsculas o combinaciones raras**:  
  - `shell.PHP` o `image.JpG`
- Normalizar a minúsculas asegura que **no pasen extensiones no permitidas**, reforzando la seguridad.

## 3️⃣ Buena práctica / estandarización en código

- No hay un estándar que obligue a usar minúsculas, pero la mayoría de frameworks y tutoriales lo hacen.  
- Beneficios:  
  - Comparaciones correctas y consistentes  
  - Evita errores en condicionales  
  - Mantiene consistencia en almacenamiento de archivos

## 🔹 Resumen práctico

| Razón       | Beneficio |
|------------|-----------|
| Consistencia | Evita problemas por sensibilidad de mayúsculas/minúsculas en OS |
| Seguridad    | Bloquea intentos de evadir validación usando mayúsculas |
| Buena práctica | Facilita comparaciones y mantenimiento del código |

💡 **Conclusión:**  
Convertir extensiones a minúsculas es una **práctica de seguridad y buena práctica de programación**, aunque no sea un requisito oficial.
