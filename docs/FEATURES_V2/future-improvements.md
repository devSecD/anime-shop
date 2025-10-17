- Agregar en todo el codigo, metodos, funciones, etc el secure logger donde sea necesario y el bloque try catch. Generalmente en catch se escribe en el secure logger el mensaje que devuelva el erro con toda la informacion encesaria del contexto del error.
- Meter paginacion en panel de administracion a los controladores de listado que faltaron
- En algunos controladores falto definir un constructor para pdoer setear valores que se usan en toda la clase o en su mayoria
- Para el catologo de los productos falto incluir el filtro de categorias
- Para los paginados en general debemos setearle el valor a partir de lo que el super administrador o administrador pongan en la configuracion
- Para el home debo de reescribir la aprte donde cargo la vista ya que lo hago copn el metodo render del controlador y lo mejor ea hacerlo como lo hago con los demas controladores al cargar una vista
- Agregar a todos los controladores el helper response con el metodo jsonResponse
- Agregar honeypot en todos los formularios publicos
- Agregar reCAPTCHA: solo en los críticos o donde realmente se detecte spam/abuso
- Enviar correo al enviar datos en el formulario de contacto
- Implementar el metodo "requireGetParams" del helper "RequestHelper" para los siguientes casos
    1. Más de un parámetro obligatorio → sí, siempre requireGetParams.
    2. Uno solo → no es obligatorio, pero recomendable si quieres mantener consistencia en todo tu MVC.
- Para el el "ResultController" dentro del metodo "renderByStatus" agregar lo siguiente
    1. Un codigo de estado 404 (recurso no encontrado)
    2. Un logger
    3. Un response json usando el helper response con el metodo jsonResponse
- Para verificacion de tokens ya sea por experiacion, no perteneciente al usuario, no coincide con el token hasehado en bd:
    - Frontend JS: puedes devolver JSON con status 404 o 400 y mensaje genérico.
    - Backend: 404 HTTP para token inválido, expirado o ya usado.
    * Nunca revelar si el token era válido ni mostrar detalles del usuario.
        * 💡 Lo importante es no dar pistas: ni si el token era válido, ni si un email está registrado, ni si la cuenta existe.
- Para los repositoriso y modelos se deben pasar a inyeccion por dependencia (a como lo hago en algunos repositorios)
- Agregar a todos los modelos el metodo 'prepare' de PDO sin importar si se necesitan o no preparar la consultas. Esto garantiza consistencia en la capa de acceso a datos y protege de posibles inyecciones SQL.
- Siempre asignar a una variable la cadena de la consulta SQL en los metodo de los mdeolos
- Implementar en todos los metodos de los mdelos un 'try-catch' usando dentro del 'catch' el helper de logger crenado un archivo especifico ya se por modelo o entidad, etc y datos que ayuden a mapear la excepcion capturada. Recuerda usar 'use PDOExepction'
- Para todos los modelos que tienen el 'use PDO' en la clase deben de quitarse los '\PDO' y dejarlo como 'PDO'
- Aunque la clase 'Cart' esta dentro de la carpeta de los modelos solo se implementan sesion propia del carrito. Se debe bd para mejorar el proyecto (aun no tenemos una tabla en bd para el carrito se necesita crear con sus respectivos campos)
- 
    # Mejora del método `subscribe` en NewsletterModel

    ```php
    use App\Helpers\SecureLogger;

    /**
     * Inserta una nueva suscripción al newsletter
     */
    public function subscribe($email)
    {
        $logger = new SecureLogger('newsletter.log');

        try {
            $stmt = $this->db->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (:email)");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Si ya existe el email (clave primaria) o violación de integridad
            if ($e->getCode() == 23000 && $e->errorInfo[1] == 1062) {
                // Loguear el intento duplicado de forma segura
                $logger->write('Intento de suscripción duplicada', ['email' => $email]);
                return false;
            }
            // Para otros errores, lanzar excepción
            $logger->write('Error desconocido al guardar suscripción', ['email' => $email, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
    ```

    ### 🔹 Mejoras aplicadas

    1. Se valida específicamente el `errno 1062` para detectar **duplicados de clave primaria**.
    2. Se integra el `SecureLogger` para registrar intentos duplicados de manera segura.
    3. Otros errores distintos al duplicado se registran y se re-lanzan, manteniendo el flujo seguro.
    4. El log se almacena fuera de `public` y con permisos seguros, siguiendo buenas prácticas de seguridad.

