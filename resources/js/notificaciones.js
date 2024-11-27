document.addEventListener('DOMContentLoaded', function () {
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    const toggleThemeLink = document.getElementById('toggleTheme');
    const body = document.body;
    const increaseTextButton = document.querySelector('#increaseText');
    const decreaseTextButton = document.querySelector('#decreaseText');

    // Verificar si hay un tema guardado en localStorage y aplicarlo
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
        toggleThemeLink.textContent = 'Tema Claro';
    }

    // Alternar el tema cuando se haga clic en el enlace
    toggleThemeLink.addEventListener('click', function(event) {
        event.preventDefault();
        body.classList.toggle('dark-theme');

        // Guardar la preferencia del usuario en localStorage
        if (body.classList.contains('dark-theme')) {
            localStorage.setItem('theme', 'dark');
            toggleThemeLink.textContent = 'Tema Claro';
        } else {
            localStorage.setItem('theme', 'light');
            toggleThemeLink.textContent = 'Tema Oscuro';
        }
    });

    // Función para aumentar el tamaño del texto
    increaseTextButton.addEventListener('click', () => {
        const currentFontSize = parseFloat(window.getComputedStyle(body).fontSize); // Obtiene el tamaño actual
        body.style.fontSize = `${currentFontSize + 2}px`; // Incrementa en 2 píxeles
    });

    // Función para reducir el tamaño del texto
    decreaseTextButton.addEventListener('click', () => {
        const currentFontSize = parseFloat(window.getComputedStyle(body).fontSize); // Obtiene el tamaño actual
        body.style.fontSize = `${Math.max(10, currentFontSize - 2)}px`; // Reduce en 2 píxeles, con un mínimo de 10px
    });
    
    // Función para cargar notificaciones
    async function cargarNotificaciones() {
        const response = await fetch(`notificaciones/${userId}`);
        const notificaciones = await response.json();

        const notificacionesContainer = document.querySelector('.list-group');
        notificacionesContainer.innerHTML = '';

        notificaciones.forEach(notificacion => {
            // Crea un elemento HTML para la notificación
            let notificacionHTML = `
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">${notificacion.Tipo}</div>
                        ${notificacion.Mensaje}
                    </div>
                    <small class="text-muted">${notificacion.Fecha}</small>
            `;

            // Agregar botones para solicitudes de amistad
            if (notificacion.Tipo === 'Solicitud de amistad') {
                notificacionHTML += `
                    <button class="btn btn-success btn-sm ms-2" onclick="acceptFriendRequest(${notificacion.UsuarioEmisor})">Aceptar</button>
                    <button class="btn btn-danger btn-sm ms-2" onclick="rejectFriendRequest(${notificacion.UsuarioEmisor})">Rechazar</button>
                `;
            }

            notificacionHTML += '</div>'; // Cerrar el div principal
            notificacionesContainer.insertAdjacentHTML('beforeend', notificacionHTML);
        });
    }

    // Función para aceptar solicitud de amistad
    window.acceptFriendRequest = async function (id) {
        const response = await fetch(`friend-request/${id}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        showModalMessage(data.message);
        cargarNotificaciones(); // Recargar las notificaciones
    };

    // Función para rechazar solicitud de amistad
    window.rejectFriendRequest = async function (id) {
        const response = await fetch(`friend-request/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        showModalMessage(data.message);
        cargarNotificaciones(); // Recargar las notificaciones
    };

    // Función para mostrar mensajes en la ventana modal
    function showModalMessage(message) {
        const modalBody = document.getElementById('modalMessageBody');
        modalBody.textContent = message;
        const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
        messageModal.show();
    }

    // Llama a la función para cargar notificaciones al iniciar
    cargarNotificaciones();
});
