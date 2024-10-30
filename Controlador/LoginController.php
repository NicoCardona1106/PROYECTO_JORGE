<?php 
    session_start();
    include_once '../modelo/Usuario.php';
    $usuario = new Usuario();
    
    // Comprobar si se está intentando hacer login como invitado
    if (isset($_POST['action']) && $_POST['action'] == 'guest_login') {
        // Configurar la sesión para el invitado
        $_SESSION['id_tipo_us'] = 4;
        $_SESSION['nombre_us'] = 'Invitado';
        $_SESSION['dni_us'] = 'GUEST';
        
        // Redirigir al invitado a la página de clientes
        header('location: ../vista/PaginaCI.php');
        exit();
    }

    $dni_us = $_POST['dni_us'];
    $contrasena_us = $_POST['contrasena_us'];

    // Si la sesión ya está creada (Ya se logueó)
    if (!empty($_SESSION['id_tipo_us'])){
        switch ($_SESSION['id_tipo_us']){
            case 1:
                header ('location: ../vista/adm_nav.php');
                break;
            case 2:
                header ('location: ../vista/PaginaCI.php');
                break;
            case 3:
                header ('location: ../vista/PaginaP.php');
                break;
            case 4:
                header ('location: ../vista/PaginaCI.php');
                break;
        }
    }
    else{
        $usuario->Loguearse($dni_us, $contrasena_us);
        // Valida que el objeto venga con datos
        if (!empty($usuario->objetos)){
            foreach($usuario->objetos as $key => $objeto){
                // Si el password es correcto debe crear la SESSION[]
                $_SESSION['id_usuario']=$objeto->id_usuario;
                $_SESSION['id_tipo_us']=$objeto->id_tipo_us;
                $_SESSION['nombre_us']=$objeto->nombre_us;
                $_SESSION['dni_us']=$objeto->dni_us;
            }
            switch ($_SESSION['id_tipo_us']){
                case 1:
                    header ('location: ../vista/adm_nav.php');
                    break;
                case 2:
                    header ('location: ../vista/PaginaCI.php');
                    break;
                case 3:
                    header ('location: ../vista/PaginaP.php');
                    break;
                case 4:
                    header ('location: ../vista/PaginaCI.php');
                    break;
            }
        }
        else {
            header ('location: ../vista/login.php');
            echo json_encode(array('status' => 'error', 'message' => 'Datos incorrectos. Por favor, verifica e intenta nuevamente.'));
        }
    }
?>
