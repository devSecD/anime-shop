Javascript

- Carga dinámica de módulos JavaScript por página
  El proyecto utiliza un sistema de carga de scripts específicos por página para optimizar el rendimiento y mantener la modularidad del frontend.
  - El archivo main.js es el punto de entrada global del frontend y determina qué módulo cargar según la página actual.
  - Para ello, obtiene el identificador de página desde el atributo data-page del elemento <main> y llama a la función loadPageModule, pasando ese identificador como parámetro.
  - La función loadPageModule, definida en public/assets/js/core/pageLoader.js, se encarga de importar dinámicamente el módulo correspondiente a la página y ejecutarlo.
  - Cada módulo se encuentra en public/assets/js/components/ y se inicializa solo cuando la página correspondiente está activa. Esto permite cargar solo el JS necesario para cada vista, evitando scripts innecesarios en páginas que no los requieren.
- Para registrar o actualizar un formulario, se realiza el siguiente flujo de acciones:
  - Se obtienen los datos ingresados en el formulario.
  - Se realizan las validaciones correspondientes sobre los datos.
    - Se usa el validador externo public\assets\js\util\validation.js
  - Si alguna validación falla, se muestra un mensaje de error al usuario.
  - Si todas las validaciones se cumplen, se envía la petición al servidor.
    - El objeto 'e' del evento submit se ejecuta el metodo "preventDefault" que previene que se refresque la pagina
    - Para el envio de la peticion hacia el servidor se usa public\assets\js\ajax\sendForm.js
  - Se procesa la respuesta del servidor para reflejar los resultados en la página.
    - Generalmente para mostrar respuestas limpias al usuario se usa public\assets\js\components\alertToast.js
  - Generalmente se usa la funcion "showToast" para mostrar para mostrar una alerta de tipo 'succes' o 'error'

