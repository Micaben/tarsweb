// calculosTotales.js

// Configuración de tasas
const IGV_RATE = 0.18;
const RET_RATE = 0.03;

// Función para calcular el total de la columna 7
function calcularTotalSuma() {
    let totalSuma = 0;
    $("td:nth-child(7) span").each(function () {
        totalSuma += parseFloat($(this).text()) || 0;
    });
    return totalSuma;
}

// Función para calcular los valores de subtotal, IGV y total según el estado del checkbox
function calcularTotales(igvChecked) {
    const totalSuma = calcularTotalSuma();
    let subtotal, igv, total;

    if (igvChecked) {
        subtotal = totalSuma / (1 + IGV_RATE);
        igv = subtotal * IGV_RATE;
        total = totalSuma;
    } else {
        subtotal = totalSuma;
        igv = subtotal * IGV_RATE;
        total = subtotal + igv;
    }

    return { subtotal, igv, total };
}

// Función para actualizar los valores en los inputs
function actualizarInputs(conRetencion) {
    const igvCheckbox = document.getElementById('inigv');
    const { subtotal, igv, total } = calcularTotales(igvCheckbox.checked);

    $("#subtotal").val(subtotal.toFixed(2));
    $("#igv").val(igv.toFixed(2));
    $("#totalSuma").val(total.toFixed(2));

    let retencion = 0;
    if (conRetencion) {
        retencion = total * RET_RATE;
        $("#mtoretencion").val(retencion.toFixed(2));
    } else {
        $("#mtoretencion").val("0.00");
    }

    const netoAPagar = total - retencion;
    $("#netoapagar, #netopagar").val(netoAPagar.toFixed(2));
    $("#totalin").val(total.toFixed(2));
}

// Inicializa la función de actualización en el checkbox y en carga inicial
function inicializarCalculoTotales() {
    const igvCheckbox = document.getElementById('inigv');
    const conRetencionCheckbox = document.getElementById('conretencion');

    // Actualizar valores al hacer click en los checkboxes
    igvCheckbox.addEventListener('change', function () {
        const conRetencion = conRetencionCheckbox.checked;
        actualizarInputs(conRetencion);
    });

    conRetencionCheckbox.addEventListener('change', function () {
        const conRetencion = conRetencionCheckbox.checked;
        actualizarInputs(conRetencion);
    });

    // Carga inicial de valores
    const conRetencion = conRetencionCheckbox.checked;
    actualizarInputs(conRetencion);
}

