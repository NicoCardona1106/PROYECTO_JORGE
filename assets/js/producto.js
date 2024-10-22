$(document).ready(function () {
    var funcion;
    var edit = false;

    select_laboratorio();
    select_tipo();
    select_presentacion();
    select_proveedor();
    

    //----------------------------------------------------------
    // Construccion DataTable
    //----------------------------------------------------------
    var tabla = $('#tabla').DataTable({  
        dom:
        "<'row'<'col-sm-12 col-md-6'Bl><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
       
        "lengthMenu":		[[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
        "iDisplayLength":	5,
        "responsive": true,
        "autoWidth": false,
        //Parametrizar lenguaje
        "language" : 
        {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
             },
             "sProcessing":"Procesando...",
        },
        "ajax":{            
            "url": "../controlador/ProductoController.php",
            "method": 'POST', //usamos el metodo POST
            "data":{funcion:'listar'}, //enviamos POST
            "dataSrc":""
        },
        
        "columns":[
            { "data": "id", "title":"ID"},
            { "data": "nombre", "title":"Nombre"},
            { "data": "concentracion", "title":"Concentracion"},
            { "data": "precio", "title":"Precio"},
            { "data": "laboratorio", "title":"Laboratorio"},
            { "data": "tipo", "title":"Tipo"},
            { "data": "presentacion", "title":"Presentacion"},
            { "data": "proveedor", "title":"Proveedor"},

            {
                "defaultContent": "<div class='btn-group'>" +
                  "<button class='avatar btn bg-teal btn-sm' title='Cambiar imagen' data-toggle='modal' data-target='#cambiaravatar'><i class='fas fa-image'></i></button>" +
                  "<button class='editar btn btn-sm btn-primary' style='background-color: #005d16; title='Editar' data-toggle='modal' data-target='#crear'><i class='fas fa-pencil-alt'></i></button>" +
                  "<button class='anadir_lote btn btn-sm' style='background-color: #007bff; color: #FFFFFF;' title='Añadir Lote' data-toggle='modal' data-target='#addLoteModal'><i class='far fa-plus-square' style='color: #FFFFFF;'></i></button>" +
                  "<button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>",
                "title": "Acciones"
              }
                      ],    
        //Configurar COLUMNAS ---- Centrado Acciones
        "columnDefs": [ 
            {   "className": "text-center",
                "targets": [0,1],
                "visible": true,
                "searchable": true
            }
        ],
        buttons: ["copy", "excel", "pdf", "print", "colvis"]    
    });

    tabla.buttons().container().appendTo($('.col-md-6:eq(0)', tabla.table().container()));  

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
            url:'../controlador/ProductoController.php',
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
    
    //----------------------------------------------------------
    // Funcion que evalua click en CREAR
    // Solo para limpiar el formulario
    //----------------------------------------------------------
    $(document).on('click','.btn-crear',(e)=>{
        $('#form-crear').trigger('reset');
        $('#tit_ven').html('Crear producto');
        edit = false;
    });

    //----------------------------------------------------------
    // Funcion que evalua click en EDITAR y obtiene el id
    // en DATATABLES responsives
    //----------------------------------------------------------
    $(document).on('click','.editar',function(){
        edit = true;
        $('#tit_ven').html('Editar producto');
         if(tabla.row(this).child.isShown())
              var data = tabla.row(this).data();
         else
              var data = tabla.row($(this).parents("tr")).data();
         
        const id = data.id; //capturo el ID
        buscar(id);        
    }); 
    
    //-------------------------------------------------------------
    //Buscar 
    //-------------------------------------------------------------
    function buscar(dato) {
        funcion = 'buscar';
        $.post('../controlador/ProductoController.php',{dato, funcion},(response)=>{
            const respuesta = JSON.parse(response);
            $('#id_producto').val(respuesta.id);
            $('#nombre').val(respuesta.nombre);
            $('#concentracion').val(respuesta.concentracion);
            $('#adicional').val(respuesta.adicional);
            $('#precio').val(respuesta.precio);
            $('#laboratorio').val(respuesta.laboratorio).trigger('change');
            $('#tipo').val(respuesta.tipo).trigger('change');
            $('#presentacion').val(respuesta.presentacion).trigger('change');
            $('#proveedor').val(respuesta.proveedor).trigger('change');

            ///CAMBIO LOGO
            $('#nombre_avatar').html(respuesta.nombre);       //Nombre Producto
            $('#id_avatar').val(respuesta.id);                //Id del prodcuto en la ventana modal cambio logo
            $('#avataractual').attr('src','../assets/img/prod/'+respuesta.avatar);  //Logo actual
        })
    };

    //----------------------------------------------------------
    // Funcion para crear o editar laboratorio en el formulario
    //----------------------------------------------------------
    $('#form-crear').submit(e=>{
        //Cargar los objetos de los formulario en variables JS
        let id = $('#id_producto').val();
        let nombre = $('#nombre').val();
        let concentracion = $('#concentracion').val();
        let adicional = $('#adicional').val();
        let precio = $('#precio').val();
        let laboratorio = $('#laboratorio').val();
        let tipo = $('#tipo').val();
        let presentacion = $('#presentacion').val();
        let avatar = 'default.png';
        let proveedor = $('#proveedor').val();

        if (edit == true)
            funcion = 'editar';
        else
            funcion = 'crear';

        $.post('../controlador/ProductoController.php',{id, nombre, concentracion, adicional, precio, laboratorio,tipo,presentacion, avatar, proveedor, funcion},(response)=>{
            console.log(response);
            if(response == 'add' || response == 'update' ){ 
                  //Mensaje de agregar productos al carrito
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })
                  
                  Toast.fire({
                    icon: 'success',
                    title: nombre,
                    html: 'Agregado con exito'
                  })
                
                $('#crear').modal('hide');
                tabla.ajax.reload(null, false);
            }   
            else{
                $('#noadd').hide('slow');
                $('#noadd').show(1000);
                $('#noadd').hide(2000);
            }  
        });
        e.preventDefault();
    });      

    //----------------------------------------------------------
    // Funcion que evalua click en ELIMNAR y obtiene el id
    // navegando a traves de la propiedad parentElement
    //----------------------------------------------------------
    $(document).on('click','.eliminar',function(){          
        if(tabla.row(this).child.isShown()){
            var data = tabla.row(this).data();
        }else{
            var data = tabla.row($(this).parents("tr")).data();
        }
        const id = data.id; //capturo el ID
        const nombre = data.nombre; //capturo el nombre		            
        //Cargo los objetos ocultos obtenidos con javascript y enviarlos al controlador
        buscar(id);        
        funcion = 'eliminar';

        Swal.fire({
            title: 'Desea eliminar '+nombre+'?',
            text: "Esto no se podra revertir!",
            icon: 'warning',
            showCancelButton: true,
            reverseButtons: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
          }).then((result) => {
            if (result.value) {
                $.post('../controlador/ProductoController.php',{id, funcion},(response)=>{
                    if(response == 'eliminado' ){ 
                        Swal.fire(
                            'Eliminado!',
                            nombre + ' fue eliminado.',
                            'success'
                          )
                    }   
                    else{
                        Swal.fire(
                            'No se pudo eliminar!',
                            nombre + ' esta utilizado',
                            'error'
                          )
                    }  
                    tabla.ajax.reload(null, false);
                });
            }
          })
    });

    //----------------------------------------------------------
    // Funcion que carga el comboxLABORATORIO de la ventana crear y editar
    //----------------------------------------------------------
    function select_laboratorio(){
        funcion = "seleccionar";
        $.post('../controlador/LaboratorioController.php',{funcion},(response)=>{
            const registros = JSON.parse(response);
            let template ='';
            registros.forEach(registro => {
                template +=`<option value="${registro.id}">${registro.nombre}</option>`
            });
            $('#laboratorio').html(template);
        })
    };

    //----------------------------------------------------------
    // Funcion que carga el combox TIPOS de la ventana crear y editar
    //----------------------------------------------------------
    function select_tipo(){
        funcion = "seleccionar";
        $.post('../controlador/TipoProductoController.php',{funcion},(response)=>{
            const registros = JSON.parse(response);
            let template ='';
            registros.forEach(registro => {
                template +=`<option value="${registro.id}">${registro.nombre}</option>`
            });
            $('#tipo').html(template);
        })
    };

    //----------------------------------------------------------
    // Funcion que carga el combox presentacion de la ventana crear y editar
    //----------------------------------------------------------
    function select_presentacion(){
        funcion = "seleccionar";
        $.post('../controlador/PresentacionController.php',{funcion},(response)=>{
            const registros = JSON.parse(response);
            let template ='';
            registros.forEach(registro => {
                template +=`<option value="${registro.id}">${registro.nombre}</option>`
            });
            $('#presentacion').html(template);
        })
    };

    //----------------------------------------------------------
    // Funcion que carga el combox presentacion de la ventana añadir_lote
    //----------------------------------------------------------
    function select_proveedor(){
        funcion = "seleccionar";
        $.post('../controlador/ProveedorController.php',{funcion},(response)=>{
            const registros = JSON.parse(response);
            let template ='';
            registros.forEach(registro => {
                template +=`<option value="${registro.id}">${registro.nombre}</option>`
            });
            $('#proveedor').html(template);
        })
    };

    //----------------------------------------------------------
    // Funcion que evalua Click en anadir lote
    //----------------------------------------------------------
    $(document).on('click','.anadir_lote',function(){
        if(tabla.row(this).child.isShown()){
            var data = tabla.row(this).data();
        } else {
            var data = tabla.row($(this).parents("tr")).data();
        }

        $('#producto').val(data.id); // asignar ID del producto al campo oculto en el modal de añadir lote
        $('#nombre_producto').html(data.nombre); // asignar nombre del producto al campo de texto en
    })

    //----------------------------------------------------------
    // Funcion que añade lote
    //----------------------------------------------------------
    $('#form-add-lote').submit(function (e) {
        e.preventDefault();
    
        var proveedor = $('#proveedor').val();
        var producto = $('#producto').val();
        var vencimiento = $('#fechaVencimiento').val();
        var stock = $('#stock').val();
        funcion = 'crear';

        $.post('../controlador/LoteController.php',{proveedor,producto,vencimiento,stock, funcion},(response)=>{
            console.log(response);
            if(response == 'add'){ 
                  //Mensaje de agregar productos al lote
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })
                  
                  Toast.fire({
                    icon: 'success',
                    title: 'Lote',
                    html: 'Agregado con exito'
                  })
                
                $('#addLoteModal').modal('hide');
                tabla.ajax.reload(null, false);
            }   
            else{
                $('#noadd').hide('slow');
                $('#noadd').show(1000);
                $('#noadd').hide(2000);
            }  
        });
        e.preventDefault();
    });     
});