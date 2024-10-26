function hacerConsulta(url, columnConfig, tablaID, modalID, bladeId, checkIgv) {
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            if (Array.isArray(data)) {
                construirTabla(data, columnConfig, tablaID, modalID, bladeId, checkIgv);
            } else {
                console.error("La respuesta no es un array, convirtiendo a array:", data);
                construirTabla([data], columnConfig, tablaID, modalID, bladeId, checkIgv);
            }
        },
        error: function (error) {
            console.error("Error en la consulta AJAX", error);
        }
    });
}

function construirTabla(data, columnas, nombreTabla, modalid, bladeId, checkIgv) {
    if (!Array.isArray(columnas)) {
        console.error("Columnas no es un array", columnas);
        return;
    }

    var contenedorTabla = $('#' + nombreTabla);
    var tabla = $('<table class="table table-striped table-bordered" width="100%" style="border-collapse: collapse;"><thead><tr></tr></thead><tbody></tbody></table>');
    var filaEncabezado = tabla.find('thead tr');

    columnas.forEach(function (columna) {
        filaEncabezado.append('<th style="background-color: #1663be; color: white; height: 7px;line-height: 7px; padding: 5;">' + columna.title + '</th>');
    });

    var cuerpo = tabla.find('tbody');

    data.forEach(function (fila) {
        var tr = $('<tr></tr>').css('height', '10px');
        columnas.forEach(function (columna) {
            var td = $('<td style="padding: 5px; border: 1px solid #ddd;"></td>');
            td.html(fila[columna.name]);
            if (columna.align === 'right') {
                td.css('text-align', 'right'); // Alinear a la derecha
            } else if (columna.align === 'center') {
                td.css('text-align', 'center'); // Alinear al centro
            } else {
                td.css('text-align', 'left'); // Alinear a la izquierda como predeterminado
            }
            tr.append(td);
        });

        cuerpo.append(tr);

        // Verificar si el stock o saldo es vacío o igual a cero
        if (bladeId === 'ingreso') {
            tr.on('dblclick', function () {
                agregarAFilaProductos(fila, 'tablaProductos', checkIgv);
                //$('#modal-agregar-producto').modal('hide');
                $('#' + modalid).modal('hide');
            });
        } else { // Si no es 'ingreso', aplicar la lógica de stock
            if (fila['saldo'] === null || parseFloat(fila['saldo']) <= 0) {
                tr.addClass('fila-sin-stock'); // Agregar clase para identificar la fila sin stock
            } else {
                tr.on('dblclick', function () {
                    agregarAFilaProductos(fila, 'tablaProductos', checkIgv);
                    //$('#modal-agregar-producto').modal('hide');
                    $('#' + modalid).modal('hide');
                });
            }
        }
    });

    // Deshabilitar doble click para las filas sin stock
    cuerpo.find('.fila-sin-stock').off('dblclick');

    contenedorTabla.empty();
    contenedorTabla.append(tabla);
    tabla.DataTable({
        columns: columnas
    });
}

function actualizarTotal(tablaID, checkIgv) {
    // console.log('Valor de checkIgv en actualizarTotal:', checkIgv); // Verifica el valor recibido en esta función
    checkIgv = (checkIgv === true || checkIgv === 'true');
    var totalGeneral = 0;
    var totalIGV = 0;
    var totalSuma = 0;
    var igvRate = 0.18; // Porcentaje del IGV
    var SIGV_RATE = 1.18;

    $('#' + tablaID + ' tbody tr').each(function () {
        var tr = $(this);
        var cantidad = parseFloat(tr.find('input[name="Cantidad"]').val()) || 0;
        var precio = parseFloat(tr.find('input[name="Precio"]').val()) || 0;

        var subtotal = cantidad * precio;
        var igv, total;
        if (checkIgv) {
            //  console.log('IGV activado:', checkIgv);
            subtotal = subtotal / SIGV_RATE;
            var igv = subtotal * igvRate;
            var total = subtotal * SIGV_RATE;
        } else {
            // console.log('Checkbox desactivado:', checkIgv);
            var igv = subtotal * igvRate;
            var total = subtotal + igv;
        }

        //tr.find('td:nth-child(7) span').text(precio.toFixed(2));  // Actualiza el total en la fila
        tr.find('td:nth-child(7) span').text(subtotal.toFixed(2));  // Actualiza el total en la fila

        totalGeneral += subtotal;
        totalIGV += igv;
        totalSuma += total;
    });

    $('#subtotal').val(totalGeneral.toFixed(2));
    $('#igv').val(totalIGV.toFixed(2));
    $('#totalSuma').val(totalSuma.toFixed(2));
}

