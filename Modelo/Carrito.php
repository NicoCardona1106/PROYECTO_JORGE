<?php
include_once 'Conexion.php';

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

    // Obtener los productos del carrito del usuario
    public function obtenerCarrito($id_usuario) {
        $query = "SELECT p.*, c.cantidad, p.id_proveedor 
                  FROM carrito c 
                  JOIN producto p ON c.id_producto = p.id_producto 
                  WHERE c.id_usuario = ?";
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
        $query = "SELECT SUM(p.precio * c.cantidad) as total 
                  FROM carrito c 
                  JOIN producto p ON c.id_producto = p.id_producto 
                  WHERE c.id_usuario = ?";
        $stmt = $this->acceso->prepare($query);
        $stmt->execute([$id_usuario]);
        $total = $stmt->fetchColumn();

        // Si el total es null (cuando no hay productos), retornamos 0
        return $total !== null ? $total : 0;
    }

    // **Nuevo método para procesar la compra**
    public function finalizarCompra($id_usuario, $estado = 'PENDIENTE') {
        try {
            $this->acceso->beginTransaction();

            // Obtener los productos del carrito
            $productos = $this->obtenerCarrito($id_usuario);
            if (empty($productos)) {
                throw new Exception("El carrito está vacío.");
            }

            // Calcular el total
            $total = $this->calcularTotal($id_usuario);

            // Insertar una nueva compra
            $query = "INSERT INTO compras (id_usuario, fecha, total, estado) VALUES (?, NOW(), ?, ?)";
            $stmt = $this->acceso->prepare($query);
            $stmt->execute([$id_usuario, $total, $estado]);

            // Obtener el ID de la compra recién creada
            $id_compra = $this->acceso->lastInsertId();

            // Insertar los detalles de la compra
            foreach ($productos as $producto) {
                $query = "INSERT INTO detalle_compras (id_compra, id_producto, id_proveedor, cantidad, precio_unitario) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([
                    $id_compra,
                    $producto['id_producto'],
                    $producto['id_proveedor'], // Incluye el proveedor
                    $producto['cantidad'],
                    $producto['precio']
                ]);
            }

            // Vaciar el carrito después de finalizar la compra
            $query = "DELETE FROM carrito WHERE id_usuario = ?";
            $stmt = $this->acceso->prepare($query);
            $stmt->execute([$id_usuario]);

            $this->acceso->commit();
            return ['success' => true, 'message' => 'Compra procesada correctamente.', 'id_compra' => $id_compra];
        } catch (Exception $e) {
            $this->acceso->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
