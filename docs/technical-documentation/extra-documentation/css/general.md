- Para todos los estilos en linea (inline) quitarlos y moverlos a un CSS ya creado o si no crear uno nuevo.
CSS
- Reutilizacion de archivos CSS

  1. Panel administrativo (administradores y super administrador)

      Para las paginas de listado y de configuracion
      - 'admin_products_list'
      - 'admin_orders_list'
      - 'admin_users_list'
      - 'admin_newsletter_list'
      - 'updateConfiguration'

      Se reutilizan estos archivos CSS
      - admin-products.css
      - modal-delete.css

  2. Paginas publicas para el cliente

    Para los errores se reutiliza este archivo CSS
    - errors.css

    Para las alertas se reutiliza este archivo CSS
    - alerts.css

  3. Para todos los formulario tanto para clientes y panel administrativo se reutilizan estos archivos CSS
      - form.css
      - spinner.css

- Seteo de variables CSS desde el archivo variables.css en paginas del cliente y panel administrativo
- Reseteo de estilos pro defecto del HTML se usa el archivo CSS reset.css solo para las paginas del cliente
- Se usa un index.css para importar los siguientes CSS:
  - reset.css
  - variables.css
  - components/header.css
  - components/catalog_products.css
  - components/footer.css
- Se usa fontawesome CSS version 6.7.2 web para las paginas del cliente
- Para los estilos del sidebar del panel administrativo se usa el archivo CSS sidebar.css
- Para los estilos del dashboard del panel administrativo se usa el archivo CSS dashboard.css
- Se usa fontawesome CSS version 6.4.0 para las paginas del panel administrativo
- Se usan fuentes de la API de Google:
  - 'family=Poppins:wght@400;600&family=Roboto&display=swap'
- Eliminar el public\assets\css\index.css y poner tanto en app\view\layouts\base.php y app\view\home\index.php todos los css que se importan dentro:
    1. reset.css
    2. variables.css
    3. components/header.css
    4. components/catalog_products.css
    5. components/footer.css