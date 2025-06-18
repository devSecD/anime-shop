/**
 * Inserta (o revela) un spinner dentro del botón y deshabilita el click.
 * @param {HTMLButtonElement} btn - Botón submit al que se le aplicará el loader
 * @param {string} loadingText  - Texto a mostrar mientras carga (opcional)
 */
export function startButtonLoader(btn, loadingText = "Procesando...") {
    if (!btn) return;

    // Guardar texto original si aun no se ha guardado
    if (!btn.dataset.originalText) {
        btn.dataset.originalText = btn.textContent.trim();
    }

    btn.disabled = true;
    btn.classList.add("opacity-60", "cursor-not-allowed"); // tailwind friendly; opcional

    // Cambiar texto
    const textContainer = btn.querySelector(".btn-text");
    if (textContainer) {
        textContainer.textContent = loadingText;
    }else {
        btn.textContent = loadingText;
    }

    // Mostrar o crear spinner
    let spinner = btn.querySelector(".spinner");
    if (!spinner) {
        spinner = document.createElement("span");
        spinner.classList.add("spinner");
        btn.appendChild(spinner);
    }
    spinner.classList.remove("hidden");
}

export function stopButtonLoader(btn) {
    if (!btn) return;

    const originalText = btn.dataset.originalText;

    const textContainer = btn.querySelector(".btn-text");
    if (textContainer) {
        textContainer.textContent = originalText;
    }else {
        btn.textContent = originalText;
    }

    const spinner = btn.querySelector(".spinner");
    if (spinner) spinner.classList.add("hidden");

    btn.disabled = false;
    btn.classList.remove("opacity-60", "cursor-not-allowed");
}