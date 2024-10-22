<?php
session_start();
if ($_SESSION['id_tipo_us'] == 2) {
  $titulo_pag = 'Bienvenido a la sección de productos';
  include_once 'layouts/header.php';
  include_once 'layouts/navCliente.php';
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?php echo $titulo_pag; ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="PaginaCI.php">Home</a></li>
            <li class="breadcrumb-item active"><?php echo $titulo_pag; ?></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-12 text-center">
        <div class="card card-secondary">
          <div class="container">
            <div id="productos" class="row"> <!-- Aquí se cargarán las tarjetas de productos -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
  include_once 'layouts/footer.php';
} else {
  header('location: ../vista/login.php');
}
?>
<script src="../assets/js/Productos.js"></script>
