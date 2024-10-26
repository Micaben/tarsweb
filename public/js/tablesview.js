function hacerConsulta(url, columnas, nombreTabla, modalid) {
    console.log("URL:", url);
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            construirTabla(data, columnas, nombreTabla, modalid);
           // console.log(data);
        },
        error: function (error) {
            console.error('Error al hacer la consulta:', error);
        }
    });
}

function construirTabla(data, columnas, nombreTabla, modalid) {
    // Obtener el contenedor de la tabla
    //console.log("Datos de la tabla:", data);
    //console.log("Columnas:", columnas);
    // Verificar si columnas es un array
    if (!Array.isArray(columnas)) {
        console.error("Columnas no es un array", columnas);
        return;
    }

    // Obtener el contenedor de la tabla
    var contenedorTabla = $('#' + nombreTabla);

    // Crear la tabla y obtener la fila de encabezados
    var tabla = $('<table class="table table-striped table-bordered" width="100%" style="border-collapse: collapse;"><thead><tr></tr></thead><tbody></tbody></table>');
    var filaEncabezado = tabla.find('thead tr');

    // Construir encabezados
    columnas.forEach(function (columna) {
        filaEncabezado.append('<th style="background-color: #1663be; color: white; height: 5px; padding: 5px;">' + columna.title + '</th>');
    });

    var cuerpo = tabla.find('tbody');

    // Llenar la tabla con los datos
    data.forEach(function (fila) {
        var tr = $('<tr></tr>');
        columnas.forEach(function (columna) {
            var td = $('<td style="padding: 5px; height: 10px; border: 1px solid #ddd;"></td>');
            td.html(fila[columna.name]);
            tr.append(td);
        });

        cuerpo.append(tr);
        tr.on('dblclick', function () {
            mostrarModalEdicion(fila, modalid);
        });
    });

    // Limpiar el contenedor y agregar la tabla
    contenedorTabla.empty();
    contenedorTabla.append(tabla);
    tabla.DataTable({
        columns: columnas
    });
    // Ahora DataTables se inicializará después de que la tabla esté completamente cargada en el DOM
}




