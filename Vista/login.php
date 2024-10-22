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
    margin-bottom: 10px; /* Ajusta el valor según el espacio que necesites */
  }
  </style>
</head>

<?php
session_start();

if (!empty($_SESSION['id_tipo_us'])){ 	
    header('location: ../controlador/LoginController.php');
}
else{
    session_destroy();
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
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
          </div>
          <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" required>
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" class="form-control" id="edad" name="edad" required>
          </div>
          <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
          </div>
          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
          </div>
          <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
          </div>
          <div class="form-group">
            <label for="sexo">Sexo</label>
            <select class="form-control" id="sexo" name="sexo" required>
              <option value="">Seleccionar</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otro</option>
            </select>
          </div>
          <div class="form-group">
            <label for="infoadicional">Información Adicional</label>
            <textarea class="form-control" id="infoadicional" name="infoadicional" rows="3"></textarea>
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
$(document).ready(function() {
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
    $.ajax({
      url: '../controlador/RegistroController.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        if(response == 'success') {
          alert('Cuenta creada correctamente');
          location.reload();
        } else {
          alert('Hubo un problema al crear la cuenta');
        }
      },
      error: function() {
        alert('Hubo un problema de conexión');
      }
    });
  });
});
</script>
</body>
</html>
<?php
}
?>
