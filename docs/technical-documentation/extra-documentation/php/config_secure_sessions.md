- Sesiones en PHP que se usan principalmente en el helper SessionHelper.
  - session.use_strict_mode
    - Qué hace:
      Evita que PHP acepte IDs de sesión arbitrarios enviados por el cliente.
    - Por qué es importante:
      Sin esta opción, un atacante podría crear un ID de sesión inventado y forzar al servidor a usarlo.
      Con use_strict_mode = 1, PHP solo acepta IDs que el servidor haya generado previamente.
    - En resumen:
      Evita “Session Fixation” (fijación de sesión).
  - session.cookie_httponly
    - Qué hace:
      Evita que JavaScript acceda a la cookie de sesión (por ejemplo, mediante document.cookie).
    - Por qué es importante:
      Protege contra ataques XSS (Cross-Site Scripting), ya que un script malicioso no puede robar la cookie.
    - En resumen:
      Protege la cookie de sesión del acceso por JS.
  - session.cookie_secure
    - Qué hace:
      Indica que la cookie de sesión solo debe enviarse por conexiones HTTPS.
    - Por qué es importante:
      Si se envía por HTTP normal, alguien en la red podría interceptar la cookie (robar sesión).
      Con esta bandera activa, PHP solo enviará la cookie si la conexión es segura (SSL/TLS).
    - En resumen:
      Protege la cookie de sesión en tránsito.
  - session_set_cookie_params() configura las cookies de sesión
    - httponly
      - Qué hace:
        Exactamente igual que session.cookie_httponly:
        Evita que el contenido de la cookie sea accesible desde JavaScript.
      - Por qué es importante:
        Protege la cookie frente a ataques XSS.
    - secure
      - Qué hace:
          Indica que la cookie solo debe enviarse por HTTPS, nunca por HTTP.
      - Por qué es importante:
        Evita que las cookies se filtren si alguien intercepta la comunicación en texto plano.
    - samesite
      - Qué hace:
        Controla cuándo se envía la cookie en solicitudes cruzadas (cross-site).
      - Esto ayuda a mitigar ataques CSRF (Cross-Site Request Forgery).
      - 'Strict'
        La cookie solo se envía si la navegación se origina en el mismo dominio. Es la opción más segura, pero puede romper ciertas integraciones externas.