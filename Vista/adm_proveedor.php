<?php
session_start();
if ($_SESSION['id_tipo_us'] == 1 || $_SESSION['id_tipo_us'] == 3) {
    $titulo_pag = 'Proveedor';
    include_once 'layouts/header.php';
    include_once 'layouts/nav.php';
?>

<!-------------------------------------------------------> 
<!--   Ventana Modal para CREAR Y EDITAR PROVEEDOR     -->
<!-------------------------------------------------------> 
<div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tit_ven">Crear Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success text-center" id="add" style='display:none;'>
                    <i class="fa fa-check-circle m-1"> Proveedor agregado correctamente</i>
                </div>
                <div class="alert alert-danger text-center" id="noadd" style='display:none;'>
                    <i class="fa fa-times-circle m-1"> El proveedor ya existe</i>
                </div>
                <div class="alert alert-success text-center" id="edit" style='display:none;'>
                    <i class="fa fa-check-circle m-1"> Proveedor editado correctamente</i>
                </div>
                <div class="alert alert-success text-center" id="delete" style='display:none;'>
                    <i class="fa fa-check-circle m-1"> Proveedor eliminado correctamente</i>
                </div>
                <form id="form-crear">
                    <input type="hidden" id="id"> <!-- Campo oculto para almacenar el ID del proveedor -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" placeholder="Ingrese nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" class="form-control" placeholder="Ingrese apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" class="form-control" placeholder="Ingrese DNI" required>
                    </div>
                    <div class="form-group">
                        <label for="edad">Edad</label>
                        <input type="date" id="edad" class="form-control" placeholder="Ingrese edad" required>
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select id="sexo" class="form-control" required>
                            <option value="">Seleccione sexo</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" class="form-control" placeholder="Ingrese correo" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" class="form-control" placeholder="Ingrese teléfono">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" class="form-control" placeholder="Ingrese dirección">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avatar</label>
                        <input type="text" id="avatar" class="form-control" placeholder="Ingrese URL del avatar">
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

<!-------------------------------------------------------> 
<!--   Tabla para listar los proveedores                  -->
<!-------------------------------------------------------> 
<div class="content-wrapper">
    <div class="container-fluid"> <!-- Añadido contenedor para mantener el contenido centrado y alineado -->
        <section class="content-header">
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
        </section>

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

        <!------------------ Main content ------------------------------> 
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Proveedores</h3>
                        </div>
                        <div class="card-body">
                            <table id="tabla" class="table table-bordered table-striped table-hover dataTable dtr-inline" role="grid">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>DNI</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Avatar</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los registros se generarán dinámicamente aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div> <!-- /.container-fluid -->
</div>



<?php
    include_once 'layouts/footer.php';
} else {
    // Redirecciona al login si no tiene los permisos adecuados
    header('location: ../Vista/login.php');
}
?>

<!-- Asegúrate de que se cargue correctamente el archivo JavaScript -->
<script src="../assets/js/proveedor.js"></script>
