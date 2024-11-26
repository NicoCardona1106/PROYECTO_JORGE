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
    if ($cliente->existeCliente($nombre, $email, $dni) === false) {
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
    if ($cliente->existeCliente($nombre, $apellido, $dni) === false) {
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
    $id = $_POST['id']; // Capturar el ID enviado desde el frontend
    $cliente->eliminar($id); // Llamar al método en el modelo
}



//----------------------------------------------------------------------------------
// Este tipo de función solo aplica para el formData (envío de archivos e imágenes)
//----------------------------------------------------------------------------------

if ($_POST['funcion'] == 'cambiar_logo') {
    if (($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/gif')) {
        // Generar un nombre único para la nueva imagen
        $nombre = uniqid() . '-' . $_FILES['photo']['name'];
        // Ruta donde se almacenará la imagen
        $ruta = '../assets/img/cliente/' . $nombre;
        // Subir la nueva imagen al servidor
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $ruta)) {
            // Cambiar el logo en la base de datos
            $cliente->CambiarLogo($_POST['id_avatar'], $nombre);

            foreach ($cliente->objetos as $objeto) {
                // Verificar que no sea la imagen predeterminada y que el archivo exista antes de eliminar
                if ($objeto->avatar != 'default.png') {
                    $ruta_actual = '../assets/img/cliente/' . $objeto->avatar;
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
