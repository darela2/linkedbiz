document.addEventListener('DOMContentLoaded', function () {
    // Obtener el token CSRF del meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.getElementById('forgotPasswordForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const email = document.getElementById('forgotPasswordEmail').value;

        fetch(passwordResetUrl, { // Usa la variable passwordResetUrl
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // Usa la variable csrfToken
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            } else {
                alert('Ocurrió un error, por favor intenta nuevamente.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error, por favor intenta nuevamente.');
        });
    });
});
