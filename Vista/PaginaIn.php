<?php
session_start();
if ($_SESSION['id_tipo_us'] == 4) {
  $titulo_pag = 'Bienvenido a Nuestra Tienda Virtual';
  include_once 'layouts/header.php';
  include_once 'layouts/navInvitado.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $titulo_pag; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $titulo_pag; ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!------------------ Main content ------------------------------->  
    <section class="content">
      <div class="row">
        <div class="col-12 text-center">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">¡Bienvenido a Nuestra Tienda Virtual!</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <p class="lead">Explora nuestros productos y disfruta de una experiencia de compra única.</p>
              <div class="btn-group mt-3" role="group" aria-label="Basic example">
                <a href="productosIn.php" class="btn btn-primary">Ver Productos</a>
                <a href="ofertas.php" class="btn btn-success">Ofertas Especiales</a>
                <a href="adm_contactos.php" class="btn btn-info">Contáctanos</a>
                <a href="login.php?register=true" class="btn btn-warning">Registrarse</a>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Incluir el script de promociones -->
<script src="../assets/js/promociones.js"></script>

<!-- Modal para Promociones -->
<div id="modal-container"></div> <!-- Contenedor para cargar dinámicamente el modal -->

<?php
  include_once 'layouts/footer.php';
} else {
  // Redirecciona al login
  header('location: ../vista/login.php');
}
?>