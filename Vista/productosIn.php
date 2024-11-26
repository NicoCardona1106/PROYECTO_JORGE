<?php
session_start();
if ($_SESSION['id_tipo_us'] == 4) {
  $titulo_pag = 'Bienvenido a la sección de productos';
  include_once 'layouts/header.php';
  include_once 'layouts/navInvitado.php';
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

<!-- Modal para mostrar detalles del producto y agregar al carrito -->
<div class="modal fade" id="modalAgregarCarrito" tabindex="-1" role="dialog" aria-labelledby="modalAgregarCarritoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarCarritoLabel">Detalles del Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formAgregarCarrito">
          <!-- Campo para el ID del producto -->
          <div class="form-group">
            <label for="productoId">ID del Producto</label>
            <input type="text" class="form-control" id="productoId" disabled>
          </div>

          <div class="form-group">
            <label for="productoNombre">Nombre del Producto</label>
            <input type="text" class="form-control" id="productoNombre" disabled>
          </div>
          <div class="form-group">
            <label for="productoPrecio">Precio</label>
            <input type="text" class="form-control" id="productoPrecio" disabled>
          </div>
          <div class="form-group">
            <label for="productoDescripcion">Descripción</label>
            <textarea class="form-control" id="productoDescripcion" rows="3" disabled></textarea>
          </div>
          <button type="button" class="btn btn-primary" id="btnConfirmarAgregar" onclick="window.location.href='login.php?register=true'">Agregar al carrito</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
  include_once 'layouts/footer.php';
} else {
  header('location: ../vista/login.php');
}
?>

<script src="../assets/js/Productos.js"></script>
