- Del Javascript public\assets\js\ajax\sendRequest.js:
  
    const {
      method = 'POST', 
      contentType = 'application/x-www-form-urlencoded'
    } = options;
  
  - Esto no crea un nuevo objeto llamado options, sino que:

  - Extrae las propiedades method y contentType del objeto existente options.
  - Si alguna de esas propiedades no existe, usa el valor por defecto ('POST' o 'application/x-www-form-urlencoded').
    En otras palabras, es una desestructuración de objetos con valores por defecto.
  - Ejemplo para entenderlo mejor
    Imagina esto:

      const options = { method: 'GET' };

      const { 
        method = 'POST', 
        contentType = 'application/x-www-form-urlencoded' 
      } = options;

      console.log(method);        // "GET"
      console.log(contentType);   // "application/x-www-form-urlencoded"

    Si el objeto options no tiene la propiedad contentType, entonces se usa el valor por defecto.
  - Otro ejemplo

    Si no pasas ningún objeto:

    const options = {};
    const { method = 'POST', contentType = 'application/x-www-form-urlencoded' } = options;

    console.log(method); // "POST"
    console.log(contentType); // "application/x-www-form-urlencoded"

  - En resumen

  | Código                                                                        | Qué hace                                   |
  | ----------------------------------------------------------------------------- | ------------------------------------------ |
  | `const options = { ... }`                                                     | Crea un nuevo objeto                       |
  | `const { method, contentType } = options;`                                    | Extrae propiedades de un objeto existente  |
  | `const { method = 'POST', contentType = 'x-www-form-urlencoded' } = options;` | Extrae propiedades con valores por defecto |

- Para el Javascript public\assets\js\components\wishlistIcon.js especialmente en la animacion del latido del icono del corazon (latido) usando void icon.offsetWidth fuerza al navegador a recalcular el layout del elemento
  - Esto asegura que la animación CSS se reinicie cada vez que haces clic.
  - No necesitamos el valor, solo que ocurra el reflow.