function agregarAFilaProductos(fila, tablaID) {
    var tabla = $('#' + tablaID + ' tbody');
    var tr = $('<tr></tr>');
    var columnasConfig = [
        { name: 'index', isIndex: true }, // Columna de índice
        { name: 'codProducto', editable: false }, // Columna no editable
        { name: 'Descripcion', editable: false }, // Columna no editable
        { name: 'Unidad', editable: false }, // Columna no editable
        { name: 'Cantidad', editable: true, width: '90px' }, // Columna editable
        { name: 'Precio', editable: true, width: '90px' }, // Columna editable
        { name: 'Total', editable: false } // Columna no editable
    ];

    // Agregar columnas según la configuración
    columnasConfig.forEach(function (columna, index) {
        var td = $('<td></td>');

        if (columna.isIndex) {
            td.text(tabla.children().length + 1); // Asigna el índice
        } else if (columna.editable) {
            var input = $('<input type="number"  style="width: ' + columna.width + ';">');

            if (columna.name === 'Cantidad') {
                input.attr('required', true);
                input.on('input', function () {
                    var checkIgv = $('#inigv').prop('checked'); // Obtener el estado del checkbox dinámicamente
                    actualizarTotal(tablaID, checkIgv);
                });
                input.on('keypress', function (e) {
                    if (e.which === 13) { // 13 es el código de la tecla Enter
                        e.preventDefault(); // Evitar el comportamiento predeterminado
                        var nextInput = $(this).closest('td').next().find('input'); // Encontrar el siguiente input
                        if (nextInput.length > 0) {
                            nextInput.focus(); // Asignar el foco al siguiente input
                        }
                    }
                });
            }

            if (columna.name === 'Precio') {
                input.attr('required', true);
                input.on('input', function () {
                    var checkIgv = $('#inigv').prop('checked'); // Obtener el estado del checkbox dinámicamente
                    actualizarTotal(tablaID, checkIgv);
                });
                input.on('keypress', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        var nextInput = $(this).closest('td').next().find('button'); // Encontrar el siguiente input
                        if (nextInput.length > 0) {
                            nextInput.focus(); // Asignar el foco al siguiente input
                        } else {
                            // Si no hay siguiente input, puedes mover el foco a la primera celda de la siguiente fila si existe
                            var nextRow = $(this).closest('tr').next();
                            if (nextRow.length > 0) {
                                var firstInputInNextRow = nextRow.find('button').first(); // Primer input de la siguiente fila
                                firstInputInNextRow.focus(); // Asignar el foco al primer input de la siguiente fila
                            }
                        }
                    }
                });
            }

            input.attr('name', columna.name);
            td.append(input);
        } else {
            if (columna.name === 'Total') {
                var totalSpan = $('<span></span>');
                var checkIgv = $('#inigv').prop('checked'); // Obtener el estado del checkbox dinámicamente
                actualizarTotal(tablaID, checkIgv); // Actualizar el total inicialmente
                td.append(totalSpan);
            } else {
                td.text(fila[columna.name]);
            }
        }
        tr.append(td);
        setTimeout(function () {
            var inputCantidad = tr.find('input[name="Cantidad"]');
            if (inputCantidad.length > 0) {
                inputCantidad.focus(); // Enfocar en el campo "Cantidad"
            }
        }, 300);  // Esperar 300ms para asegurarse de que el modal se haya cerrado

    });

    var btnEliminar = $('<button class="btn btn-danger btn-sm">Eliminar</button>').on('click', function () {
        tr.remove();
        actualizarIndices(tabla);
        var checkIgv = $('#inigv').prop('checked'); // Obtener el estado del checkbox dinámicamente
        actualizarTotal(tablaID, checkIgv);
    });
    tr.append($('<td></td>').append(btnEliminar));

    tabla.append(tr);

    var checkIgv = $('#inigv').prop('checked'); // Obtener el estado del checkbox dinámicamente
    actualizarTotal(tablaID, checkIgv);

}

function actualizarIndices(tabla) {
    tabla.children().each(function (index, tr) {
        $(tr).children().first().text(index + 1); // Actualizar el índice de cada fila
    });
}


