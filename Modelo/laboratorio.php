<?php
    include_once 'Conexion.php';

    class Laboratorio {
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
                $sql = "SELECT * FROM laboratorio WHERE nombre LIKE :consulta";      
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta'=>"%$consulta%"));
                $this->objetos = $query->fetchall();
            }
            else{
                $sql = "SELECT * FROM laboratorio WHERE nombre NOT LIKE '' ORDER BY id_laboratorio";          
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
            $sql = "SELECT * FROM laboratorio WHERE id_laboratorio = :id";          
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
            $sql = "SELECT id_laboratorio FROM laboratorio WHERE id_laboratorio = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchall();
            if(!empty($this->objetos)){
                echo 'noaddlaboratorio';
            }
            else{
                $sql = "INSERT INTO laboratorio (id_laboratorio,nombre)
                        VALUES (:id,:nombre)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id, ':nombre'=>$nombre));
                echo 'addlaboratorio';
            }
        }

        //-----------------------------------------------------------
        // Editar
        //-----------------------------------------------------------
        function Editar($id, $nombre){
            $sql = "UPDATE laboratorio SET nombre =:nombre 
                    WHERE id_laboratorio = :id";        
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id,':nombre'=>$nombre));
            echo 'updatelaboratorio';
         }

         //-----------------------------------------------------------
        // Eliminar
        //-----------------------------------------------------------
        function Eliminar($id){
            $sql = "DELETE FROM laboratorio WHERE id_laboratorio = :id";        
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
            $sql = "SELECT * FROM laboratorio ORDER BY nombre asc";        
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall(); 
            //Retorna la consulta
            return $this->objetos;
        }

    }

?>