- Archivos componentes Js
  - account.js; solamente manda a llamar la funcion "initNewsletterToggle" que esta en "newsletterToggle.js"
  - newsletterToggle.js; sirve para la logica del toggle en la seccion de la cuenta del usuario
  - alertToast.js; muestra un toast es decir un mensaje emergente temporal y se le formatea el texto que lleva dentro poniendole saltos de linea con la funcion "formatMultilineMessage"
  - burgerMenu.js; sirve para mostrar o ocultar el menu de hamburguesa al momento de darle clic
  - cart.js; realiza los siguientes procesos:
    1. Agregar producto al carrito
      1. Se obtiene id y cantidad del producto
      2. Realiza una peticion al servidor a la ruta del backend
      3. Si la respuesta de la peticion es correcta:
        - Actualiza el contador del carrito ('#mini-cart-count') con la funcion "updateCartCounter"
        - Actualiza el costo total de los productos en el carrito ('.cart-total') con la funcion "updateCartTotal"
        - Se muestra un toast de que todo se hizo correctamente
      4. Si la respuesta de la peticion no fue coorrecta se atrapa el error con la funcion "handleCartError" mostrando con un toast un mensaje amigable al usuario
    2. Eliminar producto del carrito
      1. Se obtiene el id del producto
      2. Realiza una peticion al servidor a la ruta del backend
      3. Si la respuesta de la peticion es correcta:
        - Actualiza el contador del carrito ('#mini-cart-count') con la funcion "updateCartCounter"
        - Actualiza el costo total de los productos en el carrito ('.cart-total') con la funcion "updateCartTotal"
        - Se muestra un toast de que todo se hizo correctamente
      4. Si la respuesta de la peticion no fue coorrecta se atrapa el error con la funcion "handleCartError" mostrando con un toast un mensaje amigable al usuario
    3. Actualizar/Modificar cantidad del producto en el carrito
      1. Se obtiene el id del producto
      2. Realiza una peticion al servidor a la ruta del backend
      3. Si la respuesta de la peticion es correcta:
        - Actualiza el contador del carrito ('#mini-cart-count') con la funcion "updateCartCounter"
        - Actualiza el costo total de los productos en el carrito ('.cart-total') con la funcion "updateCartTotal"
        - Se recarga la pagina
      4. Si la respuesta de la peticion no fue coorrecta se atrapa el error con la funcion "handleCartError" mostrando con un toast un mensaje amigable al usuario
  - contactForm.js; sigue el mismo proceso de registro solo que tiene agregado lo siguiente:
    - Honeypot (para mas detalle de la implementacion ve al archivo 'honeypot_documentacion.md')
    - Recaptcha (para mas detalle de la implementacion ve al archivo 'recaptcha_documentacion.md.md')
  - createForm.js; sigue el mismo proceso de registro solo que tiene agregado lo siguiente:
    - Ejecuta la funcion "initFileNameDisplay" que carga el nombre de la imagen seleccionada
    - Ejecuta la funcion "startButtonLoader" que poner un spinner dentro del boton con un texto y deshabilita el click, "stopButtonLoader" quita el spinner dentro del boton y restaura el texto original del botón ('Enviar')
    - Y realiza una redireccion
  - forgotPasswordForm.js; aqui a diferencia del 'createForm.js' ejecuta la funcion "stopButtonLoader" en el finally del 'try'
  - modal-delete.js; este puede reutilizazrse en especial para los casos de eliminar datos de una fila/registro(bd)
    - Para esta version 1 de Anime Shop solo la reutilizamos en el panel administrativo en:
      - Listado de newsletter con boton por cada fila para borrar. Aqui se hace un borrado con 'DELETE' en bd
      - Listado de productos con boton por cada fila para borrar. Aqui  se hace un borrado con 'soft delete' es decir no se borra fisicamente en bd solo se desactiva un campo de la tabla de productos
  - modal.js; se usa solamente en el detalle del producto para la version 1 de Anime Shop  y especificamente es para mostrar las iamgenes disponibles del producto aunque esta abierta a ser reutilizable como modal (para version 2 o posterior).
  - newsletterToggle.js; realiza las siguientes acciones en el apartado del perfil del usuario:
    1. Inicializa toggle con el estado de suscripcion de la newsletter
    2. Maneja los cambios del toggle
  - paymentForm.js; sigue el mismo flujo de como si fuera un registro.
  - productDetail.js; invocamos la modal para ver cada imagen del producto y la funcion de la wishlist "initWishlist" que esta documentada mas adelante:
    - En el callback "onOpen":
      1. Seleccionamos la imagen para poder validar que se trata realmente de una imagen
      2. Si se cumple la validacion entonces mostramos la imagen a la cual se le dio click para abrir la modal y mostrarla
  - registerForm.js; sigue el mismo proceso de un registro solo agrega una redireccion y si la respuesta de la peticion es correcta y trae una redireccion desde el backend se redirecciona a otra pagina
  - resetPasswordForm.js; este se encarga de cambiar la contraseña actual por una nueva a travez del backend. Se sigue el  mismo proceso de un registro y si la respuesta de la peticion es correcta y trae una redireccion desde el backend se redirecciona a otra pagina
  - shippingAddressForm.js; se sigue el  mismo proceso de un registro. Ademas tambien se incializa el toogle para mostrar u ocultar el contenedor para agregar una nueva direccion. Y si la respuesta de la peticion es correcta y trae una redireccion desde el backend se redirecciona a otra pagina
  - updateConfiguration.js; se sigue el  mismo proceso de una actualizacion agregando solamente lo siguiente:
    1. Si el boton de envio del formulario esta desactivado entonces se cancela el envio de datos
    2. Se ciclan en un foreach todos los campos de la configuracion del panel administrativo:
      - Obtiene el nombre del logico del campo (key, sin la parte settings[...]).
      - Obtiene el valor real del campo, adaptándose según el tipo de input:
        1. Si es un checkbox (type="checkbox"), toma su propiedad booleana .checked (true o false).
        2. Si no, toma el valor textual (.value) y elimina espacios al inicio y final (trim()).
      - De acuerdo al nombre del campo obtenido se realiza la validacion especifica depende del caso en el switch y si no hay un caso entonces solo se pide que no sea vacio el valor del campo
      - Se dedsactiva boton de envio de formulario y se pone dentro un spinner acompañado de un texto antes de realizar la peticion
      - Una vez que se realiza la peticion se vuelve a dejar el boton a como estaba originalmente
      - Por ultimo realiza una redireccion si es que el backend manda una propiedad llamada redireccion
  - updateForm.js; se sigue el  mismo proceso de una actualizacion agregando solamente lo siguiente:
    - Actualiza el nombre de la imagen al momento de cargar la pagina y de seleccionar una imagen neuva a travez del input
    - Se dedsactiva boton de envio de formulario y se pone dentro un spinner acompañado de un texto antes de realizar la peticion
    - Una vez que se realiza la peticion se vuelve a dejar el boton a como estaba originalmente
    - Si la respuesta de la peticion es correcta y trae una redireccion desde el backend se redirecciona a otra pagina
  - userDropdown.js; menu desplegable del usuario
  - wishlist.js; tiene solamente la funcion exportable "initWishlist" e importa otros javascript que son usados
  - wishlistIndex.js; tiene solamente la funcion exportable "initWishlist" que ya esta explicada

- Tenemos dos archivos js para enviar peticiones al backend y que devuelvan una respuesta:
  1. public\assets\js\ajax\sendForm.js para envios de datos desde un formulario
  2. public\assets\js\ajax\sendRequest.js para envio de datos que no vienen de un formulario