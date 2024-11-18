$(document).ready(function () {
    var funcion;
    var edit = false;

    select_laboratorio();
    select_tipo();
    select_presentacion();

    // Obtener el proveedor desde PHP y configurarlo en el frontend
    const currentProviderId = window.currentProviderId;
    const currentProviderName = window.currentProviderName;

    //----------------------------------------------------------
    // Construcción DataTable
    //----------------------------------------------------------
    var tabla = $('#tabla').DataTable({
        dom:
            "<'row'<'col-sm-12 col-md-6'Bl><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

        "lengthMenu": [[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
        "iDisplayLength": 5,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
        "ajax": {
            "url": "../controlador/ProductoController.php",
            "method": 'POST',
            "data": { funcion: 'listarPorProveedor' },
            "dataSrc": ""
        },
        "columns": [
            { "data": "id", "title": "ID" },
            { "data": "nombre", "title": "Nombre" },
            { "data": "concentracion", "title": "Concentración" },
            { "data": "precio", "title": "Precio" },
            { "data": "laboratorio", "title": "Laboratorio" },
            { "data": "tipo", "title": "Tipo" },
            { "data": "presentacion", "title": "Presentación" },
            {
                "data": "avatar",
                "title": "Imagen",
                "render": function (data) {
                    return `<img src="../assets/img/prod/${data}" class="img-fluid" width="50" height="50">`;
                }
            },
            {
                "defaultContent": "<div class='btn-group'>" +
                    "<button class='avatar btn bg-teal btn-sm' title='Cambiar imagen' data-toggle='modal' data-target='#cambiaravatar'><i class='fas fa-image'></i></button>" +
                    "<button class='editar btn btn-sm btn-primary' title='Editar' data-toggle='modal' data-target='#crear'><i class='fas fa-pencil-alt'></i></button>" +
                    "<button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>",
                "title": "Acciones"
            }
        ],
        "columnDefs": [
            {
                "className": "text-center",
                "targets": [0, 1],
                "visible": true,
                "searchable": true
            }
        ],
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    tabla.buttons().container().appendTo($('.col-md-6:eq(0)', tabla.table().container()));

    //----------------------------------------------------------
    // Función que evalúa click en CAMBIAR LOGO y obtiene el id
    //----------------------------------------------------------
    $(document).on('click', '.avatar', function () {
        let data;
        if (tabla.row(this).child.isShown()) {
            data = tabla.row(this).data();
        } else {
            data = tabla.row($(this).parents("tr")).data();
        }

        const id = data.id; // Captura el ID del producto

        // Actualizar campos del modal de cambio de logo
        $('#id_avatar').val(id);
        $('#nombre_avatar').html(data.nombre);
        $('#avataractual').attr('src', `../assets/img/prod/${data.avatar}`);
        $('#funcion').val('cambiar_logo'); // Aseguramos que la función se establece correctamente
    });

    //----------------------------------------------------------
    // Click en submit Cambiar logo
    //---------------------------------------------------------
    $('#form-logo').submit(e => {
        e.preventDefault();

        let formData = new FormData($('#form-logo')[0]); // Crear el objeto FormData con el formulario
        $.ajax({
            url: '../controlador/ProductoController.php', // URL del backend
            type: 'POST', // Método HTTP
            data: formData, // Datos del formulario
            cache: false,
            processData: false, // Importante para enviar FormData
            contentType: false // Importante para enviar FormData
        }).done(function (response) {
            console.log(response); // Depuración en consola
            try {
                const json = JSON.parse(response); // Parsear respuesta JSON
                if (json.alert === 'editalogo') {
                    $('#avataractual').attr('src', json.ruta); // Actualiza la imagen en el modal
                    Swal.fire('Éxito', 'Imagen cambiada correctamente.', 'success');
                    tabla.ajax.reload(null, false); // Recarga la tabla
                } else {
                    Swal.fire('Error', 'Formato de imagen incorrecto o fallo en el cambio.', 'error');
                }
            } catch (error) {
                console.error('Error al procesar la respuesta del servidor:', error);
                Swal.fire('Error', 'Hubo un problema con el cambio de imagen.', 'error');
            }
        });
    });

    //----------------------------------------------------------
    // Función que evalúa click en ELIMINAR
    //----------------------------------------------------------
    $(document).on('click', '.eliminar', function () {
        if (tabla.row(this).child.isShown()) {
            var data = tabla.row(this).data();
        } else {
            var data = tabla.row($(this).parents("tr")).data();
        }

        const id = data.id; // Captura el ID del producto
        const nombre = data.nombre;

        Swal.fire({
            title: `¿Desea eliminar ${nombre}?`,
            text: "Esto no se podrá revertir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controlador/ProductoController.php', { id, funcion: 'eliminar' }, (response) => {
                    if (response === 'eliminado') {
                        Swal.fire('Eliminado', `${nombre} fue eliminado correctamente.`, 'success');
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', `No se pudo eliminar ${nombre}.`, 'error');
                    }
                });
            }
        });
    });

    //----------------------------------------------------------
    // Función para preparar el formulario de creación
    //----------------------------------------------------------
    $(document).on('click', '.btn-crear', function () {
        $('#form-crear').trigger('reset');
        $('#tit_ven').html('Crear producto');
        edit = false;
    
        // Configurar el ID del producto automáticamente desde el servidor
        $.post('../controlador/ProductoController.php', { funcion: 'obtenerNuevoId' }, (response) => {
            const nuevoId = JSON.parse(response).nuevoId;
            $('#id_producto').val(nuevoId).prop('readonly', true); // Autogenerado y solo lectura
        });
    
        // Fijar el proveedor actual
        $('#proveedor').html(`<option value="${currentProviderId}" selected>${currentProviderName}</option>`);
        $('#proveedor').prop('disabled', true); // Bloquear selección
    });
    
    //----------------------------------------------------------
    // Función para preparar el formulario de edición
    //----------------------------------------------------------
    $(document).on('click', '.editar', function () {
        edit = true;
        $('#tit_ven').html('Editar producto');

        var data = tabla.row($(this).parents("tr")).data();
        const id = data.id; // Captura el ID del producto
        buscar(id); // Carga los datos en el formulario

        // Fijar el proveedor actual
        $('#proveedor').html(`<option value="${currentProviderId}" selected>${currentProviderName}</option>`);
        $('#proveedor').prop('disabled', true); // Bloquear selección
    });

    //-------------------------------------------------------------
    // Buscar producto
    //-------------------------------------------------------------
    function buscar(dato) {
        funcion = 'buscar';
        $.post('../controlador/ProductoController.php', { dato, funcion }, (response) => {
            const respuesta = JSON.parse(response);
            $('#id_producto').val(respuesta.id).prop('readonly', true); // Solo lectura
            $('#nombre').val(respuesta.nombre);
            $('#concentracion').val(respuesta.concentracion);
            $('#adicional').val(respuesta.adicional);
            $('#precio').val(respuesta.precio);
            $('#laboratorio').val(respuesta.laboratorio).trigger('change');
            $('#tipo').val(respuesta.tipo).trigger('change');
            $('#presentacion').val(respuesta.presentacion).trigger('change');
        });
    }

    //----------------------------------------------------------
    // Crear o editar producto
    //----------------------------------------------------------
    $('#form-crear').submit(function (e) {
        e.preventDefault();
    
        let id = $('#id_producto').val();
        let nombre = $('#nombre').val();
        let concentracion = $('#concentracion').val();
        let adicional = $('#adicional').val();
        let precio = $('#precio').val();
        let laboratorio = $('#laboratorio').val();
        let tipo = $('#tipo').val();
        let presentacion = $('#presentacion').val();
        let avatar = 'default.png';
        let proveedor = $('#proveedor').val(); // Toma el ID del proveedor desde el campo oculto
    
        funcion = edit ? 'editar' : 'crear';
        $.post('../controlador/ProductoController.php', {
            id, nombre, concentracion, adicional, precio,
            laboratorio, tipo, presentacion, avatar, proveedor, funcion
        }, (response) => {
            console.log(response);
            if (response == 'add' || response == 'update') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
    
                Toast.fire({
                    icon: 'success',
                    title: nombre,
                    html: 'Guardado con éxito'
                });
    
                $('#crear').modal('hide');
                tabla.ajax.reload(null, false);
            } else {
                $('#noadd').hide('slow');
                $('#noadd').show(1000);
                $('#noadd').hide(2000);
            }
        });
    });
    

    //----------------------------------------------------------
    // Selección de combos (laboratorio, tipo, presentación)
    //----------------------------------------------------------
    function select_laboratorio() {
        funcion = "seleccionar";
        $.post('../controlador/LaboratorioController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.id}">${registro.nombre}</option>`;
            });
            $('#laboratorio').html(template);
        });
    }

    function select_tipo() {
        funcion = "seleccionar";
        $.post('../controlador/TipoProductoController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.id}">${registro.nombre}</option>`;
            });
            $('#tipo').html(template);
        });
    }

    function select_presentacion() {
        funcion = "seleccionar";
        $.post('../controlador/PresentacionController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.id}">${registro.nombre}</option>`;
            });
            $('#presentacion').html(template);
        });
    }
});
