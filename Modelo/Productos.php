<?php
include_once 'Conexion.php';

class Productos {
    var $objetos;  // Variable para almacenar el resultado de las consultas

    public function __construct() {
        // Constructor que establece la conexión a la base de datos
        $db = new Conexion();
        $this->acceso = $db->pdo;  // Utiliza PDO para la conexión
    }

    function listar() {
        // Consulta SQL para obtener los datos de los productos
        $sql = "SELECT id_producto,nombre, concentracion, adicional,precio,avatar FROM producto";
        
        // Prepara y ejecuta la consulta
        $query = $this->acceso->prepare($sql);
        $query->execute();
        
        // Almacena los resultados de la consulta en la variable 'objetos'
        $this->objetos = $query->fetchall(PDO::FETCH_ASSOC);
        
        // Devuelve los resultados de la consulta
        return $this->objetos;
    }
}

?>
