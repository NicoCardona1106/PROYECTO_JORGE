<?php
session_start();
if ($_SESSION['id_tipo_us'] == 3 && isset($_SESSION['id_proveedor'])) { // Validar id_proveedor
    $titulo_pag = 'Órdenes Recibidas';
    include_once 'layouts/header.php';
    include_once 'layouts/navProveedor.php';
?>
<script>
    const currentProviderId = <?php echo $_SESSION['id_proveedor']; ?>;
    const currentProviderName = "<?php echo $_SESSION['nombre_us']; ?>";
</script>

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
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Órdenes Recibidas</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabla-ordenes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Orden</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th> <!-- Nueva columna para el total -->
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos serán llenados dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'layouts/footer.php';
} else {
    header('location: ../vista/login.php');
}
?>
<script src="../assets/js/ordenes.js"></script>
