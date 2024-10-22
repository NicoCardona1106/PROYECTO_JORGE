<?php
include_once '../modelo/Proveedor.php';
$proveedor = new Proveedor();

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
    $id_proveedor = $_POST['id_proveedor']; // Cambiado a id_proveedor
    $proveedor->eliminar($id_proveedor);
    echo 'deleted'; // Proveedor eliminado
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

//----------------------------------------------------------------------------------
// Este tipo de función solo aplica para el formData (envío de archivos e imágenes)
//----------------------------------------------------------------------------------

if ($_POST['funcion'] == 'cambiar_logo') {
    if (($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/gif')) {
        // Se obtiene el nombre del archivo
        $nombre = uniqid() . '-' . $_FILES['photo']['name'];
        // Concatenar el directorio con el nombre del archivo
        $ruta = '../assets/img/proveedor/' . $nombre;
        // Función PHP que sube la imagen al servidor
        move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
        $proveedor->CambiarLogo($_POST['id_avatar'], $nombre);

        foreach ($proveedor->objetos as $objeto) {
            if ($objeto->avatar != 'default.png') {
                unlink('../assets/img/proveedor/' . $objeto->avatar);
            }
        }
        // Retorno de un Json con dos valores 
        $json = array();
        $json[] = array(
            'ruta' => $ruta,
            'alert' => 'editalogo'
        );
    } else {
        // En caso de una imagen con formato incorrecto
        $json = array();
        $json[] = array(
            'alert' => 'noeditalogo'
        );
    }
    echo json_encode($json[0]);
}
?>
