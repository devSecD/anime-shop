- Para el envio de correo en la clase MailHelper (app\helpers\MailHelper.php) justo en el metodo "send":
  - Los encabezados de correo (como Subject) solo admiten caracteres ASCII según la norma MIME (RFC 2047).
  - Si el asunto contiene acentos, eñes o símbolos (por ejemplo, “Confirmación de compra”), estos podrían mostrarse mal en algunos clientes de correo.
  - Para evitarlo, se codifica el asunto en Base64 y se indica que está en UTF-8, usando esta estructura:
  
    =?UTF-8?B?texto_codificado_en_base64?=

  - Ejemplo en PHP:

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

  - Así, el correo mostrará correctamente los caracteres especiales y será compatible con todos los clientes (Gmail, Outlook, etc.).