- Especificar tipo de retorno para todos los metodos de las clases de los repositorios
- Especificar tipo de retorno para todos los metodos de las clases de los modelos
- Para el modelo de ordenes se creara un metodo llamado "getOrderWithItems()" para que internamente llame a los metodos "getOrderById" y "getOrderItems". Esto con el objetivo de no romper los metodos del repositorio de ordenes que son; "getOrderWithItems" y "exists"

// Modelo
public function getOrderWithItems(int $orderId): ?array
{
    $order = $this->getOrderById($orderId);
    if (!$order) return null;

    $order['items'] = $this->getOrderItems($orderId);
    return $order;
}

- Modificar el metodo 

    /**
     * Ejecuta un query con parámetros y devuelve el resultado como array asociativo.
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function executeQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $this->getPDOType($value)); // no va ser $this
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    - Los metodos getPDOType y handleUnknownType dentro de la ruta app\core y nombre de archivo Model o cambiarlo a BaseModel

        namespace App\Models;

        use App\Helpers\SecureLogger;
        use PDO;
        use PDOStatement;

        class BaseModel
        {
            protected PDO $db;

            public function __construct(PDO $db) {
                $this->db = $db;
            }
            // --------------------------------------
            // Helpers protegidos para uso en herencia
            // --------------------------------------

            /**
            * Devuelve el tipo PDO adecuado para bindValue según el valor.
            *
            * @param mixed $value
            * @return int
            */
            protected function getPDOType($value): int
            {
                return match (true) {
                    is_int($value)   => PDO::PARAM_INT,
                    is_string($value) => PDO::PARAM_STR,
                    is_bool($value)  => PDO::PARAM_BOOL,
                    is_null($value)  => PDO::PARAM_NULL,
                    default => $this->handleUnknownType($value),
                };
            }

            /**
            * Maneja tipos desconocidos para bindValue, registrando un log seguro.
            *
            * @param mixed $value
            * @return int Devuelve PDO::PARAM_STR como tipo por defecto
            */
            protected function handleUnknownType($value): int
            {
                $logger = new SecureLogger('pdo_errors.log');
                $logger->write('Tipo de parámetro desconocido en bindValue', [
                    'value' => $value,
                    'type' => gettype($value)
                ]);

                return PDO::PARAM_STR;
            }
        }

- Quitar el comodin "*" y mejor listar explicitamente los campos en el caso de los modelos.
- Todos los mdelos que interactuan con base de datos:
    1. NombreArchivoModel.php
    2. Y el nombre de la clase igual al nombre del archivo
- Para las validaciones de logica de negocio como por ejemplo: if ($qty <= 0 || $qty > $stock) return false;
    Se creara un clase especifica por entidad:

namespace App\Domain\Product;

class ProductRules
{
    public static function isValidQuantity(int $qty, int $stock): bool
    {
        return $qty > 0 && $qty <= $stock;
    }

    // Aquí podrían ir más reglas del dominio producto
}

Y asi se deberia implementar para el metodo "isValidQuantity"

use App\Domain\Product\ProductRules;

if (!ProductRules::isValidQuantity($qty, $stock)) return false;

Tambien debemos meter esta logica de negocio:

        $discounted = isset($data['price_discounted']) && $data['price_discounted'] !== ''
            ? (float)$data['price_discounted'] 
            : null;

        $regular = isset($data['price']) && $data['price'] !== ''
            ? (float)$data['price'] 
            : null;

- Para el paginado en 'items_per_page' se debe extraer este dato desde la tabla de settings para todas las partes en donde se use paginado de algun listado
- Usar la funcion nativa de PHP hash_equals para el metodo de verificacion de token
- Para el metodo "getCount" del repositorio de la wishlist modificar el cuerpo del metodo y llamar a este metodo desde el modelo de la wishlist que se tiene que crear en el mismo (modelo).

// En WishlistModel
public function countByUser(int $userId): int
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

// Y en el repositorio:
public function getCount(int $userId): int
{
    return $this->model->countByUser($userId);
}

