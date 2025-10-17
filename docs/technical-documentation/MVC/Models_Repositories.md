Repositorios y Modelos

- Todos los repositorios o en su mayoria tienen las siguientes caracteristicas:
  1. Tenemos un contructor
  2. Tienen metodos con tipo de retorno ya sea con el operador '?' (tipo nullable) ó '|' (union type)
  3. Solo si es necesario llevan logica del negocio
  4. Con base al modelo tenemos dos casos:
  - El repositorio instancia y utiliza el modelo directamente, acoplando la lógica de acceso a datos al repositorio (acoplamiento directo).  
  - El repositorio recibe el modelo por inyección de dependencias y lo utiliza sin conocer los detalles de la base de datos (acoplamiento indirecto y más flexible).

- Todos los modelos o en su mayoria tienen las siguientes caracteristicas:
  1. Tenemos un contructor
  2. Tienen metodos con tipo de retorno ya sea con el operador '?' (tipo nullable) ó '|' (union type)
  3. El cuerpo de los metodos deben llevar exclusivamente consultas SQL con PDO

1. Modelo Cart Solo maneja sesion del carrito:
  - Constante de clase 'SESSION_KEY'
  - Propiedad' 'repo' como tipo objeto repository product
  - Metodos
    1. Constructor (__construct)
    2. getProductRepository
    3. init
    4. add
      1. Obtenemos datos del producto por id. En caso de no encontrar el producto lanzamos una excepcion
      2. Si el stock del producto es menor a 1 lanzamos excepcion
      3. Obtenemos datos del carrito
      4. Si el producto existe solo aumentamos la cantidad del producto
      5. Si el producto no existe lo agregamos con los datos siguientes:
        - 'qty'
        - 'price'
        - 'name'
        - 'image'
      6. Actualizamos el carrito a travez de su sesion
    5. remove
      1. Si la cantidad del producto es menor o igual a 0 lo removemos de la sesion
      2. Si la cantidad del producto es mayor a 0 obtenemos los datos carrito (items/productos principalmente)
        - Si el producto existe aumentamos la cantidad y actualizamos el carrito a traves de su sesion
    6. items
      - Obtenemos los items/productos del carrito si tiene datos y si no lo ponemos como arreglo vacio
    7. getQuantity
      1. Obtenemos items/productos
      2. Si el producto existe obtenemos su cantidad y si no lo ponemos en 0
    8. total
      1. Recorre todos los items del carrito y retorna la suma total de (precio * cantidad) iniciando en 0.0
    9. count
      Recorre todos los items del carrito y retorna la suma total de las cantidades (qty) iniciando en 0
    10. clear
      1. Limpia el carrito como un arreglo vacio

1. Para registrar un usuario usamos el metodo "registerWithRole" del repositorio de usuario el cual requiere los datos y el nombre del rol del usuario
  1. Se aplica hash a la contraseña
  2. Se registran los datos del usuario
  3. Obtenemos el id del rol del usuario
  4. Se asigna el rol del usuario