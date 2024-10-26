

function listarOpcionesProductos(selector, url, valueField, textField, unidadField, colorField, anchoField) {
    $.ajax({
        url: url,
        method: 'GET',
        success: function (response) {
            var select = $(selector);
            select.empty();

            // Agregar la opción "Seleccione"
            var defaultOption = $('<option>')
                .val('')
                .text('Seleccione')
                .attr('disabled', 'disabled')
                .attr('selected', 'selected');
            select.append(defaultOption);

            // Agregar las opciones desde el response
            $.each(response, function (index, item) {
                var option = $('<option>')
                    .val(item[valueField])
                    .text(item[textField])
                    .attr('data-um', item[unidadField])
                    .attr('data-color', item[colorField])
                    .attr('data-ancho', item[anchoField]);
                select.append(option);
            });
        },
        error: function (error) {
            console.error(error);
        }
    });
}

function obtenerultimoNumero(input, url) {
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response) {
                var numero = response[0].ultimo;
                if (numero !== null && numero !== undefined) { // Verifica si el número es válido
                    var numeroFormateado = numero.toString().padStart(8, '0');
                    $(input).val(numeroFormateado);
                } else {
                    Swal.fire({
                        title: 'Hubo un error',
                        text: "No se pudo obtener el número de la base de datos.",
                        icon: 'warning'
                    });
                }
            } else {
                Swal.fire({
                    title: 'Hubo un error',
                    text: "No ha registrado una serie para este documento !!",
                    icon: 'warning'
                });
            }
        },
        error: function (xhr, status, error) {
            console.log("Error en la solicitud AJAX:", error);
        }
    });
}

function listarOpcionesChofer(selector, url, valorCampo, textoCampo, concatenarCampos = [], incluirVacio = false) {
    // Hacer la petición AJAX
    $.get(url, function (data) {
        let $select = $(selector);
        $select.empty();

        // Si incluir opción vacía es true
        if (incluirVacio) {
            $select.append(`<option value="">Seleccione una opción</option>`);
        }

        // Iterar sobre los datos recibidos
        data.forEach(function (item) {
            let texto = item[textoCampo]; // Obtener el campo 'nombres'

            // Concatenar los campos adicionales si es necesario
            concatenarCampos.forEach(function (campo) {
                texto += ' ' + item[campo]; // Concatenar apellidos, dni, etc.
            });

            // Agregar la opción al select
            $select.append(`<option value="${item[valorCampo]}">${texto}</option>`);
        });
    });
}

function listarOpciones(selector, url, idCampo, textoCampo, incluirSeleccione = true, valorDefecto = null) {
    const $select = $(selector);
    $select.empty();

    // Si incluirSeleccione es true y no se ha pasado un valor por defecto
    if (incluirSeleccione && !valorDefecto) {
        $select.append($('<option>', {
            value: "",
            text: "Seleccione",
            disabled: true,
            selected: true
        }));
    }

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $select.append($('<option>', {
                    value: item[idCampo],
                    text: item[textoCampo],
                    selected: item[idCampo] === valorDefecto // Si coincide con el valor por defecto, lo selecciona
                }));
            });

            // Si se pasó un valor por defecto y no se ha seleccionado ningún valor aún
            if (valorDefecto && !$select.val()) {
                $select.val(valorDefecto); // Selecciona el valor por defecto
            }
        },
        error: function (error) {
            console.error('Error al cargar las opciones:', error);
        }
    });
}