- Para los servicios debemos crear un validador dedicado a ello. Esto lo dejare pendiente para otra version posterior a la 2
- Colocar el archivo fomr.css adentro de la carpeta components
- Agregar archivo reset.css a las paginas del panel administrativo
- Para la version organizar bien los estilos CSS de cada archivo CSS.
    1. Organizar siguiendo el flujo del HTML
    2. Las media queries ordenadas por ancho en pixeles de menor a mayor (mobile a desktop) con comentarios claros y consistentes
    3. Unificar media queries que tengan la misma condicion
    4. Quitar las variables CSS que no existen
- Para una version posterior a la 2 reorganizaremos el CSS aplicando lo siguiente:
    1. Migrar a BEM para el nombrado.
    2. Usar ITCSS para la estructura de carpetas.
- Para las variables que se usan en otros archivos CSS y que no existan en el archivo variables.css se agregaran

        # 🧭 Guía de Migración: De CSS Tradicional a BEM + ITCSS

        ## 🎯 Objetivo

        Esta guía te servirá como hoja de ruta para migrar el CSS clásico de tu
        proyecto **Anime Shop** hacia una arquitectura **BEM + ITCSS** en una 
        **versión posterior a la 2**, priorizando escalabilidad, orden y mantenibilidad.

        ------------------------------------------------------------------------

        ## 🧱 1. Situación actual (CSS Tradicional)

        Actualmente tu CSS está organizado de manera funcional, por archivos
        temáticos como:

            /css/
            │── variables.css
            │── header.css
            │── catalog.css
            │── footer.css
            │── forms.css

        Esto es perfecto para un MVP: simple, rápido y entendible.

        ------------------------------------------------------------------------

        ## 🧩 2. Qué es BEM

        **BEM (Block, Element, Modifier)** es una convención de nombres para
        clases CSS que facilita el mantenimiento y la lectura del código.

        ------------------------------------------------------------------------------
        Concepto               Ejemplo                     Descripción
        ---------------------- --------------------------- ---------------------------
        **Block**              `.product-card`             Un componente independiente
                                                            (bloque).

        **Element**            `.product-card__title`      Parte interna de un bloque.

        **Modifier**           `.product-card--featured`   Variante o estado del
                                                            bloque.
        ------------------------------------------------------------------------------

        **Ejemplo práctico:**

        ``` html
        <div class="product-card product-card--featured">
        <img class="product-card__image" src="img.jpg" alt="">
        <h3 class="product-card__title">Figura de Goku</h3>
        </div>
        ```

        ------------------------------------------------------------------------

        ## 🗂️ 3. Qué es ITCSS

        **ITCSS (Inverted Triangle CSS)** organiza los archivos CSS según su
        nivel de especificidad.

        -----------------------------------------------------------------------
        Nivel        Tipo de estilos             Ejemplo de archivo
        ------------ --------------------------- ------------------------------
        1️⃣ Settings  Variables globales,         `_settings.css`
                    fuentes, colores            

        2️⃣ Tools     Mixins, funciones           `_tools.css`

        3️⃣ Generic   Reset, normalize            `_generic.css`

        4️⃣ Elements  Estilos HTML (body, h1, p,  `_elements.css`
                    etc.)                       

        5️⃣ Objects   Layouts generales (grid,    `_objects.css`
                    container)                  

        6️⃣           Componentes reutilizables   `_components.css`
        Components   (cards, navbar)             

        7️⃣ Utilities Clases rápidas              `_utilities.css`
                    (`.text-center`, `.mt-20`)  
        -----------------------------------------------------------------------

        **Estructura recomendada:**

            /css/
            │── itcss/
            │   ├── 1-settings/
            │   ├── 2-tools/
            │   ├── 3-generic/
            │   ├── 4-elements/
            │   ├── 5-objects/
            │   ├── 6-components/
            │   └── 7-utilities/
            │── main.css  ← Archivo que importa todo el orden ITCSS

        ------------------------------------------------------------------------

        ## 🔄 4. Estrategia de migración

        1.  **Mantén el diseño actual.**\
            No cambies HTML ni estructura visual todavía.

        2.  **Empieza por variables.**\
            Mueve todos tus colores, fuentes y tamaños a
            `/1-settings/_variables.css`.

        3.  **Normaliza tu base.**\
            Crea `/3-generic/_reset.css` para definir estilos globales
            coherentes.

        4.  **Adapta nombres a BEM.**

            -   Renombra clases como `.card` → `.product-card`\
            -   Subclases como `.card-title` → `.product-card__title`\
            -   Variantes como `.card-highlight` → `.product-card--highlighted`

        5.  **Divide por niveles ITCSS.**\
            Mueve gradualmente los estilos al nivel que corresponde.

        6.  **Importa todo en orden en `main.css`:**

            ``` css
            @import "itcss/1-settings/_variables.css";
            @import "itcss/3-generic/_reset.css";
            @import "itcss/6-components/_product-card.css";
            @import "itcss/7-utilities/_helpers.css";
            ```

        ------------------------------------------------------------------------

        ## 🧠 5. Consejos finales

        -   Usa **nombres consistentes** en inglés o español, pero no
            mezclados.\
        -   Evita anidaciones profundas (`.a .b .c`) → reduce mantenimiento.\
        -   Documenta cada bloque con comentarios breves.\
        -   No olvides mantener tu `variables.css` como fuente única de verdad.\
        -   Usa un **minificador automático** en producción (`postcss`, `vite`,
            etc.).

        ------------------------------------------------------------------------

        ## 🚀 Resultado esperado

        Tras la migración: - Tu CSS será **más modular y reutilizable.** - La
        colaboración con otros devs será más fluida. - Los cambios visuales no
        afectarán otras secciones.

        ------------------------------------------------------------------------

        **Autor:** Emmanuel\
        **Proyecto:** Anime Shop (Versión posterior a la 2 - Planeación técnica)\
        **Metodología recomendada:** BEM + ITCSS\
        **Propósito:** Escalabilidad, consistencia y mantenibilidad visual.


