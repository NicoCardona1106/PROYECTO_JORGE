<?php
include_once 'Conexion.php';

class Cliente {
    var $objetos;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
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
        $stmt->execute(array(':id' => $id));
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
}
?>
