$(document).ready(function() {
  listar_productos();  // Llama a la función que lista los productos cuando el documento está listo

  function listar_productos() {
      $.post('../controlador/ProductosController.php', {funcion: 'listar'}, (response) => {
          let productos = JSON.parse(response);
          let template = '';

          productos.data.forEach(producto => {
              template += `
                  <div class="col-md-4 mb-3">
                      <div class="card h-100">
                          <div class="card-body d-flex flex-column">
                              <h5 class="card-title text-center">${producto.nombre}</h5>
                              <img src="../assets/img/prod/${producto.avatar}" class="card-img-top img-fluid mx-auto d-block" alt="Imagen del producto" style="width: 200px; height: 200px; object-fit: cover;">
                              <p class="card-text">${producto.concentracion}</p>
                              <p class="card-text">${producto.adicional}</p>
                              <p class="card-text">Precio: $${producto.precio}</p>
                              <button class="btn btn-primary btn-agregar-carrito" data-id="${producto.id_producto}" data-nombre="${producto.nombre}" data-precio="${producto.precio}" data-descripcion="${producto.concentracion}">Agregar al carrito</button>
                          </div>
                      </div>
                  </div>
              `;
          });

          $('#productos').html(template);
      });
  }

  // Maneja el click para mostrar el modal y cargar los detalles del producto
  $(document).on('click', '.btn-agregar-carrito', function() {
      const id_producto = $(this).data('id');
      const nombre_producto = $(this).data('nombre');
      const precio_producto = $(this).data('precio');
      const descripcion_producto = $(this).data('descripcion');

      // Llenar los campos del modal con la información del producto
      $('#productoId').val(id_producto);  // Mostrar el ID del producto
      $('#productoNombre').val(nombre_producto);
      $('#productoPrecio').val('$' + precio_producto);
      $('#productoDescripcion').val(descripcion_producto);

      // Mostrar el modal
      $('#modalAgregarCarrito').modal('show');
  });

  // Confirmar agregar al carrito desde el modal
  $('#btnConfirmarAgregar').on('click', function() {
      const id_producto = $('#productoId').val();  // Obtener el ID del producto desde el campo visible
      const nombre_producto = $('#productoNombre').val();
      const precio_producto = $('#productoPrecio').val();

      $.post('../controlador/CarritoController.php', {
          funcion: 'agregar_carrito',
          id_producto: id_producto
      }, (response) => {
          const data = JSON.parse(response);
          if (data.status === 'success') {
              Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: `Producto "${nombre_producto}" agregado al carrito. Total: ${data.num_items} items`
              });
              $('#modalAgregarCarrito').modal('hide');  // Cerrar el modal
              $('#num_items_carrito').text(data.num_items);  // Actualizar el número de productos en el carrito (si tienes este contador)
          } else {
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Hubo un problema al agregar el producto al carrito'
              });
          }
      });
  });
});
