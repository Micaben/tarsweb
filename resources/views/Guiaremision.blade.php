<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Guia de remision</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mensajeAnulado.css') }}">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('sidebarprincipal')

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('upperbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-2 text-gray-800">Guia de remision</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="row">
                            <div class="col-12">
                                <form id="form-pedidos" method="POST" action="{{ url('guardar-guiaremision') }}">
                                    <div class="d-flex justify-content-between">
                                    <div class="margin">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group"
                                                style="margin-top: 10px;">
                                                <button class="btn btn-primary btn-icon-split btn-sm" type="button"
                                                    onclick="modalNuevoRegistro()"><span class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <button class="btn btn-primary btn-icon-split btn-sm"
                                                style="margin-top: 10px;" id="btnBuscar" data-toggle="modal"
                                                type="button" data-target="#modal-mostrar-registros" data-columns='[{"name": "Documento", "align": "left"},
                                                            {"name": "Fecha", "align": "left"},
                                                            {"name": "RUC", "align": "left"},
                                                            {"name": "Cliente", "align": "left"},
                                                            {"name": "Estado", "align": "center"},
                                                            {"name": "MDA", "align": "center"},
                                                            {"name": "Total", "align": "right"},
                                                            {"name": "id", "align": "center"}]'
                                                data-url="{{ route('listar-mostrarRegistrosGuia') }}"
                                                data-route-name="registroGuia"
                                                data-routedetalle-name="registroGuiadetalle"
                                                data-inputs='["cboserie", "numeronota", "fechaproceso", "cboconcepto", "cboproveedor", "proveedor", "cbomoneda","vigencia","ordencompra","cboalmacen","comentarios","iddeldocumento","subtotal","inigv","igv","lblmoneda","totalSuma"]'
                                                data-input-mapping='{"cboserie":"grm_serie", "numeronota":"grm_numero", "fechaproceso":"grm_Fecha", "cboconcepto":"Concepto", "cboproveedor":"ruc", "proveedor":"ruc", "cbomoneda":"Moneda", "ordencompra":"grm_numeroOCompra", "cboalmacen":"almacen","comentarios":"grm_OrderReference_ID", "iddeldocumento":"id", "subtotal":"baseimp","inigv":"in_igv", "igv":"igv","lblmoneda":"Factor2", "totalSuma":"Total"}'
                                                data-message-id="mensajeAnulado">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <span class="text">Buscar</span>
                                            </button>
                                            <div class="btn-group" style="margin-top: 10px;">
                                                <button class="btn btn-primary btn-icon-split btn-sm" id="btnRegistrar"
                                                    name="btnRegistrar" type="submit"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group" style="margin-top: 10px;">
                                                <button class="btn btn-primary btn-icon-split btn-sm" id="btnImprimir"
                                                    type="button" name="btnImprimir" onclick="imprimirReporte()"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-print"></i></span><span
                                                        class="text">Imprimir</span></button>
                                            </div>
                                            <div class="btn-group" style="margin-top: 10px;">
                                                <button data-input="proveedor" data-iddocumento="iddeldocumento"
                                                    type="button" class="btn btn-primary btn-icon-split btn-sm"
                                                    data-toggle="modal" data-target="#modal-agregar-transporte"
                                                    data-blade-id="transporte"><span class="icon text-white-50"><i
                                                            class="fa fa-truck"></i></span><span
                                                        class="text">Transporte</span></button>
                                            </div>
                                            <div class="btn-group" style="margin-top: 10px;">
                                                <button class="btn btn-primary btn-icon-split btn-sm" type="button"
                                                    onclick="anular(event)"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Anular</span></button>
                                            </div>
                                            <div id="mensajeAnulado" class="estilo-texto" style="display: none;">A N U L
                                                A D O
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <input id="iddeldocumento" name="iddeldocumento" type="text"
                                            class="form-control form-control-sm" readonly="">
                                        <input id="tipodocumento" name="tipodocumento" type="text"
                                            class="form-control form-control-sm" value="09" readonly="">
                                        <div class="row">                                        
                                            <div class="form-group col-sm-2 mb-2 ">
                                                <label class="my-0">Serie:</label>
                                                <select class="form-control form-control-sm" required="" id="cboserie"
                                                    name="cboserie">
                                                </select>
                                            </div>
                                            
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Numero:</label>
                                                <input id="numeronota" type="text" class="form-control form-control-sm"
                                                    required="" name="numeronota" maxlength="8" readonly>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Fecha de proceso:</label>
                                                <input id="fechaproceso" type="date"
                                                    class="form-control form-control-sm" required="" name="fechaproceso"
                                                    maxlength="10">
                                            </div>
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Concepto:</label>
                                                <select class="form-control form-control-sm" required=""
                                                    id="cboconcepto" name="cboconcepto">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Moneda:</label>
                                                <select class="form-control form-control-sm" id="cbomoneda"
                                                    name="cbomoneda" onchange="cambiaMoneda('lblmoneda', this.value)">
                                                </select>
                                            </div>
                                            <div class="ml-auto" style="margin-right: 5px;">
                                                <div style="text-align: right;"
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="inigv"
                                                        name="inigv" checked>
                                                    <label class="custom-control-label" for="inigv">In igv?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-5 mb-2">
                                                <label class="my-0">Proveedor:</label>
                                                <select class="form-control form-control-sm" required=""
                                                    id="cboproveedor" name="cboproveedor">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Ruc:</label>
                                                <input id="proveedor" type="text" class="form-control form-control-sm"
                                                    required="" name="proveedor" readonly maxlength="11">
                                            </div>

                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Condicion:</label>
                                                <select id="cbocondicion" class="form-control form-control-sm"
                                                    name="cbocondicion">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-3 ">
                                                <label class="my-0">Vendedor:</label>
                                                <select class="form-control form-control-sm" id="cbovendedor"
                                                    name="cbovendedor">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">O/Compra:</label>
                                                <input id="ordencompra" type="text" class="form-control form-control-sm"
                                                    name="ordencompra" maxlength="20" value="">
                                            </div>
                                            <div class="form-group col-sm-3 ">
                                                <label class="my-0">Almacen:</label>
                                                <select class="form-control form-control-sm" id="cboalmacen"
                                                    name="cboalmacen">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="my-0">Comentarios:</label>
                                                <input id="comentarios" type="text" class="form-control form-control-sm"
                                                    name="comentarios" maxlength="150" value="">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <button type="button" id="btnAgregar" name="btnAgregar" value="Agregar"
                                                class="btn btn-outline-primary btn-sm custom-button" data-toggle="modal"
                                                data-target="#modal-agregar-producto" data-blade-id="guiaremision">
                                                <span class="fa fa-plus"></span> Agregar Productos
                                            </button>
                                        </div>
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-bordered table-striped dataTable table-hover clase_table table-sm"
                                                    id="tablaProductos" class="display">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width: 40px; text-align: center;">Item</th>
                                                            <th style=" text-align: center;">Codigo</th>
                                                            <th style=" text-align: center;">Descripción</th>
                                                            <th style=" text-align: center;">U.M</th>
                                                            <th style=" text-align: center;">Cantidad</th>
                                                            <th style=" text-align: center;">Precio</th>
                                                            <th style=" text-align: center;">Total</th>
                                                            <th style="width: 65px; text-align: center">Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaIngresos">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <div class="col-sm-12">
                                                <label style="text-align: right" class="col-sm-9"><strong>
                                                        <FONT SIZE=4>Subtotal <label name="lblsubtotal"
                                                                id="lblsubtotal"></label>
                                                        </FONT>
                                                    </strong></label>
                                                <strong>
                                                    <FONT color="#1b5e20" SIZE=4> <input type="text" name="subtotal"
                                                            id="subtotal"
                                                            style="text-align: right; width:130px; border: none;"
                                                            readonly> </FONT>
                                                </strong>
                                                <label style="text-align: right" class="col-sm-9"><strong>
                                                        <FONT SIZE=4>IGV 18%<label name="lbligv" id="lbligv"></label>
                                                        </FONT>
                                                    </strong></label>
                                                <strong>
                                                    <FONT color="#1b5e20" SIZE=4> <input type="text" name="igv" id="igv"
                                                            style="text-align: right; width:130px; border: none;"
                                                            readonly> </FONT>
                                                </strong>
                                                <label style="text-align: right" class="col-sm-9"><strong>
                                                        <FONT SIZE=4>Total <label name="lblmoneda"
                                                                id="lblmoneda"></label>
                                                        </FONT>
                                                    </strong></label>
                                                <strong>
                                                    <FONT color="#1b5e20" SIZE=4> <input type="text" name="totalSuma"
                                                            id="totalSuma"
                                                            style="text-align: right; width:130px; border: none;"
                                                            readonly> </FONT>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Main Content -->
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Mycsoft 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
            @include('modalListaProductos')
            @include('modalListaRegistros')
            @include('modalListaTransporte')
        </div>

    </div>
    <!-- End of Page Wrapper -->
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/selects.js') }}"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        $(document).ready(function () {
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('lblmoneda').innerText = 'S/';
            listarOpciones("#cboserie", "{{ route('obtener-series', ['id' => '09']) }}", 'id', 'serie', true);
            listarOpciones("#cboconcepto", "{{ route('listar-salida') }}", 'id', 'descripcion', false, 4);
            listarOpciones("#cboproveedor", "{{ route('listar-datosclientes') }}", 'ruc', 'razonsocial', true);
            listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
            listarOpciones("#cboalmacen", "{{ route('listar-datosalmacen') }}", 'id', 'descripcion', false);
            listarOpciones("#cbocondicion", "{{ route('listar-condicion') }}", 'id', 'descripcion', false);
            listarOpciones("#cbovendedor", "{{ route('listar-vendedor') }}", 'id', 'nombres', false);
        });

        document.getElementById('cboproveedor').addEventListener('change', function () {
            var select = document.getElementById("cboproveedor");
            var valorSeleccionado = select.value;
            document.getElementById("proveedor").value = valorSeleccionado;
        });

        document.addEventListener('DOMContentLoaded', function () {
            enableEnterNavigationForForms(); 
            var cboSerie = document.getElementById('cboserie');
            if (cboSerie) {
                cboSerie.addEventListener('change', function () {
                    var valorSeleccionado = this.value;
                    var url = "{{ url('obtener-ultimonumero') }}/09/" + encodeURIComponent(
                        valorSeleccionado);
                    obtenerultimoNumero("#numeronota", url);
                });
            } else {
                console.error("Elemento con ID 'cboserie' no encontrado.");
            }
        });

        function formatErrorMessages(errors) {
            if (Array.isArray(errors)) {
                return errors.join(', ');
            } else {
                return 'Error desconocido';
            }
        }

        function modalNuevoRegistro() {
            clearFormInputsSelects('form-pedidos', 'cboserie');
            clearFormInputsSelects('form-transporte', 'ructransporte', true, ['ructransporte', 'ubigeopartida', 'direccionpartida']);
            limpiarTablaDataTable('form-pedidos', 'tablaProductos');
            $("#inigv").prop("checked", true);
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('mensajeAnulado').style.display = 'none';
            document.getElementById("tipodocumento").value = '09';
            document.getElementById('btnAgregar').disabled = false;            
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('collapseThree').classList.add('show');
        });

        function getSelectText(id) {
            var selectElement = document.getElementById(id);
            return selectElement.options[selectElement.selectedIndex].text;
        }

        document.getElementById('form-pedidos').addEventListener('submit', function (event) {
            event.preventDefault();
            var form = document.getElementById('form-pedidos');
            var formData = new FormData(form);
            var tabla = document.getElementById('tablaProductos').getElementsByTagName('tbody')[0];
            var filas = tabla.getElementsByTagName('tr');
            if (filas.length === 0) {
                showErrorMessage('La tabla está vacía. Por favor, agrega productos antes de guardar.');
                return;
            }

            const keys = ['modalidad', 'direccionllegada', 'fechatraslado', 'textoempresatr',
                'ructransporte', 'nombres', 'apellidos', 'cbodocumento', 'dni', 'licencia',
                'unidad', 'placa', 'peso', 'bultos', 'ubigeopartida', 'direccionpartida', 'ubigeollegada'
            ];
            let missingFields = [];

            // Iterar sobre las claves y agregar a formData si existe en localStorage
            keys.forEach(key => {
                const value = localStorage.getItem(key);
                if (!value || value.trim() === '') {
                    missingFields.push(key);
                } else {
                    formData.append(key, value);
                }
            });
            if (missingFields.length > 0) {
                showErrorMessage('Ingrese los datos del transportista: ' + missingFields.join(', '));
                return;
            }
            localStorage.clear();
            var inputElement = document.getElementById('iddeldocumento');
            var lblmoneda = document.getElementById('lblmoneda').textContent;
            var valorSelect = document.getElementById('cboalmacen').value;
            var valorInput = 'G';
            var productos = [];
            var igvPorcentaje = 18;
            for (var i = 0; i < filas.length; i++) {
                var columnas = filas[i].getElementsByTagName('td');
                var precio = parseFloat(columnas[5].getElementsByTagName('input')[0].value);
                var cantidad = parseFloat(columnas[4].getElementsByTagName('input')[0].value);
                var totalSinIGV = precio * cantidad;
                var igv = totalSinIGV * (igvPorcentaje / 100);
                var totalConIGV = totalSinIGV + igv;
                var totalSIGV = precio - igv;
                var producto = {
                    item: columnas[0].textContent.trim(),
                    codigo: columnas[1].textContent.trim(),
                    descripcion: columnas[2].textContent.trim(),
                    unidad: columnas[3].textContent.trim(),
                    cantidad: cantidad,
                    precio: precio,
                    totalsigv: totalSIGV.toFixed(2),
                    igv: igv.toFixed(2), // IGV calculado
                    total: columnas[6].textContent.trim(),
                    almacen: valorSelect,
                    afectacion: valorInput,
                    id: inputElement.value
                };
                productos.push(producto);
            }
            formData.append('cboserie_text', getSelectText('cboserie'));
            formData.append('cboproveedor_text', getSelectText('cboproveedor'));
            formData.append('cboconcepto_text', getSelectText('cboconcepto'));
            formData.append('productos', JSON.stringify(productos));
            formData.append('lblmoneda', lblmoneda);
            $('input[type=checkbox], input[type=radio]').each(function () {
                let inputinigv = $(this).attr('id');
                let inputChecked = $(this).is(':checked');
                formData.append(inputinigv, inputChecked);
            });

            fetch('/guardar-guiaremision', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.text())
                .then(data => {
                    //console.log('Respuesta recibida del servidor:', data); // Muestra el contenido completo en la consola
                    try {
                        const jsonData = JSON.parse(data); // Intenta convertirlo a JSON
                        if (jsonData.success) {
                             //showSuccessMessage('Operación exitosa: ' + (jsonData.message ? jsonData.message : 'Registro guardado correctamente'));
                            document.getElementById('iddeldocumento').value = jsonData.identpedidoId;
                            var url = '{{ route("imprimir.reporteguia", ":id") }}';
                            url = url.replace(':id', jsonData.identpedidoId);
                            showSuccessWithActions(jsonData.identpedidoId, url, '/enviar-documento-por-correo');
                        } else {
                            let errorMessage = jsonData.errors ? jsonData.errors.message : 'Error desconocido.';
                            showErrorMessage('Error al procesar la solicitud: ' + errorMessage);
                        }
                    } catch (e) {
                        //console.error('Error al parsear el JSON:', e);
                        showErrorMessage('Error al procesar la respuesta del servidor. Contenido no válido.'+ e.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error al procesar la solicitud. Código de estado: ' + error.message);
                });
        });

        function anular(event) {
            const table = document.getElementById('tablaIngresos');
            var inputElement = document.getElementById('iddeldocumento').value;
            if (table.rows.length <= 0) {
                showErrorMessage('No hay ningún documento seleccionado para anular.');
                return;
            } else {
                Swal.fire({
                    title: '¿Desea anular el documento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/anularRegistroGuia/' + inputElement,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Token CSRF para Laravel
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Documento Anulado',
                                    text: 'Anulación: Documento anulado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                }).then(() => {
                                    // Después de cerrar el Swal de éxito, muestra el mensaje "ANULADO"
                                    document.getElementById('mensajeAnulado').style.display =
                                        'block';
                                });
                            },
                            error: function (xhr) {
                                showErrorMessage('Error al procesar la solicitud: ' + xhr.responseJSON
                                    .error);
                            }
                        });
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const igvCheckbox = document.getElementById('inigv');
            const IGV_RATE = 0.18;

            const subtotalInput = document.getElementById('subtotal');
            const igvInput = document.getElementById('igv');
            const totalSumaInput = document.getElementById('totalSuma');

            // Función para calcular IGV y actualizar los campos
            function actualizarTotales() {
                let subtotal = parseFloat(subtotalInput.value) || 0;
                let igv = 0;
                let total = subtotal;

                if (igvCheckbox.checked) {
                    igv = subtotal * IGV_RATE;
                    total += igv;
                }
                subtotalInput.value = subtotal.toFixed(2);
                igvInput.value = igv.toFixed(2);
                totalSumaInput.value = total.toFixed(2);
            }

            igvCheckbox.addEventListener('change', function () {
                actualizarTotales();
            });
            actualizarTotales();
        });

        function validarFormulario(formularioId, camposFormulario) {
            const configuracionValidacion = configurarValidacion(formularioId, camposFormulario);
            $(formularioId).validate(configuracionValidacion);
            return $(formularioId).valid();
        }

        function imprimirReporte() {
            const table = document.getElementById('tablaIngresos');
            if (table.rows.length <= 0) {
                showErrorMessage('No hay ningún documento seleccionado para imprimir.');
                return;
            } else {
                var id = document.getElementById('iddeldocumento').value;
                var url = '{{ route("imprimir.reporteguia", ":id") }}';
                url = url.replace(':id', id);
                window.open(url, '_blank');
            }
        }

        $(document).ready(function () {
            $('#btnAbrirModalTransporte').on('click', function (event) {
                event.preventDefault();
                var rucValue = $('#proveedor').val();
                if (!rucValue) {
                    showErrorMessage('Elija un cliente !!');
                } else {
                    $('#modal-agregar-transporte').modal('show');
                }
            });
        });

    </script>
</body>

</html>