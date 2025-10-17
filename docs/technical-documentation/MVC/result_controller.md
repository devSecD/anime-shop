DOCUMENTACION ESPECIFICA Y DETALLADA DEL RESULTCONTROLLER DE LA RUTA (app\controllers\Payment\ResultController.php)

Este controlador sirve para manejar el resultado de un pago realizado despues de que lo procesa Mercado Pago.
Tenemos los siguientes metodos
1. index
2. loadOrderData
3. renderByStatus
4. successView
5. pendingView
6. failureView

- Metodo "index"
  - Obtenemos todos los parametros que vienen por GET que deben de ser obligatorios
  - Asignamos cada parametro a un variable y lo asignamos a la propiedad/atributo paymentParams como arreglo asociativo
  - Validamos que tenga el "external_reference" y si lo tiene invocamos al metodo "loadOrderData" pasando como parametro el "external_reference" como entero
  - Invocamos al metodo "renderByStatus" pasandole como parametro el status de la orden
- Metodo loadOrderData
  - Invocamos al metodo "getOrderWithItems" del repositorio para obtener la orden con sus items. Si no existe una orden se retorna nulo
- Metodo "renderByStatus"
  - A traves de un switch con case´s que representan los estus de la orden (approved, in_process, failure, rejected) rederigimos a la vista adecuada. Si el estatus de la orden no esta en nigun case redirigimos a la vista "failureView"
    - 'approved' muestra la vista "successView"
    - 'in_process' muestra la vista "pendingView"
    - 'failure'
    - 'rejected' muestra la vista "failureView"
    - default de momento muestra la vista "failureView" auqnue para una segunda version se implementara de una mejor manera
- Meotodo "successView"
  - Obtiene el paymentId (id que te devuelve Mercado Pago) para mostrarlo en la vista
  - Se valida que la orden exista y que el estaus de la misma sea 'paid' (pagada)
  - Instanciamos la clase del repositorio de producto
  - Ciclamos los items/productos de la orden obteniendo id del producto, cantidad y stock.
    - Validamos que exista el id del producto, que la cantidad sea mayor a 0 y sea menor o igual al stock
    - Restemos ekl stock e incremntamos el contador 
  - Limpiamos/vaciamos el carrito
- Metodo "pendingView"
  - Obtiene el paymentId (id que te devuelve Mercado Pago) para mostrarlo en la vista

- Metodo "failureView"
  - Obtiene el paymentId (id que te devuelve Mercado Pago) para mostrarlo en la vista