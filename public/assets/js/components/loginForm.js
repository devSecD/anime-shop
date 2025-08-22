import { isNotEmpty, isMinLength, isValidEmail } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { updateHeaderCounter } from '../../../assets/js/util/wishlistCounter.js';
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initLoginForm() {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", function (e){
        e.preventDefault();

        const email = form.elements['email'].value;
        const password = form.elements['password'].value;

        let errors = [];

        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (!isNotEmpty(password))
            errors.push('La contraseña es obligatoria.');

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        // Incluir el localWishlist si existe en localStorage
        const localWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        let wishlistInput = form.querySelector('input[name="localWishlist"]');

        if (!wishlistInput) {
            wishlistInput = document.createElement('input');
            wishlistInput.type = 'hidden';
            wishlistInput.name = 'localWishlist';
            form.appendChild(wishlistInput);
        }

        wishlistInput.value = JSON.stringify(localWishlist);

        sendForm(form, function (response){

            if (!response.success) {
                showToast(response.message, 'error');
            }
            if (response.success && response.redirect) {
                showToast(response.message, 'success');

                // Actualizar contador usando el valor del servidor
                updateHeaderCounter('#wishlist-count', response.wishlistCount);

                // Limpiar wishlist del localStorage
                localStorage.removeItem('wishlist');

                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 3000);
            }

        });

    });
}