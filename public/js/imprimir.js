function imprimirReporte(config) {
    const table = document.getElementById(config.tablaId); // Tabla específica
    if (table.rows.length === 0 || (table.rows.length === 1 && !table.rows[0].textContent.trim())) { 
        showErrorMessage(config.mensajeError || 'No hay ningún documento seleccionado para imprimir.');
        return;
    } else {
        var idDocumento = document.getElementById(config.inputDocumentoId).value; // Obtener el valor del input específico
        var url = config.ruta.replace(':id', idDocumento); // Reemplazar placeholder con el id real
        window.open(url, '_blank'); // Abrir en una nueva pestaña
    }
}