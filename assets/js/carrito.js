$(document).ready(function() {
    listar_carrito();  // Llama a la función para listar los productos del carrito cuando el documento está listo

    // Función para listar los productos del carrito
    function listar_carrito() {
        $.post('../controlador/CarritoController.php', {funcion: 'obtenerCarrito'}, (response) => {
            let carrito = JSON.parse(response);
            let template = '';

            carrito.data.forEach(item => {
                template += `
                    <div class="row" data-id="${item.id_producto}">
                        <div class="col-6">
                            <img src="../assets/img/prod/${item.avatar}" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-6">
                            <h5>${item.nombre}</h5>
                            <p>Precio: $${item.precio}</p>
                            <p>Cantidad: ${item.cantidad}</p>
                            <button class="btn btn-danger btn-eliminar" data-id="${item.id_producto}">Eliminar</button>
                        </div>
                    </div>
                    <hr>
                `;
            });

            $('#carrito-items').html(template);
            $('#total-carrito').text(carrito.total);
        });
    }

    // Maneja el click para eliminar un producto del carrito
    $(document).on('click', '.btn-eliminar', function() {
        const id_producto = $(this).data('id');
        const id_usuario = $("#id_usuario").val();  // Aquí, recuperamos el id_usuario desde un campo oculto o del DOM

        $.post('../controlador/CarritoController.php', {
            funcion: 'eliminar_producto',
            id_producto: id_producto,
            id_usuario: id_usuario  // Pasamos el id_usuario correctamente
        }, (response) => {
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
});
