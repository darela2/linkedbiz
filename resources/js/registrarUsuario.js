document.addEventListener('DOMContentLoaded', function () {
    // Obteniendo el token CSRF desde el meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Seleccionando el formulario de registro de usuario
    const registerForm = document.getElementById('userRegisterForm');

    // Seleccionar todos los botones de alternancia de contraseña
    const togglePasswordButton = document.querySelector('.toggle-password');

    togglePasswordButton.addEventListener('click', function () {
        // Seleccionar el input asociado al botón
        const passwordInput = this.previousElementSibling;
        const icon = this.querySelector('i');

        // Alternar el tipo de input entre 'text' y 'password'
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });


    // Función para calcular la edad
    function calculateAge(birthDate) {
        const today = new Date();
        const birthDateObj = new Date(birthDate);
        let age = today.getFullYear() - birthDateObj.getFullYear();
        const monthDifference = today.getMonth() - birthDateObj.getMonth();

        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDateObj.getDate())) {
            age--;
        }
        return age;
    }

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Evita el envío tradicional del formulario

        // Crear una instancia de FormData para manejar los datos del formulario
        const formData = new FormData();
        formData.append('userName', document.getElementById('userName').value);
        formData.append('userSurname', document.getElementById('userSurname').value);
        formData.append('userBiography', document.getElementById('userBiography').value);
        formData.append('user', document.getElementById('user').value);
        formData.append('userPassword', document.getElementById('userPassword').value);
        formData.append('userEmail', document.getElementById('userEmail').value);
        formData.append('userBirthDate', document.getElementById('userBirthDate').value);
        formData.append('companyCIF', document.getElementById('company').value);

        // Validar que el usuario sea mayor de 16 años
        const userBirthDate = document.getElementById('userBirthDate').value;
        if (calculateAge(userBirthDate) < 16) {
            // Mostrar ventana modal con mensaje de error
            document.getElementById('modalBody').innerText = "Necesitas tener al menos 16 años para registrarte como usuario.";
            $('#feedbackModal').modal('show');
            return; // Detener el proceso de envío si el usuario es menor de 16 años
        }

        // Verificar si se ha seleccionado una foto (campo opcional)
        const userPhotoInput = document.getElementById('userPhoto');
        if (userPhotoInput.files.length > 0) {
            formData.append('userPhoto', userPhotoInput.files[0]); // Solo agregar si se seleccionó un archivo
        }

        // Enviar los datos al servidor mediante fetch y AJAX
        fetch(registerUserUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken // Incluyendo el token CSRF
            },
            body: formData // Enviar formData, no JSON.stringify
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            // Procesar la respuesta del servidor
            if (data.success) {
                document.getElementById('modalBody').innerText = "Usuario registrado con éxito.";
            } else {
                document.getElementById('modalBody').innerText = data.message || "Error en el registro.";
            }
            $('#feedbackModal').modal('show'); // Mostrar modal con el mensaje de éxito o error
        })
        .catch(error => {
            // Manejo de errores
            console.error('Error en el registro del usuario:', error);
            // Mostrar modal de error inesperado
            document.getElementById('modalBody').innerText = "Error inesperado: " + error.message;
            $('#feedbackModal').modal('show');
        });
    });
});
