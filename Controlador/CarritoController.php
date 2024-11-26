<?php
session_start();
include_once '../modelo/Carrito.php';

$carrito = new Carrito();

// Agregar producto al carrito
if ($_POST['funcion'] == 'agregar_carrito') {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $id_producto = $_POST['id_producto'] ?? null;

        if ($id_producto) {
            $carrito->agregarProducto($id_usuario, $id_producto);
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto agregado al carrito'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de producto no especificado'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no identificado en la sesión'
        ]);
    }
}

// Obtener productos del carrito
if ($_POST['funcion'] == 'obtenerCarrito') {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $producto = $carrito->obtenerCarrito($id_usuario);
        $total = $carrito->calcularTotal($id_usuario);

        echo json_encode([
            'data' => $producto,
            'total' => $total
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no identificado en la sesión'
        ]);
    }
}

// Eliminar producto del carrito
if ($_POST['funcion'] == 'eliminar_producto') {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $id_producto = $_POST['id_producto'] ?? null;

        if ($id_producto) {
            $carrito->eliminarProducto($id_usuario, $id_producto);
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto eliminado del carrito'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de producto no especificado'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no identificado en la sesión'
        ]);
    }
}

// Finalizar compra
if ($_POST['funcion'] == 'finalizar_compra') {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $estado = $_POST['estado'] ?? 'PENDIENTE';

        $resultado = $carrito->finalizarCompra($id_usuario, $estado);

        if ($resultado['success']) {
            echo json_encode([
                'status' => 'success',
                'message' => $resultado['message'],
                'id_compra' => $resultado['id_compra']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $resultado['message']
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no identificado en la sesión'
        ]);
    }
}

##Actualizar cantidad
if ($_POST['funcion'] == 'actualizar_cantidad') {
    $id_producto = $_POST['id_producto'] ?? null;
    $cantidad_comprada = $_POST['cantidad'] ?? 0;

    if ($id_producto && $cantidad_comprada > 0) {
        // Obtener la cantidad actual del producto
        $sql = "SELECT cantidad, precio_original FROM producto WHERE id_producto = :id_producto";
        $stmt = $carrito->getConexion()->prepare($sql);
        $stmt->execute(['id_producto' => $id_producto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $nueva_cantidad = $producto['cantidad'] - $cantidad_comprada;

            // Actualizar la cantidad del producto
            $sql_update = "UPDATE producto SET cantidad = :cantidad WHERE id_producto = :id_producto";
            $stmt_update = $carrito->getConexion()->prepare($sql_update);
            $stmt_update->execute([
                'cantidad' => $nueva_cantidad,
                'id_producto' => $id_producto
            ]);

            // Verificar si la cantidad es menor a 20 y restaurar el precio original
            if ($nueva_cantidad < 20 && $producto['precio_original'] !== null) {
                $sql_restore_price = "UPDATE producto 
                                      SET precio = precio_original, precio_original = NULL 
                                      WHERE id_producto = :id_producto";
                $stmt_restore_price = $carrito->getConexion()->prepare($sql_restore_price);
                $stmt_restore_price->execute(['id_producto' => $id_producto]);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Cantidad actualizada y precio verificado'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID de producto o cantidad no especificada'
        ]);
    }
}
?>
