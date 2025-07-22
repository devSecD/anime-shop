// Reemplaza esto por la Public Key de pruebas que ves en https://www.mercadopago.com.mx/developers/panel/credentials
const mp = new MercadoPago('TU_PUBLIC_KEY_AQUI', {
    locale:'es-MX'
});

fetch('/payment/createpreference')
.then(response => response.json())
.then(data => {
    mp.checkout({
        preference: {
            id: data.id
        }, 
        render: {
            container: '#wallet_container', // ID del div donde aparecerá el botón
            label: 'Pagar con Mercado Pago'
        }
    });
})
.catch(error => console.error('Error', error))