document.addEventListener('DOMContentLoaded', function () {
    // Obteniendo el token CSRF desde el meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Seleccionando el formulario de registro de empresa
    const registerForm = document.getElementById('companyRegisterForm');

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Evita el envío tradicional del formulario

        // Obtener los datos del formulario
        const formData = {
            companyID: document.getElementById('companyID').value,
            companyName: document.getElementById('companyName').value,
        };

        // Verificar y agregar los datos opcionales si están presentes
        const direccion = document.getElementById('companyLocation').value;
        const localidad = document.getElementById('companyCity').value;
        const email = document.getElementById('companyEmail').value;
        const telefono = document.getElementById('companyPhone').value;
 
        if (direccion) formData.companyAddress = direccion; // Dirección (opcional)
        if (localidad) formData.companyCity = localidad;    // Localidad (opcional)
        if (email) formData.companyEmail = email;           // Email (opcional)
        if (telefono) formData.companyPhone = telefono;     // Teléfono (opcional)

        // Enviar los datos al servidor mediante fetch y AJAX
        fetch(registerCompanyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Incluyendo el token CSRF
            },
            body: JSON.stringify(formData) // Convertir los datos a JSON
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            // Procesar la respuesta del servidor
            console.log('Empresa registrada con éxito:', data);
            if (data.success){
                // Establecer el mensaje y mostrar la modal
                document.getElementById('modalBody').innerText = "Empresa registrada con éxito.";
                $('#feedbackModal').modal('show');
            }
            
        })
        .catch(error => {
            // Manejo de errores
            console.error('Error en el registro de la empresa:', error);
            // Mostrar modal de error
            document.getElementById('modalBody').innerText = error.message;
            console.log(document.getElementById('modalBody'));
            console.log(document.getElementById('modalBody').innerText);
            $('#feedbackModal').modal('show');
        });
    });
});