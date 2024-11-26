<?php
include_once 'Conexion.php';

class Cliente {
    var $objetos;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    // Método para crear un cliente desde un usuario
    function crearDesdeUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $direccion, $avatar, $dni, $edad, $sexo) {
        $sql = "INSERT INTO clientes(nombre, apellido, email, telefono, direccion, avatar, dni, edad, sexo, id_usuario) 
                            VALUES (:nombre, :apellido, :email, :telefono, :direccion, :avatar, :dni, :edad, :sexo, :id_usuario)";
        $query = $this->acceso->prepare($sql);
        $resultado = $query->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':id_usuario' => $id_usuario
        ));
        return $resultado;
    }

    // Método para crear un cliente
    function crear($nombre, $apellido, $email, $telefono, $direccion, $avatar, $dni, $edad, $sexo) {
        $sql = "INSERT INTO clientes(nombre, apellido, email, telefono, direccion, avatar, dni, edad, sexo) 
                VALUES (:nombre, :apellido, :email, :telefono, :direccion, :avatar, :dni, :edad, :sexo)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo
        ));
        echo 'add';
    }

    // Método para buscar clientes
    function buscar() {
        if (!empty($_POST['consulta'])) {
            $consulta = $_POST['consulta'];
            $sql = "SELECT * FROM clientes WHERE nombre LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta' => "%$consulta%"));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        } else {
            $sql = "SELECT * FROM clientes";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }
    }

    // Método para editar un cliente existente
    public function editar($id, $nombre, $apellido, $email, $telefono, $direccion, $avatar, $dni, $edad, $sexo) {
        $query = "UPDATE clientes SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, 
                  direccion = :direccion, avatar = :avatar, dni = :dni, edad = :edad, sexo = :sexo 
                  WHERE id = :id";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':id' => $id
        ));
    }

    // Método para eliminar un cliente
    public function eliminar($id) {
        $query = "DELETE FROM clientes WHERE id = :id";
        $stmt = $this->acceso->prepare($query);
    
        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute(array(':id' => $id))) {
            echo 'eliminado'; // Mensaje de éxito
        } else {
            echo 'noeliminado'; // Mensaje de error
        }
    }

    // Método para verificar si el cliente ya existe
    public function existeCliente($nombre, $email, $id = null) {
        $query = "SELECT * FROM clientes WHERE (nombre = :nombre OR apellido = :apellido OR email = :email)";
        
        // Si se proporciona un ID, lo ignoramos para la comparación
        if ($id) {
            $query .= " AND id != :id";
        }

        $stmt = $this->acceso->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $nombre);
        $stmt->bindParam(':email', $email);
        
        // Si se proporciona un ID, lo vinculamos también
        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        $stmt->execute();
        
        // Si hay algún resultado, el cliente ya existe
        return $stmt->rowCount() > 0;
    }


    //--------------------------------
    //Cambiar Avatar    
    //--------------------------------
    function CambiarLogo($id, $img){
        //Consulta el nombre de la imagen antes de borrarla
        $sql = 'SELECT avatar from clientes WHERE id = :id';        
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos = $query->fetchall();
        
        //Actualiza la imagen
        $sql = 'UPDATE clientes SET avatar = :img WHERE id = :id';
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id,':img'=>$img));

        //Retorna la imagen antigua
        return $this->objetos;
        
    }


}
?>