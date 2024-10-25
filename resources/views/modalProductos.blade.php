<!-- Archivo: partials/modal_productos.blade.php -->
<!-- *************  MODAL AGREGAR PRODUCTO ********************* -->
<div class="modal fade" id="modal-agregar-producto">
    <div class="modal-dialog modal-lg notice" style="min-width:80%;">
        <div class="modal-content">
            <div class="card card-info">
                <div class="card-header ">
                    <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioSublinea">Lista de Productos
                    </h6>
                </div>
                <form id="form-productos">
                    <div class="card-body">
                        <input id="numitem" type="hidden">
                        <div class="row">
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Código:</label>
                                <input id="codproducto" type="text" class="form-control custom-button"
                                    name="codproducto" maxlength="10" required="">
                            </div>
                            <div class="form-group col-sm-5 mb-2">
                                <label class="my-0">Producto:</label>
                                <select class="form-control custom-button" onchange="consultaStockProducto()"
                                    id="cboproducto" name="cboproducto" required="">
                                </select>
                            </div>
                            <div class="form-group col-sm-1 mb-2">
                                <label class="my-0">U. M.:</label>
                                <input id="umedida" type="text" class="form-control custom-button" readonly=""
                                    name="umedida" value="">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Color:</label>
                                <input id="color" type="text" class="form-control custom-button" readonly=""
                                    name="color" value="">
                            </div>
                            <div class="form-group col-sm-1 mb-2">
                                <label class="my-0">Ancho:</label>
                                <input id="ancho" type="text" class="form-control custom-button" readonly=""
                                    name="ancho" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 form-group mb-2">
                                <label class="my-0">Saldo:</label>
                                <input id="saldo" type="text" class="form-control custom-button" readonly=""
                                    name="saldo" maxlength="10" value="">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Comprometido:</label>
                                <input id="comprometido" type="text" class="form-control custom-button" readonly=""
                                    name="comprometido" maxlength="10" value="">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Stock actual:</label>
                                <input id="stockproducto" type="text" class="form-control custom-button" readonly=""
                                    name="stockproducto" maxlength="10" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group  col-sm-2 mb-2">
                                <label class="my-0">Cantidad:</label>
                                <input id="cantidad" type="text" class="form-control monto custom-button"
                                    onkeyup="calculate()" oninput="validarInput(this)" name="cantidad" maxlength="10">
                            </div>
                            <div class="form-group  col-sm-2 mb-2">
                                <label class="my-0">Precio:</label>
                                <input id="precio" type="text" class="form-control monto custom-button"
                                    onkeyup="calculate()" oninput="validarInput(this)" name="precio" maxlength="10">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Total:</label>
                                <input id="total" type="text" class="form-control custom-button" name="total"
                                    maxlength="15" readonly="">
                            </div>
                        </div>
                        <p>
                        <div class="margin">
                            <div class="btn-group" style="margin-top: 10px;">
                                <button class="btn btn-primary btn-icon-split btn-sm" id="btnAgregarFila"
                                    name="btnAgregarFila" onclick="agregarProductos()"><span
                                        class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                        class="text">Agregar</span></button>
                            </div>
                            <div class="btn-group" style="display: none">
                                <button class="btn btn-primary btn-icon-split btn-sm" id="btnEditarFila"
                                    name="btnEditarFila"><span class="icon text-white-50"><i
                                            class="fas fa-check"></i></span><span
                                        class="text">Actualizar</span></button>
                            </div>
                            <div class="btn-group" style="margin-top: 10px;">
                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                            class="fas fa-times"></i></span><span class="text">Cerrar</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script>

    let IdTabla, alm, idtablapr, detalleg, inigv, subtot, base, grav, tigv, tot, idd, boton;

    $(document).ready(function () {
        listarOpcionesProductos("#cboproducto", "{{ route('listar-activos') }}", 'codproducto', 'descripcion', '   unidad_des', 'color_des','ancho');
    });

    function mostrarProductos() {
        $("#modal-agregar-producto").modal('show');
        $('#modal-agregar-producto').on('shown.bs.modal', function () {
            $('#codproducto').focus();
        });
    }

    function consultaStockProducto() {
        var select = document.getElementById("cboproducto");
        var valorSeleccionado = select.value;
        var selectedOption = select.options[select.selectedIndex];
        document.getElementById("codproducto").value = valorSeleccionado;
        document.getElementById("umedida").value = selectedOption.getAttribute("data-um");
        document.getElementById("color").value = selectedOption.getAttribute("data-color");
        document.getElementById("ancho").value = selectedOption.getAttribute("data-ancho");
        listarStockP(valorSeleccionado)
            .then(result => {
                $('#stockproducto').val(result.stock);
                $('#comprometido').val(result.comprometido);
                $('#saldo').val(result.saldo);
            })
            .catch(error => {
                console.error(error);
            });
    }

    function listarStockP(producto) {
        var empresa = "{{ session('empresa') }}";
        var usuario = ""; // Ajusta según tu lógica
        var descripcion = ""; // Ajusta según tu lógica
        var activo = 1; // Ajusta según tu lógica

        var url = '/productos/' + empresa + usuario + producto + descripcion + activo;
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    var result = {
                        stock: parseFloat(data.stock),
                        comprometido: parseFloat(data.pedido),
                        saldo: parseFloat(data.saldo)
                    };
                    result.stock = isNaN(result.stock) ? 0.00 : result.stock;
                    result.comprometido = isNaN(result.comprometido) ? 0.00 : result.comprometido;
                    result.saldo = isNaN(result.saldo) ? 0.00 : result.saldo;
                    resolve(result);
                },
                error: function (xhr, status, error) {
                    console.error("Error en la llamada AJAX:", error);
                    reject(error);
                }
            });
        });
    }


    function findNextFocusableElement(currentElement, elements) {
        const currentIndex = elements.indexOf(currentElement);
        for (let i = currentIndex + 1; i < elements.length; i++) {
            const element = elements[i];
            if (element &&
                ((element.tagName === 'INPUT' && (element.type === 'text' || element.type === 'number')) || // Incluye type 'number'
                    (element.tagName === 'BUTTON') ||
                    (element.tagName === 'SELECT') ||
                    (element.tagName === 'INPUT' && element.type === 'date'))) {
                if (element.tagName === 'BUTTON') {
                    element.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            if (element.id === 'btnAgregar') {
                                limpiarmodalproducto();
                            }
                        }
                    });
                }
                return element;
            }
        }
        return null;
    }

    var btnAgregar = document.getElementById('btnAgregarFila');
    btnAgregarFila.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            agregarProductos();
            setTimeout(function () {
                document.getElementById('codproducto').focus();
            }, 0);
        }
    });

    function validarFormulario(formularioId, camposFormulario) {

    }

    function agregarProductos() {
        if (validarFormulario("#form-productos", ['codproducto', 'cboproducto', 'cantidad', 'precio', 'total'])) {
            var stockDisponible = parseFloat(document.getElementById("saldo").value);
            var repetido = false;
            var numTr = $('#' + IdTabla + ' tbody tr').length + 1;
            var idLib = $('#codproducto').val();
            var valorCat = $("#cboproducto option:selected").text();
            var unidad = $('#umedida').val();
            var cant = $('#cantidad').val();
            var pre = $('#precio').val();
            var total = $('#total').val();
            var cantidad = parseFloat(cant).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var precio = parseFloat(pre).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            // Verificar si el stock es suficiente            
            if (stockDisponible >= parseFloat(cant)) {
                var arr = $('#' + IdTabla + ' tr').find('td:first').map(function () {
                    return $(this).text();
                }).get();

                for (var i = 0; i < arr.length; i++) {
                    if (idLib === arr[i]) {
                        repetido = true;
                    }
                }
                var tabla = $('#' + IdTabla);
                if (!repetido) {
                    tabla.append('<tr data-id="' + numTr + '"><td class="item text-center">' + numTr + '</td><td class="codigo">' + idLib + '</td><td>' + valorCat + '</td><td class="cantidad text-center">' + unidad + '</td><td class="cantidad text-right">' + cantidad + '</td><td class="precio text-right">' + precio + ' </td><td class="totalpro text-right">' + total + '</td><td class="text-center">\n\
        <button type="button" onclick="editarFila(this, alm, IdTabla, detalleg, inigv, subtot, base, grav, tigv, tot)" class="btn btn-warning "><span class="fa fa-pencil-alt"></span></button></td><td class="text-center"><button type="button"  onclick="eliminarFila(this, alm, IdTabla, detalleg, inigv, subtot, base, grav, tigv, tot)" class="btn btn-danger"><span class="fa fa-trash"></span></button>\n\
        </td></tr>');
                    if (detalleg === "tablaDetalleGrilla") {
                        sumatoria(IdTabla, inigv, subtot, base, grav, tigv, tot, 6);
                        mensajeAgregaProducto();
                        limpiarmodalproducto();
                    } else if (detalleg === "tablaDetallePedido") {
                        sumatoria(IdTabla, inigv, subtot, base, grav, tigv, tot, 6);
                        mensajeAgregaProducto();
                        limpiarmodalproducto();
                        registrarped();
                    } else if (detalleg === "tablaDetalleGuia") {
                        sumatoria(IdTabla, inigv, subtot, base, grav, tigv, tot, 6);
                        mensajeAgregaProducto();
                        limpiarmodalproducto();
                    } else if (detalleg === "tablaIngresos" || detalleg === "tablaSalida") {
                        suma(IdTabla, tot);
                        mensajeAgregaProducto();
                        limpiarmodalproducto();
                    } else if (IdTabla === "tablaFacturarGuia") {
                        $.ajax({
                            url: 'srvGuiaremision?accion=updateIds',
                            data: { idsPedidos: idsPedidoG }
                        });
                        actualizarTablaG("srvGuiaremision?accion=actualizavista");
                    } else if (detalleg === "tablaDetalleComprobante") {
                        sumarComprobante(IdTabla, inigv, subtot, grav, base, tigv, tot, retencion, impcuota, retencioncuota, netocuota);
                        mensajeAgregaProducto();
                        limpiarmodalproducto();
                    }
                } else {
                    // swal("Advertencia !!", "El producto ya existe en la tabla!!", "warning");
                }
            } else {
                mensaje('No hay suficiente stock disponible !!', 'Revise la cantidad ingresada');
                document.addEventListener('keyup', (event) => {
                    if (event.key === 'Enter' && Swal.isVisible()) {
                        const confirmButton = document.querySelector('.swal2-confirm');
                        confirmButton.focus();
                    }
                });
            }
        } else {
            mensaje('Hubo un error !!', 'Complete los campos requeridos');
            document.addEventListener('keyup', (event) => {
                if (event.key === 'Enter' && Swal.isVisible()) {
                    const confirmButton = document.querySelector('.swal2-confirm');
                    confirmButton.focus();
                }
            });
        }
    }

    function editarFila(btn, almac, idtablap, idtablabody, checkigv, sub, baseimp, grava, igvtt, imp) {
        $("#btnEditarFila").css('display', 'block');
        $("#btnAgregarFila").css('display', 'none');
        $("#tituloP").html("Editar Producto");
        var fila = $(btn).closest('tr');
        var rowIndex = fila.index();
        var celdas = fila.find('td');

        document.getElementById('numitem').value = celdas[0].textContent;
        document.getElementById('codproducto').value = celdas[1].textContent;
        document.getElementById('cboproducto').value = celdas[2].textContent;
        document.getElementById('umedida').value = celdas[3].textContent;
        document.getElementById('cantidad').value = celdas[4].textContent.replace(/,/g, '');
        document.getElementById('precio').value = celdas[5].textContent.replace(/,/g, '');
        document.getElementById('total').value = celdas[6].textContent;
        const valorDeseado = celdas[1].textContent;
        $("#cboproducto").val(function () {
            for (let i = 0; i < this.options.length; i++) {
                const option = this.options[i];
                const optionValue = option.value;
                const valores = optionValue.split(',');
                const valorParametro1 = valores[0];
                const valorParametro2 = valores[1];
                if (valorParametro1 === valorDeseado) {
                    return valorParametro1;
                }
            }
        });
        $('#modal-agregar-producto').modal('show');
        alm = almac;
        idtablapr = idtablap;
        detalleg = idtablabody;
        inigv = checkigv;
        subtot = sub;
        base = baseimp;
        grav = grava;
        tigv = igvtt;
        tot = imp;
        consultaStockProducto();
        var celdasItemNumber = document.querySelectorAll('.item');
        celdasItemNumber.forEach(function (celda, index) {
            celda.innerText = index + 1;
        });
    }

    var filasEliminadas = [];

    function eliminarFila(btn, almac, idtablap, idtablabody, checkigv, sub, baseimp, grava, igvtt, imp) {
        boton = btn;
        alm = almac;
        idtablapr = idtablap;
        detalleg = idtablabody;
        inigv = checkigv;
        subtot = sub;
        base = baseimp;
        grav = grava;
        tigv = igvtt;
        tot = imp;
        console.log(idtablabody);
        console.log(idtablap);
        var fila = $(btn).closest('tr'); // Obtener la fila más cercana al botón
        var tabla = document.getElementById(detalleg);
        var rowIndex = fila.index();
        filasEliminadas.push(fila);
        fila.remove();
        var celdasItemNumber = document.querySelectorAll('.item');
        celdasItemNumber.forEach(function (celda, index) {
            celda.innerText = index + 1;
        });
        //var row = btn.parentNode.parentNode;
        //row.parentNode.removeChild(row);
        if (detalleg === "tablaDetalleGrilla") {
            sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
        } else if (detalleg === "tablaDetallePedido") {
            $.ajax({
                url: 'srvNotaventa?accion=eliminarPed',
                data: {
                    codigodetalle: idd,
                },
            });
            sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
            registrarped();
        } else if (detalleg === "tablaDetalleGuia") {
            sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
        } else if (detalleg === "tablaIngresos" || detalleg === "tablaSalida") {
            suma(idtablapr, tot);
        }
    }

    $(document).ready(function () {
        $('#btnEditarFila').on('click', function () {
            var stockDisponible = parseFloat(document.getElementById("saldo").value);
            var cant = $('#cantidad').val();
            if (stockDisponible >= parseFloat(cant)) {
                var idTabla = detalleg;
                var numitemValue = $('#numitem').val();
                var rowIndex = parseInt(numitemValue) - 1;
                var filaActualizada = $('#' + idTabla + ' tr').eq(rowIndex);

                filaActualizada.find('td:eq(1)').text($('#codproducto').val());
                filaActualizada.find('td:eq(2)').text($("#cboproducto option:selected").text());
                filaActualizada.find('td:eq(3)').text($('#umedida').val());
                filaActualizada.find('td:eq(4)').text(parseFloat($('#cantidad').val()).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                filaActualizada.find('td:eq(5)').text(parseFloat($('#precio').val()).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                filaActualizada.find('td:eq(6)').text(parseFloat($('#total').val().replace(/,/g, '')).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                if (detalleg === "tablaDetalleGrilla") {
                    sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
                } else if (detalleg === "tablaDetallePedido") {
                    sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
                    registrarped();
                } else if (detalleg === "tablaDetalleGuia") {
                    sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
                } else if (detalleg === "tablaIngresos" || detalleg === "tablaSalida") {
                    suma(idtablapr, tot);
                } else if (IdTabla === "tablaFacturarGuia") {
                    $.ajax({
                        url: 'srvGuiaremision?accion=updateIds',
                        data: { idsPedidos: idsPedidoG }
                    });
                    actualizarTablaG("srvGuiaremision?accion=actualizavista");
                } else if (detalleg === "tablaDetalleComprobante") {
                    sumarComprobante(idtablapr, inigv, subtot, grav, base, tigv, tot, retencion, impcuota, retencioncuota, netocuota);
                } else if (detalleg === "tablaDetalleProductosnc") {
                    sumatoria(idtablapr, inigv, subtot, base, grav, tigv, tot, 6);
                }
                $('#modal-agregar-producto').modal('hide');
            } else {
                mensaje('No hay suficiente stock disponible !!', 'Revise la cantidad ingresada');
                document.addEventListener('keyup', (event) => {
                    if (event.key === 'Enter' && Swal.isVisible()) {
                        const confirmButton = document.querySelector('.swal2-confirm');
                        confirmButton.focus();
                    }
                });
            }
        });
    });

    function limpiarmodalproducto() {
        $("#cboproducto").prop('selectedIndex', 0);
        const elementosALimpiar = [
            $("#stockproducto"), $("#numitem"), $("#codproducto"), $("#umedida"), $("#cantidad"), $("#precio"), $("#total"), $("#saldo"), $("#comprometido")];

        document.getElementById('codproducto').focus();
        $('.form-group input, .form-group select').each(function () {
            $(this).removeClass('is-invalid is-valid');
        });
    }

    document.getElementById('form-productos').addEventListener('keydown', function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            const formElements = Array.from(this.elements);
            formElements.sort((a, b) => {
                const order = ['codproducto', 'cboproducto', 'umedida', 'cantidad', 'precio', 'total', 'btnAgregarFila']; // Define el orden deseado
                return order.indexOf(a.name) - order.indexOf(b.name);
            });
            const currentElement = document.activeElement;
            const nextFocusableElement = findNextFocusableElement(currentElement, formElements);
            if (nextFocusableElement) {
                nextFocusableElement.focus();
            }
        }
    });

    document.getElementById('codproducto').addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && this.value.trim() !== '') {
            var codigoIngresado = this.value.trim();
            var select = document.getElementById('cboproducto');
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value === codigoIngresado) {
                    select.selectedIndex = i;
                    break;
                }
            }
            consultaStockProducto();
        }
    });
</script>