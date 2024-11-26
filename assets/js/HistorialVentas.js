$(document).ready(function () {
    listar_historial();

    function listar_historial() {
        $.post('../controlador/ProveedorController.php', { funcion: 'obtenerHistorialVentas' }, function (response) {
            let data;
            try {
                data = JSON.parse(response);
            } catch (error) {
                Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                return;
            }

            let template = '';
            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(venta => {
                    template += `
                        <tr>
                            <td>${venta.id_compra}</td>
                            <td>${venta.producto}</td>
                            <td>${venta.cantidad}</td>
                            <td>$${parseFloat(venta.precio_unitario).toFixed(2)}</td>
                            <td>$${parseFloat(venta.total).toFixed(2)}</td>
                            <td>${venta.fecha}</td>
                        </tr>
                    `;
                });

                $('#tabla-historial tbody').html(template);
                $('#total-vendido').text(`$${parseFloat(data.total_vendido).toFixed(2)}`);
            } else {
                $('#tabla-historial tbody').html('<tr><td colspan="6" class="text-center">No hay ventas aprobadas para mostrar</td></tr>');
                $('#total-vendido').text('$0.00');
            }
        }).fail(function () {
            Swal.fire('Error', 'No se pudo cargar el historial de ventas', 'error');
        });
    }
});
