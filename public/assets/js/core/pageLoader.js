const pageModules = {
    register: () => import('../components/registerForm.js').then(m => m.initRegisterForm()),
    login: () => import('../components/loginForm.js').then(m => m.initLoginForm()),
    forgot_password: () => import('../components/forgotPasswordForm.js').then(m => m.initForgotPasswordForm()),
    reset_password: () => import('../components/resetPasswordForm.js').then(m => m.initResetPasswordForm()),
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
