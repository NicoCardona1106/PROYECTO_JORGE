<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/css/adminlte.min.css">

  <!-- Estilo personalizado para agregar la imagen de fondo -->
  <style>
    body {
      background-image: url("../assets/img/Fondos/Login.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }
    .login-box {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
    }
    .btn-block {
      margin-bottom: 10px;
    }
  </style>
</head>
<?php
session_start();
if (!empty($_SESSION['id_tipo_us'])) { 	
    // Si quieres que el registro funcione incluso con sesión activa, 
    // puedes comentar la línea de redirección o agregar una condición
    // header('location: ../controlador/LoginController.php');
} else {
    session_destroy();
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <span class="h1"><b>Tienda</b> Minorista</span>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Inicio de sesión</p>
      <form action="../controlador/LoginController.php" method="post">
        <div class="input-group mb-3">
          <input type="text" name="dni_us" id="dni_us" class="form-control" placeholder="Usuario">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="contrasena_us" id="contrasena_us" class="form-control" placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row" style="display:flex; justify-content:center">
          <div class="col-12">
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">Iniciar</button>
            </div>
          </div>
        </div>
      </form>

      <div class="text-center mt-3">
        <form action="../controlador/LoginController.php" method="post">
            <button type="submit" name="action" value="guest_login" class="btn btn-secondary btn-block">
                Ingresar como invitado
            </button>
        </form>
        <button class="btn btn-success btn-block" id="btn-crear-cliente">Crear cuenta de Cliente</button>
        <button class="btn btn-info btn-block" id="btn-crear-proveedor">Crear cuenta de Proveedor</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para crear cuenta -->
<div class="modal fade" id="crearCuentaModal" tabindex="-1" role="dialog" aria-labelledby="crearCuentaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearCuentaModalLabel">Crear Cuenta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCrearCuenta">
          <input type="hidden" id="tipoUsuario" name="tipoUsuario">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el Nombre" required>
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el Apellido" required>
          </div>
          <div class="form-group">
            <label for="dni">DNI</label>
            <input type="number" class="form-control" id="dni" name="dni" placeholder="Ingrese el DNI" required>
          </div>
          <div class="form-group">
            <label for="edad">Fecha De Nacimiento</label>
            <input type="date" class="form-control" id="edad" name="edad" placeholder="Ingrese la edad" required>
          </div>
          <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese la Contraseña" required>
          </div>
          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el Teléfono" min="1" required>
          </div>
          <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese la Dirección" required>
          </div>
          <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese el Correo" required>
          </div>
          <div class="form-group">
            <label for="sexo">Sexo</label>
            <select class="form-control" id="sexo" name="sexo" required>
              <option value="">Seleccionar</option>
              <option value="Masculino">Masculino</option>
              <option value="Femenino">Femenino</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          <div class="form-group">
            <label for="infoadicional">Información Adicional</label>
            <textarea class="form-control" id="infoadicional" name="infoadicional" placeholder="Ingrese la informacion adicional" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="avatar">Avatar</label>
            <input type="file" class="form-control-file" id="avatar" name="avatar">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCuenta">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/js/adminlte.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // Detecta el parámetro "error" en la URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('error')) {
    Swal.fire({
      icon: 'error',
      title: 'Credenciales Incorrectas',
      text: 'El usuario o la contraseña son incorrectos. Por favor, inténtalo nuevamente.',
      confirmButtonText: 'Aceptar'
    });
  }
});

$(document).ready(function() {
  // Verificar si se debe abrir el modal de registro
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('register') || urlParams.has('open_modal')) {
    $('#tipoUsuario').val('cliente');
    $('#crearCuentaModalLabel').text('Crear Cuenta de Cliente');
    $('#crearCuentaModal').modal('show');
  }

  $('#btn-crear-cliente').click(function() {
    $('#tipoUsuario').val('cliente');
    $('#crearCuentaModalLabel').text('Crear Cuenta de Cliente');
    $('#crearCuentaModal').modal('show');
  });

  $('#btn-crear-proveedor').click(function() {
    $('#tipoUsuario').val('proveedor');
    $('#crearCuentaModalLabel').text('Crear Cuenta de Proveedor');
    $('#crearCuentaModal').modal('show');
  });

  $('#btnGuardarCuenta').click(function() {
  var formData = new FormData($('#formCrearCuenta')[0]);
  let isFormValid = true;

  // Validación del campo de correo para verificar que contenga un @
  const correo = $('#correo').val();
  if (!correo.includes('@')) {
    isFormValid = false;
    $('#correo').addClass('is-invalid');
    Swal.fire({
      icon: 'error',
      title: 'Correo no válido',
      text: 'Por favor, ingresa un correo electrónico válido que incluya "@".',
    });
  } else {
    $('#correo').removeClass('is-invalid');
  }

  $('#formCrearCuenta [required]').each(function() {
    if ($(this).val() === "") {
      isFormValid = false;
      $(this).addClass('is-invalid');
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos requeridos.',
      });
    } else {
      $(this).removeClass('is-invalid');
    }
  });

  if (!isFormValid) return;

  // Ajax para enviar datos
  $.ajax({
    url: '../controlador/UsuarioController.php',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(response) {
      var resultado = JSON.parse(response);
      if (resultado.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Cuenta creada',
          text: 'Tu cuenta ha sido creada exitosamente.',
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          location.reload();
        });
      } else if (resultado.status === 'error') {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resultado.message,
        });
      } else if (resultado.status === 'exists') {
        Swal.fire({
          icon: 'warning',
          title: 'Usuario Existente',
          text: resultado.message,
        });
      }
    },
    error: function() {
      Swal.fire({
        icon: 'error',
        title: 'Error de Conexión',
        text: 'Hubo un problema de conexión con el usuario.',
      });
    }
  });
});
});
</script>
</body>
</html>