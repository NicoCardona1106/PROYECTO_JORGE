<?php
include_once 'Conexion.php';

class Proveedor {
    var $objetos;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    // Método para crear un proveedor desde un usuario
    function crearDesdeUsuario($id_usuario, $nombre, $apellido, $dni, $edad, $sexo, $correo, $telefono, $direccion, $avatar) {
        $sql = "INSERT INTO proveedor(nombre, apellido, dni, edad, sexo, correo, telefono, direccion, avatar, id_usuario) 
                VALUES (:nombre, :apellido, :dni, :edad, :sexo, :correo, :telefono, :direccion, :avatar, :id_usuario)";
        $query = $this->acceso->prepare($sql);
        $resultado = $query->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':avatar' => $avatar,
            ':id_usuario' => $id_usuario
        ));
        return $resultado;
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
            return $stmt->execute(array(':id_proveedor' => $id_proveedor)); // Retornar true o false
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
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            
            //Actualiza la imagen
            $sql = 'UPDATE proveedor SET avatar = :img WHERE id_proveedor = :id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id,':img'=>$img));

            //Retorna la imagen antigua
            return $this->objetos;
            
        }

        //-----------------------------
        // Historial de Ventas
        //-----------------------------
        public function obtenerHistorialVentas($id_proveedor) {
            try {
                $query = "
                    SELECT 
                        dc.id_detalle, 
                        dc.id_compra, 
                        p.nombre AS producto, 
                        dc.cantidad, 
                        dc.precio_unitario, 
                        (dc.cantidad * dc.precio_unitario) AS total, 
                        c.fecha
                    FROM detalle_compras dc
                    JOIN producto p ON dc.id_producto = p.id_producto
                    JOIN compras c ON dc.id_compra = c.id_compra
                    WHERE dc.id_proveedor = :id_proveedor AND dc.estado = 'APROBADO'
                    ORDER BY c.fecha DESC
                ";
                
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':id_proveedor' => $id_proveedor]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("Error en obtenerHistorialVentas: " . $e->getMessage());
                return ['status' => 'error', 'message' => 'Error al obtener el historial de ventas.'];
            }
        }
        
        public function obtenerTotalVendido($id_proveedor) {
            try {
                $query = "
                    SELECT 
                        SUM(dc.cantidad * dc.precio_unitario) AS total_vendido
                    FROM detalle_compras dc
                    WHERE dc.id_proveedor = :id_proveedor AND dc.estado = 'APROBADO'
                ";
                
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':id_proveedor' => $id_proveedor]);
                return $stmt->fetch(PDO::FETCH_ASSOC)['total_vendido'];
            } catch (Exception $e) {
                error_log("Error en obtenerTotalVendido: " . $e->getMessage());
                return 0;
            }
        }
        


        //-----------------------------
        // Manejo De Órdenes
        //-----------------------------
        public function obtenerOrdenesPorProveedor($id_proveedor) {
            try {
                $query = "
                    SELECT 
                        dc.id_detalle, 
                        dc.id_compra, 
                        p.nombre AS producto, 
                        dc.cantidad, 
                        dc.precio_unitario, 
                        dc.estado, 
                        c.fecha
                    FROM detalle_compras dc
                    JOIN producto p ON dc.id_producto = p.id_producto
                    JOIN compras c ON dc.id_compra = c.id_compra
                    WHERE dc.id_proveedor = :id_proveedor AND dc.estado IN ('PENDIENTE', 'RECHAZADO')
                    ORDER BY c.fecha DESC
                ";
                
                $stmt = $this->acceso->prepare($query); // Preparar la consulta
                $stmt->execute([':id_proveedor' => $id_proveedor]); // Pasar parámetros
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener resultados
            } catch (Exception $e) {
                error_log("Error en obtenerOrdenesPorProveedor: " . $e->getMessage());
                return ['status' => 'error', 'message' => 'Error al obtener órdenes del proveedor.'];
            }
        }
        

        public function actualizarEstadoProducto($id_detalle, $nuevo_estado) {
            try {
                // Actualizar el estado del producto en detalle_compras
                $query = "UPDATE detalle_compras SET estado = :estado WHERE id_detalle = :id_detalle";
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':estado' => $nuevo_estado, ':id_detalle' => $id_detalle]);
        
                // Restar la cantidad del producto si el nuevo estado es 'APROBADO'
                if ($nuevo_estado === 'APROBADO') {
                    $this->restarCantidadProducto($id_detalle);
                }
        
                // Actualizar el estado de la compra si es necesario
                $this->actualizarEstadoCompra($id_detalle);
        
                return ['status' => 'success', 'message' => 'Estado actualizado correctamente'];
            } catch (Exception $e) {
                error_log("Error al actualizar estado del producto: " . $e->getMessage());
                return ['status' => 'error', 'message' => 'No se pudo actualizar el estado'];
            }
        }
        
        private function restarCantidadProducto($id_detalle) {
            try {
                // Obtener la cantidad y el producto relacionado con el detalle
                $query = "
                    SELECT dc.id_producto, dc.cantidad, p.cantidad AS stock_actual
                    FROM detalle_compras dc
                    JOIN producto p ON dc.id_producto = p.id_producto
                    WHERE dc.id_detalle = :id_detalle
                ";
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':id_detalle' => $id_detalle]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($producto) {
                    $nueva_cantidad = $producto['stock_actual'] - $producto['cantidad'];
                    if ($nueva_cantidad < 0) {
                        error_log("Error: La cantidad en stock no puede ser negativa para el producto ID: " . $producto['id_producto']);
                        return;
                    }
        
                    // Actualizar el stock del producto
                    $query_update = "UPDATE producto SET cantidad = :nueva_cantidad WHERE id_producto = :id_producto";
                    $stmt_update = $this->acceso->prepare($query_update);
                    $stmt_update->execute([
                        ':nueva_cantidad' => $nueva_cantidad,
                        ':id_producto' => $producto['id_producto']
                    ]);
                }
            } catch (Exception $e) {
                error_log("Error al restar cantidad del producto: " . $e->getMessage());
            }
        }        
        

        private function actualizarEstadoCompra($id_detalle) {
            try {
                // Verificar el estado de los productos en la misma compra
                $query = "
                    SELECT 
                        dc.id_compra, 
                        SUM(dc.estado = 'APROBADO') AS aprobados, 
                        SUM(dc.estado = 'RECHAZADO') AS rechazados, 
                        COUNT(dc.id_detalle) AS total
                    FROM detalle_compras dc
                    WHERE dc.id_compra = (SELECT id_compra FROM detalle_compras WHERE id_detalle = :id_detalle)
                    GROUP BY dc.id_compra
                ";
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':id_detalle' => $id_detalle]);
        
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // Determinar el nuevo estado de la compra
                $estado_compra = 'PENDIENTE';
                if ($result['rechazados'] > 0) {
                    $estado_compra = 'RECHAZADO';
                } elseif ($result['aprobados'] == $result['total']) {
                    $estado_compra = 'PAGADO';
                }
        
                // Actualizar el estado de la compra
                $query = "UPDATE compras SET estado = :estado WHERE id_compra = :id_compra";
                $stmt = $this->acceso->prepare($query);
                $stmt->execute([':estado' => $estado_compra, ':id_compra' => $result['id_compra']]);
            } catch (Exception $e) {
                error_log("Error al actualizar estado de la compra: " . $e->getMessage());
            }
        }
    }
?>
