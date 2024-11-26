<?php
include_once '../modelo/ProductosOferta.php'; // Asegúrate de que este archivo incluye la clase actualizada
$producto = new Productos();

if ($_POST['funcion'] == 'listar') {
    // Llamar a la función para verificar y actualizar los precios
    $producto->verificarYActualizarPrecios();

    // Obtener la lista de productos después de actualizar los precios
    $productos = $producto->listar();

    $json = array(); // Crear un array JSON para almacenar los productos

    // Iterar sobre los productos y añadirlos al array JSON
    foreach ($productos as $prod) {
        $json['data'][] = [
            'id_producto' => $prod['id_producto'],
            'nombre' => $prod['nombre'],
            'concentracion' => $prod['concentracion'],
            'adicional' => $prod['adicional'],
            'precio' => $prod['precio'],
            'precio_original' => $prod['precio_original'], // Incluye el precio original
            'descuento_porcentaje' => $prod['descuento_porcentaje'], // Incluye el porcentaje de descuento
            'avatar' => $prod['avatar'],
            'cantidad' => $prod['cantidad'],
            'tipo_descuento' => $prod['tipo_descuento'] // Incluir el tipo de descuento
        ];
    }

    // Convertir el array a formato JSON y devolverlo como respuesta
    echo json_encode($json);
}
?>
