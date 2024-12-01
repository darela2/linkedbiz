document.addEventListener('DOMContentLoaded', function () {

    loadCompanies();
    loadStatistics();

    let deleteType = ''; // Tipo de elemento a eliminar (empresa, usuario, post)
    let deleteId = ''; // ID del elemento a eliminar

    const confirmModal = document.getElementById('confirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    // Abre el modal de confirmación
    function openConfirmModal(type, id, name) {
        deleteType = type;
        deleteId = id;

        const confirmMessage = document.getElementById('confirmMessage');
        confirmMessage.textContent = `¿Estás seguro de que deseas eliminar ${type === 'company' ? 'la empresa' : type === 'user' ? 'el usuario' : 'la publicación'} ${name}?`;
    
        confirmModal.style.display = 'flex';
    }

    // Cierra el modal de confirmación
    function closeConfirmModal() {
        confirmModal.style.display = 'none';
        deleteType = '';
        deleteId = '';
    }

    // Listener de confirmación de eliminación
    confirmDeleteBtn.addEventListener('click', function () {
        if (deleteType && deleteId) {
            if (deleteType === 'company') {
                deleteCompany(deleteId);
            } else if (deleteType === 'user') {
                deleteUser(deleteId);
            } else if (deleteType === 'post') {
                deletePost(deleteId);
            }
        }
        closeConfirmModal();
    });

    // Listener de cancelación
    cancelDeleteBtn.addEventListener('click', closeConfirmModal);

    // Ejemplo de integración con botones
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-company-btn')) {
            const companyId = event.target.dataset.id;
            const companyName = event.target.closest('tr').querySelector('td:nth-child(2)').textContent; // Supongamos que el nombre está en la 2da columna
            openConfirmModal('company', companyId, companyName);
        } else if (event.target.classList.contains('delete-user-btn')) {
            const userId = event.target.dataset.id;
            const userName = event.target.closest('tr').querySelector('td:nth-child(4)').textContent; // Nombre de usuario
            openConfirmModal('user', userId, userName);
        } else if (event.target.classList.contains('delete-post-btn')) {
            const postId = event.target.dataset.id;
            openConfirmModal('post', postId, 'esta publicación'); // Ejemplo genérico para publicaciones
        }
    });

    // Función para cargar las empresas y mostrarlas en la tabla
    function loadCompanies() {
        fetch('empresas')
            .then(response => response.json())
            .then(data => {
                const companiesTableBody = document.getElementById('companiesTableBody');
                companiesTableBody.innerHTML = ''; // Limpiar contenido previo
                data.forEach(company => {
                    console.log(company);
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${company.CIF}</td>
                        <td>${company.Nombre}</td>
                        <td>${company.Direccion ? company.Direccion : 'Sin datos'}</td>
                        <td>${company.Localidad ? company.Localidad : 'Sin datos'}</td>
                        <td>${company.Email ? company.Email : 'Sin datos'}</td>
                        <td>${company.Telefono ? company.Telefono : 'Sin datos'}</td>
                        <td>
                            <button class="btn btn-info btn-sm view-users-btn" data-id="${company.CIF}">Ver Usuarios</button>
                            <button class="btn btn-danger btn-sm delete-company-btn" data-id="${company.CIF}">Eliminar</button>
                        </td>
                    `;
                    companiesTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al cargar empresas:', error));
    }

    // Agrega evento para el botón de ver usuarios
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('view-users-btn')) {
            const companyId = event.target.getAttribute('data-id');
            event.target.setAttribute("data-selected", "true");
            loadUsers(companyId);
        }
        else event.target.setAttribute("data-selected", "false");
    });

    // Carga usuarios de una empresa específica
    function loadUsers(companyId) {
        fetch(`empresas/${companyId}/usuarios`)
            .then(response => response.json())
            .then(users => {
                document.getElementById("companiesSection").style.display = 'none';
                document.getElementById("usersSection").style.display = 'block';

                document.getElementById("backToUsers").setAttribute("data-company-id", companyId);

                const usersTableBody = document.getElementById("usersTableBody");
                usersTableBody.innerHTML = '';

                users.forEach(user => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${user.Nombre}</td>
                        <td>${user.Apellidos}</td>
                        <td>${user.Biografia}</td>
                        <td>${user.Usuario}</td>
                        <td>${user.Contraseña}</td>
                        <td>${user.Email}</td>
                        <td>${user.FechaNacimiento}</td>
                        <td>${user.Fotografia ? `<img src="/proyectofinal-linkedbiz/storage/app/app/${user.Fotografia}" alt="Foto perfil" style="width: 50px; height: auto;">` : `<img src="https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg" alt="Foto perfil" style="width: 50px; height: auto;">`}</td>
                        <td>
                            <button class="btn btn-info btn-sm view-posts-btn" data-id="${user.ID}">Ver Publicaciones</button>
                            <button class="btn btn-danger btn-sm delete-user-btn" data-id="${user.ID}">Eliminar</button>
                        </td>
                    `;
                    usersTableBody.appendChild(row);
                });
            })
            .catch(error => console.error("Error al cargar usuarios:", error));
    }

    // Listener para boton "Ver Publicaciones" en la tabla de usuarios
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('view-posts-btn')) {
            const userId = event.target.getAttribute('data-id');
            event.target.setAttribute("data-selected", "true");
            loadPosts(userId);
        }
    });

    // Carga publicaciones de un usuario específico
    function loadPosts(userId) {
        fetch(`usuarios/${userId}/publicaciones`)
            .then(response => response.json())
            .then(posts => {
                document.getElementById("usersSection").style.display = 'none';
                document.getElementById("postsSection").style.display = 'block';

                const postsTableBody = document.getElementById("postsTableBody");
                postsTableBody.innerHTML = '';

                posts.forEach(post => {
                    console.log(post);
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${post.Contenido}</td>
                        <td>
                            ${post.Imagen ? `<img src="/proyectofinal-linkedbiz/storage/app/public/${post.Imagen}" alt="Imagen" style="width: 50px; height: auto;">` : 'No disponible'}
                        </td>
                        <td>${post.Fecha}</td>
                        <td>${post.likes_count}</td>
                        <td>${post.dislikes_count}</td>
                        <td>${post.comentarios_count}</td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-post-btn" data-id="${post.ID}">Eliminar</button>
                        </td>
                    `;
                    postsTableBody.appendChild(row);
                });
            })
            .catch(error => console.error("Error al cargar publicaciones:", error));
    }


    // Listener para el botón "Volver a Usuarios"
    document.getElementById("backToUsers").addEventListener("click", function () {
        document.getElementById("postsSection").style.display = 'none';
        document.getElementById("usersSection").style.display = 'block';
        const companyId = this.getAttribute("data-company-id");
        loadUsers(companyId);
    });

    // Listener para el botón "Volver a Empresas"
    document.getElementById("backToCompanies").addEventListener("click", function () {
        document.getElementById("usersSection").style.display = 'none';
        document.getElementById("companiesSection").style.display = 'block';
        loadCompanies();
    });

     // Función para eliminar empresas
    function deleteCompany(companyId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`empresas/delete/${companyId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Agregamos el token CSRF aquí
            }
        })
        .then(response => {
            if (response.ok) {
                loadCompanies(); // Recargar la lista de empresas
            } else {
                console.error('Error al eliminar empresa:', response.statusText);
            }
        })
        .catch(error => console.error('Error al eliminar empresa:', error));
    }

    // Función para eliminar usuarios
    function deleteUser(userId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`usuarios/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Agregamos el token CSRF aquí
            }
        })
        .then(response => {
            if (response.ok) {
                const selectedButton = document.querySelector('.view-users-btn[data-selected="true"]');
                if (selectedButton) {
                    const companyId = selectedButton.getAttribute('data-id');
                    loadUsers(companyId); // Recargar la lista de usuarios de la empresa
                }
            } else {
                console.error('Error al eliminar usuario:', response.statusText);
            }
        })
        .catch(error => console.error('Error al eliminar usuario:', error));
    }

    // Función para eliminar publicaciones
    function deletePost(postId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`publicaciones/${postId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Agregamos el token CSRF aquí
            }
        })
        .then(response => {
            if (response.ok) {
                const selectedButton = document.querySelector('.view-posts-btn[data-selected="true"]');
                if (selectedButton) {
                    const userId = selectedButton.getAttribute('data-id');
                    loadPosts(userId); // Recargar la lista de publicaciones del usuario
                }
            } else {
                console.error('Error al eliminar publicación:', response.statusText);
            }
        })
        .catch(error => console.error('Error al eliminar publicación:', error));
    }


    // Función para cargar las estadísticas de la aplicación
    function loadStatistics() {
        fetch('estadisticas')
            .then(response => response.json())
            .then(data => {
                document.getElementById('usersCount').textContent = "Número de usuarios: " + data.totalUsers;
                document.getElementById('companiesCount').textContent = "Número de empresas: " + data.totalCompanies;
                document.getElementById('postsCount').textContent = "Número de publicaciones: " + data.totalPosts;
            })
            .catch(error => console.error('Error al cargar estadísticas:', error));
    }

    
});
