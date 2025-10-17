# 📘 Documentación: Soft Delete en Sistemas MVC / Anime Shop

## 🧩 ¿Qué es un Soft Delete?

Un **Soft Delete** es una técnica que permite marcar un registro como
"eliminado" sin borrarlo físicamente de la base de datos.\
En lugar de ejecutar un `DELETE`, se actualiza una columna
(`deleted_at`) con la fecha y hora de la eliminación.

### Ejemplo

``` sql
UPDATE products SET deleted_at = NOW() WHERE id = 10;
```

De esta forma, el registro permanece en la tabla, pero se considera
"eliminado" a nivel lógico.

------------------------------------------------------------------------

## ⚖️ Diferencias entre Soft Delete y Hard Delete

  --------------------------------------------------------------------------
  Tipo       Descripción            Ventajas          Desventajas
  ---------- ---------------------- ----------------- ----------------------
  **Soft     Marca el registro como Recuperable,      Más espacio en tabla,
  Delete**   eliminado              auditable,        requiere filtros
             (`deleted_at`)         mantiene          
                                    historial         

  **Hard     Borra físicamente el   Simplicidad,      Irreversible, rompe
  Delete**   registro               libera espacio    historial
  --------------------------------------------------------------------------

------------------------------------------------------------------------

## 🧱 Estructura de Base de Datos Recomendada

``` sql
ALTER TABLE products
  ADD COLUMN deleted_at DATETIME NULL,
  ADD COLUMN deleted_by INT NULL,
  ADD COLUMN deleted_reason VARCHAR(255) NULL;

CREATE INDEX idx_products_deleted_at (deleted_at);
```

Estas columnas permiten conocer **cuándo**, **quién** y **por qué** se
eliminó el registro.

------------------------------------------------------------------------

## 🧠 Implementación en PHP (Ejemplo estilo Repository)

``` php
public function softDeleteProduct(int $id, ?int $deletedBy = null, ?string $reason = null): bool {
    $sql = "UPDATE products SET deleted_at = NOW(), deleted_by = :deleted_by, deleted_reason = :reason
            WHERE product_id = :id AND deleted_at IS NULL";
}

public function restoreProduct(int $id): bool {
    $sql = "UPDATE products SET deleted_at = NULL, deleted_by = NULL, deleted_reason = NULL
            WHERE product_id = :id AND deleted_at IS NOT NULL";
}

public function forceDeleteProduct(int $id): bool {
    $sql = "DELETE FROM products WHERE product_id = :id";
}
```

------------------------------------------------------------------------

## 📋 Casos de Uso donde se Debe Implementar

1.  **Productos** → Mantener historial de referencias en pedidos
    antiguos.\
2.  **Usuarios** → Permitir desactivación sin perder registros
    asociados.\
3.  **Reviews o Comentarios** → Moderación o restauración de contenido.\
4.  **Auditoría** → Registrar quién eliminó y cuándo.\
5.  **Flujos Reversibles** → Errores humanos o eliminación temporal.

------------------------------------------------------------------------

## 🚫 Casos donde No Conviene Usarlo

1.  **Cumplimiento legal (GDPR)** → Cuando se exige eliminar
    definitivamente datos personales.\
2.  **Datos contables o financieros** → Se manejan con estados
    (`cancelled`, `refunded`).\
3.  **Tablas con altísimo volumen** → Riesgo de pérdida de rendimiento.\
4.  **Datos temporales sin valor histórico** → Ejemplo: items de carrito
    o sesiones.

------------------------------------------------------------------------

## 🧰 Buenas Prácticas

-   Aplicar `WHERE deleted_at IS NULL` en todas las consultas normales.\
-   Usar columnas: `deleted_at`, `deleted_by`, `deleted_reason`.\
-   Indexar `deleted_at` para evitar penalizaciones en consultas.\
-   Implementar cascada manual de Soft Delete (no automática).\
-   Añadir método `restore()` para revertir eliminaciones.\
-   Definir política de retención: eliminar físicamente tras X días.\
-   Incluir pruebas unitarias para restaurar, eliminar y filtrar.\
-   Controlar crecimiento de tabla por acumulación de soft deletes.

------------------------------------------------------------------------

## 🧩 Recomendaciones para el Proyecto *Anime Shop*

  ------------------------------------------------------------------------
  Entidad        Estrategia de Eliminación           Justificación
  -------------- ----------------------------------- ---------------------
  **Products**   Soft Delete                         Mantener histórico de
                                                     productos en órdenes.

  **Orders**     No eliminar                         Mantener integridad
                                                     contable.

  **Payments**   No eliminar                         Registros inmutables.

  **Users**      Soft Delete (desactivación)         Cumplir GDPR con
                                                     opción de borrado
                                                     real.

  **Reviews**    Soft Delete                         Moderación y
                                                     reversión.

  **Cart items** Hard Delete                         No requieren
                                                     historial.
  ------------------------------------------------------------------------

------------------------------------------------------------------------

## 🧩 Alternativas Avanzadas

-   **Tabla de archivo (`archive table`)**: mover filas eliminadas a
    otra tabla.\
-   **Event sourcing**: guardar eventos en lugar de estados.\
-   **Tombstone table**: guardar solo metadatos de eliminación.

------------------------------------------------------------------------

## ✅ Checklist de Decisión Rápida

  ------------------------------------------------------------------------
  Pregunta                   Respuesta                  Acción
  -------------------------- -------------------------- ------------------
  ¿Se necesita recuperar el  Sí                         Soft delete
  registro?                                             

  ¿Rompe relaciones si se    Sí                         Soft delete
  borra?                                                

  ¿Existen requisitos        Sí                         Hard delete o
  legales de eliminación                                anonimizar
  total?                                                

  ¿Tabla con millones de     Sí                         Archivado o
  registros?                                            limpieza periódica
  ------------------------------------------------------------------------

------------------------------------------------------------------------

## 🧾 Conclusión

El **Soft Delete** es una práctica esencial cuando la información tiene
valor histórico o relacional.\
Permite mantener trazabilidad, recuperación y auditoría, a cambio de un
ligero costo en complejidad y espacio.

> En el proyecto *Anime Shop*, se recomienda implementarlo en productos,
> usuarios y reviews, dejando los módulos financieros y temporales con
> hard delete o manejo de estados.