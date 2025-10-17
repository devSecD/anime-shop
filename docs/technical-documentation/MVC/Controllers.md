Controladores

- Se usa un contrlador base este principalmente es para obtener las credenciales de base de datos y poder llevarlas desde el controlador hasta el modelo. Este es reutlizable
- Generalmente para mostrar una vista tengo el metodo index
- A veces sule usarse los metodos extract combinado con compact que son nativos de PHP para poder mostrar variables en la vista


- Se setean variables que toda vista debe tener que son las siguientes:
  - Content que almacena la vista que se requiere mostrar y se muestra en el layaout base con un include
  - title
  - page que es el nombre de la apgina de la vista lo que va en title de html
  - assets que sirven basicamente para incluir css ya sea reutilizable o css propio de la vista
  ... (AUN FALTAN MAS POR AGREGAR)


- Solo para los contrladores de Administradores y Super Administradores estos vienen en la ruta app\controllers\Admin y se le agrega a los metodos el middleware
- Para los casos de las vistas de Administradores y Super Administradores se agregan los siguientes componentes:
  - html_head
    - Viene todo lo que se debe incluir en la etiqueta head de html
  - sidebar
    - Barra lateral en escritorio o menu de hamburguesa en moviles en el cual se ponen la lista de todas las secciones/modulos del panel administrativo
  - Y el componente propio de la vista que se requeira mostrar

- Por buenas practicas se usa un helper de validacion dentro de los controladores. Este es reutlizable
- Por buenas practicas se usa un helper de respuesta de json dentro de los controladores si se requiere. Este es reutlizable
- Para el borrado de elementos en base de datos se usa el metodo POST para poder enviar principalmente el id
- Para los casos en que se requiere eliminar un recurso se usa un componente nombrado como modalConfirmDelete que es basicamente una moda e igualmente el js se reutiliza un componente nombrado modal-delete. Esto generalmente se pone en los listados de recursos.
- Generalmente dentro del constructor se setean o asignan valores a las propiedades/atributos como la conexion a la base de datos e instancias a objeto de los respositorios y/o modelos
- Para la paginacion se reutilizo codigo y se implemento una clase en PHP core con todos los metodos necesarios
- Para los casos en los que los formularios eran estructuralmente iguales como por ejemplo el del registro de producto y actualizacion del producto se usa un helper de formulario para setear valores comunes pero no iguales como la url, nombre de la pagina, texto del boton del formulario, etc.
- Para la creacion de recursos se usan metodos nombrados como process, ...
- Para la actualizacion de recursos se usan metodos nombrados como update, ...
- Para la creacion o actualizacion de recursos se usa una variabla generalmente llamada data que es un arreglo en donde se colocan los valores que vienen por POST
- Para la creacion de recursos se usa una variable generalmente llamada insertData que es un arreglo  en donde se colocan los valores que se insertaran
- Para la actualizacio de recursos se usa una variable generalmente llamada updateData que es un arreglo  en donde se colocan los valores que se actualizaran
- Para los casos en donde se requiere subir un archivo se usa un servicio de upload
- Para cuando se actualiza un recurso y en el caso de que se requiera subir un archivo se borra el archivo anterior y se reemplaza por el nuevo archivo
- Para subir un archivo usamos un helper nombrado como validateImageUpload el cual valida los siguientes aspectos estos cubriendo vulnerabilidad que pueden ser ejecutadas en subida de archivos:
  1. Archivo requerido
  2. Tamaño maximo que por estandar solo permite como maximo 5MB
  3. Extension segura
  4. Tipo MIME real
  5. Validacion de que realmente es una imagen con dimensiones validas
- Para el carrito se usa sesiones para poder obtener(get) o setear(set). Tenemos estois 3 metodos:
  1. Add
  2. Update
  3. Remove
  4. Count
  5. View que es la vista pricipal del carrito
- A partir de la direccion de envio en el checkout se necesita tener o iniciar sesion
- Para el controlador de Payment Controller se uso un servicio para los iguientes propositos:
  1. Crear una preferencia llamadno al metodo de la librearia dx-php de Mercado Pago para pdoer crear una preferencia
  2. Mapear los items del carrito a formato compatible con Mercado Pago
