const pageModules = {
    register: () => import('../components/registerForm.js').then(m => m.initRegisterForm()),
    login: () => import('../components/loginForm.js').then(m => m.initLoginForm()),
    // Puedes agregar más páginas aquí en el futuro:
    // catalog: () => import('../components/catalog.js').then(m => m.initCatalog()),
    // profile: () => import('../components/profile.js').then(m => m.initProfile()),
};

export function loadPageModule(page) {
    if (pageModules[page]) {
        pageModules[page]()
            .catch(err => console.error(`Error cargando el módulo de la página '${page}':`, err));
    } else {
        // Si no hay módulo definido para la página, puedes ignorar o loguear:
        console.warn(`No hay módulo definido para la página '${page}'.`);
    }
}
