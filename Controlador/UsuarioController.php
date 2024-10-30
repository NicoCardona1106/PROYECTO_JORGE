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
    $avatar = 'default.png';  // Valor por defecto
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        // Si se carga una imagen, se usa la ruta de la imagen cargada
        $avatar = 'path/to/avatar/' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], '../assets/img/avatars/' . basename($_FILES['avatar']['name']));
    }

    // Verificar si el usuario ya existe
    if ($usuario->existeUsuario($dni, $correo)) {
        echo json_encode(['status' => 'exists', 'message' => 'El usuario ya existe.']);
        exit;
    }

    // Asignar id_tipo_us basado en el tipo de usuario (cliente o proveedor)
    if ($tipoUsuario == 'cliente') {
        $id_tipo_us = 2;  // Cliente
    } else if ($tipoUsuario == 'proveedor') {
        $id_tipo_us = 3;  // Proveedor
    }

    // Crear usuario con id_tipo_us
    $id_usuario = $usuario->crear($nombre, $apellido, $dni, $edad, $contrasena, $telefono, $direccion, $correo, $sexo, $infoadicional, $avatar, $id_tipo_us);

    if ($id_usuario) {
        // Crear cliente o proveedor según corresponda
        if ($tipoUsuario == 'cliente') {
            $resultado = $cliente->crearDesdeUsuario($id_usuario, $nombre, $apellido, $correo, $telefono, $direccion, $avatar, $dni, $edad, $sexo);
        } else if ($tipoUsuario == 'proveedor') {
            $resultado = $proveedor->crearDesdeUsuario($id_usuario, $nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar);
        }

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Cuenta creada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al registrar el cliente/proveedor.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al crear el usuario.']);
    }
} else {
    echo json_encode(['status' => 'invalid_request', 'message' => 'Solicitud inválida.']);
}
?>
