import { initFileNameDisplay } from '../../../assets/js/ui/fileNameDisplay.js';
import { isNotEmpty, validatePositive, mustBePositiveInt, mustBeOptionalBoolean } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';
import { startButtonLoader, stopButtonLoader } from '../../../assets/js/components/formLoader.js';

export function initUpdateProductForm() {

    initFileNameDisplay();
    const form = document.getElementById("updateForm");
    const submitBtn = form.querySelector("#btn-update-product");

    form.addEventListener("submit", function (e){
        e.preventDefault();

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

        if(price_discounted !== null && price_discounted !== '') {
            if (!validatePositive(price_discounted))
                errors.push('El precio con descuento debe ser mayor a 0.');
        }

        if (!isNotEmpty(stock))
            errors.push('El stock es obligatorio.');
        else if (!mustBePositiveInt(stock))
            errors.push('El stock debe ser un número entero mayor o igual a 1.');

        if (!isNotEmpty(category_id))
            errors.push('La categoría es obligatoria.');

        if (!isNotEmpty(brand_id))
            errors.push('La marca es obligatoria.');

        // En edición, la imagen es opcional, solo valida si se seleccionó algo
        if (image) {
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const ext = image.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(ext)) {
                errors.push('La imagen debe ser un archivo JPG, PNG, GIF o WebP.');
            }
        }

        const onSaleError = mustBeOptionalBoolean(is_on_sale, 'En oferta');
        if (onSaleError) errors.push(onSaleError);

        const preorderError = mustBeOptionalBoolean(is_preorder, 'Preventa');
        if (preorderError) errors.push(preorderError);

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        startButtonLoader(submitBtn, "Actualizando...");

        sendForm(form, function (response){
            stopButtonLoader(submitBtn);

            if (!response.success) {
                showToast(response.message || 'Error al actualizar el producto.', 'error');
                return;
            }

            showToast(response.message || 'Producto actualizado exitosamente.', 'success');

            if (response.redirect) {
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2500);
            }
        });
    });
}
