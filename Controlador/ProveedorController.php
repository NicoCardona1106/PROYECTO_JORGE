<?php
session_start(); // Asegúrate de que la sesión esté iniciada correctamente
include_once '../modelo/Proveedor.php';

$proveedor = new Proveedor();


//-------------------------------------------------------------------
// Funcion para buscar un producto
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'buscar'){
    $json = Array();
    // Llamado al controlador
    $proveedor->Buscar($_POST['dato']);
    foreach ($proveedor->objetos as $objeto) {
        $json[]=array(
            'id'=>$objeto->id_proveedor,
            'nombre'=>$objeto->nombre,
            'apellido'=>$objeto->apellido,
            'dni'=>$objeto->dni,
            'edad'=>$objeto->edad,
            'sexo'=>$objeto->sexo,
            'correo'=>$objeto->correo,
            'telefono'=>$objeto->telefono,
            'direccion'=>$objeto->direccion,
            'avatar'=>$objeto->avatar
        );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}
//-------------------------------------------------------------------
// Función Crear
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $avatar = $_POST['avatar']; // Asume que el avatar se envía de forma adecuada

    // Verificar si el proveedor ya existe
    if (!$proveedor->existeProveedor($nombre, $apellido, $dni, $correo)) {
        $proveedor->crear($nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar);
        echo 'add'; // Proveedor creado
    } else {
        echo 'exists'; // El proveedor ya existe
    }
}

//-------------------------------------------------------------------
// Función Listar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'listar') {
    $proveedor->buscar();
    $json = array();
    foreach ($proveedor->objetos as $objeto) {
        $json['data'][] = $objeto; // Cambiado para que coincida con el formato esperado
    }
    echo json_encode($json);
}

//-------------------------------------------------------------------
// Función Editar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'editar') {
    $id_proveedor = $_POST['id_proveedor'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $avatar = $_POST['avatar'];

    // Aquí actualizas el proveedor en la base de datos
    $proveedor->editar($id_proveedor, $nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar);

    echo 'edit';
}



//-------------------------------------------------------------------
// Función Eliminar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'eliminar') {
    $id_proveedor = $_POST['id']; // Asegúrate de que el frontend envía este valor como 'id'
    $resultado = $proveedor->eliminar($id_proveedor); // Llamada al método en el modelo

    if ($resultado) {
        echo 'eliminado'; // Enviar respuesta de éxito
    } else {
        echo 'noeliminado'; // Enviar respuesta de error
    }
}

//-------------------------------------------------------------------
// Función para cargar select  
//-------------------------------------------------------------------    
if ($_POST['funcion'] == 'seleccionar') {
    $json = array();
    // Llamado al controlador
    $proveedor->Seleccionar();
    foreach ($proveedor->objetos as $objeto) {
        $json[] = array(
            'id' => $objeto->id_proveedor, // Cambiado a id_proveedor
            'nombre' => $objeto->nombre
        );
    }
    echo json_encode($json);
}


//-------------------------------------------------------------------
// Historial de Ventas
//-------------------------------------------------------------------

if ($_POST['funcion'] == 'obtenerHistorialVentas') {
    if (isset($_SESSION['id_proveedor']) && !empty($_SESSION['id_proveedor'])) {
        $id_proveedor = $_SESSION['id_proveedor'];
        $ventas = $proveedor->obtenerHistorialVentas($id_proveedor);
        $total_vendido = $proveedor->obtenerTotalVendido($id_proveedor);

        echo json_encode([
            'status' => 'success',
            'data' => $ventas,
            'total_vendido' => $total_vendido ?? 0
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Proveedor no identificado en la sesión.'
        ]);
    }
}



//-------------------------------------------------------------------
// Manejo De Órdenes
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'obtenerOrdenes') {
    if (isset($_SESSION['id_proveedor']) && !empty($_SESSION['id_proveedor'])) {
        $id_proveedor = $_SESSION['id_proveedor'];
        $ordenes = $proveedor->obtenerOrdenesPorProveedor($id_proveedor);

        if (!empty($ordenes)) {
            echo json_encode([
                'status' => 'success',
                'data' => $ordenes
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron órdenes para este proveedor.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Proveedor no identificado en la sesión.'
        ]);
    }
}

if ($_POST['funcion'] == 'actualizarEstadoProducto') {
    $id_detalle = $_POST['id_detalle'] ?? null;
    $nuevo_estado = $_POST['nuevo_estado'] ?? null;

    if ($id_detalle && $nuevo_estado) {
        try {
            $resultado = $proveedor->actualizarEstadoProducto($id_detalle, $nuevo_estado);

            if ($resultado['status'] === 'success') {
                echo json_encode([
                    'status' => 'success',
                    'message' => $resultado['message'],
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $resultado['message'],
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar estado del producto: " . $e->getMessage()); // Log del error
            echo json_encode([
                'status' => 'error',
                'message' => 'Ocurrió un error al actualizar el estado. Por favor, intente nuevamente.',
            ]);
        }
    } else {
        error_log("Error: Datos incompletos para actualizar estado (id_detalle: $id_detalle, nuevo_estado: $nuevo_estado).");
        echo json_encode([
            'status' => 'error',
            'message' => 'Datos incompletos para actualizar el estado.',
        ]);
    }
}



//----------------------------------------------------------------------------------
// Este tipo de función solo aplica para el formData (envío de archivos e imágenes)
//----------------------------------------------------------------------------------

if ($_POST['funcion'] == 'cambiar_logo') {
    if (($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/gif')) {
        // Generar un nombre único para la nueva imagen
        $nombre = uniqid() . '-' . $_FILES['photo']['name'];
        // Ruta donde se almacenará la imagen
        $ruta = '../assets/img/proveedor/' . $nombre;
        // Subir la nueva imagen al servidor
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $ruta)) {
            // Cambiar el logo en la base de datos
            $proveedor->CambiarLogo($_POST['id_avatar'], $nombre);

            foreach ($proveedor->objetos as $objeto) {
                // Verificar que no sea la imagen predeterminada y que el archivo exista antes de eliminar
                if ($objeto->avatar != 'default.png') {
                    $ruta_actual = '../assets/img/proveedor/' . $objeto->avatar;
                    if (file_exists($ruta_actual)) {
                        unlink($ruta_actual);
                    }
                }
            }

            // Respuesta JSON con la nueva ruta
            $json = array();
            $json[] = array(
                'ruta' => $ruta,
                'alert' => 'editalogo'
            );
        } else {
            // En caso de que la carga de la nueva imagen falle
            $json = array();
            $json[] = array(
                'alert' => 'error_upload'
            );
        }
    } else {
        // En caso de un formato de imagen incorrecto
        $json = array();
        $json[] = array(
            'alert' => 'noeditalogo'
        );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}
?>
