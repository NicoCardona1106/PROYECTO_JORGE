<?php
include_once 'Conexion.php';

class Proveedor {
    var $objetos;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    // Método para crear un proveedor
    function crear($nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar) {
        $sql = "INSERT INTO proveedor(nombre, apellido, dni, edad, sexo, correo, telefono, direccion, avatar) 
                VALUES (:nombre, :apellido, :dni, :edad, :sexo, :correo, :telefono, :direccion, :avatar)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar
        ));
        echo 'add';
    }

    // Método para buscar proveedores
    function buscar() {
        if (!empty($_POST['consulta'])) {
            $consulta = $_POST['consulta'];
            $sql = "SELECT * FROM proveedor WHERE nombre LIKE :consulta OR apellido LIKE :consulta OR dni LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta' => "%$consulta%"));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        } else {
            $sql = "SELECT * FROM proveedor";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }
    }

    // Método para editar un proveedor existente
    public function editar($id_proveedor, $nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar) {
        $query = "UPDATE proveedor SET nombre = :nombre, apellido = :apellido, dni = :dni, edad = :edad, sexo = :sexo, 
                  correo = :correo, telefono = :telefono, direccion = :direccion, avatar = :avatar 
                  WHERE id_proveedor = :id_proveedor";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar,
            ':id_proveedor' => $id_proveedor // Cambiado para usar id_proveedor
        ));
    }

    // Método para eliminar un proveedor
    public function eliminar($id_proveedor) {
        $query = "DELETE FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute(array(':id_proveedor' => $id_proveedor)); // Cambiado para usar id_proveedor
    }

    // Método para verificar si el proveedor ya existe
    public function existeProveedor($nombre, $apellido, $dni, $correo, $id_proveedor = null) {
        $query = "SELECT * FROM proveedor WHERE (nombre = :nombre OR apellido = :apellido OR dni = :dni OR correo = :correo)";
        
        // Si se proporciona un ID, lo ignoramos para la comparación
        if ($id_proveedor) {
            $query .= " AND id_proveedor != :id_proveedor"; // Cambiado a id_proveedor
        }

        $stmt = $this->acceso->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':correo', $correo);
        
        // Si se proporciona un ID, lo vinculamos también
        if ($id_proveedor) {
            $stmt->bindParam(':id_proveedor', $id_proveedor); // Cambiado a id_proveedor
        }

        $stmt->execute();
        
        // Si hay algún resultado, el proveedor ya existe
        return $stmt->rowCount() > 0;
    }

    //-----------------------------------------------------------
    // Funcion para cargar un ComboBox
    //-----------------------------------------------------------
    function Seleccionar(){
        $sql = "SELECT * FROM proveedor ORDER BY nombre asc";        
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchAll(); 
        //Retorna la consulta
        return $this->objetos;
    }

    //--------------------------------
    //Cambiar Avatar
    //--------------------------------
    function CambiarLogo($id, $img){
        //Consulta el nombre de la imagen antes de borrarla
        $sql = 'SELECT avatar from proveedor WHERE id_proveedor = :id';        
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_proveedor'=>$id));
        $this->objetos = $query->fetchall();
            
        //Actualiza la imagen
        $sql = 'UPDATE proveedor SET avatar = :img WHERE id = :id';
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_proveedor'=>$id,':img'=>$img));

        //Retorna la imagen antigua
        return $this->objetos;
            
    }
}
?>
