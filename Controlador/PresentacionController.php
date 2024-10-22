<?php
    include_once '../modelo/presentacion.php';
    $presentacion = new Presentacion();
    
    //-------------------------------------------------------------------
    // Funcion para buscar todos los registros DATATABLES
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'listar'){
        $json = Array();
        //LLamado al controlador
        $presentacion->BuscarTodos('');
        foreach ($presentacion->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_presentacion,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    //-------------------------------------------------------------------
    // Funcion para buscar un presentacion
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'buscar'){
        $json = Array();
        //LLamado al controlador
        $presentacion->Buscar($_POST['dato']);
        foreach ($presentacion->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_presentacion,
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
        $presentacion->Crear($_POST['id'],$_POST['nombre']);
    }

    //-------------------------------------------------------------------
    // Funcion Editar
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'editar'){
        $presentacion->Editar($_POST['id'],$_POST['nombre']);
    }

    //-------------------------------------------------------------------
    // Funcion eliminar
    //-------------------------------------------------------------------
    if ($_POST['funcion']=='eliminar'){
        $presentacion->Eliminar($_POST['id']);        
    }

     //-------------------------------------------------------------------
    // Funcion para cargar select  
    //-------------------------------------------------------------------    
    if ($_POST['funcion']=='seleccionar'){
        $json = Array();
        //LLamado al controlador
        $presentacion->Seleccionar();
        foreach ($presentacion->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_presentacion,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }


?>
