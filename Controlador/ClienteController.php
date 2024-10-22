<?php
include_once '../modelo/Cliente.php';
$cliente = new Cliente();

//-------------------------------------------------------------------
// Funcion Crear
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $avatar = $_POST['avatar'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    
    // Verificar si el cliente ya existe
    if ($cliente->existeCliente($nombre, $email, $dni, $apellido) === false) {
        $cliente->crear($nombre, $apellido, $email, $telefono, $direccion, $avatar, $dni, $edad, $sexo);
        echo 'add'; // Cliente creado
    } else {
        echo 'exists'; // El cliente ya existe
    }
}

//-------------------------------------------------------------------
// Funcion Listar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'listar') {
    $cliente->buscar();
    $json = array();
    foreach ($cliente->objetos as $objeto) {
        $json['data'][] = $objeto;
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

//-------------------------------------------------------------------
// Funcion Editar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'editar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $avatar = $_POST['avatar'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];

    // Verificar si el cliente ya existe, pero ignorar si es el mismo
    if ($cliente->existeCliente($nombre, $apellido, $dni, $email, $id) === false) {
        $cliente->editar($id, $nombre, $apellido, $email, $telefono, $direccion, $avatar, $dni, $edad, $sexo);
        echo 'edit'; // Cliente editado
    } else {
        echo 'exists'; // El cliente ya existe
    }
}

//-------------------------------------------------------------------
// Funcion Eliminar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'eliminar') {
    $id = $_POST['id'];
    $cliente->eliminar($id);
    echo 'deleted'; // Cliente eliminado
}
?>
