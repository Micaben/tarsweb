function hacerConsulta(
    url,
    columnas,
    nombreTabla,
    modalid,
    fechaDesde,
    fechaHasta
) {
    // console.log("URL:", url);
    $.ajax({
        url: url,
        method: "GET",
        data: {
            fechadesde: fechaDesde,
            fechahasta: fechaHasta,
        },
        dataType: "json",
        success: function (data) {
            construirTabla(data, columnas, nombreTabla, modalid);
            //console.log(data);
        },
        error: function (error) {
            console.error("Error al hacer la consulta:", error);
        },
    });
}

function construirTabla(data, columnas, nombreTabla, modalid) {
    //console.log("Datos de la tabla:", data);
    console.log("Columnas:", columnas);
    if (!Array.isArray(columnas)) {
        // console.error("Columnas no es un array", columnas);
        return;
    }

    var contenedorTabla = $("#" + nombreTabla);
    var tabla = $(
        '<table class="table table-striped table-bordered" width="100%" style="border-collapse: collapse;"><thead><tr></tr></thead><tbody></tbody></table>'
    );
    var filaEncabezado = tabla.find("thead tr");

    // Construir encabezados
    columnas.forEach(function (columna) {
        filaEncabezado.append(
            '<th style="background-color: #1477d2; color: white; height: 5px; padding: 5px; text-align: center">' +
                columna.title +
                "</th>"
        );
    });

    var cuerpo = tabla.find("tbody");
    data.forEach(function (fila) {
        var tr = $("<tr></tr>");
        columnas.forEach(function (columna) {
            var td = $(
                '<td style="padding: 5px; height: 5px; border: 1px solid #ddd;"></td>'
            );
            if (columna.name === "empresat") {
                td.html(fila.razonsocial + "<br>" + fila.empresa); 
            } else if (columna.name === "destinatario") {
                td.html(fila.grm_Delivery_DeliveryCustomerParty_Name + "<br>" + fila.grm_Delivery_DeliveryCustomerParty_ID ); 
            } else if (columna.name === "descargar") {
                var pdfButton = $('<button class="btn btn-info btn-sm">PDF</button>' );
                var xmlButton = $('<button class="btn btn-info btn-sm">XML</button>' );
                var cdrButton = $('<button class="btn btn-info btn-sm">CDR</button>' );
                // Asignar eventos a los botones
                var fechaOriginal = fila.grm_Fecha;                    
                    var fecha = new Date(fechaOriginal + "T00:00:00");       
                    var year = fecha.getFullYear();
                    var month = ("0" + (fecha.getMonth() + 1)).slice(-2); 
                    var day = ("0" + fecha.getDate()).slice(-2);
                    var fechaFormateada = year + month + day;
                    var documento = fila.documento;
                    var ruc = "10425068500";
                pdfButton.on("click", function () {
                    var rutaPdf = "/storage/PDF/" + fechaFormateada + "/" + ruc + "-09-" + documento +'.pdf';
                    var a = document.createElement('a');
                    a.href = rutaPdf;
                    a.download = rutaPdf.split('/').pop(); 
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
                xmlButton.on("click", function () {                    
                    var rutaXml = "/storage/XML/" + fechaFormateada + "/" + ruc + "-09-" + documento +'.xml';
                    var a = document.createElement('a');
                    a.href = rutaXml;
                    a.download = rutaXml.split('/').pop(); 
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
                cdrButton.on("click", function () {                    
                    var rutaCdr = "/storage/XML/" + fechaFormateada + "/R-" + ruc + "-09-" + documento +'.xml';
                    var a = document.createElement('a');
                    a.href = rutaCdr;
                    a.download = rutaCdr.split('/').pop(); 
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
                td.append(pdfButton)
                    .append(" ")
                    .append(xmlButton)
                    .append(" ")
                    .append(cdrButton);
            } else {
                td.html(fila[columna.name]);
            }
            tr.append(td);
        });

        cuerpo.append(tr);
        tr.on("dblclick", function () {
            mostrarModalEdicion(fila, modalid);
        });
    });

    contenedorTabla.empty();
    contenedorTabla.append(tabla);
    tabla.DataTable({
        columns: columnas,
    });
}
