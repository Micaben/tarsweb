function showSuccessMessage(message) {
    Swal.fire({
        icon: "success",
        title: "Éxito",
        text: message,
    });
}

function showErrorMessage(message) {
    Swal.fire({
        icon: "error",
        title: "Error",
        html: message, // Utilizar la opción html
        allowHtml: true, // Permitir contenido HTML
    });
}

function showWarningMessage(message) {
    Swal.fire({
        icon: "warning",
        title: "Advertencia",
        html: message, // Utilizar la opción html
        allowHtml: true, // Permitir contenido HTML
    });
}

function showSuccessWithActions(jsonData, imprimirUrl, enviarCorreoUrl) {
    let fechaInput = document.getElementById("fechaproceso").value; // ID del input tipo date
    let serieInput = document.getElementById("cboserie");
    let textoSelect = serieInput.options[serieInput.selectedIndex].text;
    let numeroInput = document.getElementById("numeronota").value; // ID del input para el número
    numeroInput = parseInt(numeroInput, 10)
    // Convertir la fecha al formato yyyyMMdd
    let fechaFormateada = convertirFecha(fechaInput);
    // Construir la ruta del PDF
    let pdfPath = `storage/PDF/${fechaFormateada}/10425068500-09-${textoSelect}-${numeroInput}.pdf`;
    Swal.fire({
        title: "Operación exitosa",
        text: jsonData.message || "Registro guardado correctamente",
        icon: "success",
        showCancelButton: true,
        confirmButtonText: "Imprimir Documento",
        html: `
            <div class="input-group mb-3">
                <input type="text" name="telefono" id="telefono" placeholder="Telefono" class="form-control form-control-sm">
                    <div class="input-group-append">
                        <button id="enviar-whatsapp" class="btn btn-outline-primary btn-sm" type="button">Enviar</button>
                    </div>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="correo" id="correo" class="form-control form-control-sm" placeholder="Correo">
                    <div class="input-group-append">
                        <button id="enviar-correo" class="btn btn-outline-primary btn-sm" type="button">Enviar</button>
                    </div>
            </div>        
        `,
        preConfirm: () => {
            window.open(imprimirUrl, "_blank");
        },
    }).then((result) => {
        if (result.isDenied) {
            return;
        }
    });

    document.getElementById('enviar-whatsapp').addEventListener('click', function() {
        const telefono = document.getElementById("telefono").value;
        const pdfUrl = `10425068500-09-${textoSelect}-${numeroInput}.pdf`; // Reemplaza esta ruta con la ruta real de tu archivo PDF
        const mensaje = `Hola, aquí tienes el documento que solicitaste: ${window.location.origin}${pdfUrl}`;
    
        if (!telefono) {
            Swal.fire("Error", "Por favor, introduce un número de teléfono.", "error");
            return;
        }
    
        // Quitar espacios y formatear el número si es necesario (por ejemplo, quitar el '+' si lo tiene)
        const telefonoFormateado = telefono.replace(/\D+/g, '');  // Eliminar cualquier carácter no numérico
    
        // Crear el enlace de WhatsApp
        const enlaceWhatsApp = `https://wa.me/${telefonoFormateado}?text=${encodeURIComponent(mensaje)}`;
    
        // Abrir el enlace en una nueva pestaña o aplicación de WhatsApp
        window.open(enlaceWhatsApp, '_blank');
    });

    // Asignar la funcionalidad al botón de "Enviar por Correo"
    document.getElementById('enviar-correo').addEventListener('click', function() {
        const correo = document.getElementById("correo").value;
        if (!correo) {
            Swal.fire("Error", "Por favor, introduce un correo electrónico.", "error");
            return;
        }
        // Llamar a la función para enviar por correo
        enviarDocumentoPorCorreo(jsonData, enviarCorreoUrl, pdfPath, correo);
    });
}

function enviarDocumentoPorCorreo(identpedidoId, url, pdfPath, correo) {
    let formData = new FormData();
    formData.append("identpedidoId", identpedidoId); // El ID del pedido
    formData.append("correo", correo);  // El correo al que se enviará el PDF
    formData.append("archivoPdf", pdfPath); // La ruta del archivo PDF
    
    console.log(url);

    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"), // Token CSRF para seguridad
        },
    })
    .then(response => {
        //console.log("Response:", response);
        return response.text();  // Cambiar a .text() para ver el contenido crudo
    })
    .then(data => {
        //console.log("Raw response data:", data);  // Ver lo que devuelve el servidor
        try {
            let jsonData = JSON.parse(data);  // Intentar parsear manualmente si es JSON válido
            if (jsonData.success) {
                Swal.fire("Éxito", "El documento ha sido enviado por correo correctamente.", "success");
            } else {
                Swal.fire("Error", jsonData.message || "Error al enviar el correo.", "error");
            }
        } catch (error) {
            console.error("Error al procesar la respuesta:", error);
            Swal.fire("Error", "La respuesta no es un JSON válido.", "error");
        }
    })
    .catch(error => {
        console.error("Error al enviar el correo:", error);
        Swal.fire("Error", "Error al procesar la solicitud: " + error.message, "error");
    });
}

function enviarPorWhatsApp(identpedidoId, telefono) {
    if (telefono) {
        // Lógica para enviar por WhatsApp
        console.log(
            `Enviando documento con ID ${identpedidoId} a WhatsApp número: ${telefono}`
        );
        // Lógica de envío (puedes usar un servicio de WhatsApp API)
    } else {
        Swal.fire(
            "Error",
            "Por favor, ingresa un número de teléfono.",
            "error"
        );
    }
}

function convertirFecha(fecha) {
    // La fecha del input tipo 'date' está en formato yyyy-MM-dd
    let partes = fecha.split("-"); // Dividir por guiones, no por barras
    let anio = partes[0];
    let mes = partes[1];
    let dia = partes[2];
    return `${anio}${mes}${dia}`; // Formato yyyyMMdd
}
