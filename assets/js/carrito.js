$(document).ready(function () {
    listar_carrito(); // Llama a la función para listar los productos del carrito al cargar la página.

    // Función para listar los productos del carrito
    function listar_carrito() {
        $.post('../controlador/CarritoController.php', { funcion: 'obtenerCarrito' }, function (response) {
            let carrito = JSON.parse(response);
            let template = '';

            if (carrito.data && carrito.data.length > 0) {
                carrito.data.forEach(item => {
                    template += `
                        <div class="row mb-3" data-id="${item.id_producto}" data-proveedor="${item.id_proveedor}">
                            <div class="col-4">
                                <img src="../assets/img/prod/${item.avatar}" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                            <div class="col-8">
                                <h5>${item.nombre}</h5>
                                <p>Precio: $${item.precio}</p>
                                <p>Cantidad: ${item.cantidad}</p>
                                <p>Proveedor: ${item.id_proveedor}</p>
                                <button class="btn btn-danger btn-eliminar" data-id="${item.id_producto}">Eliminar</button>
                            </div>
                        </div>
                        <hr>
                    `;
                });
                $('#carrito-items').html(template);
                $('#total-carrito').text(`$${carrito.total}`);
            } else {
                $('#carrito-items').html('<p class="text-center">El carrito está vacío</p>');
                $('#total-carrito').text('$0.00');
            }
        });
    }

    $(document).on('click', '.btn-agregar-carrito', function () {
        const id_producto = $(this).data('id');
        let precio = $(this).data('precio');  // Obtén el precio del producto (puede ser con descuento)
        
        // Verifica si el producto tiene descuento y ajusta el precio si es necesario
        if (descuentoDisponible) {  // Si hay un descuento disponible, ajustamos el precio
            precio = precioConDescuento;  // Precio con descuento
        }
    
        // Hacer la solicitud al backend para agregar el producto al carrito
        $.post('../controlador/CarritoController.php', {
            funcion: 'agregar_carrito',
            id_producto: id_producto,
            precio: precio_descuento  // Enviar el precio calculado (con o sin descuento)
        }, function (response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto agregado',
                    text: 'El producto se ha agregado al carrito.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        });
    });
    
    //Update de la cantidad
    $(document).on('click', '.btn-comprar', function () {
        const id_producto = $(this).data('id');
        const cantidad_comprada = $(this).data('cantidad');
    
        $.post('../controlador/CarritoController.php', {
            funcion: 'actualizar_cantidad',
            id_producto: id_producto,
            cantidad: cantidad_comprada
        }, (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Compra realizada',
                    text: data.message
                });
                listar_carrito(); // Actualizar la vista del carrito
                listar_productos(); // Actualizar la lista de productos
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        });
    });
    

    // Maneja el click para eliminar un producto del carrito
    $(document).on('click', '.btn-eliminar', function () {
        const id_producto = $(this).data('id');

        $.post('../controlador/CarritoController.php', {
            funcion: 'eliminar_producto',
            id_producto: id_producto
        }, function (response) {
            const data = JSON.parse(response);

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto eliminado',
                    text: 'El producto ha sido eliminado correctamente del carrito.'
                });
                listar_carrito(); // Actualiza el carrito después de eliminar el producto
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al eliminar el producto del carrito.'
                });
            }
        });
    });

    // Finalizar compra
    $('#btn-finalizar-compra').click(function () {
        // Verificar si el carrito está vacío
        if ($('#carrito-items').children().length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'No puedes finalizar una compra sin productos en el carrito.'
            });
            return;
        }

        Swal.fire({
            title: '¿Deseas finalizar la compra?',
            text: "Esta acción procesará todos los productos en tu carrito.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, finalizar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controlador/CarritoController.php', {
                    funcion: 'finalizar_compra',
                    estado: 'PENDIENTE' // Estado inicial de la compra
                }, function (response) {
                    const data = JSON.parse(response);

                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Compra finalizada',
                            text: 'Tu compra ha sido procesada correctamente.',
                            footer: `ID de compra: ${data.id_compra}`
                        });
                        listar_carrito(); // Limpia el carrito después de finalizar la compra
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                });
            }
        });
    });
});
