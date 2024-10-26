function obtenerFechaActual(fechaprocesoId, fechacompraId) {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var ano = fecha.getFullYear();

    if (dia < 10)
        dia = '0' + dia;
    if (mes < 10)
        mes = '0' + mes;

    document.getElementById(fechaprocesoId).value = ano + "-" + mes + "-" + dia;
    document.getElementById(fechacompraId).value = dia + "/" + mes + "/" + ano;
}

function obtenerFechaTraslado(fechatrasladoId) {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var ano = fecha.getFullYear();

    if (dia < 10)
        dia = '0' + dia;
    if (mes < 10)
        mes = '0' + mes;

    document.getElementById(fechatrasladoId).value = ano + "-" + mes + "-" + dia;
}

function obtenerMesAnio(mesId, anioId) {
    var fechaActual = new Date();
    var mesActual = fechaActual.getMonth() + 1;
    var anioActual = fechaActual.getFullYear();

    document.getElementById(mesId).value = mesActual.toString();

    var selectAnio = document.getElementById(anioId);
    var anioMinimo = 1990;
    for (var i = anioActual; i >= anioMinimo; i--) {
        var option = document.createElement("option");
        option.value = i;
        option.text = i;
        selectAnio.appendChild(option);
    }
    selectAnio.value = anioActual;
}

function actualizarFecha(plazoValue, fechainput) {
    if (plazoValue !== undefined && plazoValue !== null && plazoValue !== '' && plazoValue.trim() !== '') {
        plazoValue = plazoValue.toString().replace(/[.,]/g, '');
        var plazoDias = parseInt(plazoValue, 10);

        if (!isNaN(plazoDias)) {
            var fechaActual = new Date();
            fechaActual.setDate(fechaActual.getDate() + plazoDias);

            var dia = String(fechaActual.getDate()).padStart(2, '0');
            var mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
            var anio = fechaActual.getFullYear();
            var nuevaFecha = dia + '/' + mes + '/' + anio;

            document.getElementById(fechainput).value = nuevaFecha;
        } else {
            console.error("Error: El valor de plazo no es un nÃºmero vÃ¡lido.");
        }
    } else {
        document.getElementById(fechainput).value = '';
    }
}

function formatearFecha(fecha) {
    // Verificar si la fecha es válida y no está vacía
    if (!fecha || fecha.trim() === '') {
        console.error('La fecha está vacía o no está presente:', fecha);
        return fecha;
    }

    // Dividir la cadena de fecha en sus componentes
    var partes = fecha.split('-');
    if (partes.length !== 3) {
        console.error('Formato de fecha no válido:', fecha);
        return fecha;
    }

    // Reorganizar los componentes para obtener el formato deseado (dd/MM/yyyy)
    return partes[2] + '/' + partes[1] + '/' + partes[0];
}

function getConfiguracionValidacion(campos) {
    const rules = {};
    const messages = {};
    campos.forEach(function (campo) {
        rules[campo] = {
            required: true,
        };
        messages[campo] = {
            required: "Ingrese información",
        };
    });
    return {
        rules: rules,
        messages: messages
    };
}

function configurarValidacion(formularioId, camposFormulario) {
    const configuracionValidacion = getConfiguracionValidacion(camposFormulario);

    $(formularioId).validate({
        rules: configuracionValidacion.rules,
        messages: configuracionValidacion.messages,
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    $('body').on('click', '.btn-update', function (evt) {
        $(formularioId).valid();
    });
}


function validarCampos() {
    // Aquí puedes agregar tus validaciones específicas
    var campo1 = document.getElementById('campo1').value;
    var campo2 = document.getElementById('campo2').value;
    // Agrega más campos según sea necesario

    // Ejemplo de validación: Verificar si los campos no están vacíos
    if (campo1 === '' || campo2 === '') {
        alert('Por favor, completa todos los campos.');
        return false; // Detiene la ejecución y no muestra el modal
    }

    // Si todas las validaciones pasan, retorna true
    return true;
}

function cambiaMoneda(idLabel, nuevoValor) {
    // Obtener el elemento label por su ID
    var label = document.getElementById(idLabel);

    // Actualizar el texto del label con el nuevo valor seleccionado
    if (nuevoValor === '01') {
        label.textContent = 'S/';
    } else {
        label.textContent = 'US$';
    }
}

function focusNextOnEnter(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevenir el comportamiento por defecto del Enter

        let form = event.target.form;
        let index = Array.prototype.indexOf.call(form, event.target);

        // Si el elemento actual es un botón (button, submit, etc.)
        if (event.target.tagName === 'BUTTON' || event.target.type === 'submit' || event.target.type === 'button') {
            if (event.target.type === 'submit') {
                // Si es un botón de tipo submit, envía el formulario
                form.submit();
            } else {
                // Si es un botón de otro tipo, simula un clic
                event.target.click();
            }
        } else {
            // Mover el foco al siguiente elemento si no es un botón
            let nextElement = form.elements[index + 1];
            if (nextElement) {
                nextElement.focus();
            }
        }
    }
}

// Función para agregar la funcionalidad a todos los formularios
function enableEnterNavigationForForms() {
    let forms = document.querySelectorAll('form');
    forms.forEach((form) => {
        form.addEventListener('keydown', focusNextOnEnter);
    });
}

