<?php
session_start();
if ($_SESSION['id_tipo_us'] == 3 && isset($_SESSION['id_proveedor'])) {
    $titulo_pag = 'Historial de Ventas';
    include_once 'layouts/header.php';
    include_once 'layouts/navProveedor.php';
?>
<script>
    const currentProviderId = <?php echo $_SESSION['id_proveedor']; ?>;
</script>

<div class="content-wrapper">
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

    <section class="content">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Historial de Ventas</h3>
            </div>
            <div class="card-body">
                <table id="tabla-historial" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos dinámicos -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <h4>Total Vendido: <span id="total-vendido">0.00</span></h4>
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
<script src="../assets/js/HistorialVentas.js"></script>
