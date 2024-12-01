document.addEventListener('DOMContentLoaded', function() {
    const user = JSON.parse(sessionStorage.getItem('user'));
    const editProfileForm = document.getElementById('editProfileForm');
    const toggleThemeLink = document.getElementById('toggleTheme');
    const body = document.body;
    const postButton = document.getElementById('postButton');
    const newPostInput = document.getElementById('newPostInput');
    const newPostImage = document.getElementById('newPostImage');
    const postsContainer = document.getElementById('postsContainer');
    const userID = document.querySelector('meta[name="user-id"]') ? document.querySelector('meta[name="user-id"]').content : null;
    const increaseTextButton = document.querySelector('#increaseText');
    const decreaseTextButton = document.querySelector('#decreaseText');

     // Función para mostrar el mensaje de error en el modal
     function showErrorModal(message) {
        const errorModalMessage = document.getElementById('errorModalMessage');
        errorModalMessage.textContent = message;
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    if (user) {
        updateProfileInfo(user);
    }

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

    // Nueva función para cargar publicaciones y comentarios
    async function loadPosts() {
        try {
            const response = await fetch(`../publicaciones/${userID}`); // Cambia la URL según tu backend
            if (response.ok) {
                const posts = await response.json();
                posts.forEach(post => {
                    console.log(post);
                    renderPost(post);
                });
            } else {
                console.error('Error al cargar las publicaciones:', response.statusText);
                showErrorModal('Hubo un problema al cargar las publicaciones. Inténtalo de nuevo.');
            }
        } catch (error) {
            console.error('Error en la solicitud de carga de publicaciones:', error);
            showErrorModal('Hubo un problema en la solicitud de carga las publicaciones. Inténtalo de nuevo.');
        }
    }


    // Nueva función para cargar amigos
    async function loadFriends() {
        try {
            const response = await fetch(`${userID}/friends`); // Cambia la URL según tu backend
            if (response.ok) {
                const friends = await response.json();
                friends.forEach(friend => {
                    renderFriend(friend);
                });
            } else {
                console.error('Error al cargar los amigos:', response.statusText);
                showErrorModal('Hubo un problema al cargar tus amigos. Inténtalo de nuevo.');
            }
        } catch (error) {
            console.error('Error en la solicitud de carga de amigos:', error);
            showErrorModal('Hubo un problema en la solicitud de carga de amigos. Inténtalo de nuevo.');
        }
    }

    // Llama a la función para cargar publicaciones al cargar la página
    loadPosts();

    // Llama a la función para cargar amigos al cargar la página
    loadFriends();


    // Escuchar cuando se abre el modal
    $('#editProfileModal').on('show.bs.modal', function (event) {
        // Obtener los datos del perfil del usuario desde los elementos del DOM
        const userName = document.getElementById('userName').innerText;
        const userCallName = document.getElementById('userCallName').innerText;
        const userBiography = document.getElementById('userBiography').innerText;
        const userEmail = document.getElementById('userEmail').innerText;
        const userBirthDate = document.getElementById('userBirthDate').innerText;
        const userCompany = document.getElementById('userCompany').innerText;

        // Dividir userCallName en Nombre y Apellidos
        const [nombre, ...apellidos] = userCallName.split(' '); // Divide en palabras
        const apellidosStr = apellidos.join(' '); // Une el resto como una cadena

        // Asignar estos datos a los campos del modal
        document.getElementById('editUsuario').value = userName;
        document.getElementById('editNombre').value = nombre; // Primera palabra como Nombre
        document.getElementById('editApellidos').value = apellidosStr; // Resto como Apellidos
        document.getElementById('editBiografia').value = userBiography;
        document.getElementById('editEmail').value = userEmail;
        //Pasar la fecha a un formato valido DATE
        const [day, month, year] = userBirthDate.split('/');
        const formattedDate = `${year}-${month}-${day}`;
        document.getElementById('editFechaNacimiento').value = formattedDate;
        // Aquí se asume que el nombre de la empresa es solo texto, puedes ajustarlo según tu necesidad
        const companySelect = document.getElementById('company');
        for (let i = 0; i < companySelect.options.length; i++) {
            if (companySelect.options[i].text === userCompany) {
                companySelect.selectedIndex = i;  // Seleccionar la opción correspondiente
                break;
            }
        }
    });
    

    editProfileForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(editProfileForm);

        try {
            const response = await fetch(`../usuarios/editar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData, 
            });
    
            if (response.ok) {
                const data = await response.json();
                console.log(data);
    
                // Actualizar los datos del perfil en la vista
                document.getElementById('userName').textContent= data.user.Usuario;
                document.getElementById('userCallName').textContent = data.user.Nombre + ' ' + data.user.Apellidos;
                document.getElementById('userBiography').textContent = data.user.Biografia;
                document.getElementById('userEmail').textContent = data.user.Email;
                document.getElementById('userBirthDate').textContent = data.user.FechaNacimiento;

                // Actualizar el nombre de la empresa en la vista si es necesario
                const empresaSelect = document.getElementById('company');
                const empresaNombre = empresaSelect.options[empresaSelect.selectedIndex].text;
                document.getElementById('userCompany').textContent = empresaNombre;
    
                // Si hay una nueva foto, actualizar la imagen de perfil
                if (data.user.Fotografia) {
                    const newPhotoUrl = `/proyectofinal-linkedbiz/storage/app/app/${data.user.Fotografia}`;
                    console.log(newPhotoUrl);
                    document.getElementById('userPhoto').src = newPhotoUrl;
                }
    
                $('#editProfileModal').modal('hide');
            } else {
                const errorData = response.json();
                showErrorModal('Error al actualizar el perfil: ' + errorData.message);
            }
        } catch (error) {
            showErrorModal('Error al realizar la solicitud:', error);
        }
    });

    postButton.addEventListener('click', async function(e) {
        e.preventDefault();
        const textoPublicacion = newPostInput.value.trim();
        const imagenPublicacion = newPostImage.files[0]; // Obtén el archivo de imagen seleccionado
    
        if (textoPublicacion || imagenPublicacion) { // Permitir si hay texto o imagen
            try {
                const formData = new FormData();
                formData.append('Contenido', textoPublicacion);
                formData.append('user_id', userID);
                
                if (imagenPublicacion) {
                    formData.append('Imagen', imagenPublicacion); // Agregar la imagen solo si hay una seleccionada
                }
    
                const response = await fetch('../publicaciones', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData,
                });
    
                if (response.ok) {
                    const newPost = await response.json();
                    renderPost(newPost); // Renderiza la nueva publicación en el frontend
                    newPostInput.value = ''; // Limpiar el input de texto
                    newPostImage.value = ''; // Limpiar el input de imagen
                } else {
                    console.error('Error al crear la publicación:', response.statusText);
                    showErrorModal('Hubo un problema al publicar tu mensaje. Inténtalo de nuevo.');
                }
            } catch (error) {
                console.error('Error en la solicitud de creación de publicación:', error);
                showErrorModal('Hubo un problema en la solicitud de creación de publicación. Inténtalo de nuevo.');
            }
        } else {
            showErrorModal("¡No puedes publicar un mensaje vacío!");
        }
    });
    

    function renderPost(post) {
        const nuevaPublicacion = document.createElement('div');
        nuevaPublicacion.classList.add('card', 'mb-4');
    
        const formattedDate = post.Fecha ? new Date(post.Fecha).toLocaleString() : 'Fecha no disponible';
        const postContent = post.Contenido || 'Sin contenido'; 
        const currentUserName = user.Nombre + " " + user.Apellidos;
        const currentUserPhoto = user.Fotografia ? `/proyectofinal-linkedbiz/storage/app/app/${user.Fotografia}` : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';
        
        let postImageHtml = '';
        if (post.Imagen) {
            postImageHtml = `<img src="/proyectofinal-linkedbiz/storage/app/public/${post.Imagen}" alt="Imagen de la publicación" class="img-fluid mt-3">`;
        }

        nuevaPublicacion.innerHTML = `
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img class="rounded-circle me-3" src="${currentUserPhoto}" alt="Foto de perfil" width="50" height="50">
                    <div>
                        <h6 class="card-title mb-0">${currentUserName}</h6>
                        <small class="text-muted">${formattedDate}</small>
                    </div>
                </div>
                <p class="card-text mt-3">${postContent || 'Sin contenido'}</p>
                ${postImageHtml} <!-- Aquí es donde se añade la imagen -->
                <br><br>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary btn-sm like-btn" data-post-id="${post.ID}">
                        <i class="fas fa-thumbs-up icono-verde"></i>
                        <span class="like-count">${post.likes || 0}</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm dislike-btn" data-post-id="${post.ID}">
                        <i class="fas fa-thumbs-down icono-rojo"></i>
                        <span class="dislike-count">${post.dislikes || 0}</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm toggle-comment-btn">Comentar</button>
                </div>
                <div class="mt-3 comment-section" style="display: none;">
                    <textarea class="form-control comment-input" rows="2" placeholder="Escribe un comentario..."></textarea>
                    <button class="btn btn-sm btn-primary mt-2 comment-btn" data-post-id="${post.ID}">Publicar comentario</button>
                </div>
                <ul class="list-group mt-3 comments-list"></ul>
            </div>
        `;
    
        postsContainer.prepend(nuevaPublicacion);
        
         // Renderizar comentarios existentes si los hay
        const commentsList = nuevaPublicacion.querySelector('.comments-list');
        if (post.comentarios && post.comentarios.length > 0) {
            post.comentarios.forEach(comment => renderComment(comment, commentsList));
        }

        // Añadir eventos para botones de "Me gusta" y "No me gusta"
        const likeButton = nuevaPublicacion.querySelector('.like-btn');
        const dislikeButton = nuevaPublicacion.querySelector('.dislike-btn');
    
        likeButton.addEventListener('click', async function () {
            const postId = this.getAttribute('data-post-id');
            await handleReaction(postId, 'like');
        });
    
        dislikeButton.addEventListener('click', async function () {
            const postId = this.getAttribute('data-post-id');
            await handleReaction(postId, 'dislike');
        });
    
        const toggleCommentButton = nuevaPublicacion.querySelector('.toggle-comment-btn');
        toggleCommentButton.addEventListener('click', function () {
            const commentSection = this.closest('.card-body').querySelector('.comment-section');
            commentSection.style.display = commentSection.style.display === 'none' || commentSection.style.display === '' ? 'block' : 'none';
        });
    
        const commentButton = nuevaPublicacion.querySelector('.comment-btn');
        commentButton.addEventListener('click', async function () {
            const postId = this.getAttribute('data-post-id');
            const commentInput = this.previousElementSibling;
            const commentText = commentInput.value.trim();
    
            if (commentText) {
                try {
                    const formData = new FormData();
                    formData.append('Contenido', commentText);
                    formData.append('Publicacion', postId);
                    formData.append('user_id', userID);
    
                    const response = await fetch('../comentarios', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData,
                    });
    
                    if (response.ok) {
                        const newComment = await response.json();
                        renderComment(newComment, this.closest('.card-body').querySelector('.comments-list'));
                        commentInput.value = '';
                        this.closest('.comment-section').style.display = 'none';
                    } else {
                        console.error('Error al publicar el comentario:', response.statusText);
                        showErrorModal('Hubo un problema al publicar tu comentario. Inténtalo de nuevo.');
                    }
                } catch (error) {
                    console.error('Error en la solicitud de creación de comentario:', error);
                    showErrorModal('Hubo un problema en la solicitud de creación de comentarios. Inténtalo de nuevo.');
                }
            }
        });
    }
    
    async function handleReaction(postId, reactionType) {
        try {
            // Define la URL según el tipo de reacción
            const url = reactionType === 'like' ? `../publicaciones/${postId}/like` : `../publicaciones/${postId}/dislike`;
    
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Cambiado a JSON para la compatibilidad
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
    
            if (response.ok) {
                const result = await response.json();
                if(result.success){
                    // Suponiendo que la respuesta contiene los nuevos contadores de reacciones
                    updateReactionCount(postId, result.likes, result.dislikes);
                }
                else showErrorModal(result.message);
            } else {
                console.error('Error al registrar la reacción:', response.statusText);
                showErrorModal('Hubo un problema al registrar la reacción. Inténtalo de nuevo.');
            }
        } catch (error) {
            console.error('Error en la solicitud de reacción:', error);
            showErrorModal('Hubo un problema en la solicitud de reacción. Inténtalo de nuevo.');
        }
    }
    
    
    function updateReactionCount(postId, likes, dislikes) {
        console.log(postId, likes, dislikes);
        const postElement = document.querySelector(`[data-post-id="${postId}"]`).closest('.card-body');
        postElement.querySelector('.like-count').textContent = likes;
        postElement.querySelector('.dislike-count').textContent = dislikes;
    }
    

    function renderComment(comment, commentList) {
        console.log(comment);
        const newComment = document.createElement('li');
        newComment.classList.add('list-group-item');
        console.log(comment.Fecha);
        const formattedDate = comment.Fecha.toLocaleString();
        const currentUserName = comment.usuario.Nombre + " " + comment.usuario.Apellidos;
        const currentUserPhoto = comment.usuario.Fotografia ? `/proyectofinal-linkedbiz/storage/app/app/${comment.usuario.Fotografia}` : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';

        newComment.innerHTML = `
            <div class="d-flex align-items-center">
                <img src="${currentUserPhoto}" class="rounded-circle me-3" alt="Foto de perfil" width="40" height="40">
                <div>
                    <h6 class="mb-0">${currentUserName}</h6>
                    <small class="text-muted">${formattedDate}</small>
                    <p class="mt-2">${comment.Contenido}</p>
                </div>
            </div>
        `;

        commentList.appendChild(newComment);
    }

    function renderFriend(friend) {
        const friendsContainer = document.getElementById('friendsList');
        const friendCard = document.createElement('div');
        console.log(friend.fotografia);
        const fotoSrc = friend.fotografia ? `/proyectofinal-linkedbiz/storage/app/app/${friend.fotografia}` : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';
        friendCard.classList.add('card', 'mb-2', 'friend-card');
        friendCard.innerHTML = `
            <div class="card-body d-flex align-items-center">
                <img src="${fotoSrc}" alt="Foto de ${friend.username}" class="rounded-circle me-3" style="width: 40px; height: 40px;">

                <div>
                    <h6 class="card-title mb-0"><a href="${friend.perfil_url}">${friend.username}</a></h6>
                    <p class="card-text">${friend.nombre} ${friend.apellido}</p>
                </div>
            </div>
        `;
        
        friendsContainer.querySelector('.list-group').appendChild(friendCard);
    }
    

    function updateProfileInfo(user) {
        document.getElementById('userName').textContent = "@" + user.Usuario;
        document.getElementById('userCallName').textContent = user.Nombre + " " + user.Apellidos;
        document.getElementById('userBiography').textContent = user.Biografia;
        document.getElementById('userEmail').textContent = user.Email;
        document.getElementById('userBirthDate').textContent = user.FechaNacimiento;
        document.getElementById('userCompany').textContent = user.Empresa;

        const userPhotoElement = document.getElementById('userPhoto');
        if (user.Fotografia) {
            userPhotoElement.setAttribute('src', `/proyectofinal-linkedbiz/storage/app/app/${user.Fotografia}`);
        } else {
            userPhotoElement.setAttribute('src', 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg');
        }
    }
});