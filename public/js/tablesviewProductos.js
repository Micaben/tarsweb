function hacerConsulta(url, columnConfig, tablaID, modalID, bladeId, checkIgv) {
    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (Array.isArray(data)) {
                construirTabla(data, columnConfig, tablaID, modalID, bladeId, checkIgv);
            } else {
                console.error("La respuesta no es un array, convirtiendo a array:", data);
                construirTabla([data], columnConfig, tablaID, modalID, bladeId, checkIgv);
            }
        },
        error: function (error) {
            console.error("Error en la consulta AJAX", error);
        },
    });
}

function construirTabla(data, columnas, nombreTabla, modalid, bladeId, checkIgv) {
    if (!Array.isArray(columnas)) {
        console.error("Columnas no es un array", columnas);
        return;
    }

    var contenedorTabla = $("#" + nombreTabla);
    var tabla = $(
        '<table class="table table-striped table-bordered" width="100%"><thead><tr></tr></thead><tbody></tbody></table>'
    );
    var filaEncabezado = tabla.find("thead tr");

    columnas.forEach(function (columna) {
        filaEncabezado.append(
            '<th style="background-color: #1477d2; color: white; height: 7px;line-height: 7px; padding: 5;">' +
                columna.title +
                "</th>"
        );
    });

    var cuerpo = tabla.find("tbody");

    data.forEach(function (fila) {
        var tr = $("<tr></tr>").css("height", "10px");
        columnas.forEach(function (columna) {
            var td = $('<td style="padding: 5px;  "></td>');
            td.html(fila[columna.name]);
            if (columna.align === "right") {
                td.css("text-align", "right"); // Alinear a la derecha
            } else if (columna.align === "center") {
                td.css("text-align", "center"); // Alinear al centro
            } else {
                td.css("text-align", "left"); // Alinear a la izquierda como predeterminado
            }
            tr.append(td);
        });

        cuerpo.append(tr);

        // Verificar si el stock o saldo es vacío o igual a cero
        tr.on("dblclick", function (event) {
            if (bladeId === "ingreso") {
                agregarAFilaProductos(fila, "tablaProductos", checkIgv);
                $("#" + modalid).modal("hide");
            } else {
                if (fila["saldo"] === null || parseFloat(fila["saldo"]) <= 0) {
                    Swal.fire({
                        title: "Stock insuficiente",
                        text: "El producto seleccionado no tiene stock.",
                        icon: "warning",
                        confirmButtonText: "Aceptar",
                    }).then((result) => {
                        // Solo cerrar el modal si se confirma la alerta
                        if (result.isConfirmed) {
                            // No hacemos nada aquí porque el modal no debería cerrarse en caso de stock insuficiente
                        }
                    });
                    event.preventDefault(); // Evita el cierre del modal si se ha mostrado el alert
                    event.stopPropagation(); // Evita que el evento se propague
                } else {
                    agregarAFilaProductos(fila, "tablaProductos", checkIgv);
                    $("#" + modalid).modal("hide");
                }
            }
        });
    });

    contenedorTabla.empty();
    contenedorTabla.append(tabla);
    tabla.DataTable({
        columns: columnas,
    });
}

function actualizarTotal(tablaID, checkIgv) {
    checkIgv = checkIgv === true || checkIgv === "true";
    const igvRate = 0.18; 
    const SIGV_RATE = 1.18;
    const RET_RATE = 0.03; 
    let totalGeneral = 0;
    let totalIGV = 0;
    let totalSuma = 0;
    const retencionInput = document.getElementById("mtoretencion");
    const conRetencion = $("#conretencion").prop("checked");
    const conCortesia = $("#iscortesia").prop("checked");
    const codafecValue = $("#codafec").val();

    $("#" + tablaID + " tbody tr").each(function () {
        const tr = $(this);
        const cantidad = parseFloat(tr.find('input[name="Cantidad"]').val()) || 0;
        const precio = parseFloat(tr.find('input[name="Precio"]').val()) || 0;
        let subtotal = cantidad * precio;
        let igv = 0, total = 0, retencion = 0;

        if (conCortesia) {
            total = 0.00;
            subtotal = cantidad * precio;
            igv = 0.00;
        } else if (codafecValue === "40") {
            igv = 0.00;
            total = subtotal;
        } else if (codafecValue === "10") {
            subtotal = subtotal / SIGV_RATE;
            igv = subtotal * igvRate;
            total = subtotal * SIGV_RATE;
            
        }if (checkIgv) {
            subtotal = subtotal / SIGV_RATE;
             igv = subtotal * igvRate;
             total = subtotal * SIGV_RATE;
        } else {
            subtotal = cantidad * precio ;
             igv = subtotal * igvRate;
             total = subtotal + igv;
        }

        // Actualiza el subtotal en la fila
        tr.find("td:nth-child(7) span").text(subtotal.toFixed(2));
        totalGeneral += subtotal;
        totalIGV += igv;
        totalSuma += total;
    }); 
    let retencion = 0;
    if (conRetencion) {
        retencion = totalSuma * RET_RATE;
        retencionInput.value = retencion.toFixed(2);
    }
    $("#subtotal").val(totalGeneral.toFixed(2));
    $("#igv").val(totalIGV.toFixed(2));
    $("#totalSuma").val(totalSuma.toFixed(2));
    $("#total").val(totalSuma.toFixed(2));
    $("#retencioncuota").val(retencion.toFixed(2));
    let netpag = totalSuma - retencion;
    $("#netoapagar").val(netpag.toFixed(2));
}

