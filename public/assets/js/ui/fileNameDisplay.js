/**
 * Inicializa la actualización dinámica del nombre del archivo seleccionado
 * en el input tipo file con id "image".
 * Actualiza el texto del elemento con id "file-name".
 */
export function initFileNameDisplay() {
        const imageInput = document.getElementById('image');
        const fileNameSpan = document.getElementById('file-name');

        if (imageInput && fileNameSpan) {
            imageInput.addEventListener('change', function () {
                const fileName = this.files.length > 0 ? this.files[0].name : 'Ningún archivo seleccionado';
                fileNameSpan.textContent = fileName;
            });
        }
}
