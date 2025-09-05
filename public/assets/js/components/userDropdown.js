const userDropdown = document.querySelector(".nav-user-dropdown");

if (userDropdown) {

    const toggleBtn = userDropdown.querySelector(".nav-user-info");
    const menu = userDropdown.querySelector(".dropdown-menu");

    if (menu) {
        toggleBtn.addEventListener("click", (e) => {
            e.preventDefault();
            menu.classList.toggle("show");
        });

        // Cierra si das click fuera
        document.addEventListener("click", (e) => {
            if (!userDropdown.contains(e.target)) {
                menu.classList.remove("show");
            }
        });
    }

}