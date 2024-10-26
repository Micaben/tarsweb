function clearFormInputsSelects(formId, focusInputId, clearSelect = true) {
    $('#' + formId + ' :input').each(function() {
        if ($(this).is('input')) {
            $(this).val('');
        } else if ($(this).is('select')) {
            if (clearSelect) {
                $(this).val($(this).find('option:first').val());
            }
        }
    });

    if (focusInputId) {
        $('#' + focusInputId).focus();
    }
}

function clearFormInputs(formId, focusInputId) {
    var form = document.getElementById(formId);

    if (form) {
        var formInputs = form.querySelectorAll('input');

        formInputs.forEach(function(input) {
            input.value = '';
        });

        if (focusInputId) {
            var focusInput = document.getElementById(focusInputId);
            if (focusInput) {
                focusInput.focus();
            }
        }
    }
}

function limpiarTablaDataTable(idFormulario, idTabla) {
    $('#' + idFormulario).find('#' + idTabla + ' tbody').empty();
}
