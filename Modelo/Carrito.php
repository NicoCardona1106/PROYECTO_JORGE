<?php
include_once 'Conexion.php'; // Asegúrate de que la clase Conexion esté correctamente definida

class Carrito {

    private $acceso;

    public function __construct() {
        // Obtiene la conexión a la base de datos a través de la clase Conexion
        $db = new Conexion();
        $this->acceso = $db->pdo; // La conexión PDO debe ser asignada a $this->acceso
    }

    // Método para agregar un producto al carrito (en base de datos)
    public function agregarProducto($id_usuario, $id_producto) {
        // Verificar si el producto ya está en el carrito
        $query = "SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute([$id_usuario, $id_producto]);
        $producto_en_carrito = $stmt->fetch();

        if ($producto_en_carrito) {
            // Si ya existe, incrementamos la cantidad
            $query = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = ? AND id_producto = ?";
            $stmt = $this->acceso->prepare($query);
            $stmt->execute([$id_usuario, $id_producto]);
        } else {
            // Si no existe, agregamos el producto al carrito
            $query = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)";
            $stmt = $this->acceso->prepare($query);
            $stmt->execute([$id_usuario, $id_producto]);
        }
    }

    // Obtener los producto del carrito del usuario
    public function obtenerCarrito($id_usuario) {
        $query = "SELECT p.*, c.cantidad FROM carrito c JOIN producto p ON c.id_producto = p.id_producto WHERE c.id_usuario = ?";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar un producto del carrito
    public function eliminarProducto($id_usuario, $id_producto) {
        $query = "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute([$id_usuario, $id_producto]);
    }

    // Calcular el total del carrito
    public function calcularTotal($id_usuario) {
        $query = "SELECT SUM(p.precio * c.cantidad) as total FROM carrito c JOIN producto p ON c.id_producto = p.id_producto WHERE c.id_usuario = ?";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn();
    }
}
?>
