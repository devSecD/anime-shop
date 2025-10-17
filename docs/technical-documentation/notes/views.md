- Notas para la vista de registro de producto app\view\admin\products\create.php

  - Los checkboxes (is_on_sale e is_preorder) solo enviarán el valor "1" si están marcados.
  - El campo price_discounted es opcional (no required), así puedes dejarlo vacío si el producto no tiene descuento.
  - Los campos como sold_count, created_at, y product_id no se incluyen en el formulario ya que:
      - product_id lo genera la base de datos automáticamente.
      - sold_count inicia en 0 por defecto.
      - created_at es un timestamp automático.

- Notas para la vista app\view\admin\products\list.php

  Campo	            ¿Mostrar?	Comentario
  product_id	        ✅	        Útil como identificador interno. Puede ir oculto o visible.
  name	                ✅	        Nombre del producto (principal).
  price	                ✅	        Precio base (sin descuento).
  price_discounted	    ✅	        Si existe, mostrar junto al precio o con un ícono de oferta.
  stock	                ✅	        Crítico para administración.
  sold_count	        ✅	        Útil para ver qué tan popular ha sido.
  is_on_sale	        ✅	        Mostrar con ícono o etiqueta si está en oferta.
  is_preorder	        ✅	        Mostrar si está en preventa.
  image	                ✅	        Mostrar miniatura (opcional, mejora visualmente).
  category_id	        ✅	        Mostrar nombre de categoría (usando join).
  brand_id	            ✅	        Mostrar nombre de marca (usando join).
  created_at	        ✅	        Mostrar solo si necesitas control temporal (ordenar por reciente, etc.).
  description	        ❌	        No mostrar en la tabla resumen; es demasiado larga.
  Acciones	            ✅	        Botones para "Editar", "Eliminar", "Ver detalle", etc.