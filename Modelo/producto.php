<?php

include_once 'Conexion.php';

class Producto {
    var $objetos;

    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

        //-----------------------------------------------------------
        // Buscar los registros segun criterio de busqueda en consulta
        //-----------------------------------------------------------
        function BuscarTodos($consulta) {
            if (!empty($consulta)) {
                $sql = "SELECT id_producto, producto.nombre, concentracion, adicional, precio, cantidad, producto.id_laboratorio,
                                laboratorio.nombre as laboratorio, producto.id_tip_prod, tipo_producto.nombre as tipo, 
                                producto.id_presentacion, presentacion.nombre as presentacion, producto.avatar, proveedor.id_proveedor, proveedor.nombre as proveedor
                        FROM    producto 
                        JOIN    laboratorio ON producto.id_laboratorio = laboratorio.id_laboratorio
                        JOIN    tipo_producto ON producto.id_tip_prod = tipo_producto.id_tip_prod
                        JOIN    presentacion ON producto.id_presentacion = presentacion.id_presentacion
                        JOIN    proveedor ON producto.id_proveedor = proveedor.id_proveedor
                        WHERE   producto.nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
            } else {
                $sql = "SELECT id_producto, producto.nombre, concentracion, adicional, precio, cantidad, producto.id_laboratorio,
                                laboratorio.nombre as laboratorio, producto.id_tip_prod, tipo_producto.nombre as tipo, 
                                producto.id_presentacion, presentacion.nombre as presentacion, producto.avatar, proveedor.id_proveedor, proveedor.nombre as proveedor
                        FROM    producto 
                        JOIN    laboratorio ON producto.id_laboratorio = laboratorio.id_laboratorio
                        JOIN    tipo_producto ON producto.id_tip_prod = tipo_producto.id_tip_prod
                        JOIN    presentacion ON producto.id_presentacion = presentacion.id_presentacion
                        JOIN    proveedor ON producto.id_proveedor = proveedor.id_proveedor
                        WHERE   producto.nombre NOT LIKE '' 
                        ORDER BY id_producto";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
            }
            return $this->objetos;
        }

        //--------------------------------
        //Obtener Ultimo ID
        //--------------------------------
        public function ObtenerNuevoId() {
            try {
                $sql = "SELECT COALESCE(MAX(id_producto) + 1, 1) AS nuevoId FROM producto";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
        
                // Validar que la consulta retorne un valor válido
                if ($result && isset($result['nuevoId'])) {
                    return $result['nuevoId'];
                } else {
                    // Si no hay resultados, devolver 1 como valor por defecto
                    return 1;
                }
            } catch (Exception $e) {
                // Manejar errores
                error_log("Error al obtener el nuevo ID: " . $e->getMessage());
                return 1; // Valor por defecto en caso de error
            }
        }
        
