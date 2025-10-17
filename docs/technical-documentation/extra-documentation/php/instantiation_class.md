# Guía de instanciación de clases en Anime Shop

En el proyecto **Anime Shop** existen dos formas comunes de instanciar clases como modelos, repositorios y helpers. A continuación se documenta la recomendación técnica para mantener consistencia en el código.

---

## 1️⃣ Instanciación con `use` y `new`
```php
use Models\Newsletter\NewsletterRepository;

$this->newsletterRepository = new NewsletterRepository($this->db);
```

### Ventajas
- Código más **limpio y legible**.
- **Mantenible**, si cambia el namespace se modifica solo el `use`.
- Mejor integración con **IDE y autocompletado**.
- Consistencia con **PSR-12**.

### Recomendación
Usar este método cuando la clase se utiliza **varias veces** en el mismo archivo o es parte central del flujo (modelos, repositorios, servicios principales).

---

## 2️⃣ Instanciación con FQN (Fully Qualified Name)
```php
$repo = new \Models\Checkout\ShippingAddressRepository($db);
```

### Ventajas
- Instanciación **directa**, no requiere `use`.
- Útil para **casos puntuales** o cuando la clase se usa **una sola vez**.
- Evita colisiones de nombres entre clases con el mismo nombre.

### Desventajas
- Código menos **legible** al incluir todo el namespace.
- Menos **mantenible**, si cambia el namespace debe actualizarse en todas las llamadas.

---

## 📌 Recomendación final
- **Usar `use` + `new Clase`** para clases que se usen más de una vez o sean parte principal del archivo.  
- **Usar `new \Namespace\Clase`** solo para instancias puntuales o casos únicos.

---

## 📖 Ejemplo combinado
```php
<?php
namespace Controllers\Newsletter;

use Models\Newsletter\NewsletterRepository;

class SubscribeController
{
    private NewsletterRepository $newsletterRepository;

    public function __construct($db)
    {
        // Uso frecuente → con `use`
        $this->newsletterRepository = new NewsletterRepository($db);

        // Uso puntual → con FQN
        $logger = new \Utils\Logger\FileLogger('/logs/app.log');
        $logger->info("Newsletter subscription initialized");
    }
}
```