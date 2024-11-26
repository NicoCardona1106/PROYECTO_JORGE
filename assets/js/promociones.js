$(document).ready(function () {
    function cargarPromociones() {
        $.post('../controlador/ProductoController.php', { funcion: 'listar_descuentos' }, function (response) {
            const data = JSON.parse(response);

            if (data.status === 'success' && data.data.length > 0) {
                let modalHtml = `
                    <div class="modal fade" id="promocionesModal" tabindex="-1" role="dialog" aria-labelledby="promocionesLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="promocionesLabel">¡Productos en Descuento!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">`;

                    data.data.forEach(producto => {
                        modalHtml += `
                            <div class="promo-item mb-4" style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                                <img 
                                    src="../assets/img/prod/${producto.avatar}" 
                                    alt="${producto.nombre}" 
                                    style="width: 100%; max-width: 300px; height: 200px; object-fit: cover; display: block; margin: 0 auto 15px auto; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                                <h5 style="margin-bottom: 10px; font-weight: bold; text-align: center;">${producto.nombre}</h5>
                                <p style="margin-bottom: 10px; text-align: center;">${producto.tipo_descuento}</p>
                                <p style="margin-bottom: 5px; text-align: center;"><strong>Antes:</strong> $${producto.precio_original}</p>
                                <p style="margin-bottom: 10px; text-align: center; color: green;"><strong>Ahora:</strong> $${producto.precio}</p>
                            </div>`;
                    });
                                
                                

                modalHtml += `
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                $('#modal-container').html(modalHtml); // Insertar el modal en el contenedor
                $('#promocionesModal').modal('show'); // Mostrar el modal
            }
        });
    }

    // Cargar promociones al cargar la página
    cargarPromociones();
});
