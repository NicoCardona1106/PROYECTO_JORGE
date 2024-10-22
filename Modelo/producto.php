<?php

    include_once 'Conexion.php';

    class Producto {
        var $objetos;

        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        //-----------------------------------------------------------
        // Buscar los registros segun criterio de busqueda en consulta
        //-----------------------------------------------------------
        function BuscarTodos($consulta){
            if(!empty($consulta)){                
                $sql = "SELECT id_producto, producto.nombre, concentracion, adicional, precio, producto.id_laboratorio,
                                laboratorio.nombre as laboratorio, producto.id_tip_prod, tipo_producto.nombre as tipo, 
                                producto.id_presentacion, presentacion.nombre as presentacion, producto.avatar, proveedor.id_proveedor, proveedor.nombre as proveedor
                        FROM    producto 
                        JOIN    laboratorio ON producto.id_laboratorio = laboratorio.id_laboratorio
                        JOIN    tipo_producto ON producto.id_tip_prod = tipo_producto.id_tip_prod
                        JOIN    presentacion ON producto.id_presentacion = presentacion.id_presentacion
                        JOIN    proveedor ON producto.id_proveedor = proveedor.id_proveedor
                        WHERE   producto.nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta'=>"%$consulta%"));
                $this->objetos = $query->fetchall();
            }
            else{
                $sql = "SELECT id_producto, producto.nombre, concentracion, adicional, precio, producto.id_laboratorio,
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
        
        
        
        //-----------------------------------------------------------
        // Buscar un registro por ID
        //-----------------------------------------------------------
        function Buscar($id){
            $sql = "SELECT id_producto, nombre, concentracion, adicional, precio, id_laboratorio, id_tip_prod, 
                           id_presentacion, avatar, id_proveedor 
                    FROM producto WHERE id_producto = :id";          
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            
            return $this->objetos;    
        }
        

        //-------------------------------------------
        //Crear
        //-------------------------------------------
        function Crear($id, $nombre, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion, $avatar, $proveedor){
            $sql = "SELECT id_producto FROM producto WHERE id_producto = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            if(!empty($this->objetos)){
                echo 'noadd';
            }
            else{
                $sql = "INSERT INTO producto (id_producto, nombre, concentracion, adicional,
                            precio, id_laboratorio, id_tip_prod, id_presentacion, avatar, id_proveedor)
                        VALUES (:id, :nombre, :concentracion, :adicional, :precio,
                                :laboratorio, :tipo, :presentacion, :avatar, :proveedor)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id, ':nombre'=>$nombre, ':concentracion'=>$concentracion,
                                      ':adicional'=>$adicional, ':precio'=>$precio,
                                      ':laboratorio'=>$laboratorio, ':tipo'=>$tipo,
                                      ':presentacion'=>$presentacion, ':avatar'=>$avatar,
                                      ':proveedor'=>$proveedor));
                echo 'add';
            }
        }
        
        //-----------------------------------------------------------
        // Editar
        //-----------------------------------------------------------
        function Editar($id, $nombre, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion, $proveedor){
            $sql = "UPDATE producto SET nombre = :nombre, concentracion = :concentracion,
                           adicional = :adicional, precio = :precio, 
                           id_laboratorio = :laboratorio, id_tip_prod = :tipo,
                           id_presentacion = :presentacion, id_proveedor = :proveedor 
                    WHERE id_producto = :id";        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id, ':nombre'=>$nombre, ':concentracion'=>$concentracion,
                                  ':adicional'=>$adicional, ':precio'=>$precio,
                                  ':laboratorio'=>$laboratorio, ':tipo'=>$tipo,
                                  ':presentacion'=>$presentacion, ':proveedor'=>$proveedor));
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


    }

?>