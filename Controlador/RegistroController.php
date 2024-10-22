<?php
include_once '../modelo/Usuario.php';
include_once '../modelo/Cliente.php';
include_once '../modelo/Proveedor.php';

$usuario = new Usuario();
$cliente = new Cliente();
$proveedor = new Proveedor();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoUsuario = $_POST['tipoUsuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $sexo = $_POST['sexo'];
    $infoadicional = $_POST['infoadicional'];
    
    // Manejo del avatar
    $avatar = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $avatar = file_get_contents($_FILES['avatar']['tmp_name']);
    }

    // Verificar si el usuario ya existe
    if ($usuario->existeUsuario($dni, $correo)) {
        echo 'exists';
        exit;
    }

    // Crear usuario
    $id_usuario = $usuario->crear($nombre, $apellido, $dni, $edad, $contrasena, $telefono, $direccion, $correo, $sexo, $infoadicional, $avatar);

    if ($id_usuario) {
        // Crear cliente o proveedor según corresponda
        if ($tipoUsuario == 'cliente') {
            $resultado = $cliente->crearDesdeUsuario($id_usuario);
        } else if ($tipoUsuario == 'proveedor') {
            $resultado = $proveedor->crear($id_usuario);
        }

        if ($resultado) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'invalid_request';
}
?>