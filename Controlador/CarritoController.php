<?php
session_start();
include_once '../modelo/Carrito.php';

$carrito = new Carrito();

if ($_POST['funcion'] == 'agregar_carrito') {
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que este valor está en la sesión
    $id_producto = $_POST['id_producto'];

    $carrito->agregarProducto($id_usuario, $id_producto);

    echo json_encode([
        'status' => 'success',
        'message' => 'Producto agregado al carrito'
    ]);
}

if ($_POST['funcion'] == 'obtenerCarrito') {
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que este valor está en la sesión
    $producto = $carrito->obtenerCarrito($id_usuario);
    $total = $carrito->calcularTotal($id_usuario);

    echo json_encode([
        'data' => $producto,
        'total' => $total
    ]);
}

if ($_POST['funcion'] == 'eliminar_producto') {
    $id_usuario = $_SESSION['id_usuario'];  // Asegúrate de que el id_usuario esté en la sesión
    $id_producto = $_POST['id_producto'];

    $carrito->eliminarProducto($id_usuario, $id_producto);

    echo json_encode([
        'status' => 'success',
        'message' => 'Producto eliminado del carrito'
    ]);
}

?>
