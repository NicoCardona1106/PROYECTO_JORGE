$(document).ready(function() {
    var funcion;
    var datatable;
    var edit = false; // Variable para determinar si estamos editando

    // Crear cliente o editar
    $('#form-crear').submit(e => {
        let id = $('#id').val(); // Campo id oculto para editar
        let nombre = $('#nombre').val();
        let apellido = $('#apellido').val(); // Campo adicional para apellido
        let dni = $('#dni').val(); // Campo adicional para dni
        let edad = $('#edad').val(); // Campo adicional para edad
        let sexo = $('#sexo').val(); // Campo adicional para sexo
        let email = $('#email').val();
        let telefono = $('#telefono').val();
        let direccion = $('#direccion').val(); // Campo adicional para dirección
        let avatar = $('#avatar').val(); // Campo adicional para avatar

        funcion = edit ? 'editar' : 'crear'; // Usar operador ternario para definir la función

        $.post('../controlador/ClienteController.php', {funcion, id, nombre, apellido, dni, edad, sexo, email, telefono, direccion, avatar}, (response) => {
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
        e.preventDefault();
    });

    // Función para capturar el evento de editar
    $(document).on('click', '.editar', function() {
        let data = datatable.row($(this).parents('tr')).data();
        $('#id').val(data.id); // Llenar el campo id
        $('#nombre').val(data.nombre);
        $('#apellido').val(data.apellido);
        $('#dni').val(data.dni);
        $('#edad').val(data.edad);
        $('#sexo').val(data.sexo);
        $('#email').val(data.email);
        $('#telefono').val(data.telefono);
        $('#direccion').val(data.direccion);
        $('#avatar').val(data.avatar);
        edit = true; // Establecer modo de edición
        $('#tit_ven').text('Editar Cliente');
    });

    // Evento para restablecer el formulario al cerrar el modal
    $('#crear').on('hidden.bs.modal', function () {
        $('#form-crear').trigger('reset'); // Resetea el formulario
        edit = false; // Resetea el estado a crear
        $('#tit_ven').text('Crear Cliente'); // Cambia el título de nuevo
    });

    // Función para eliminar clientes
    $(document).on('click', '.borrar', function() {
        let data = datatable.row($(this).parents('tr')).data();
        let id = data.id;

        if (confirm("¿Estás seguro de que quieres eliminar este cliente?")) {
            funcion = 'eliminar';
            $.post('../controlador/ClienteController.php', {funcion, id}, (response) => {
                if (response == 'deleted') {
                    $('#delete').show(1000).hide(2000);
                    datatable.ajax.reload();
                }
            });
        }
    });

    // Listar clientes
    function listar() {
        funcion = 'listar';
        datatable = $('#tabla').DataTable({
            "ajax": {
                "url": "../controlador/ClienteController.php",
                "method": "POST",
                "data": {funcion: funcion}
            },
            "columns": [
                { "data": "id" },
                { "data": "nombre" },
                { "data": "apellido" },
                { "data": "dni" },
                { "data": "edad" },
                { "data": "sexo" },
                { "data": "email" },
                { "data": "telefono" },
                { "data": "direccion" },
                { "data": "avatar" },
                { "defaultContent": `<button class="editar btn btn-success" type="button" data-toggle="modal" data-target="#crear"><i class="fas fa-edit"></i></button>
                                     <button class="borrar btn btn-danger"><i class="fas fa-trash-alt"></i></button>` }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            }
        });
    }

    listar();
});
