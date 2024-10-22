<?php
    include_once 'Conexion.php';

    class Tipo_Producto {
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
                $sql = "SELECT * FROM tipo_producto WHERE nombre LIKE :consulta";      
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta'=>"%$consulta%"));
                $this->objetos = $query->fetchall();
            }
            else{
                $sql = "SELECT * FROM tipo_producto WHERE nombre NOT LIKE '' ORDER BY id_tip_prod";          
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
            }
            //Retorna la consulta
            return $this->objetos;    
        }
        
        
        //-----------------------------------------------------------
        // Buscar un registro por ID
        //-----------------------------------------------------------
        function Buscar($id){
            $sql = "SELECT * FROM tipo_producto WHERE id_tip_prod = :id";          
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            
            //Retorna la consulta
            return $this->objetos;    
        }

        //-------------------------------------------
        //Crear
        //-------------------------------------------
        function Crear($id, $nombre){
            $sql = "SELECT id_tipo_prod FROM tipo_producto WHERE id_tip_prod = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            if(!empty($this->objetos)){
                echo 'noadd';
            }
            else{
                $sql = "INSERT INTO tipo_producto (id_tip_prod,nombre)
                        VALUES (:id,:nombre)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id, ':nombre'=>$nombre));
                echo 'add';
            }
        }

        //-----------------------------------------------------------
        // Editar
        //-----------------------------------------------------------
        function Editar($id, $nombre){
            $sql = "UPDATE tipo_producto SET nombre =:nombre 
                    WHERE id_tip_prod = :id";        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id,':nombre'=>$nombre));
            echo 'update';
         }

        //-----------------------------------------------------------
        // Eliminar
        //-----------------------------------------------------------
        function Eliminar($id){
            $sql = "DELETE FROM tipo_producto WHERE id_tip_prod = :id";        
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
            $sql = "SELECT * FROM tipo_producto ORDER BY nombre asc";        
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall(); 
            //Retorna la consulta
            return $this->objetos;
        }

    }

?>