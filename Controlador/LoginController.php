<?php
session_start();
include_once '../modelo/Conexion.php'; // Incluye la clase de conexión
include_once '../modelo/Usuario.php'; // Incluye el modelo de usuario

$usuario = new Usuario();
$db = new Conexion(); // Crea una instancia de la conexión
$conexion = $db->pdo; // Asigna la conexión PDO a la variable $conexion

// Comprobar si se está intentando hacer login como invitado
if (isset($_POST['action']) && $_POST['action'] == 'guest_login') {
    // Configurar la sesión para el invitado
    $_SESSION['id_tipo_us'] = 4;
    $_SESSION['nombre_us'] = 'Invitado';
    $_SESSION['dni_us'] = 'GUEST';
    
    // Redirigir al invitado a la página de clientes
    header('location: ../vista/PaginaCI.php');
    exit();
}

$dni_us = $_POST['dni_us'];
$contrasena_us = $_POST['contrasena_us'];

// Si la sesión ya está creada (Ya se logueó)
if (!empty($_SESSION['id_tipo_us'])){
    switch ($_SESSION['id_tipo_us']){
        case 1:
            header('location: ../vista/adm_nav.php');
            break;
        case 2:
            header('location: ../vista/PaginaCI.php');
            break;
        case 3:
            header('location: ../vista/PaginaP.php');
            break;
        case 4:
            header('location: ../vista/PaginaCI.php');
            break;
    }
} else {
    $usuario->Loguearse($dni_us, $contrasena_us);
    // Valida que el objeto venga con datos
    if (!empty($usuario->objetos)) {
        foreach ($usuario->objetos as $key => $objeto) {
            // Si el password es correcto debe crear la SESSION[]
            $_SESSION['id_usuario'] = $objeto->id_usuario;
            $_SESSION['id_tipo_us'] = $objeto->id_tipo_us;
            $_SESSION['nombre_us'] = $objeto->nombre_us;
            $_SESSION['dni_us'] = $objeto->dni_us;

            // Agregar id_proveedor para los proveedores
            if ($objeto->id_tipo_us == 3) { // Tipo Proveedor
                $queryProveedor = "SELECT id_proveedor FROM proveedor WHERE id_usuario = ?";
                $stmt = $conexion->prepare($queryProveedor); // Usa la conexión PDO
                $stmt->bindParam(1, $objeto->id_usuario);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $_SESSION['id_proveedor'] = $result['id_proveedor'];
                }
            }
        }
        switch ($_SESSION['id_tipo_us']) {
            case 1:
                header('location: ../vista/adm_nav.php');
                break;
            case 2:
                header('location: ../vista/PaginaCI.php');
                break;
            case 3:
                header('location: ../vista/PaginaP.php'); // Proveedor
                break;
            case 4:
                header('location: ../vista/PaginaCI.php');
                break;
        }
    } else {
        // Redirigir a login.php con el parámetro de error
        header('location: ../vista/login.php?error=1');
        exit();
    }
}
?>
