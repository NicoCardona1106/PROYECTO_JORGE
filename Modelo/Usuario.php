<?php
include_once 'Conexion.php';  // Se incluye el archivo de conexión a la base de datos

// Definición de la clase Usuario
class Usuario {
    var $objetos;  // Variable para almacenar los resultados de las consultas SQL

    // Constructor que se ejecuta al instanciar la clase
    public function __construct(){
        $db = new Conexion();  // Se crea una nueva conexión a la base de datos
        $this->acceso = $db->pdo;  // Se almacena el objeto PDO para ejecutar consultas
    }

    // Función para iniciar sesión (verificar si el usuario existe con los datos proporcionados)
    function Loguearse($dni_us, $contrasena_us){
        // Consulta SQL para buscar un usuario por su DNI y contraseña
        $sql = 'SELECT * FROM usuario WHERE dni_us = :dni_us AND contrasena_us = :contrasena_us';
        
        // Preparamos la consulta SQL
        $query = $this->acceso->prepare($sql);
        
        // Ejecutamos la consulta con los valores pasados como parámetros
        $query->execute(array(':dni_us' => $dni_us, ':contrasena_us' => $contrasena_us));
        
        // Almacenamos el resultado en la variable 'objetos'
        $this->objetos = $query->fetchall();
        
        // Devolvemos los resultados obtenidos (lista de usuarios que coinciden con los datos proporcionados)
        return $this->objetos;
    }

    // Función para crear un nuevo usuario
    function crear($nombre, $apellido, $dni, $edad, $contrasena, $telefono, $direccion, $correo, $sexo, $infoadicional, $avatar, $id_tipo_us) {
        // Verificación del avatar: si está vacío, se asigna 'default.png'
        $avatar = !empty($avatar) ? $avatar : 'default.png';

        // Consulta SQL para insertar un nuevo usuario en la base de datos
        $sql = "INSERT INTO usuario(nombre_us, apellidos_us, dni_us, edad_us, contrasena_us, telefono_us, direccion_us, correo_us, sexo_us, infoadicional_us, avatar, id_tipo_us) 
                VALUES (:nombre, :apellido, :dni, :edad, :contrasena, :telefono, :direccion, :correo, :sexo, :infoadicional, :avatar, :id_tipo_us)";
        
        // Preparamos la consulta SQL
        $query = $this->acceso->prepare($sql);
        
        // Ejecutamos la consulta pasando los datos del nuevo usuario como parámetros
        $query->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':edad' => $edad,
            // La contraseña se encripta antes de almacenarla en la base de datos
            ':contrasena' => $contrasena,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':correo' => $correo,
            ':sexo' => $sexo,
            ':infoadicional' => $infoadicional,
            ':avatar' => $avatar, // La imagen del avatar también se almacena
            ':id_tipo_us' => $id_tipo_us 
        ));
        
        // Devolvemos el ID del último registro insertado en la base de datos
        return $this->acceso->lastInsertId();
    }

    // Función para verificar si un usuario ya existe en la base de datos
    function existeUsuario($dni, $correo) {
        // Consulta SQL para buscar si el DNI o el correo ya existen en la tabla usuario
        $sql = "SELECT * FROM usuario WHERE dni_us = :dni OR correo_us = :correo";
        
        // Preparamos la consulta SQL
        $query = $this->acceso->prepare($sql);
        
        // Ejecutamos la consulta pasando el DNI y el correo como parámetros
        $query->execute(array(':dni' => $dni, ':correo' => $correo));
        
        // Devolvemos verdadero si se encuentran coincidencias (más de 0 filas)
        return $query->rowCount() > 0;
    }
}
?>
