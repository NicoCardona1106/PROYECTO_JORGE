$(document).ready(function() {
    var funcion;
    var datatable;
    var edit = false; // Variable para determinar si estamos editando

    // Listar proveedores
    function listar() {
        funcion = 'listar';
        datatable = $('#tabla').DataTable({
            "ajax": {
                "url": "../controlador/ProveedorController.php",
                "method": "POST",
                "data": {funcion: funcion}
            },
            "columns": [
                { "data": "id_proveedor" },
                { "data": "nombre" },
                { "data": "apellido" }, // Columna para apellido
                { "data": "dni" }, // Columna para dni
                { "data": "edad" }, // Columna para edad
                { "data": "sexo" }, // Columna para sexo
                { "data": "correo" },
                { "data": "telefono" },
                { "data": "direccion" }, // Columna para dirección
                { "data": "avatar"},
                {
                    "defaultContent": "<div class='btn-group'>" +
                      "<button class='avatar btn bg-teal btn-sm' title='Cambiar imagen' data-toggle='modal' data-target='#cambiaravatar'><i class='fas fa-image'></i></button>" +
                      "<button class='editar btn btn-sm btn-primary' style='background-color: #005d16; title='Editar' data-toggle='modal' data-target='#crear'><i class='fas fa-pencil-alt'></i></button>" +
                      "<button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>",
                    "title": "Acciones"
                  }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            }
        });
    }
    
    // Crear proveedor o editar
    $('#form-crear').submit(e => {
        e.preventDefault(); // Prevenir el envío por defecto del formulario

        let id_proveedor = $('#id').val(); // Campo id oculto para editar
        let nombre = $('#nombre').val();
        let apellido = $('#apellido').val(); // Campo adicional para apellido
        let dni = $('#dni').val(); // Campo adicional para dni
        let edad = $('#edad').val(); // Campo adicional para edad
        let sexo = $('#sexo').val(); // Campo adicional para sexo
        let correo = $('#correo').val();
        let telefono = $('#telefono').val();
        let direccion = $('#direccion').val(); // Campo adicional para dirección
        let avatar = $('#avatar').val(); // Campo adicional para avatar

        funcion = edit ? 'editar' : 'crear'; // Usar operador ternario para definir la función
        
        $.post('../controlador/ProveedorController.php', {
            funcion, id_proveedor, nombre, apellido, dni, edad, sexo, correo, telefono, direccion, avatar
        }, (response) => {
            if (response == 'add') {
                $('#add').show(1000).hide(2000);
            } else if (response == 'edit') {
                $('#edit').show(1000).hide(2000);
            } else {
                $('#noadd').show(1000).hide(2000);
            }
            $('#form-crear').trigger('reset');
            $('#crear').modal('hide');
            datatable.ajax.reload();
            edit = false; // Reseteamos a crear después de editar
        });
        
    });

    // Función para capturar el evento de editar
    $(document).on('click', '.editar', function() {
        let data = datatable.row($(this).parents('tr')).data(); 
        $('#id').val(data.id_proveedor); // Llenar el campo id_proveedor
        $('#nombre').val(data.nombre);
        $('#apellido').val(data.apellido);
        $('#dni').val(data.dni);
        $('#edad').val(data.edad);
        $('#sexo').val(data.sexo);
        $('#correo').val(data.correo);
        $('#telefono').val(data.telefono);
        $('#direccion').val(data.direccion);
        $('#avatar').val(data.avatar);
        edit = true; // Establecer modo de edición
        $('#tit_ven').text('Editar Proveedor'); // Cambiar el título del modal
    });
    

    // Evento para restablecer el formulario al cerrar el modal
    $('#crear').on('hidden.bs.modal', function () {
        $('#form-crear').trigger('reset'); // Resetea el formulario
        edit = false; // Resetea el estado a crear
        $('#tit_ven').text('Crear Proveedor'); // Cambia el título de nuevo
    });

    //----------------------------------------------------------
    // Función que evalúa click en ELIMINAR y obtiene el id
    //----------------------------------------------------------
    $(document).on('click', '.eliminar', function () {          
        let data;
        if (datatable.row(this).child.isShown()) {
            // Si el detalle de la fila está visible
            data = datatable.row(this).data();
        } else {
            // Si no, toma los datos de la fila padre
            data = datatable.row($(this).parents("tr")).data();
        }

        const id = data.id_proveedor; // Captura el ID del proveedor
        const nombre = data.nombre; // Captura el nombre del proveedor

        // Mostrar alerta de confirmación
        Swal.fire({
            title: `¿Está seguro de eliminar a ${nombre}?`,
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar solicitud al backend para eliminar
                $.post('../controlador/ProveedorController.php', { funcion: 'eliminar', id: id }, (response) => {
                    if (response.trim() === 'eliminado') {
                        Swal.fire(
                            '¡Eliminado!',
                            `El proveedor ${nombre} ha sido eliminado.`,
                            'success'
                        );
                        datatable.ajax.reload(null, false); // Recargar tabla
                    } else {
                        Swal.fire(
                            'Error',
                            `No se pudo eliminar a ${nombre}.`,
                            'error'
                        );
                    }
                });
            }
        });
    });


    //----------------------------------------------------------
    // Función que evalúa click en CAMBIAR LOGO y obtiene el id
    //----------------------------------------------------------
    $(document).on('click', '.avatar', function () {
        let data;
        if (datatable.row(this).child.isShown()) {
            // Si el detalle de la fila está visible, obtén los datos directamente
            data = datatable.row(this).data();
        } else {
            // Si no, obtén los datos de la fila superior
            data = datatable.row($(this).parents("tr")).data();
        }

        // Captura el ID del proveedor
        const id = data.id_proveedor;

        // Actualizar campos del modal de cambio de logo
        $('#id_avatar').val(id); // Asigna el ID del proveedor al campo oculto
        $('#nombre_avatar').html(data.nombre); // Muestra el nombre del proveedor en el modal
        $('#avataractual').attr('src', `../assets/img/proveedor/${data.avatar}`); // Ruta actual del avatar
        $('#funcion').val('cambiar_logo'); // Define la función para el controlador
    });

    //----------------------------------------------------------
    // Click en submit Cambiar logo
    //---------------------------------------------------------
    $('#form-logo').submit(e => {
        e.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData($('#form-logo')[0]); // Crear objeto FormData
        $.ajax({
            url: '../controlador/ProveedorController.php', // URL del controlador
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function (response) {
            console.log(response); // Depuración

            const json = JSON.parse(response);
            if (json.alert === 'editalogo') {
                // Actualizar la imagen en el modal y en la tabla
                $('#avataractual').attr('src', `../assets/img/proveedor/${json.ruta.split('/').pop()}`);
                $('#updatelogo').hide('slow').show(1000).hide(2000);

                // Opcional: recargar la tabla para reflejar los cambios
                datatable.ajax.reload(null, false);
            } else {
                $('#noupdatelogo').hide('slow').show(1000).hide(2000);
            }
        });
    });

        listar(); // Llamada a la función para listar proveedores
});
