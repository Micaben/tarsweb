function handleFormSubmit({
    formId, 
    tableId, 
    serieSelectId, 
    almacenSelectId, 
    documentoId, 
    submitUrl,
  
}) {
    document.getElementById(formId).addEventListener('submit', function (event) {
        event.preventDefault();
        
        // Obtener la tabla de productos
        var tabla = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        var filas = tabla.getElementsByTagName('tr');

        // Validar si la tabla está vacía
        if (filas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'La tabla está vacía. Por favor, agrega productos antes de guardar.',
                confirmButtonText: 'Entendido'
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

        // Obtener el valor de almacen
        var valorSelect = document.getElementById(almacenSelectId).value;

        // Extraer los productos de la tabla
        var productos = [];
        for (var i = 0; i < filas.length; i++) {
            var columnas = filas[i].getElementsByTagName('td');
            var producto = {
                item: columnas[0].textContent.trim(),
                codigo: columnas[1].textContent.trim(),
                descripcion: columnas[2].textContent.trim(),
                unidad: columnas[3].textContent.trim(),
                cantidad: columnas[4].getElementsByTagName('input')[0].value,
                precio: columnas[5].getElementsByTagName('input')[0].value,
                total: columnas[6].textContent.trim(),
                almacen: valorSelect,
                id: inputElement.value
            };
            productos.push(producto);
        }

        // Añadir los productos y el texto del select de serie al FormData
        formData.append('cboserie_text', textoSelect);
        formData.append('productos', JSON.stringify(productos));

        // Añadir inputs adicionales como checkbox o radio buttons
        $('input[type=checkbox], input[type=radio]').each(function () {
            let inputinigv = $(this).attr('id');
            let inputChecked = $(this).is(':checked');
            formData.append(inputinigv, inputChecked);
        });

        // Añadir inputs extra (puede ser cualquier input adicional que varíe según el blade)


        // Realizar el `fetch` a la URL proporcionada
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())        
        .then(data => {
            console.log(data);
            if (data.success) {
                showSuccessMessage('Operación exitosa: ' + (data.message ? data.message : 'Registro guardado correctamente'));
                var generatedId = data.id;
                document.getElementById(documentoId).value = generatedId; // Actualizar el ID generado
            } else {
                let errorMessages = formatErrorMessages(data.errors);

                showErrorMessage('Error al procesar la solicitud. ' + errorMessages);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error al procesar la solicitud. Código de estado: ' + error);
        });
    });
}