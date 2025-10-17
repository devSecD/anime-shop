/**
 * Descripción:
 *   Centraliza las rutas (endpoints) del backend utilizadas en las peticiones
 *   AJAX o fetch del frontend de Anime Shop.
 * 
 * Propósito:
 *   Evitar la duplicación de URLs dentro del código y facilitar el mantenimiento
 *   cuando se actualicen las rutas del backend.
 *
 * Estado:
 *   Actualmente no se utiliza en el MVP 1.0, pero se mantiene como base
 *   para futuras integraciones con el backend PHP y el router personalizado.
 *
 * Notas de mejora:
 *   - Agregar todos los endpoints de la API del backend (carrito, wishlist, newsletter, etc.)
 *   - Implementar una variable baseURL para unificar el dominio o entorno (dev/prod)
 *   - Validar que las rutas coincidan con las definidas en el router PHP
 */
export const API_ENDPOINTS = {
    register: "/User/RegisterController/process", 
    login: "/User/LoginController"
}