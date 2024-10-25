function listarOpcionesProductos(
    selector,
    url,
    valueField,
    textField,
    unidadField,
    colorField,
    anchoField
) {
    $.ajax({
        url: url,
        method: "GET",
        success: function (response) {
            var select = $(selector);
            select.empty();

            // Agregar la opción "Seleccione"
            var defaultOption = $("<option>")
                .val("")
                .text("Seleccione")
                .attr("disabled", "disabled")
                .attr("selected", "selected");
            select.append(defaultOption);

            // Agregar las opciones desde el response
            $.each(response, function (index, item) {
                var option = $("<option>")
                    .val(item[valueField])
                    .text(item[textField])
                    .attr("data-um", item[unidadField])
                    .attr("data-color", item[colorField])
                    .attr("data-ancho", item[anchoField]);
                select.append(option);
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
}

function obtenerultimoNumero(input, url) {
    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response) {
                var numero = response[0].ultimo;
                if (numero !== null && numero !== undefined) {
                    // Verifica si el número es válido
                    var numeroFormateado = numero.toString().padStart(8, "0");
                    $(input).val(numeroFormateado);
                } else {
                    Swal.fire({
                        title: "Hubo un error",
                        text: "No se pudo obtener el número de la base de datos.",
                        icon: "warning",
                    });
                }
            } else {
                Swal.fire({
                    title: "Hubo un error",
                    text: "No ha registrado una serie para este documento !!",
                    icon: "warning",
                });
            }
        },
        error: function (xhr, status, error) {
            console.log("Error en la solicitud AJAX:", error);
        },
    });
}

function listarOpcionesChofer(
    selector,
    url,
    valorCampo,
    textoCampo,
    concatenarCampos = [],
    incluirVacio = false
) {
    $.get(url, function (data) {
        let $select = $(selector);
        $select.empty();

        if (incluirVacio) {
            $select.append(`<option value="">Seleccione una opción</option>`);
        }

        data.forEach(function (item) {
            let texto = item[textoCampo];

            concatenarCampos.forEach(function (campo) {
                texto += " " + item[campo]; // Concatenar apellidos, dni, etc.
            });

            $select.append(
                `<option value="${item[valorCampo]}">${texto}</option>`
            );
        });
    });
}

function listarOpciones(
    selector,
    url,
    idCampo,
    textoCampo,
    incluirSeleccione = true,
    valorDefecto = null,
    callback = null
) {
    const $select = $(selector);
    $select.empty();

    if (incluirSeleccione && !valorDefecto) {
        $select.append(
            $("<option>", {
                value: "",
                text: "Seleccione",
                disabled: true,
                selected: true,
            })
        );
    }

    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: function (data) {
            //console.log(data)
            data.forEach(function (item) {
                $select.append(
                    $("<option>", {
                        value: item[idCampo],
                        text: item[textoCampo],
                        "data-custom-attribute": item.td,
                        "data-custom-attribute-dir": item.direccion,
                        "data-custom-attribute-td": item.tipod,
                        "data-custom-attribute-ubi": item.ubigeo,
                        "data-custom-attribute-moneda": item.moneda,
                        "data-custom-attribute-condicion": item.condicion,
                        "data-custom-attribute-vendedor": item.vendedor,
                        "data-custom-attribute-codafec": item.codAfec,
                        "data-custom-attribute-tipoperacion": item.tipoperacion,
                        "data-custom-attribute-typecode": item.typecode,
                        "data-custom-attribute-simbolomoneda": item.mda_codigo,
                        "data-custom-attribute-plazo": item.plazo,
                        "data-custom-attribute-retencion": item.agretencion,
                        "data-custom-attribute-cortesia": item.cortesia,
                        selected: item[idCampo] === valorDefecto,
                    })
                );
            });

            if (valorDefecto && !$select.val()) {
                $select.val(valorDefecto);
            }

            // Ejecuta el callback después de que las opciones se hayan cargado
            if (callback) {
                callback();
            }
        },
        error: function (error) {
            console.error("Error al cargar las opciones:", error);
        },
    });
}
