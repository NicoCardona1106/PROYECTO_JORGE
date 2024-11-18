<?php
session_start();
if ($_SESSION['id_tipo_us'] == 2 || $_SESSION['id_tipo_us'] == 4) {
    $titulo_pag = 'Carrito de Compras';
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Productos en el carrito</h3>
                        </div>
                        <div class="card-body">
                            <div id="carrito-items">
                                <!-- Aquí se cargarán los items del carrito -->
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <h4>Total: $<span id="total-carrito">0.00</span></h4>
                                    <button id="btn-finalizar-compra" class="btn btn-success">Finalizar Compra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Campo oculto con el id_usuario -->
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

<?php
    include_once 'layouts/footer.php';
} else {
    header('location: ../vista/login.php');
}
?>
<script src="../assets/js/carrito.js"></script>
