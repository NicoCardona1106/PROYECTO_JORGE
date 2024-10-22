<?php
    include_once 'Conexion.php';

    class Presentacion {
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
                $sql = "SELECT * FROM presentacion WHERE nombre LIKE :consulta";      
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta'=>"%$consulta%"));
                $this->objetos = $query->fetchall();
            }
            else{
                $sql = "SELECT * FROM presentacion WHERE nombre NOT LIKE '' ORDER BY id_presentacion";          
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
            $sql = "SELECT * FROM presentacion WHERE id_presentacion = :id";          
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
            $sql = "SELECT id_presentacion FROM presentacion WHERE id_presentacion = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            if(!empty($this->objetos)){
                echo 'noadd';
            }
            else{
                $sql = "INSERT INTO presentacion (id_presentacion,nombre)
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
            $sql = "UPDATE presentacion SET nombre =:nombre 
                    WHERE id_presentacion = :id";        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id,':nombre'=>$nombre));
            echo 'update';
         }

        //-----------------------------------------------------------
        // Eliminar
        //-----------------------------------------------------------
        function Eliminar($id){
            $sql = "DELETE FROM presentacion WHERE id_presentacion = :id";        
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
            $sql = "SELECT * FROM presentacion ORDER BY nombre asc";        
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall(); 
            //Retorna la consulta
            return $this->objetos;
        }

    }

?>