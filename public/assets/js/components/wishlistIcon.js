export function initWishlistIcon(selector = ".btn-wishlist") {
  const buttons = document.querySelectorAll(selector);

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      const icon = btn.querySelector("i");
      if (!icon) return;

      // Animación heartbeat
      icon.classList.remove("heartbeat");
      void icon.offsetWidth; // force reflow
      icon.classList.add("heartbeat");

      // Toggle color con clase .active
      icon.classList.toggle("active");

      // Opcional: toggle entre fa-regular y fa-solid
      icon.classList.toggle("fa-regular");
      icon.classList.toggle("fa-solid");
    });
  });
}
