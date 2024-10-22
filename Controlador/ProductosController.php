<?php
include_once '../modelo/Productos.php';
$producto = new Productos();

// Si la función solicitada es 'listar', ejecuta el siguiente código
if ($_POST['funcion'] == 'listar') {
    $productos = $producto->listar();  // Llama al método 'listar()' del modelo
    
    $json = array();  // Crea un array JSON para almacenar los productos
    
    // Itera sobre el resultado de la consulta y lo añade al array JSON
    foreach ($productos as $prod) {
        $json['data'][] = $prod;
    }
    
    // Convierte el array a formato JSON y lo imprime como respuesta
    echo json_encode($json);
}

?>
