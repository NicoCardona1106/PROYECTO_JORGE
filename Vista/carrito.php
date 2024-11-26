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
                                <!-- Aquí se cargarán los items del carrito dinámicamente -->
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <h4>Total: <span id="total-carrito">0.00</span></h4>
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

productos.data.forEach(producto => {
    const precio_descuento = (producto.precio * 0.8).toFixed(2);
    const advertencia = producto.cantidad < 20 ? '<p class="text-danger">¡Cantidad baja! Precio pronto volverá al original.</p>' : '';

    template += `
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">${producto.nombre}</h5>
                    <img src="../assets/img/prod/${producto.avatar}" class="card-img-top img-fluid mx-auto d-block" alt="Imagen del producto" style="width: 200px; height: 200px; object-fit: cover;">
                    <p class="card-text">${producto.concentracion}</p>
                    <p class="card-text">${producto.adicional}</p>
                    <p class="card-text">Precio Original: $${producto.precio}</p>
                    <p class="card-text text-success">Precio con Descuento: $${precio_descuento}</p>
                    ${advertencia}
                    <button class="btn btn-primary btn-agregar-carrito" data-id="${producto.id_producto}" data-nombre="${producto.nombre}" data-precio="${precio_descuento}" data-descripcion="${producto.concentracion}">Agregar al carrito</button>
                </div>
            </div>
        </div>
    `;
});


<!-- Campo oculto con el id_usuario -->
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

<!-- Modal para confirmación de compra -->
<div class="modal fade" id="modal-finalizar-compra" tabindex="-1" role="dialog" aria-labelledby="modalFinalizarCompraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFinalizarCompraLabel">Confirmación de Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas finalizar la compra?</p>
                <p>Total a pagar: $<span id="modal-total-carrito">0.00</span></p>
                <p><strong>Estado:</strong> Pendiente de pago</p>
                <!-- Desglose por proveedores -->
                <div id="detalles-proveedores">
                    <!-- Aquí se agregarán dinámicamente los detalles por proveedor -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmar-compra" class="btn btn-success">Confirmar Compra</button>
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
<script src="../assets/js/carrito.js"></script>
