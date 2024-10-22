<?php
    include_once '../modelo/producto.php';
    $producto = new Producto();
    
    //-------------------------------------------------------------------
    // Funcion para buscar todos los registros DATATABLES
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'listar'){
        $json = Array();
        // Llamado al controlador
        $producto->BuscarTodos('');
        foreach ($producto->objetos as $objeto) {
            $json[]=array(
                'id'=>$objeto->id_producto,
                'nombre'=>$objeto->nombre,
                'concentracion'=>$objeto->concentracion,
                'adicional'=>$objeto->adicional,
                'precio'=>$objeto->precio,
                'laboratorio'=>$objeto->laboratorio,
                'tipo'=>$objeto->tipo,
                'presentacion'=>$objeto->presentacion,
                'avatar'=>$objeto->avatar,
                'proveedor'=>$objeto->proveedor
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    

    //-------------------------------------------------------------------
    // Funcion para buscar un producto
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'buscar'){
        $json = Array();
        // Llamado al controlador
        $producto->Buscar($_POST['dato']);
        foreach ($producto->objetos as $objeto) {
            $json[]=array(
                'id'=>$objeto->id_producto,
                'nombre'=>$objeto->nombre,
                'concentracion'=>$objeto->concentracion,
                'adicional'=>$objeto->adicional,
                'precio'=>$objeto->precio,
                'laboratorio'=>$objeto->id_laboratorio,
                'tipo'=>$objeto->id_tip_prod,
                'presentacion'=>$objeto->id_presentacion,
                'avatar'=>$objeto->avatar,
                'proveedor'=>$objeto->id_proveedor
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }
    
    //-------------------------------------------------------------------
    // Funcion Crear
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'crear'){
        $producto->Crear($_POST['id'], $_POST['nombre'], $_POST['concentracion'],
                         $_POST['adicional'], $_POST['precio'], $_POST['laboratorio'],
                         $_POST['tipo'], $_POST['presentacion'], $_POST['avatar'], 
                         $_POST['proveedor']); // Añadimos el proveedor
    }
    
    //-------------------------------------------------------------------
    // Funcion Editar
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'editar'){
        $producto->Editar($_POST['id'], $_POST['nombre'], $_POST['concentracion'],
                          $_POST['adicional'], $_POST['precio'], $_POST['laboratorio'],
                          $_POST['tipo'], $_POST['presentacion'], $_POST['proveedor']); // Añadimos el proveedor
    }
    

    //-------------------------------------------------------------------
    // Funcion eliminar
    //-------------------------------------------------------------------
    if ($_POST['funcion']=='eliminar'){
        $producto->Eliminar($_POST['id']);        
    }

     //-------------------------------------------------------------------
    // Funcion para cargar select  
    //-------------------------------------------------------------------    
    if ($_POST['funcion']=='seleccionar'){
        $json = Array();
        //LLamado al controlador
        $producto->Seleccionar();
        foreach ($producto->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_producto,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    //----------------------------------------------------------------------------------
    //Este tipo de funcion solo aplica para el formData (envio de archivos e imagenes)
    //----------------------------------------------------------------------------------

    if($_POST['funcion']=='cambiar_logo'){
        if(($_FILES['photo']['type']=='image/jpeg')||($_FILES['photo']['type']=='image/png')||($_FILES['photo']['type']=='image/gif')){
            //se obtiene el nombre del archivo
            $nombre = uniqid().'-'. $_FILES['photo']['name'];          
            //Concatena el directorio con el nombre del archivo
            $ruta = '../assets/img/prod/'.$nombre;                              
            //Funcion PHP que sube la imagen al servidor
            move_uploaded_file($_FILES['photo']['tmp_name'],$ruta); 
            $producto->CambiarLogo($_POST['id_avatar'],$nombre);          
    
            foreach ($producto->objetos as $objeto){
                if ($objeto->avatar!='default.png')
                    unlink('../assets/img/prod/'.$objeto->avatar);    
            }
            //Retorno de un Json con dos valores 
            $json = array();
            $json[]=array(
                'ruta'=>$ruta,
                'alert'=>'editalogo'
            );
        }
        //En caso de una imagen con formato incorrecto
        else{
            $json = array();
            $json[]=array(
               'alert'=>'noeditalogo'
                );
        }
        $jsonstring=json_encode($json[0]);
        echo $jsonstring;
    }



?>
