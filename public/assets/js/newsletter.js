const form = document.getElementById('newsletter-form');
const emailInput = document.getElementById('newsletter-email');
const messageDiv = document.getElementById('newsletter-message');
const submitButton = form.querySelector('input[type="submit"]');

const showMessage = (text, type = 'error') => {
    messageDiv.textContent = text;
    messageDiv.className = ''; // Limpiar clases previas
    messageDiv.classList.add('newsletter-message', type); // Aplicar clase .suceess o .error
    messageDiv.style.display = 'block';
}

const isValidEmail = (email) => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

const setFormDisabled = (state) => {
    emailInput.disabled = state;
    submitButton.disabled = state;
}

form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = emailInput.value.trim();

    if (!email || !isValidEmail(email)) {
        showMessage('Por favor, ingresa un correo valido.', 'error');
        return;
    }

    try {
        setFormDisabled(true);

        const response = await fetch('../public/newsletter/subscribe', {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }, 
            body: new URLSearchParams({ email })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');
            emailInput.value = '';
        } else {
            showMessage(data.message, 'error');
        }
    } catch (err) {
        console.log(err);
        showMessage('Error al enviar. Intenta nuevamente.', 'error');
    } finally {
        setFormDisabled(false);
    }
});