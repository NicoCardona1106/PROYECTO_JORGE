$(document).ready(function () {
    var funcion;
    var datatable;
    var edit = false; // Variable para determinar si estamos editando

    // Listar clientes
    function listar() {
        funcion = 'listar';
        datatable = $('#tabla').DataTable({
            ajax: {
                url: "../controlador/ClienteController.php",
                method: "POST",
                data: { funcion: funcion }
            },
            columns: [
                { data: "id" },
                { data: "nombre" },
                { data: "apellido" },
                { data: "dni" },
                { data: "edad" },
                { data: "sexo" },
                { data: "email" },
                { data: "telefono" },
                { data: "direccion" },
                {
                    data: "avatar",
                    render: function (data) {
                        return `<img src="../assets/img/cliente/${data}" class="img-fluid rounded-circle" width="50" height="50">`;
                    },
                    title: "Avatar"
                },
                {
                    defaultContent: `<div class='btn-group'>
                        <button class='avatar btn bg-teal btn-sm' title='Cambiar imagen' data-toggle='modal' data-target='#cambiaravatar'><i class='fas fa-image'></i></button>
                        <button class='editar btn btn-sm btn-primary' style='background-color: #005d16;' title='Editar' data-toggle='modal' data-target='#crear'><i class='fas fa-pencil-alt'></i></button>
                        <button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>
                    </div>`,
                    title: "Acciones"
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            }
        });
    }

    // Crear cliente o editar
    $('#form-crear').submit(function (e) {
        e.preventDefault(); // Prevenir el envío por defecto del formulario

        let id = $('#id').val(); // Campo id oculto para editar
        let nombre = $('#nombre').val();
        let apellido = $('#apellido').val();
        let dni = $('#dni').val();
        let edad = $('#edad').val();
        let sexo = $('#sexo').val();
        let email = $('#email').val();
        let telefono = $('#telefono').val();
        let direccion = $('#direccion').val();
        let avatar = $('#avatar').val();

        funcion = edit ? 'editar' : 'crear';

        $.post('../controlador/ClienteController.php', { funcion, id, nombre, apellido, dni, edad, sexo, email, telefono, direccion, avatar }, function (response) {
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
            edit = false;
        });
    });

    // Capturar el evento de editar
    $(document).on('click', '.editar', function () {
        let data = datatable.row($(this).parents('tr')).data();
        $('#id').val(data.id);
        $('#nombre').val(data.nombre);
        $('#apellido').val(data.apellido);
        $('#dni').val(data.dni);
        $('#edad').val(data.edad);
        $('#sexo').val(data.sexo);
        $('#email').val(data.email);
        $('#telefono').val(data.telefono);
        $('#direccion').val(data.direccion);
        $('#avatar').val(data.avatar);
        edit = true;
        $('#tit_ven').text('Editar Cliente');
    });

    // Restablecer el formulario al cerrar el modal
    $('#crear').on('hidden.bs.modal', function () {
        $('#form-crear').trigger('reset');
        edit = false;
        $('#tit_ven').text('Crear Cliente');
    });

    // Eliminar cliente
    $(document).on('click', '.eliminar', function () {
        let data;
        if (datatable.row(this).child.isShown()) {
            data = datatable.row(this).data();
        } else {
            data = datatable.row($(this).parents('tr')).data();
        }
        const id = data.id;
        const nombre = data.nombre;

        Swal.fire({
            title: `¿Deseas eliminar a ${nombre}?`,
            text: "¡Esta acción no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                funcion = 'eliminar';
                $.post('../controlador/ClienteController.php', { id, funcion }, function (response) {
                    if (response === 'eliminado') {
                        Swal.fire('Eliminado!', `${nombre} fue eliminado correctamente.`, 'success');
                        datatable.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error!', 'No se pudo eliminar el cliente.', 'error');
                    }
                });
            }
        });
    });

    // Cambiar avatar
    $(document).on('click', '.avatar', function () {
        let data;
        if (datatable.row(this).child.isShown()) {
            data = datatable.row(this).data();
        } else {
            data = datatable.row($(this).parents('tr')).data();
        }

        const id = data.id;
        $('#id_avatar').val(id);
        $('#nombre_avatar').html(data.nombre);
        $('#avataractual').attr('src', `../assets/img/cliente/${data.avatar}`);
        $('#funcion').val('cambiar_logo');
    });

    // Cambiar logo al enviar el formulario
    $('#form-logo').submit(function (e) {
        e.preventDefault();
        let formData = new FormData($('#form-logo')[0]);
        $.ajax({
            url: '../controlador/ClienteController.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function (response) {
            console.log(response);

            const json = JSON.parse(response);
            if (json.alert === 'editalogo') {
                $('#avataractual').attr('src', `../assets/img/cliente/${json.ruta.split('/').pop()}`);
                $('#updatelogo').hide('slow').show(1000).hide(2000);
                datatable.ajax.reload(null, false);
            } else {
                $('#noupdatelogo').hide('slow').show(1000).hide(2000);
            }
        });
    });

    listar();
});
