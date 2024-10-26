function anularRegistro(tableId, inputId, anularUrl, errorMessage) {
    const table = document.getElementById(tableId);
    var inputElement = document.getElementById(inputId).value;

    // Validar si la tabla está vacía
    if (table.rows.length <= 0) {
        showErrorMessage(errorMessage || 'No hay ningún documento seleccionado para anular.');
        return;
    }

    // Mostrar confirmación antes de anular
    Swal.fire({
        title: '¿Desea anular el documento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: anularUrl + '/' + inputElement,
                type: 'POST',
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Token CSRF
                },
                success: function (response) {
                    Swal.fire({
                        title: 'Documento Anulado',
                        text: 'Anulación: Documento anulado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        // Muestra el mensaje "ANULADO" después de cerrar el Swal
                        document.getElementById('mensajeAnulado').style.display = 'block';
                    });
                },
                error: function (xhr) {
                    showErrorMessage('Error al procesar la solicitud: ' + (xhr.responseJSON?.error || 'Error desconocido'));
                }
            });
        }
    });
}