- Exclusimante para el home; al mostrar la vista lo hicimos con ayuda del metodo render el cual esta definido en el contrlador base
- Se agrego de momento solo para el formulario de contacto el honeypot y el reCAPTCHA
- En donde es necesario ocupamos un helper de sesion
- Para el controlador EditController si la actualizacion fue correcta
  - Si se actualizao la contraseña destruimos la sesiony se manda un mensaje diferente a cuando no se actualizo la contraseña
  - Seteamos esos valores actualizados a la sesion del usuario 
- Para el controlador LoginController 
  - La lgoica de la validacion de la contraseña se realiza del lado del repository usuario
  - Se actualiza la sesion del usuario
  - Se migra el wishlist del invitado al usuario que inicia sesion
  - Se setea el total de items de la whislist en la sesion
  - Se hace redireccion diferentes de acuerdo si es un customer o un admin/superadmin
- El controlador PasswordRecoveryController tiene los siguientes metodos:
  1. index; carga la pagina donde esta el formulario para meter el correo en donde se enviare el enlace de recuperacion de contraseña
  2. process; envio del formulario:
    1. Valida correo
    2. Obtiene el usuario por emial y valida que exista el usuario esto para verficar que meten un correo que este en base de datos
    3. Se genera el token criptograficamente seguro
    4. Se genera la fecha de expericion de 1 hora del token
    5. Se guarda en base de datos el token al usuario agregandole la url para resetear la contraseña
    6. Enviamos correo
    7. Enviamos una respuesta
  3. sendRecoveryEmail; se envia el correo con los datos necesarios a traves del helper de correo
- El controlador ResetPasswordController tiene los siguientes metodos:
  1. index
    1. Muesta la vista en donde esta el formulario para meter la nueva contraseña y la confirmacion de la misma
    2. Valida token y id del usuario que viene por url
    3. Valida que el token por url sea el mismo al que esta en bd hasheado. El token de la url se hashea en el repository
    4. Valida que el token sea del usuario y que no este expirado
  2. process
    1. Envio del formulario
    2. Obtiene datos
    3. Valida datos
    4. Verifica token
    5. Hashea contraseña
    6. Actualiza contraseña en bd
    7. elimina token de la bd
- Controlador WebhookController tiene un unico metodo llamado "handle"
  - Instaciamos nuestra clase de servicio "PaymentService"
  - Valido que que "paymentId" tiene un valor válido (ej. 123) distinto de vacío, null o cero
  - A aprtir del servicio "PaymentService" obtenemos la informacion del pago
  - Valido que que "paymentInfo" tiene un valor válido (ej. 123) distinto de vacío, null o cero y que el collector id sea el mismo el del servicio contra el de la configuracion
  - Valido que que "orderId" y "localStatus" tiene un valor válido distinto de vacío, null o cero
  - Si la orden existe actualizamos el estatus ("localStatus")
  - Solo si "localStatus" esta en 'paid' (estatus en pagado) definimos el pago como valido y en caso como no valido
  - Registramos  en bd el log del pago con los siguientes datos:
    1. orderId
    2. eventType
    3. payload
      - payload contiene; payload, payment_valid, status_original (estatus propio de Mercado Pago) y status_local (estatus para el proyecto Anime Shop en bd)
  - Por seguridad siempre respondemos con un 'status' con valor 'ok' y codigo de estado http 200
- El controlador "ManageController" de la wishlist solamente tiene el metodo index y nos centraremos en el nucleo de la logica
  1. Si el usuario esta logueado entonces obtenemos los items/productos de la wishlist del usuario. Seteamos la sesion para poner los id´s de los items/productos y la cantidad total de los itemes/productos de la wishlist con una clave diferente en sesion
  2. Si el usuario no esta logueado seteamos la sesion como array vacio y 0 seteamos con su respectiva clave para los items/productos y cantidad total de los items/productos de la wishlist
  3. Si la 'action' que viene por post es 'add' agregamos o 'remove' eliminamos los items/productos de la wishlist. Si no entra la action en niguno de estos le ponemos que es una 'action' no valida
  4. Actualizo los id´s y cantidad total de los items/productos de la wishlist en sesion con su respectiva clave
  5. Mandamos una respuesta json con la siguiente informacion:
    - 'success' que representa el resulado que devuelve el repositorio como booleano
    - 'count' que es la cantidad total de items/productos de la wishlist actualizado
    - 'message' que es el mensaje que le seteamos cuando el 'action' es 'add' o 'remove'