$(document).ready(function() {
    // Función para manejar el envío del formulario de login
    $('#login-form').submit(function(e) {
        e.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

        let dni = $('#dni').val().trim();  // Capturar el valor del campo 'dni'
        let contrasena = $('#contrasena').val().trim();  // Capturar el valor del campo 'contrasena'

        // Validación para campos vacíos
        if (dni === '' || contrasena === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Por favor, completa todos los campos.',
                showConfirmButton: true,
            });
            return;  // Detener si faltan campos por llenar
        }

        // Enviar los datos al backend usando AJAX
        $.post('../controlador/LoginController.php', {funcion: 'login', dni: dni, contrasena: contrasena}, function(response) {
            let resultado = JSON.parse(response); // Convertir la respuesta a JSON

            if (resultado.status === 'success') {
                // Login exitoso, redirigir al usuario a la página principal
                Swal.fire({
                    icon: 'success',
                    title: 'Login exitoso',
                    text: 'Bienvenido, redirigiendo...',
                    showConfirmButton: false,
                    timer: 1500  // Duración del mensaje
                }).then(() => {
                    window.location.href = "pagina_principal.php";  // Redirigir
                });
            } else if (resultado.status === 'error') {
                // Error en el login, mostrar alerta con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Login incorrecto',
                    text: resultado.message,  // Mostrar el mensaje del backend
                    showConfirmButton: true,  // Botón para cerrar la alerta
                });
            }
        });
    });
});
