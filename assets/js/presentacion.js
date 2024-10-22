$(document).ready(function () {
    var funcion;
    var edit = false;

    //----------------------------------------------------------
    // Construccion DataTable
    //----------------------------------------------------------
    var tablaPresentacion = $('#tablaPresentacion').DataTable({  
        //dom: 'lfBtip',
               
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
            "url": "../controlador/PresentacionController.php",
            "method": 'POST', //usamos el metodo POST
            "data":{funcion:'listar'}, //enviamos POST
            "dataSrc":""
        },
        
        "columns":[
            { "data": "id", "title":"ID"},
            { "data": "nombre", "title":"Nombre"},
            {"defaultContent": "<button class='editar btn btn-sm btn-primary' title='Editar' data-toggle='modal' data-target='#crearpresentacion'><i class='fas fa-pencil-alt'></i></button><button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>", "title":"Acciones"}
        ],
        //Configurar COLUMNAS ---- Centrado Acciones
        "columnDefs": [ 
            {   "className": "text-center",
                "targets": [2],
                "visible": true,
                "searchable": true
            }
        ],
        buttons: ["copy", "excel", "pdf", "print", "colvis"]    
    });

    tablaPresentacion.buttons().container().appendTo($('.col-md-6:eq(0)', tablaPresentacion.table().container()));  

    //----------------------------------------------------------
    // Funcion que evalua click en CREAR
    // Solo para limpiar el formulario
    //----------------------------------------------------------
    $(document).on('click','.btn-crearpresentacion',(e)=>{
        $('#form-crearpresentacion').trigger('reset');
        $('#tit_ven').html('Crear presentacion');
        edit = false;
    });

    //----------------------------------------------------------
    // Funcion que evalua click en EDITAR y obtiene el id
    // en DATATABLES responsives
    //----------------------------------------------------------
    $(document).on('click','.editar',function(){
        edit = true;
        $('#tit_ven').html('Editar presentacion');
         if(tablaPresentacion.row(this).child.isShown())
              var data = tablaPresentacion.row(this).data();
         else
              var data = tablaPresentacion.row($(this).parents("tr")).data();
         
        const id = data.id; //capturo el ID
        buscar(id);        
    }); 
    
    //-------------------------------------------------------------
    //Buscar 
    //-------------------------------------------------------------
    function buscar(dato) {
        funcion = 'buscar';
        $.post('../controlador/PresentacionController.php',{dato, funcion},(response)=>{
            const respuesta = JSON.parse(response);
            $('#id_presentacion').val(respuesta.id);
            $('#nombre').val(respuesta.nombre);
        })
    };

    //----------------------------------------------------------
    // Funcion para crear o editar laboratorio en el formulario
    //----------------------------------------------------------
    $('#form-crearpresentacion').submit(e=>{
        //Cargar los objetos de los formulario en variables JS
        let nombre = $('#nombre').val();
        let id = $('#id_presentacion').val();
        if (edit == true)
            funcion = 'editar';
        else
            funcion = 'crear';

        $.post('../controlador/PresentacionController.php',{id, nombre, funcion},(response)=>{
            console.log(response);
            if(response == 'add' || response == 'update' ){ 
                $('#add').hide('slow');
                $('#add').show(1000);
                $('#add').hide(2000);
                $('#crearpresentacion').modal('hide');
                tablaPresentacion.ajax.reload(null, false);
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
        if(tablaPresentacion.row(this).child.isShown()){
            var data = tablaPresentacion.row(this).data();
        }else{
            var data = tablaPresentacion.row($(this).parents("tr")).data();
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
                $.post('../controlador/PresentacionController.php',{id, funcion},(response)=>{
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
                    tablaPresentacion.ajax.reload(null, false);
                });
            }
          })
    })
})
