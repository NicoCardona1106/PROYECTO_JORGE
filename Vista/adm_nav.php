<?php
session_start();
if ($_SESSION['id_tipo_us'] == 1 || $_SESSION['id_tipo_us'] == 3) {
    $titulo_pag = 'Producto';
    include_once 'layouts/header.php';
    include_once 'layouts/nav.php';
?>
<!-------------------------------------------------->
<!--   Ventana Modal para CREAR Y EDITAR          -->
<!-------------------------------------------------->
<div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">      
        <div class="modal-header">
          <h5 class="modal-title"><span id="tit_ven">Crear Producto</span></h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center" id="add" style='display:none;'>
            <i class="fa fa-check-circle m-1"> Operación realizada correctamente</i>
          </div>
          <div class="alert alert-danger text-center" id="noadd" style='display:none;'>
            <i class="fa fa-times-circle m-1"> Elemento ya existe</i>
          </div>
          <form id="form-crear">
                <div class="form-group">
                    <label for="id_producto">ID</label>
                    <input type="number" id="id_producto" class="form-control" placeholder="Ingrese ID" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control" placeholder="Ingrese nombre" required>
                </div>
                <div class="form-group">
                    <label for="concentracion">Concentración</label>
                    <input type="text" id="concentracion" class="form-control" placeholder="Ingrese concentración" required>
                </div>
                <div class="form-group">
                    <label for="adicional">Info adicional</label>
                    <input type="text" id="adicional" class="form-control" placeholder="Información adicional">
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" class="form-control" placeholder="Ingrese cantidad" min="1" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" class="form-control" placeholder="Precio" min="1" required>
                </div>
                <div class="form-group">
                    <label for="laboratorio">Laboratorio</label>
                    <select name="laboratorio" id="laboratorio" class="form-control select2" style="width: 100%"></select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control select2" style="width: 100%"></select>
                </div>
                <div class="form-group">
                    <label for="presentacion">Presentación</label>
                    <select name="presentacion" id="presentacion" class="form-control select2" style="width: 100%"></select>
                </div>
                <div class="form-group">
                    <label for="proveedor">Proveedor</label>
                    <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%"></select>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn bg-gradient-primary">Guardar</button>
                </div>
          </form>
        </div>
    </div>
  </div>
</div>
<!-------------------------------------------------->
<!-- FIN Ventana Modal para CREAR Y EDITAR        -->
<!-------------------------------------------------->

  <!-------------------------------------------------->
  <!--   Ventana Modal para el cambio de logo     -->
  <!-------------------------------------------------->
  <div class="modal fade" id="cambiaravatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cambiar logo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
              <img id="avataractual" src="" class="profile-user-img img-fluid img-circle">
            </div>
            <div class="text-center">
              <b id="nombre_avatar"></b>
            </div>
            <div class="alert alert-success text-center" id="updatelogo" style='display:none;'>
              <i class="fa fa-check-circle m-1"> Se cambio la imagen</i>
            </div>
            <div class="alert alert-danger text-center" id="noupdatelogo" style='display:none;'>
              <i class="fa fa-times-circle m-1"> Formato de imagen incorrecto</i>
            </div>
            <form id="form-logo" enctype="multipart/form-data">
              <div class="input-group mb-3 ml-5">
                <input type="file" name="photo" class="input-group">
                <input type="hidden" name="funcion" id="funcion">
                <input type="hidden" name="id_avatar" id="id_avatar">
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn bg-gradient-primary">Cambiar imagen</button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <!-------------------------------------------------->
  <!-- FIN Ventana Modal para el cambio de logo   -->
  <!-------------------------------------------------->
<!-------------------------------------------------->
<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $titulo_pag; ?>
                <button class="btn-crear btn bg-gradient-primary btn-sm m-2" data-toggle="modal" data-target="#crear">Crear</button>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $titulo_pag; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">Productos</h3>
            </div>
            <div class="card-body">
              <table id="tabla" class="table table-bordered table-striped table-hover dataTable dtr-inline" role="grid">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Concentración</th>
                        <th>Info Adicional</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Laboratorio</th>
                        <th>Tipo</th>
                        <th>Presentación</th>
                        <th>Proveedor</th>
                    </tr>
                </thead>
              </table>
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
<script>
// Validación de campos para evitar números negativos o fracciones
document.addEventListener("DOMContentLoaded", function () {
    // Validar cantidad
    const cantidadInput = document.getElementById("cantidad");
    cantidadInput.addEventListener("input", function () {
        this.value = Math.max(1, Math.floor(this.value || 0));
    });

    // Validar precio
    const precioInput = document.getElementById("precio");
    precioInput.addEventListener("input", function () {
        this.value = Math.max(1, Math.floor(this.value || 0));
    });
});
</script>
<script src="../assets/js/producto.js"></script>