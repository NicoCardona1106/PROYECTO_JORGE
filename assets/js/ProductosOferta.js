$(document).ready(function () {
    listar_productos(); // Cargar la lista de productos

    // Función para listar productos
    function listar_productos() {
        $.post('../controlador/ProductosOfertaController.php', { funcion: 'listar' }, (response) => {
            let productos = JSON.parse(response);
            let template = '';

            productos.data.forEach(producto => {
                // Determina si el producto tiene descuento
                const tiene_descuento = producto.tipo_descuento && producto.tipo_descuento !== 'Ninguno';
                const precio_original = parseFloat(producto.precio_original) > 0 ? parseFloat(producto.precio_original) : parseFloat(producto.precio);
                const precio_descuento = parseFloat(producto.precio).toFixed(2);
                const descuento_porcentaje = producto.descuento_porcentaje && parseFloat(producto.descuento_porcentaje) > 0 
                    ? parseFloat(producto.descuento_porcentaje).toFixed(2) 
                    : null;

                // Validar que el precio con descuento sea diferente del precio original
                if (tiene_descuento && precio_original.toFixed(2) === precio_descuento) {
                    console.error(`Error: El precio original y el precio con descuento son iguales para el producto ID ${producto.id_producto}`);
                }

                template += `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-center">${producto.nombre}</h5>
                                <img src="../assets/img/prod/${producto.avatar}" class="card-img-top img-fluid mx-auto d-block" alt="Imagen del producto" style="width: 200px; height: 200px; object-fit: cover;">
                                <p class="card-text">${producto.concentracion}</p>
                                <p class="card-text">${producto.adicional}</p>
                                <p class="card-text">Precio Original: $${precio_original.toFixed(2)}</p>
                                ${tiene_descuento ? `
                                    <p class="card-text text-success">
                                        ${producto.tipo_descuento} ${descuento_porcentaje ? `` : ''} - 
                                        Precio con Descuento: $${precio_descuento}
                                    </p>` : ''}
                                <button class="btn btn-primary btn-agregar-carrito" 
                                        data-id="${producto.id_producto}" 
                                        data-precio="${precio_descuento}" 
                                        data-nombre="${producto.nombre}" 
                                        data-descripcion="${producto.concentracion}">
                                    Agregar al carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#productos').html(template);
        });
    }

    // Manejar el clic en "Agregar al carrito"
    $(document).on('click', '.btn-agregar-carrito', function () {
        const id_producto = $(this).data('id');
        const precio_producto = $(this).data('precio');

        // Enviar al controlador el ID del producto y el precio con descuento
        $.post('../controlador/CarritoController.php', {
            funcion: 'agregar_carrito',
            id_producto: id_producto,
            precio: precio_producto
        }, (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: `Producto agregado al carrito. Total: ${data.num_items} items`
                });
                listar_carrito(); // Refrescar el carrito
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        });
    });

    // Listar productos en el carrito
    function listar_carrito() {
        $.post('../controlador/CarritoController.php', { funcion: 'obtenerCarrito' }, (response) => {
            const carrito = JSON.parse(response);
            let template = '';

            carrito.data.forEach(item => {
                template += `
                    <tr>
                        <td>${item.nombre}</td>
                        <td>${item.cantidad}</td>
                        <td>$${item.precio}</td>
                        <td>$${(item.precio * item.cantidad).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm eliminar-producto" data-id="${item.id_producto}">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#carrito-body').html(template);
            $('#carrito-total').text(`$${carrito.total}`);
        });
    }

    // Eliminar producto del carrito
    $(document).on('click', '.eliminar-producto', function () {
        const id_producto = $(this).data('id');

        $.post('../controlador/CarritoController.php', {
            funcion: 'eliminar_producto',
            id_producto: id_producto
        }, (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Producto eliminado del carrito'
                });
                listar_carrito(); // Actualizar la lista del carrito
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        });
    });

    // Refrescar carrito en la carga inicial
    listar_carrito();
});
