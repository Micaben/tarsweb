document.addEventListener('DOMContentLoaded', function () {
    setFechaActual('fechaproceso');
    listenForProveedorChange('cboproveedor', 'proveedor');
    listenForSerieChange('cboserie', 'numeronota');
    
});

/**
 * Establece la fecha actual en un campo de fecha.
 * @param {string} inputId - El ID del campo de fecha.
 */
function setFechaActual(inputId) {
    var fechaActual = new Date().toISOString().split('T')[0];
    var inputFecha = document.getElementById(inputId);
    if (inputFecha) {
        inputFecha.value = fechaActual;
    } else {
        console.error(`Elemento con ID '${inputId}' no encontrado.`);
    }
}

/**
 * Añade un evento change a un select y actualiza un input con el valor seleccionado.
 * @param {string} selectId - El ID del select.
 * @param {string} inputId - El ID del input que será actualizado.
 */
function listenForProveedorChange(selectId, inputId) {
    var selectProveedor = document.getElementById(selectId);
    var inputProveedor = document.getElementById(inputId);

    if (selectProveedor && inputProveedor) {
        selectProveedor.addEventListener('change', function () {
            var valorSeleccionado = this.value;
            inputProveedor.value = valorSeleccionado;
        });
    } else {
        console.error(`Elementos con ID '${selectId}' o '${inputId}' no encontrados.`);
    }
}

/**
 * Añade un evento change al select de la serie y actualiza el campo de número de nota con el valor recuperado.
 * @param {string} selectId - El ID del select de serie.
 * @param {string} inputId - El ID del input donde se mostrará el número recuperado.
 */
function listenForSerieChange(selectId, inputId) {
    var cboSerie = document.getElementById(selectId);
    var tipoDocumento = document.getElementById('tipodocumento').value;
    if (cboSerie) {
        cboSerie.addEventListener('change', function () {
            var valorSeleccionado = this.value;
            var url = "/obtener-ultimonumero/" + encodeURIComponent(tipoDocumento) + "/" + encodeURIComponent(valorSeleccionado);
            console.log("URL generada: ", url); // Para depuración
            obtenerultimoNumero(inputId, url);
        });
    } else {
        console.error(`Elemento con ID '${selectId}' no encontrado.`);
    }
}

/**
 * Función ficticia que representa una llamada para obtener el último número.
 * @param {string} inputId - El ID del input donde se actualizará el número.
 * @param {string} url - La URL para hacer la solicitud AJAX.
 */
function obtenerultimoNumero(inputId, url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.json();
        })
        .then(data => {
            var inputNumeroNota = document.getElementById(inputId);
            if (inputNumeroNota) {
                if (data.length > 0 && data[0].ultimo !== undefined) {
                    inputNumeroNota.value = data[0].ultimo || ''; // Accede a `ultimo` dentro del primer objeto del array                    
                } else {
                }
            } else {
                console.error(`Elemento con ID '${inputId}' no encontrado.`);
            }
        })
        .catch(error => {
            console.error('Error al obtener el último número:', error);
        });
}