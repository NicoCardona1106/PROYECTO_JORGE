<?php
    include_once '../modelo/tipo_producto.php';
    $tipo_producto = new Tipo_Producto();
    
    //-------------------------------------------------------------------
    // Funcion para buscar todos los registros DATATABLES
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'listar'){
        $json = Array();
        //LLamado al controlador
        $tipo_producto->BuscarTodos('');
        foreach ($tipo_producto->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_tip_prod,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    //-------------------------------------------------------------------
    // Funcion para buscar un tipo_producto
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'buscar'){
        $json = Array();
        //LLamado al controlador
        $tipo_producto->Buscar($_POST['dato']);
        foreach ($tipo_producto->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_tip_prod,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }

    //-------------------------------------------------------------------
    // Funcion Crear
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'crear'){
        $tipo_producto->Crear($_POST['id'],$_POST['nombre']);
    }

    //-------------------------------------------------------------------
    // Funcion Editar
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'editar'){
        $tipo_producto->Editar($_POST['id'],$_POST['nombre']);
    }

    //-------------------------------------------------------------------
    // Funcion eliminar
    //-------------------------------------------------------------------
    if ($_POST['funcion']=='eliminar'){
        $tipo_producto->Eliminar($_POST['id']);        
    }

     //-------------------------------------------------------------------
    // Funcion para cargar select  
    //-------------------------------------------------------------------    
    if ($_POST['funcion']=='seleccionar'){
        $json = Array();
        //LLamado al controlador
        $tipo_producto->Seleccionar();
        foreach ($tipo_producto->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_tip_prod,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
?>
