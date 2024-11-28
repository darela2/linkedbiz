
# **LinkedBiz**  
**Conecta. Colabora. Crece.**  

<p align="center">
  <img src="resources/views/logo-linkedbiz.png" width="200" alt="LinkedBiz Logo">
</p>  

<p align="center">
  <a href="https://github.com/darela2/linkedbiz/releases"><img src="https://img.shields.io/github/v/release/darela2/linkedbiz" alt="Release"></a>
  <a href="https://creativecommons.org/licenses/by/4.0/"><img src="https://img.shields.io/badge/License-CC%20BY%204.0-lightgrey.svg" alt="License"></a>
</p>

---

## **Acerca del Proyecto**  
**LinkedBiz** es una red social diseñada para conectar empresas, emprendedores y profesionales. El objetivo es facilitar el networking, la colaboración y el acceso a nuevas oportunidades laborales y de negocio, todo en un entorno digital inclusivo y seguro.

---

## **Características Principales**  
- **Búsqueda y Conexiones:** Encuentra usuarios, envía solicitudes de amistad y explora perfiles.  
- **Interacción Social:** Publicaciones con reacciones y comentarios.    
- **Accesibilidad y usabilidad:** Tema oscuro, tema claro y ajuste de texto.  

---

## **Tecnologías Utilizadas**  
- **Frontend:** Bootstrap, jQuery.  
- **Backend:** Laravel.  
- **Base de Datos:** MySQL.   

---

# Configuración del proyecto

## Requisitos previos

Asegúrate de que tu entorno cumple con los siguientes requisitos:

- PHP >= 8.0
- Composer
- Node.js y npm
- XAMPP: Configurado con Apache y MySQL (o un servidor de base de datos alternativo).

---

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/darela2/linkedbiz.git
   cd linkedbiz
   ```
2. Configura XAMPP

   - Inicia los módulos Apache y MySQL en el panel de control de XAMPP.
   - Asegúrate de que las configuraciones de puertos sean las predeterminadas o ajustadas según tu entorno.

3. Instala las dependencias de PHP con Composer:
   ```bash
   composer install
   ```

4. Instala las dependencias de JavaScript:
   ```bash
   npm install
   ```

5. Configura las variables de entorno:
   Copia el archivo `.env.example` y renómbralo a `.env`. Luego, actualiza las siguientes variables:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=linkedbiz
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Importa la base de datos:**
   - Abre phpMyAdmin desde el panel de XAMPP.
   - Importa el archivo de la base de datos (linkedbiz.sql) desde la carpeta `database` del proyecto.
   - Este archivo contiene las tablas y datos necesarios para que la aplicación funcione correctamente.

7. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

8. Migra las tablas de la base de datos:
   ```bash
   php artisan migrate
   ```

   > **Nota:** Este comando solo es necesario si quieres realizar ajustes adicionales en la estructura de la base de datos después de importar el archivo inicial.

9. Compila los recursos front-end:
   ```bash
   npm run dev
   ```

10. Inicia el servidor:
   ```bash
   php artisan serve
   ```

Ahora puedes acceder al proyecto en `http://localhost:8000`.

---

## **Próximos Pasos**  
- Recomendaciones personalizadas de conexiones.  
- Chats individuales y grupales entre usuarios.
- Soporte multilingüe.  
- Optimización para dispositivos móviles.  

---

## **Contribuciones**  
¡Toda ayuda es bienvenida! Abre un issue o envía un pull request para contribuir al desarrollo de LinkedBiz.  

---

# **Licencia**  
Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT). 
Este proyecto está bajo la licencia [Creative Commons Attribution 4.0 International](https://creativecommons.org/licenses/by-nc/4.0/).

Puedes compartir, copiar y adaptar el contenido del proyecto siempre y cuando otorgues la atribución correspondiente al autor.

---

# **Contacto**  
Si tienes preguntas o sugerencias, no dudes en contactarme en [davidredondo2002.d@gmail.com](mailto:davidredondo2002.dr@gmail.com).  
