document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById('loginForm');

    // Seleccionar todos los botones de alternancia de contraseña
    const togglePasswordButton = document.querySelector('.toggle-password-login');

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
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío por defecto del formulario

            const formData = new FormData(loginForm);
            const loginUrl = loginForm.getAttribute('action');
            
            fetch(loginUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => {
                // Aquí chequeamos si la respuesta fue exitosa (2xx) o no
                if (!response.ok) {
                    throw new Error('Las credenciales de sesión son incorrectas.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success === false) {
                    // Mostrar el modal con el mensaje de error
                    const modalError = document.getElementById('loginErrorModal');
                    const modalMessage = document.getElementById('loginErrorMessage');
                    
                    if (modalMessage) {
                        modalMessage.textContent = data.message; // Mostrar el mensaje de error
                    }

                    if (modalError) {
                        // Abrir el modal
                        const modal = new bootstrap.Modal(modalError);
                        modal.show();
                    }
                } else {
                    // Transformar la fecha antes de guardarla en sessionStorage
                    const user = data.user;

                    // Cconvertir la fecha de nacimiento al formato adecuado
                    const fecha = new Date(user.FechaNacimiento);
                    const dia = String(fecha.getDate()).padStart(2, '0');
                    const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
                    const año = fecha.getFullYear();

                    // Reemplazar la fecha con el formato "día/mes/año"
                    user.FechaNacimiento = `${dia}/${mes}/${año}`;
                    
                    // Almacenar los datos del usuario en sessionStorage
                    console.log('Datos del usuario a guardar:', user);
                    sessionStorage.setItem('user', JSON.stringify(user));
                    console.log('Usuario almacenado en sessionStorage:', sessionStorage.getItem('user'));

                    // Si el inicio de sesión es exitoso, redirigir según el rol
                    if (data.role === 'admin') {
                        window.location.href = '/proyectofinal-linkedbiz/admin/dashboard'; // Redirigir al dashboard del administrador
                    } else if (data.role === 'user') {
                        window.location.href = '/proyectofinal-linkedbiz/user/dashboard'; // Redirigir al dashboard del usuario
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Mostrar el modal con el mensaje de error
                const modalError = document.getElementById('loginErrorModal');
                const modalMessage = document.getElementById('loginErrorMessage');
                
                if (modalMessage) {
                    modalMessage.textContent = error.message; // Mostrar el mensaje de error
                }

                if (modalError) {
                    // Abrir el modal
                    const modal = new bootstrap.Modal(modalError);
                    modal.show();
                }
            });
        });
    }
});
