document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchUserButton');
    const searchInput = document.getElementById('searchUserInput');
    const resultsContainer = document.getElementById('userResultsContainer');
    const toggleThemeLink = document.getElementById('toggleTheme');
    const body = document.body;

    // Función para mostrar mensajes en la ventana modal
    function showModalMessage(message) {
        const modalBody = document.getElementById('modalMessageBody');
        modalBody.textContent = message;
        const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
        messageModal.show();
    }

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

    // Evento de clic en el botón de búsqueda
    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();

        // Verificar que se haya ingresado una consulta
        if (query === '') {
            showModalMessage('Por favor, ingresa un nombre, apellidos o nombre de usuario para buscar.');
            return;
        }

        // Limpiar resultados anteriores
        resultsContainer.innerHTML = '<p>Buscando...</p>';

        // Realizar la llamada AJAX
        fetch('search-users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la búsqueda.');
            }
            return response.json();
        })
        .then(data => {
            // Limpiar el contenedor de resultados
            resultsContainer.innerHTML = '';

            // Verificar si se encontraron usuarios
            if (data.length === 0) {
                resultsContainer.innerHTML = '<p>No se encontraron usuarios.</p>';
                return;
            }

            // Mostrar los resultados
            data.forEach(user => {
                // Crear una tarjeta de usuario

                // Verificar si la imagen es nula
                if (user.Fotografia === null) {
                    user.Fotografia = 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';
                }
                else user.Fotografia = 'storage/' + user.Fotografia;

                // Crear una tarjeta de usuario
                resultsContainer.innerHTML += `
                    <div class="user-card">
                        <img src="${user.Fotografia}">

                        <div class="user-info">
                            <h5>${user.Nombre} ${user.Apellidos}</h5>
                            <p>@${user.Usuario}</p>
                            <p>${user.Biografia || 'Sin biografía'}</p>
                            <a href="user/${user.ID}" class="btn btn-primary">Ver perfil</a>
                            <button class="btn btn-secondary friend-request-btn" data-user-id="${user.ID}">Enviar solicitud de amistad</button>
                        </div>
                    </div>
                `;
            });

            // Agregar funcionalidad para enviar solicitudes de amistad
            const friendRequestButtons = document.querySelectorAll('.friend-request-btn');
            friendRequestButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');

                    // Enviar solicitud de amistad
                    fetch(`user/${userId}/friend-request`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al enviar la solicitud de amistad.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showModalMessage(data.message); // Mostrar mensaje de éxito
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showModalMessage('Ya has enviado una solicitud de amistad.');
                    });
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = '<p>Error al buscar usuarios. Por favor, intenta nuevamente más tarde.</p>';
        });
    });
});
