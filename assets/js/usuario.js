$(document).ready(function() {
    // Evento para crear cliente
    $('#btn-crear-cliente').click(function() {
        let formData = new FormData($('#formCrearCuenta')[0]);
        formData.append('tipoUsuario', 'cliente');
        
        $.ajax({
            url: '../controlador/UsuarioController.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response === 'success') {
                    alert('Cuenta de cliente creada correctamente');
                    location.reload();
                } else if (response === 'exists') {
                    alert('El usuario ya existe');
                } else {
                    alert('Hubo un error al crear la cuenta');
                }
            },
            error: function() {
                alert('Hubo un problema con la conexión');
            }
        });
    });

    // Evento para crear proveedor
    $('#btn-crear-proveedor').click(function() {
        let formData = new FormData($('#formCrearCuenta')[0]);
        formData.append('tipoUsuario', 'proveedor');
        
        $.ajax({
            url: '../controlador/UsuarioController.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response === 'success') {
                    alert('Cuenta de proveedor creada correctamente');
                    location.reload();
                } else if (response === 'exists') {
                    alert('El usuario ya existe');
                } else {
                    alert('Hubo un error al crear la cuenta');
                }
            },
            error: function() {
                alert('Hubo un problema con la conexión');
            }
        });
    });
});