- El archivo public\assets\js\ajax\apiEndpoints.js se dejara aunque no se use para esta primer version 1.0 del MVP del proyecto porque se tiene pensado usarse para una version posterior a la 2.
- Revisar a fondo el archivo public\assets\js\components\payment.js ya que al parecer no se esta usando en el proyecto y por lo que veo en el codigo es para crear una preferencia de Mercado Pago la cual yo ya la tengo implementada y probada desde el backend de PHP
- Del archivo public\assets\js\components\updateConfiguration.js desacoplar en funciones exportables los siguientes fragmentos de codigo dentro del validador js:
    1. 
    const key = input.name.replace(/^settings\[(.+)\]$/, '$1');

    2. 
    const value = input.type === 'checkbox' ? input.checked : input.value.trim();

    3. 
    switch (key) {
        case 'site_name':
            if (!isNotEmpty(value))
                errors.push('El nombre del sitio es obligatorio.');
            break;

        case 'contact_email':
            if (!isValidEmail(value))
                errors.push('El correo de contacto no es válido.');
            break;

        case 'items_per_page':
            if (!mustBePositiveInt(value))
                errors.push('Ítems por página debe ser un número entero positivo.');
            break;

        case 'timezone':
            if (!isValidTimezone(value))
                errors.push('La zona horaria no es válida.');
            break;

        case 'maintenance_mode':
            const boolError = mustBeOptionalBoolean(value, 'modo mantenimiento');
            if (boolError) errors.push(boolError);
            break;

        default:
            if (!isNotEmpty(value))
                errors.push(`El campo "${key}" es obligatorio.`);
    }

- Cambiar el nombre del archivo public\assets\js\components\updateForm.js por uno mas representativo y especifico a su funcionalidad. De sugerencia es cambiarlo por updateProductForm.js
- Renombrar archivos js que sean mas claro y especificos a su funcionalidad
- Poner toda la documentacion del codigo con la convencion adecuada y solamente para HTML no se documentara:
    1. PHPDoc para PHP
    2. JsDoc para Javascript
    3. KSS (Knyle Style Sheets) o DocBlocks estilo CSSDoc para CSS
- Remover la siguiente linea ya que esta repetida en el archivo public\assets\js\util\wishlistText.js
    - icon.classList.toggle("active", isAdded);

- Para el metodo "interpolateNamedQuery" del modelo Product el poner la funcion nativa "addslashes" dentro el valor y alrededor las comillas simples es un riesgo de SQLi (SQL Inyection). Se debe corregir en la segunda version (investigar como hacerlo mas seguro). Y al aprece ese metodo no se usa solo esta para debug o logs pero debe analizarse y estar seguro de que solo se usa para debug o logs

- El Modelo base (app\core\Model.php) no se esta usando porque no tiene nada de codigo. Este se dejara de momento si mas adelante se llega a implementar se le pondra el codigo correspondiente

