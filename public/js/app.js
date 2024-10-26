function actualizarCamposDesdeSelect(selectId, camposMap) {
    document.getElementById(selectId).addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];

        Object.keys(camposMap).forEach(function (campoId) {
            var customAttribute = selectedOption.getAttribute(camposMap[campoId]);

            // Verifica si es un checkbox
            var campoElemento = document.getElementById(campoId);
            if (campoElemento !== null) {
                if (campoElemento.type === 'checkbox') {
                    // Si es un checkbox, asigna el estado 'checked'
                    campoElemento.checked = customAttribute === '1' ? true : false; // Puedes ajustar según tus valores (1, true, etc.)
                } else {
                    // Para otros elementos, asigna el valor normal
                    campoElemento.value = customAttribute;
                }
            }
        });
    });
}

// Uso específico de `cboproveedor`
function configurarCambioProveedor() {
    actualizarCamposDesdeSelect('cboproveedor', {
        'proveedor': 'value',
        'direccion': 'data-custom-attribute-dir',
        'tipod': 'data-custom-attribute-td',
        'ubigeo': 'data-custom-attribute-ubi',
        'cbomoneda': 'data-custom-attribute-moneda',
        'cbocondicion': 'data-custom-attribute-condicion',
        'cbovendedor': 'data-custom-attribute-vendedor',
        'tipomoneda': 'data-custom-attribute-simbolomoneda',
        'plazo': 'data-custom-attribute-plazo',
        'conretencion': 'data-custom-attribute-retencion'
    });

    // Después de cambiar proveedor, también actualizar la fecha
    document.getElementById('cboproveedor').addEventListener('change', function () {
        var plazo = document.getElementById("plazo").value;
        actualizarFecha(plazo, 'fechav');
    });
}

// Uso específico de `cboconcepto`
function configurarCambioConcepto() {
    actualizarCamposDesdeSelect('cboconcepto', {
        'codafec': 'data-custom-attribute-codAfec',
        'tipoperacion': 'data-custom-attribute-tipoperacion',
        'typecod': 'data-custom-attribute-typecode',
        'iscortesia': 'data-custom-attribute-cortesia'
    });
}

// Uso específico de `cbocondicion`
function configurarCambioCondicion() {
    actualizarCamposDesdeSelect('cbocondicion', {
        'plazo': 'data-custom-attribute-plazo'
    });

    // Después de cambiar la condición, también actualizar la fecha
    document.getElementById('cbocondicion').addEventListener('change', function () {
        var plazo = document.getElementById("plazo").value;
        actualizarFecha(plazo, 'fechav');
    });
}

// Inicializar todas las configuraciones
function inicializarEventos() {
    configurarCambioProveedor();
    configurarCambioConcepto();
    configurarCambioCondicion();
}

// Ejecutar la inicialización cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function () {
    inicializarEventos();
});