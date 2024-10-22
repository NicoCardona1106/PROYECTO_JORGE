<?php
    include_once '../modelo/laboratorio.php';
    $laboratorio = new Laboratorio();
    
    //-------------------------------------------------------------------
    // Funcion para buscar todos los registros DATATABLES
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'listar'){
        $json = Array();
        //LLamado al controlador
        $laboratorio->BuscarTodos('');
        foreach ($laboratorio->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_laboratorio,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    //-------------------------------------------------------------------
    // Funcion para buscar un laboratorio
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'buscar'){
        $json = Array();
        //LLamado al controlador
        $laboratorio->Buscar($_POST['dato']);
        foreach ($laboratorio->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_laboratorio,
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
        $laboratorio->Crear($_POST['id'],$_POST['nombre']);
    }

    //-------------------------------------------------------------------
    // Funcion Editar
    //-------------------------------------------------------------------
    if ($_POST['funcion'] == 'editar'){
        $laboratorio->Editar($_POST['id'],$_POST['nombre']);
    }

        //-------------------------------------------------------------------
    // Funcion eliminar
    //-------------------------------------------------------------------
    if ($_POST['funcion']=='eliminar'){
        $laboratorio->Eliminar($_POST['id']);        
    }

    //-------------------------------------------------------------------
    // Funcion para cargar select  
    //-------------------------------------------------------------------    
    if ($_POST['funcion']=='seleccionar'){
        $json = Array();
        //LLamado al controlador
        $laboratorio->Seleccionar();
        foreach ($laboratorio->objetos as $objeto) {
            $json[]=array(
                            'id'=>$objeto->id_laboratorio,
                            'nombre'=>$objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    




?>
