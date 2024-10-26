<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo de jsGrid</title>
    <!-- Incluir CSS de jsGrid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid-theme.min.css" />
</head>

<body>
    <!-- *************  MODAL AGREGAR PRODUCTO ********************* -->
    <div class="modal fade" id="modal-mostrar-registros" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="min-width:90%;">
            <div class="modal-content">
                <div class="card card-info">
                    <div class="card-header ">
                        <h6 class="m-0 font-weight-bold text-primary" id="modalLabel">Registros</h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-2 mb-2">
                                <label class="my-0">Mes:</label>
                                <select id="mes" class="form-control form-control-sm" name="mes">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="form-group col-2 mb-2">
                                <label class="my-0">Año:</label>
                                <select id="anio" class="form-control form-control-sm" name="anio"></select>
                            </div>
                        </div>
                        <div class="card">
                            <div id="tablaRegistros"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script src="{{ asset('js/tablesviewProductos.js') }}"></script>
    <script>

        function mostrarRegistros() {
            var btnBuscarId = 'btnAgregar';  // ID del botón
            var columns = JSON.parse($('#btnBuscar').attr('data-columns'));
            var url = $('#btnBuscar').attr('data-url');
            var mes = $('#mes').val();
            var anio = $('#anio').val();
            var routeName = $('#btnBuscar').attr('data-route-name');
            var routedetalleName = $('#btnBuscar').attr('data-routedetalle-name');
            var inputs = JSON.parse($('#btnBuscar').attr('data-inputs'));
            var inputMapping = JSON.parse($('#btnBuscar').attr('data-input-mapping'));
            const container = document.querySelector(`[data-routedetalle-name="${routedetalleName}"]`);
            const messageId = container ? container.getAttribute('data-message-id') : null;

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    mes: mes,
                    anio: anio
                },
                success: function (response) {
                    console.log(response)
                    var modal = $('#modal-mostrar-registros');
                    var modalBody = modal.find('#tablaRegistros');
                    var table = $('<table>').addClass('table table-striped table-bordered');

                    // Crear encabezados de la tabla
                    var thead = $('<thead>');
                    var tr = $('<tr>').css({
                        'background-color': '#1663be', // Color de fondo del encabezado
                        'color': 'white',              // Color del texto
                        'height': '5px'               // Altura del encabezado
                    });
                    columns.forEach(function (column) {
                        var th = $('<th>')
                            .text(column.name)
                            .css({
                                'text-align': 'center',
                                'padding': '5px'  // Espaciado interno (padding)
                            });
                        tr.append(th);
                    });
                    thead.append(tr);
                    table.append(thead);

                    // Crear cuerpo de la tabla
                    var tbody = $('<tbody>');
                    response.forEach(function (item) {
                        var tr = $('<tr>').css('height', '4px');

                        // Agregar cada columna a la fila
                        columns.forEach(function (column) {
                            var campo = column.name.toLowerCase().replace(' ', '');
                            tr.append($('<td>')
                                .text(item[campo])
                                .css({
                                    'text-align': column.align,  // Alineación en los datos
                                    'padding': '3px'            // Espaciado interno (padding)
                                })
                            );
                        });

                        // Añadir evento de doble clic a la fila
                        tr.dblclick(function () {
                            var id = item.id; // Obtener el valor de la última columna (suponiendo que es 'id')                        

                            let url;
                            let urldet;
                            if (routeName === 'registroIngreso') {
                                url = '/buscarRegistro/' + id;
                            } else if (routeName === 'registroCotizacion') {
                                url = '/buscarRegistroCot/' + id;
                            } else if (routeName === 'registroPedido') {
                                url = '/buscarRegistroPedido/' + id;
                            }
                            $.ajax({
                                url: url,
                                method: 'GET',
                                success: function (response) {
                                    if (response.success) {
                                        const data = response.data;
                                        console.log(data);

                                        inputs.forEach(function (input) {
                                            let mappedProperty = inputMapping[input];
                                            if ($('#' + input).length > 0 && data.hasOwnProperty(mappedProperty)) {
                                                let value = data[mappedProperty] !== null && data[mappedProperty] !== undefined ? data[mappedProperty] : '';

                                                if ($('#' + input).is('select')) {
                                                    // Asignar valor al select
                                                    $('#' + input + ' option').each(function () {
                                                        if ($(this).val() == value || $(this).text() == value) {
                                                            $(this).prop('selected', true);
                                                        }
                                                    });
                                                } else if ($('#' + input).is(':checkbox') || $('#' + input).is(':radio')) {
                                                    // Configurar valor para checkbox o radio button
                                                    if (value) {
                                                        $('#' + input).prop('checked', true);
                                                    } else {
                                                        $('#' + input).prop('checked', false);
                                                    }
                                                } else if ($('#' + input).attr('type') === 'date') {
                                                    let dateValue = formatDateForInput(value);
                                                    // console.log(`Setting date value for #${input} to ${dateValue}`);
                                                    $('#' + input).val(dateValue);
                                                } else {
                                                    if ($('#' + input).is('input')) {
                                                        $('#' + input).val(value);
                                                    } else if ($('#' + input).is('label')) {
                                                        $('#' + input).text(value);
                                                    }
                                                } if (messageId && data.Estado === 'A') {
                                                    document.getElementById(messageId).style.display = 'block';
                                                    document.getElementById(btnBuscarId).disabled = true;
                                                } else {
                                                    document.getElementById(messageId).style.display = 'none';
                                                    document.getElementById(btnBuscarId).disabled = false;
                                                }
                                            }
                                        });

                                        // Lógica para series y otros campos específicos
                                        if (routedetalleName === 'registroIngresodetalle') {
                                            urldet = '/buscarDetalle/' + id;
                                        } else if (routedetalleName === 'registroCotizaciondetalle') {
                                            urldet = '/buscarDetalleCot/' + id;
                                        } else if (routedetalleName === 'registroPedidodetalle') {
                                            urldet = '/buscarDetallePedido/' + id;
                                        }
                                        $.ajax({
                                            url: urldet,
                                            type: 'GET',
                                            dataType: 'json',
                                            success: function (response) {
                                                var detalleGuia = response.detalleGuia;
                                                var tablaIngresos = $('#tablaIngresos');
                                                console.log(detalleGuia)
                                                tablaIngresos.empty();
                                                detalleGuia.forEach(function (detalle) {
                                                    var fila = '<tr>' +
                                                        '<td>' + detalle.item + '</td>' +
                                                        '<td>' + detalle.producto + '</td>' +
                                                        '<td>' + detalle.Descripcion + '</td>' +
                                                        '<td>' + detalle.Umedida + '</td>' +
                                                        '<td><input type="number" class="form-control cantidad" name="Cantidad" value="' + detalle.Cantidad + '"></td>' +
                                                        '<td><input type="number" class="form-control precio" name="Precio" value="' + detalle.Precio + '"></td>' +
                                                        '<td><span>' + detalle.Totalpro + '</span></td>' +
                                                        '<td><button class="btn btn-danger btn-sm">Eliminar</button></td>' +
                                                        '</tr>';
                                                    tablaIngresos.append(fila);
                                                });
                                            },
                                            error: function (error) {
                                                let errorMessages = formatErrorMessages(response.errors);
                                                showErrorMessage('Error al procesar la solicitud. Detalles: ' + errorMessages);
                                            }
                                        });
                                        $('#modal-mostrar-registros').modal('hide');
                                    } else {
                                        let errorMessages = formatErrorMessages(response.errors);
                                        showErrorMessage('Error al procesar la solicitud. Detalles: ' + errorMessages);
                                    }
                                },
                                error: function (error) {
                                    console.log(error);
                                }
                            });
                        });

                        tbody.append(tr);
                    });
                    table.append(tbody);

                    // Limpiar y agregar la tabla al modal
                    modalBody.empty();
                    modalBody.append(table);
                    var lastIndex = columns.length - 1;
                    modalBody.find('th:eq(' + lastIndex + '), td:nth-child(' + (lastIndex + 1) + ')').hide();

                    // Mostrar el modal
                    modal.modal('show');
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        $(document).ready(function () {
            $('#tablaIngresos').on('input', 'input.cantidad, input.precio', function () {
                actualizarTotal('tablaProductos');  // Asegúrate de que pasas el ID correcto de la tabla
            });
        });

        $(document).ready(function () {
            obtenerMesAnio('mes', 'anio'); // Asumiendo que esta función carga los valores de mes y año
            $('#mes').on('change', function () {
                mostrarRegistros();
            });
            $('#anio').on('change', function () {
                mostrarRegistros();
            });

            $('#btnBuscar').on('click', function () {
                mostrarRegistros();
            });

        });

        function formatDateForInput(dateString) {
            let parts = dateString.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // Convertir a 'yyyy-MM-dd' para el input de tipo date
            }
            return dateString; // Si el formato es diferente, devolver la cadena original
        }

        function formatErrorMessages(errors) {
            let formattedErrors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }
    </script>
</body>

</html>