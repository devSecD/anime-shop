// components/burgerMenu.js

export function initBurgerMenu() {
    const burgerToggle = document.getElementById("burger-toggle");
    const menuBurger = document.querySelector(".menu-burger");

    if (burgerToggle && menuBurger) {
        burgerToggle.addEventListener("change", () => {
            // Aplica la clase 'open' si el checkbox está marcado
            menuBurger.classList.toggle("open", burgerToggle.checked);
        });
    }
}
