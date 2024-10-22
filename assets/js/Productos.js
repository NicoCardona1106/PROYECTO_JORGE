$(document).ready(function() {
  listar_productos();  // Llama a la función que lista los productos cuando el documento está listo

  function listar_productos() {
      // Hace una petición POST a 'ProductosController.php', enviando el parámetro 'funcion' con el valor 'listar'
      $.post('../controlador/ProductosController.php', {funcion: 'listar'}, (response) => {
          
          // Convierte la respuesta JSON a un objeto JavaScript
          let productos = JSON.parse(response);
          let template = '';  // Variable para almacenar el HTML generado dinámicamente

          // Recorre el array de productos devueltos desde el servidor
          productos.data.forEach(producto => {
              // Por cada producto, genera una tarjeta con sus datos e imagen
              template += `
                <div class="col-md-4 mb-3">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">${producto.nombre}</h5>
                      <img src="../assets/img/prod/${producto.avatar}" class="card-img-top img-fluid mx-auto d-block" alt="Imagen del producto" style="width: 200px; height: 200px; object-fit: cover;">
                      <p class="card-text">${producto.concentracion}</p>
                      <p class="card-text">${producto.adicional}</p>
                      <p class="card-text">Precio: $${producto.precio}</p>
                      <button class="btn btn-primary">Agregar al carrito</button>
                    </div>
                  </div>
                </div>
              `;
          });

          // Inserta todo el HTML generado en el contenedor con el id 'productos'
          $('#productos').html(template);
      });
  }
});