- Implementar en el catalogo de productos el paginador app\core\Paginator.php

- La View base (app\core\View.php) no se esta usando porque no tiene nada de codigo. Este se dejara de momento si mas adelante se llega a implementar se le pondra el codigo correspondiente

- El helper de autenticacion (app\helpers\AuthHelper.php) no se esta usando porque no tiene nada de codigo. Este se dejara de momento si mas adelante se llega a implementar se le pondra el codigo correspondiente

- Para el metodo "upload" del servcicio UploadService (para subir archivos independientemente del tipo de archivo) se dede mejorar con lo siguiente:
    1. Usar las validaciones del helper especificamente el metodo "validateRequiredFile"
    2. Detecte tipo de archivo
    3. Usar un match por extensión para generar prefijos: img_, doc_, vid_, file_ en vez de usar un harcodeado como en el codigo 'img_'
    3. Valide extensiones permitidas
    4. Definir los permisos 0644 para subia de archivos
    5. Añadir logs y control de errores más detallado
    6. Bloquear ejecución de PHP en la carpeta de uploads

- De la vista app\view\admin\components\product_from.php desacoplar este codigo javascript que sirve para mostrar el preview de la imagen del producto

    <script>
    document.getElementById('image').addEventListener('change', function (event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
        }
    });
    </script>

- De la vista app\view\admin\components\sidebar.php desacoplar este codigo javascript

    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
        });

        // Opcional: cerrar al hacer clic fuera del sidebar en móviles
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                sidebar.classList.add('sidebar-hidden');
            }
        });
    </script>

- Para todas las vista en el caso de fechas usar el helper date:
    - Ejemplo de implementacion

        <?= \App\helpers\DateHelper::formatShort($subscriber['subscribed_at']) ?>

- Para la vista app\view\admin\index.php crear la ruta y todo el backend del sold count
    1. /anime-shop/public/admin/products/sold_count
    2. title="Ver ventas totales"
    3. Crear controlador, metodo repo, metodo modeelo en products

- En los href de las vistas donde se tenga "mailto:soporte@anime-shop.com" se debe poner el correo ya oficial y en produccion/desarrollo del hosting del servidor de correo es decir que si exista:
    
- De la vista app\view\product\detail.php verificar si el Javscript que esta en el  se esta usando y si es asi se debe desacoplar en un Js existente o en uno nuevo o si no en el mains js. De caso contrario se pasara a eliminar.

- Para una version futura implementar el msotrar el medio de pago que se utilizo para la compra de los productos en una ordeen. Este dato se debera obtener desde el servicio de Mercado Pago (libreria SDK)

- Todas las urls de los Javascripts deben empezar con '/anime-shop/public/'

- De la vista app\view\user\reset_password_form.php verificar y confirmar para estar seguro de eliminar las primeras lineas que corresponden al siguiente codigo:

    <?php if (!empty($errors)): ?>
        <p style="color: red;"><?= htmlspecialchars($errors); ?></p>
    <?php endif; ?>

- Del Javascript public\assets\js\ajax\apiEndpoints.js no se esta usando para la version 1.0 de Anime Shop y se piensa usar e implementar en versiones futuras.

- Actualmente las rutas de todos los imports de los Javascript´s son relativas y se pretende que sean absolutas en una futura vesion del proyecto. Se tiene esta propuesta para pdoer hacerlo con js vanilla y PHP vanilla ya que el proyecto siempre usara codigo vanilla:

    - Si quieres máxima seguridad y flexibilidad, puedes definir una constante de base URL en PHP y usarla para generar rutas absolutas en JS:

        <?php
        define('BASE_URL', '/anime-shop/');
        ?>
        <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
        </script>
        <script type="module">
        import { sendForm } from `${BASE_URL}assets/js/sendForm.js`;
        </script>

    - Ventaja:
        - Funciona en cualquier subdirectorio.
        - Permite cambiar la raíz en un solo lugar.
        - Compatible con vanilla PHP + JS.

- Del Javascript public\assets\js\util\validation.js dentro de la funcion "isValidTimezone" se debe agregar la zona horaria de Mexico y al final la constante fallback qeudaria asi:

    const fallback = [
        'UTC',
        'America/New_York',
        'Europe/London',
        'Asia/Tokyo',
        'America/Mexico_City'
    ];