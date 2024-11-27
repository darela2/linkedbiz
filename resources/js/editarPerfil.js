document.addEventListener('DOMContentLoaded',function(){
    // Obteniendo el token CSRF desde el meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.getElementById('editProfileForm').addEventListener('submit', async function (event) {
        event.preventDefault();
    
        const formData = new FormData(this);
        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    
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
                alert(data.message);
    
                // Actualizar los datos del perfil en la vista
                document.getElementById('userName').textContent = formData.get('Nombre') + ' ' + formData.get('Apellidos');
                document.getElementById('userBiography').textContent = formData.get('Biografia');
                document.getElementById('userEmail').textContent = formData.get('Email');
                document.getElementById('userBirthDate').textContent = formData.get('FechaNacimiento');
    
                // Actualizar el nombre de la empresa en la vista si es necesario
                const empresaSelect = document.getElementById('editEmpresa');
                const empresaNombre = empresaSelect.options[empresaSelect.selectedIndex].text;
                document.getElementById('userCompany').textContent = empresaNombre;
    
                // Si hay una nueva foto, actualizar la imagen de perfil
                if (formData.get('Fotografia').name) {
                    const newPhotoUrl = URL.createObjectURL(formData.get('Fotografia'));
                    document.getElementById('userPhoto').src = newPhotoUrl;
                }
    
                $('#editProfileModal').modal('hide');
            } else {
                const errorData = response.json();
                alert('Error al actualizar el perfil: ' + errorData.message);
            }
        } catch (error) {
            console.error('Error al realizar la solicitud:', error);
        }
    });
})