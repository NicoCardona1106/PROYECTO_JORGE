<?php
include_once 'Conexion.php';

class Productos {
    var $objetos; // Variable para almacenar el resultado de las consultas

    public function __construct() {
        // Constructor que establece la conexión a la base de datos
        $db = new Conexion();
        $this->acceso = $db->pdo; // Utiliza PDO para la conexión
    }

    function listar() {
        $sql = "SELECT 
                    id_producto, 
                    nombre, 
                    concentracion, 
                    adicional, 
                    precio, 
                    avatar, 
                    cantidad, 
                    tipo_descuento, 
                    precio_original,
                    CASE 
                        WHEN precio_original > 0 THEN ROUND(((precio_original - precio) / precio_original) * 100, 2) 
                        ELSE NULL 
                    END AS descuento_porcentaje
                FROM producto 
                WHERE precio_original > 0"; // Filtrar productos con descuentos
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $this->objetos;
    }

    function verificarYActualizarPrecios() {
        try {
            $sql = "SELECT id_producto, cantidad, precio, precio_original FROM producto";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $productos = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($productos as $producto) {
                $id_producto = $producto['id_producto'];
                $cantidad = $producto['cantidad'];
                $precio_actual = $producto['precio'];
                $precio_original = $producto['precio_original'];
                $tipo_descuento = null;
                $precio_con_descuento = $precio_actual;

                // Restaurar precio antes de recalcular descuentos si ya hay un descuento aplicado
                if ($precio_original > 0) {
                    $precio_actual = $precio_original;
                }

                // Aplicar descuentos dinámicos
                if ($cantidad > 50) {
                    $tipo_descuento = "30% de descuento por alto inventario";
                    $precio_con_descuento = $precio_actual * 0.7;
                } elseif ($cantidad > 30 && $precio_actual > 30000) {
                    $tipo_descuento = "25% de descuento combinado";
                    $precio_con_descuento = $precio_actual * 0.75;
                } elseif ($cantidad > 20) {
                    $tipo_descuento = "20% de descuento por inventario medio";
                    $precio_con_descuento = $precio_actual * 0.8;
                } elseif ($precio_actual > 50000) {
                    $tipo_descuento = "15% de descuento por alto valor";
                    $precio_con_descuento = $precio_actual * 0.85;
                }

                // Actualizar producto con descuento
                if ($tipo_descuento) {
                    if ($precio_original == 0 || $precio_original == null) {
                        // Aplicar descuento por primera vez
                        $sql_update = "UPDATE producto 
                                       SET precio = :precio_con_descuento, 
                                           precio_original = :precio_actual, 
                                           tipo_descuento = :tipo_descuento 
                                       WHERE id_producto = :id_producto";
                        $query_update = $this->acceso->prepare($sql_update);
                        $query_update->execute([
                            ':precio_con_descuento' => $precio_con_descuento,
                            ':precio_actual' => $precio_actual,
                            ':tipo_descuento' => $tipo_descuento,
                            ':id_producto' => $id_producto
                        ]);
                    } elseif ($precio_actual != $precio_con_descuento) {
                        // Asegurarse de que el precio con descuento es correcto
                        $sql_fix = "UPDATE producto 
                                    SET precio = :precio_con_descuento 
                                    WHERE id_producto = :id_producto";
                        $query_fix = $this->acceso->prepare($sql_fix);
                        $query_fix->execute([
                            ':precio_con_descuento' => $precio_con_descuento,
                            ':id_producto' => $id_producto
                        ]);
                    }
                } else {
                    // Restaurar el precio original si no hay descuentos
                    if ($precio_original != 0 && $precio_original != null) {
                        $sql_restore = "UPDATE producto 
                                        SET precio = :precio_original, 
                                            precio_original = 0, 
                                            tipo_descuento = NULL 
                                        WHERE id_producto = :id_producto";
                        $query_restore = $this->acceso->prepare($sql_restore);
                        $query_restore->execute([
                            ':precio_original' => $precio_original,
                            ':id_producto' => $id_producto
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error al verificar y actualizar precios: " . $e->getMessage());
        }
    }

    
}
?>