        public function restaurarPrecioOriginal($id_producto) {
            try {
                // Verificar la cantidad actual del producto
                $sql_check = "SELECT cantidad, precio_original FROM producto WHERE id_producto = :id_producto";
                $stmt_check = $this->acceso->prepare($sql_check);
                $stmt_check->execute(['id_producto' => $id_producto]);
                $producto = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
                if ($producto && $producto['cantidad'] < 20) {
                    // Actualizar el precio al precio original
                    $sql_update = "UPDATE producto SET precio = :precio_original WHERE id_producto = :id_producto";
                    $stmt_update = $this->acceso->prepare($sql_update);
                    $stmt_update->execute([
                        'precio_original' => $producto['precio_original'],
                        'id_producto' => $id_producto
                    ]);
        
                    return true; // Precio actualizado correctamente
                }
                return false; // No se necesitó actualizar el precio
            } catch (PDOException $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        //-----------------------------------------------------------
        // Buscar un registro por ID
        //-----------------------------------------------------------
        function Buscar($id) {
            $sql = "SELECT id_producto, nombre, concentracion, adicional, precio, cantidad, id_laboratorio, id_tip_prod, 
                        id_presentacion, avatar, id_proveedor 
                    FROM producto WHERE id_producto = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();

            return $this->objetos;
        }
                

        //-------------------------------------------
        // Crear
        //-------------------------------------------
        function Crear($id, $nombre, $concentracion, $adicional, $precio, $cantidad, $laboratorio, $tipo, $presentacion, $avatar, $proveedor) {
            $sql = "SELECT id_producto FROM producto WHERE id_producto = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();
            if (!empty($this->objetos)) {
                echo 'noadd';
            } else {
                $sql = "INSERT INTO producto (id_producto, nombre, concentracion, adicional,
                            precio, cantidad, id_laboratorio, id_tip_prod, id_presentacion, avatar, id_proveedor)
                        VALUES (:id, :nombre, :concentracion, :adicional, :precio, :cantidad,
                                :laboratorio, :tipo, :presentacion, :avatar, :proveedor)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':nombre' => $nombre, ':concentracion' => $concentracion,
                    ':adicional' => $adicional, ':precio' => $precio, ':cantidad' => $cantidad,
                    ':laboratorio' => $laboratorio, ':tipo' => $tipo,
                    ':presentacion' => $presentacion, ':avatar' => $avatar,
                    ':proveedor' => $proveedor));
                echo 'add';
            }
        }
            
        //-----------------------------------------------------------
        // Editar
        //-----------------------------------------------------------
        function Editar($id, $nombre, $concentracion, $adicional, $precio, $cantidad, $laboratorio, $tipo, $presentacion, $proveedor) {
            $sql = "UPDATE producto SET nombre = :nombre, concentracion = :concentracion,
                        adicional = :adicional, precio = :precio, cantidad = :cantidad,
                        id_laboratorio = :laboratorio, id_tip_prod = :tipo,
                        id_presentacion = :presentacion, id_proveedor = :proveedor 
                    WHERE id_producto = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre, ':concentracion' => $concentracion,
                ':adicional' => $adicional, ':precio' => $precio, ':cantidad' => $cantidad,
                ':laboratorio' => $laboratorio, ':tipo' => $tipo,
                ':presentacion' => $presentacion, ':proveedor' => $proveedor));
            echo 'update';
        }

        //-----------------------------------------------------------
        // Eliminar
        //-----------------------------------------------------------
        function Eliminar($id){
            $sql = "DELETE FROM producto WHERE id_producto = :id";        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id)); 
            if(!empty($query->execute(array(':id'=>$id))))
                echo 'eliminado';
            else 
                echo 'noeliminado';
        }

        //-----------------------------------------------------------
        // Funcion para cargar un ComboBox
        //-----------------------------------------------------------
        function Seleccionar(){
            $sql = "SELECT * FROM producto ORDER BY nombre asc";        
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall(); 
            //Retorna la consulta
            return $this->objetos;
        }


        //--------------------------------
        //Cambiar Avatar
        //--------------------------------
        function CambiarLogo($id, $img){
            //Consulta el nombre de la imagen antes de borrarla
            $sql = 'SELECT avatar from producto WHERE id_producto = :id';        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            
            //Actualiza la imagen
            $sql = 'UPDATE producto SET avatar = :img WHERE id_producto = :id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id,':img'=>$img));

            //Retorna la imagen antigua
            return $this->objetos;
            
        }


        //--------------------------------
        //Buscar Por Proveedor
        //--------------------------------

        public function BuscarPorProveedor($idProveedor) {
            $sql = "SELECT id_producto, producto.nombre, concentracion, adicional, precio, cantidad, producto.id_laboratorio,
                            laboratorio.nombre as laboratorio, producto.id_tip_prod, tipo_producto.nombre as tipo, 
                            producto.id_presentacion, presentacion.nombre as presentacion, producto.avatar
                    FROM    producto 
                    JOIN    laboratorio ON producto.id_laboratorio = laboratorio.id_laboratorio
                    JOIN    tipo_producto ON producto.id_tip_prod = tipo_producto.id_tip_prod
                    JOIN    presentacion ON producto.id_presentacion = presentacion.id_presentacion
                    WHERE   producto.id_proveedor = :id_proveedor";
            
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_proveedor' => $idProveedor));
            $this->objetos = $query->fetchAll(PDO::FETCH_OBJ);
        
            return $this->objetos;
        }        
        

        //--------------------------------
        //Buscar Productos con Descuento
        //--------------------------------

        public function obtenerProductosConDescuento() {
            try {
                $sql = "SELECT 
                            id_producto, 
                            nombre, 
                            concentracion, 
                            adicional, 
                            precio, 
                            precio_original, 
                            avatar, 
                            tipo_descuento 
                        FROM producto 
                        WHERE tipo_descuento IS NOT NULL";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("Error al obtener productos con descuento: " . $e->getMessage());
                return [];
            }
        }

    }

?>