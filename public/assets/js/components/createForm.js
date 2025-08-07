import { initFileNameDisplay } from '../../../assets/js/ui/fileNameDisplay.js';
import { isNotEmpty, validatePositive, mustBePositiveInt, mustBeOptionalBoolean } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';
import { startButtonLoader, stopButtonLoader } from '../../../assets/js/components/formLoader.js';

export function initRegisterProductForm() {

    initFileNameDisplay();
    const form = document.getElementById("createForm");
    const submitBtn = form.querySelector("#btn-add-product");

    form.addEventListener("submit", function (e){
        e.preventDefault();

        // Evitar doble clic
        if (submitBtn.disabled) return;

        const name = form.elements['name'].value;
        const description = form.elements['description'].value;
        const price = form.elements['price'].value;
        const price_discounted = form.elements['price_discounted'].value;
        const stock = form.elements['stock'].value;
        const category_id = form.elements['category_id'].value;
        const brand_id = form.elements['brand_id'].value;
        const image = form.elements['image'].value;

        const is_on_sale = form.elements['is_on_sale'].checked;
        const is_preorder = form.elements['is_preorder'].checked;

        let errors = [];

        if (!isNotEmpty(name))
            errors.push('El nombre es obligatorio.');

        if (!isNotEmpty(description))
            errors.push('La descripción es obligatoria.');

        if (!isNotEmpty(price))
            errors.push('El precio es obligatorio.');
        else if (!validatePositive(price)) 
            errors.push('El precio debe ser mayor a 0.');

        if(price_discounted !== null && price_discounted !== '') 
        {
            if (!validatePositive(price_discounted))
                errors.push('El precio con descuento debe ser mayor a 0.');
        }

        if (!isNotEmpty(stock))
            errors.push('El stock es obligatorio.');
        else if (!mustBePositiveInt(stock))
            errors.push('El stock debe ser un número entero mayor o igual a 1.');

        if (!isNotEmpty(category_id))
            errors.push('La categoria es obligatoria.');

        if (!isNotEmpty(brand_id))
            errors.push('La marca es obligatoria.');

        if (!image)
            errors.push('La imagen del producto es obligatoria.');

        const onSaleError = mustBeOptionalBoolean(is_on_sale, 'En oferta');
        if (onSaleError) errors.push(onSaleError);

        const preorderError = mustBeOptionalBoolean(is_preorder, 'Preventa');
        if (preorderError) errors.push(preorderError);

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        // Mostrar spinner y deshabilitar botón
        startButtonLoader(submitBtn, "Guardando...");

        sendForm(form, function (response){
            // Quitar spinner y habilitar botón
            stopButtonLoader(submitBtn);

            if (!response.success) {
                showToast(response.message || 'Error al registrar el producto.', 'error');
                return;
            }

            showToast(response.message || 'Producto registrado exitosamente.', 'success');

            if (response.redirect) {
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2500);
            }
        });
    });

}