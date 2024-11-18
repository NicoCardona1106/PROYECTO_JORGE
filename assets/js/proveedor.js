$(document).ready(function() {
    var funcion;
    var datatable;
    var edit = false; // Variable para determinar si estamos editando

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

    // Función para eliminar proveedores
    $(document).on('click', '.borrar', function() {
        let data = datatable.row($(this).parents('tr')).data();
        let id_proveedor = data.id_proveedor;

        if (confirm("¿Estás seguro de que quieres eliminar este proveedor?")) { // Confirmación antes de eliminar
            funcion = 'eliminar';
            $.post('../controlador/ProveedorController.php', {funcion, id_proveedor}, (response) => {
                if (response == 'deleted') {
                    $('#delete').show(1000).hide(2000); // Mostrar alerta de eliminación
                    datatable.ajax.reload(); // Recargar la tabla
                }
            });
        }
    });

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
                { "data": "avatar" }, // Columna para avatar
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

    //----------------------------------------------------------
    // Funcion que evalua click en CAMBIAR LOGO y obtiene el id
    //----------------------------------------------------------
    $(document).on('click','.avatar',function(){
        if(tabla.row(this).child.isShown())
            var data = tabla.row(this).data();
        else
            var data = tabla.row($(this).parents("tr")).data();
        
        const id = data.id; //capturo el ID	            
        //Cargo los objetos ocultos obtenidos con javascript y enviarlos al controlador
        buscar(id);
        funcion = 'cambiar_logo';
        $('#funcion').val(funcion);
    });
    
    //----------------------------------------------------------
    // Click en submit Cambiar logo
    //---------------------------------------------------------
    $('#form-logo').submit(e=>{
        let formData = new FormData($('#form-logo')[0]);
        $.ajax({
            url:'../controlador/ProveedorController.php',
            type:'POST',
            data:formData,
            cache:false,
            processData:false,
            contentType:false
        }).done(function(response){

            console.log(response);
            const json= JSON.parse(response);
            if(json.alert == 'editalogo'){
                $('#avataractual').attr('src',json.ruta);
                $('#updatelogo').hide('slow');
                $('#updatelogo').show(1000);
                $('#updatelogo').hide(2000);
                //$('#form-logo').trigger('reset');
                buscar_todos();
            }
            else{
                $('#noupdatelogo').hide('slow');
                $('#noupdatelogo').show(1000);
                $('#noupdatelogo').hide(2000);
                //$('#form-logo').trigger('reset');
            }
        });
        e.preventDefault();
    });
    
    listar(); // Llamada a la función para listar proveedores
});
