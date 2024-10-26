function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: message,
    });
}

function showErrorMessage(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: message, // Utilizar la opción html
        allowHtml: true // Permitir contenido HTML
    });
}