<?php
session_start();
if ($_SESSION['id_tipo_us'] == 3) { // Asegúrate de ajustar esto según el rol de proveedor
  $titulo_pag = 'Bienvenido al Portal de Proveedores';
  include_once 'layouts/header.php';
  include_once 'layouts/navProveedor.php'; // Cambia a una barra de navegación específica para proveedores
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
              <h3 class="card-title">¡Bienvenido al Portal de Proveedores!</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <p class="lead">Gestiona tus productos, revisa órdenes y accede a herramientas exclusivas para proveedores.</p>
              <div class="btn-group mt-3" role="group" aria-label="Basic example">
                <a href="MisProductos.php" class="btn btn-primary">Mis Productos</a>
                <a href="ordenes.php" class="btn btn-success">Órdenes Recibidas</a>
                <a href="adm_contactos.php" class="btn btn-info">Soporte</a>
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

<?php
  include_once 'layouts/footer.php';
} else {
  // Redirecciona al login
  header('location: ../vista/login.php');
}
?>
