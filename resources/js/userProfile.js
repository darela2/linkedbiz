document.addEventListener("DOMContentLoaded", function() {
    const toggleThemeLink = document.getElementById('toggleTheme');
    const body = document.body;

    const increaseTextButton = document.querySelector('#increaseText');
    const decreaseTextButton = document.querySelector('#decreaseText');

    // Obtener la URL desde el atributo data
    const scriptTag = document.querySelector('script[data-comentarios-store-url]');
    const comentariosStoreUrl = scriptTag ? scriptTag.getAttribute('data-comentarios-store-url') : '';
    const friendsList = document.getElementById('friendsList');
    const amigosData = JSON.parse(friendsList.getAttribute('data-amigos'));
    console.log(amigosData);

    console.log(comentariosStoreUrl);

    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
        toggleThemeLink.textContent = 'Tema Claro';
    }

    toggleThemeLink.addEventListener('click', function(event) {
        event.preventDefault();
        body.classList.toggle('dark-theme');

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

    document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const action = button.closest('form').getAttribute('action');

            fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success){
                    if (action.includes('dislike')) {
                        console.log(button.querySelector('.dislike-count'));
                        button.querySelector('.dislike-count').textContent = data.dislikes;
                    } else {
                        console.log(button.querySelector('.like-count'));
                        button.querySelector('.like-count').textContent = data.likes;
                    }
                } else {
                    //Mostrar mensaje de error en el modal
                    showModalMessage(data.message);
                }
                
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Capturar el evento submit del formulario de comentarios
    document.querySelectorAll(`form[action="${comentariosStoreUrl}"]`).forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            const formData = new FormData(this); // Obtener datos del formulario

            // Validar el contenido del comentario
            const contenido = formData.get('contenido');
            if (contenido.trim() === '') {
                showModalMessage('Por favor, ingresa un comentario.');
                return;
            }

            console.log(formData.get('contenido'));
            console.log(formData.get('publicacion'));

            // Realizar la llamada AJAX
            fetch(comentariosStoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'

                },
                body: JSON.stringify({
                    Publicacion: formData.get('publicacion'),
                    Contenido: formData.get('contenido')
                    
                })
            })
            .then(response => response.json())
            .then(data => {
                // Procesar la respuesta del servidor
                console.log(data);
                if (data.success) {
                    const commentsList = this.closest('.card-body').querySelector('.mt-3 ul');
                    const newComment = document.createElement('li');
                    newComment.innerHTML = `${data.Contenido} - <em>${data.NombreUsuario}</em> <small class="text-muted">${data.Fecha}</small>`;
                    commentsList.appendChild(newComment);

                    this.querySelector('textarea[name="contenido"]').value = '';
                } else {
                    console.error('Error al agregar el comentario:', data.error);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
        });
    });

    // Cargar la lista de amigos
    amigosData.forEach(amigo => {
        const friendCard = document.createElement('div');
        friendCard.classList.add('card', 'mb-2', 'friend-card'); // Clases para el diseño de la tarjeta
        const fotoSrc = `/proyectofinal-linkedbiz/storage/app/app/${amigo.Fotografia}` ? `/proyectofinal-linkedbiz/storage/app/app/${amigo.Fotografia}` : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';
        friendCard.innerHTML = `
            <div class="card-body d-flex align-items-center">
                <img src="${fotoSrc}" alt="Foto de ${amigo.Usuario}" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                
                <div>
                    <h6 class="card-title mb-0">${amigo.Usuario}</h6>
                    <p class="card-text">${amigo.Nombre} ${amigo.Apellidos}</p>
                </div>
            </div>
        `;

        friendsList.appendChild(friendCard);
    });


    // Función para mostrar mensajes en la ventana modal
    function showModalMessage(message) {
        const modalBody = document.getElementById('modalMessageBody');
        modalBody.textContent = message;
        const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
        messageModal.show();
    }
});
