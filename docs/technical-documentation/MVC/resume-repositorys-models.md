# 📌 Resumen técnico: Repository + Model en controladores

## 🔹 Ejemplo 1

``` php
$this->newsletterRepo = new NewsletterRepository($db);
// El repositorio recibe la conexión y él mismo crea al modelo
```

-   **Patrón aplicado:** Inyección de dependencia directa con
    acoplamiento fuerte.\
-   **Dependencia:** El `Repository` depende de la conexión (`$db`) y
    conoce la construcción del `Model`.\
-   **Ventajas:**
    -   Menos código en el controlador.\
    -   Rápido de implementar (útil en un MVP).\
-   **Desventajas:**
    -   Menor flexibilidad.\
    -   El repositorio queda atado a detalles de infraestructura (DB).\
    -   Viola parcialmente el **Principio de Inversión de Dependencias
        (DIP)**.

------------------------------------------------------------------------

## 🔹 Ejemplo 2

``` php
$model = new OrderModel($this->db);
$this->repo = new OrderRepository($model);
// El controlador crea el modelo y lo pasa al repositorio
```

-   **Patrón aplicado:** Inyección de dependencias bien implementada.\
-   **Dependencia:** El `Repository` solo depende de la abstracción del
    `Model`, no de la DB directamente.\
-   **Ventajas:**
    -   Menor acoplamiento.\
    -   Respetuoso con el **DIP**.\
    -   Más flexible (puedes sustituir el modelo por otro en pruebas o
        cambios de infraestructura).\
-   **Desventajas:**
    -   Un poco más de código en el controlador.\
    -   Ligera sobrecarga en un MVP.

------------------------------------------------------------------------

## ⚖️ Comparación general

  ------------------------------------------------------------------------
  Aspecto             Ejemplo 1 (Repo crea el Ejemplo 2 (Controller crea
                      Model)                  el Model)
  ------------------- ----------------------- ----------------------------
  **Acoplamiento**    Alto                    Bajo (más desacoplado)

  **DIP**             Parcialmente violado    Respetado

  **Flexibilidad**    Baja                    Alta

  **Código en         Menos                   Más
  controlador**                               

  **Escalabilidad**   Menos recomendable a    Más recomendable a futuro
                      futuro                  
  ------------------------------------------------------------------------

------------------------------------------------------------------------

👉 En palabras simples:\
- **Ejemplo 1** = rápido y sencillo, pero más acoplado.\
- **Ejemplo 2** = más limpio y correcto, aunque con un poco más de
código.