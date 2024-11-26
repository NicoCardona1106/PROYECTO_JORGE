$(document).ready(function () {
    listar_ordenes();

    function listar_ordenes() {
        $.post('../controlador/ProveedorController.php', { funcion: 'obtenerOrdenes' }, function (response) {
            let data;
            try {
                data = JSON.parse(response);
            } catch (error) {
                Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                return;
            }

            let template = '';
            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(orden => {
                    let acciones = '';
                    if (orden.estado === 'PENDIENTE') {
                        acciones = `
                            <button class="btn btn-success btn-aprobar" data-id="${orden.id_detalle}">Aprobar</button>
                            <button class="btn btn-danger btn-rechazar" data-id="${orden.id_detalle}">Rechazar</button>
                        `;
                    } else if (orden.estado === 'RECHAZADO') {
                        acciones = `<span class="badge badge-danger">Rechazado</span>`;
                    }

                    template += `
                        <tr data-id="${orden.id_detalle}">
                            <td>${orden.id_compra}</td>
                            <td>${orden.producto}</td>
                            <td>${orden.cantidad}</td>
                            <td>$${parseFloat(orden.precio_unitario).toFixed(2)}</td>
                            <td>${orden.estado}</td>
                            <td>${orden.fecha}</td>
                            <td>${acciones}</td>
                        </tr>
                    `;
                });
                $('#tabla-ordenes tbody').html(template);
            } else if (data.status === 'error') {
                Swal.fire('Error', data.message, 'error');
                $('#tabla-ordenes tbody').html('<tr><td colspan="7" class="text-center">No hay órdenes para mostrar</td></tr>');
            }
        }).fail(function () {
            Swal.fire('Error', 'No se pudo cargar las órdenes', 'error');
        });
    }

    // Botones para aprobar y rechazar
    $(document).on('click', '.btn-aprobar', function () {
        let id_detalle = $(this).data('id');
        actualizar_estado_producto(id_detalle, 'APROBADO');
    });

    $(document).on('click', '.btn-rechazar', function () {
        let id_detalle = $(this).data('id');
        actualizar_estado_producto(id_detalle, 'RECHAZADO');
    });

    function actualizar_estado_producto(id_detalle, nuevo_estado) {
        $.post('../controlador/ProveedorController.php', {
            funcion: 'actualizarEstadoProducto',
            id_detalle: id_detalle,
            nuevo_estado: nuevo_estado
        }, function (response) {
            let result;
            try {
                result = JSON.parse(response);
            } catch (error) {
                Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                return;
            }

            if (result.status === 'success') {
                Swal.fire('Éxito', result.message, 'success');
                listar_ordenes();
            } else {
                Swal.fire('Error', result.message || 'No se pudo actualizar el estado', 'error');
            }
        }).fail(function () {
            Swal.fire('Error', 'No se pudo comunicar con el servidor', 'error');
        });
    }
});