function agregarAFilaProductos(fila, tablaID) {
    var tabla = $("#" + tablaID + " tbody");
    var tr = $("<tr></tr>");
    var columnasConfig = [
        { name: "index", isIndex: true }, // Columna de índice
        { name: "codProducto", editable: false }, // Columna no editable
        { name: "Descripcion", editable: false }, // Columna no editable
        { name: "Unidad", editable: false }, // Columna no editable
        { name: "Cantidad", editable: true, width: "90px" }, // Columna editable
        { name: "Precio", editable: true, width: "90px" }, // Columna editable
        { name: "Total", editable: false }, // Columna no editable
    ];

    // Agregar columnas según la configuración
    columnasConfig.forEach(function (columna, index) {
        var td = $("<td></td>");

        if (columna.isIndex) {
            td.text(tabla.children().length + 1); // Asigna el índice
        } else if (columna.editable) {
            var input = $(
                '<input type="number" step="0.01" min="1" value="1" style="width: ' +
                    columna.width +
                    ';">'
            );

            if (columna.name === "Cantidad") {
                input.val(1); // Cantidad mínima de 1
                input.attr("required", true);
                input.on("input", function () {
                    var checkIgv = $("#inigv").prop("checked");
                    actualizarTotal(tablaID, checkIgv);
                });
                input.on("keypress", function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        var nextInput = $(this)
                            .closest("td")
                            .next()
                            .find("input"); // Encontrar el siguiente input
                        if (nextInput.length > 0) {
                            nextInput.focus(); // Asignar el foco al siguiente input
                        }
                    }
                });
            }

            if (columna.name === "Precio") {
                input.val(fila[columna.name]);
                input.attr("required", true);
                input.on("input", function () {
                    var checkIgv = $("#inigv").prop("checked");
                    actualizarTotal(tablaID, checkIgv);                    
                });
            }

            input.attr("name", columna.name);
            td.append(input);
        } else {
            if (columna.name === "Total") {
                var totalSpan = $("<span></span>");
                var checkIgv = $("#inigv").prop("checked"); // Obtener el estado del checkbox dinámicamente
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
        }, 500);
    });

    var btnEliminar = $(
        '<button class="btn btn-danger btn-sm">Eliminar</button>'
    ).on("click", function () {
        tr.remove();
        actualizarIndices(tabla);
        var checkIgv = $("#inigv").prop("checked");
        actualizarTotal(tablaID, checkIgv);
    });
    tr.append($("<td></td>").append(btnEliminar));
    tabla.append(tr);
    var checkIgv = $("#inigv").prop("checked");
    actualizarTotal(tablaID, checkIgv);
}

function actualizarIndices(tabla) {
    tabla.children().each(function (index, tr) {
        $(tr)
            .children()
            .first()
            .text(index + 1); // Actualizar el índice de cada fila
    });
}

function actualizarTotalesRet() {
    const conretencion = document.getElementById("conretencion");
    const totalInput = document.getElementById("totalSuma");
    const retencionInput = document.getElementById("mtoretencion");
    const RET_RATE = 0.03; // 3%

    if (!totalInput || !retencionInput) {
        console.error(
            "No se encuentran los elementos necesarios para calcular la retención."
        );
        return;
    }

    let total = parseFloat(totalInput.value) || 0;
    let retencion = 0;

    if (conretencion && conretencion.checked) {
        retencion = total * RET_RATE; // Calcula el 3% de retención
    }

    retencionInput.value = retencion.toFixed(2); // Fija dos decimales
}

// Función para inicializar el evento de cambio del checkbox
function initRetencionCalculation() {
    const conretencion = document.getElementById("conretencion");
    if (conretencion) {
        conretencion.addEventListener("change", actualizarTotalesRet);
    }
}

function actualizarTotalesInafecto() {
    const codafec = document.getElementById("codafec");
    const totalInput = document.getElementById("totalSuma");
    const ininafecto = document.getElementById("inafecto");

    if (codafec.value == "40") {
        let total = parseFloat(totalInput.value) || 0;
        document.getElementById("igv").value = 0.0;
        ininafecto.value = total.toFixed(2); // Fija dos decimales
    }
}
