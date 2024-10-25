function handleFormSubmit({
    formId,
    tableId,
    serieSelectId,
    almacenSelectId,
    documentoId,
    submitUrl,
    extraInputs = [],
}) {
    document
        .getElementById(formId)
        .addEventListener("submit", function (event) {
            event.preventDefault();

            // Obtener la tabla de productos
            var tabla = document
                .getElementById(tableId)
                .getElementsByTagName("tbody")[0];
            var filas = tabla.getElementsByTagName("tr");

            // Validar si la tabla está vacía
            if (filas.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Atención",
                    text: "La tabla está vacía. Por favor, agrega productos antes de guardar.",
                    confirmButtonText: "Entendido",
                });
                return; // Salir de la función si la tabla está vacía
            }

            var form = document.getElementById(formId);
            var formData = new FormData(form);

            // Obtener texto y valor del select de serie
            var selectElement = document.getElementById(serieSelectId);
            var textoSelect = selectElement.options[selectElement.selectedIndex].text;

            // Obtener el valor de input hidden de documento
            var inputElement = document.getElementById(documentoId);
            var lblmoneda = document.getElementById('lblmoneda').textContent;
            // Obtener el valor de almacen
            var valorSelect = document.getElementById(almacenSelectId).value;
            var igvPorcentaje = 18;
            // Extraer los productos de la tabla
            var productos = [];
            for (var i = 0; i < filas.length; i++) {
                var columnas = filas[i].getElementsByTagName('td');
                var precio = parseFloat(columnas[5].getElementsByTagName('input')[0].value);
                var cantidad = parseFloat(columnas[4].getElementsByTagName('input')[0].value);
                var totalSinIGV = precio * cantidad;
                var igv = totalSinIGV * (igvPorcentaje / 100);
                var totalConIGV = totalSinIGV + igv;
                var totalSIGV = precio - igv;
                var igvproducto = precio * 0.18;
                var cod_afectaigv = document.getElementById('codafec').value;
                var precioref = precio * 1.18;
                var estadocheck = document.getElementById('iscortesia').checked ? 1 : 0;
                var producto = {
                    item: columnas[0].textContent.trim(),
                        codigo: columnas[1].textContent.trim(),
                        descripcion: columnas[2].textContent.trim(),
                        unidad: columnas[3].textContent.trim(),
                        cantidad: cantidad,
                        precio: precio,
                        totalsigv: totalSIGV.toFixed(2),
                        igv: igv.toFixed(2), // IGV calculado
                        total: columnas[6].textContent.trim(),
                        almacen: valorSelect,
                        precioref: precioref,
                        cod_afectaigv: cod_afectaigv,
                        igvproducto: igvproducto,
                        estadoocheck: estadocheck,
                        id: inputElement.value
                };
                productos.push(producto);
            }

            // Añadir los productos y el texto del select de serie al FormData
            formData.append("cboserie_text", getSelectText("cboserie"));
            formData.append("cboproveedor_text", getSelectText("cboproveedor"));
            formData.append("cboconcepto_text", getSelectText("cboconcepto"));
            formData.append("cbocondicion_text", getSelectText("cbocondicion"));
            formData.append("cbovendedor_text", getSelectText("cbovendedor"));
            formData.append("productos", JSON.stringify(productos));
            formData.append("lblmoneda", lblmoneda);

            // Añadir inputs adicionales como checkbox o radio buttons
            $("input[type=checkbox], input[type=radio]").each(function () {
                let inputinigv = $(this).attr("id");
                let inputChecked = $(this).is(":checked")? 1 : 0; 
                formData.append(inputinigv, inputChecked);
            });
            // ************ NUEVO CÓDIGO PARA TABLA DE CUOTAS ************
            // Obtener las filas de la tabla de cuotas
            //if (cuotasGuardadas.length > 0) {
            formData.append("cuotas", JSON.stringify(cuotasGuardadas));
            var tipoMoneda = document.getElementById("tipomoneda").value;
            formData.append("tipomoneda", tipoMoneda);
            //}
            // Añadir inputs extra (puede ser cualquier input adicional que varíe según el blade)
            extraInputs.forEach((input) => {
                let inputElement = document.getElementById(input.id);
                if (inputElement) {
                    formData.append(input.name, inputElement.value);
                }
            });
            console.log([...formData]);
            // Realizar el `fetch` a la URL proporcionada
            fetch(submitUrl, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        showSuccessMessage(
                            "Operación exitosa: " +
                                (data.message
                                    ? data.message
                                    : "Registro guardado correctamente")
                        );
                        var generatedId = data.id;
                        document.getElementById(documentoId).value =
                            generatedId; // Actualizar el ID generado
                    } else {
                        let errorMessages = formatErrorMessages(data.errors);
                        showErrorMessage(
                            "Error al procesar la solicitud. " + errorMessages
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showErrorMessage(
                        "Error al procesar la solicitud. Código de estado: " +
                            error
                    );
                });
        });
}
