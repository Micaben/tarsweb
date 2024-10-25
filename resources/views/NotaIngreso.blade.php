<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Nota de Ingreso</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Nota de Ingreso</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="row">
                            <div class="col-12">
                                <form id="form-ingreso" method="POST" action="{{ url('guardar-notaingreso') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="margin">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group"
                                                    style="margin-top: 10px;">
                                                    <button class="btn btn-primary btn-icon-split btn-sm"
                                                        onclick="modalNuevoRegistro()"><span
                                                            class="icon text-white-50"><i
                                                                class="fas fa-plus"></i></span><span
                                                            class="text">Nuevo</span></button>
                                                </div>
                                                <button class="btn btn-primary btn-icon-split btn-sm"
                                                    style="margin-top: 10px;" id="btnBuscar" name="btnBuscar"
                                                    data-toggle="modal" data-target="#modal-mostrar-registros"
                                                    data-columns='[{"name": "Documento", "align": "left"},
                                                            {"name": "Fecha", "align": "left"},
                                                            {"name": "RUC", "align": "left"},
                                                            {"name": "Proveeodr", "align": "left"},
                                                            {"name": "Motivo", "align": "left"},
                                                            {"name": "Comprobante", "align": "left"},
                                                            {"name": "Guia", "align": left
                                                            {"name": "Usuario", "align": "center"},
                                                            {"name": "id", "align": "center"}]'
                                                    data-url="{{ route('listar-mostrarRegistros') }}"
                                                    data-route-name="registroIngreso"
                                                    data-routedetalle-name="registroIngresodetalle"
                                                    data-inputs='["cboconcepto", "cboserie", "numeronota", "fechaproceso",  "cboproveedor", "proveedor", "cbodocumentoasociado","documentoasociado","cbomoneda","fechacompra","tipocambio","cbomoneda","ordencompra","guiaremision","cboalmacen","cbotipooperacion","comentarios","iddeldocumento","totalSuma"]'
                                                    data-input-mapping='{"cboconcepto":"Concepto","cboserie":"serie", "numeronota":"Numero", "fechaproceso":"fecha", "cboproveedor":"cliente", "proveedor":"cliente", "cbodocumentoasociado":"DocRef","documentoasociado":"numdocref","fechacompra":"Fe_compra","tipocambio":"tcambio","cbomoneda":"Moneda","ordencompra":"OCompra","guiaremision":"GuiaRemision" ,"cboalmacen":"almacen","cbotipooperacion":"", "comentarios":"Referencia", "iddeldocumento":"id", "totalSuma":"Total"}'
                                                    data-message-id="mensajeAnulado">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-search"></i>
                                                    </span>
                                                    <span class="text">Buscar</span>
                                                </button>
                                                <div class="btn-group" style="margin-top: 10px;">
                                                    <button class="btn btn-primary btn-icon-split btn-sm"
                                                        id="btnRegistrar" name="btnRegistrar"
                                                        onclick="registrar(event)"><span class="icon text-white-50"><i
                                                                class="fas fa-save"></i></span><span
                                                            class="text">Guardar</span></button>
                                                </div>
                                                <div class="btn-group" style="margin-top: 10px;">
                                                    <button class="btn btn-primary btn-icon-split btn-sm"
                                                        id="btnImprimir" name="btnImprimir"><span
                                                            class="icon text-white-50"><i
                                                                class="fas fa-print"></i></span><span
                                                            class="text">Imprimir</span></button>
                                                </div>
                                                <div class="btn-group" style="margin-top: 10px;">
                                                    <button class="btn btn-primary btn-icon-split btn-sm"
                                                        onclick="anular(event)"><span class="icon text-white-50"><i
                                                                class="fas fa-times"></i></span><span
                                                            class="text">Anular</span></button>
                                                </div>
                                                <div id="mensajeAnulado" class="estilo-texto" style="display: none;">A N U L A D O
                                                </div>
                                            </div>
                                        </div>
                                        <input id="iddeldocumento" name="iddeldocumento" type="text"
                                            class="form-control form-control-sm" readonly="">
                                        <input id="tipodocumento" name="tipodocumento" type="text"
                                            class="form-control form-control-sm" value="38" readonly="">
                                        <div class="row">
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Concepto:</label>
                                                <select class="form-control form-control-sm" required=""
                                                    id="cboconcepto" name="cboconcepto">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Serie:</label>
                                                <select class="form-control form-control-sm" required="" id="cboserie"
                                                    name="cboserie">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Numero:</label>
                                                <input id="numeronota" type="text" class="form-control form-control-sm"
                                                    required="" name="numeronota" maxlength="8" readonly="">
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Fecha de proceso:</label>
                                                <input id="fechaproceso" type="date"
                                                    class="form-control form-control-sm" required="" name="fechaproceso"
                                                    maxlength="10">
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
                                                <label class="my-0">Documento asociado:</label>
                                                <select class="form-control form-control-sm" id="cbodocumentoasociado"
                                                    name="cbodocumentoasociado">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Numero:</label>
                                                <input id="documentoasociado" type="text"
                                                    class="form-control form-control-sm" name="documentoasociado"
                                                    maxlength="15">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 form-group mb-2">
                                                <label class="my-0">Fecha de compra:</label>
                                                <input id="fechacompra" type="date" class="form-control form-control-sm"
                                                    name="fechacompra" maxlength="15" value="">
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Tipo de Cambio:</label>
                                                <input id="tipocambio" type="text" class="form-control form-control-sm"
                                                    readonly="" name="tipocambio" maxlength="5" value="">
                                            </div>
                                            <div class="form-group col-sm-2 mb-2">
                                                <label class="my-0">Moneda:</label>
                                                <select class="form-control form-control-sm" id="cbomoneda"
                                                    name="cbomoneda" onchange="cambiaMoneda('lblmoneda', this.value)">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">O/Compra:</label>
                                                <input id="ordencompra" type="text" class="form-control form-control-sm"
                                                    name="ordencompra" maxlength="20" value="">
                                            </div>
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Guia de Remisión:</label>
                                                <input id="guiaremision" type="text"
                                                    class="form-control form-control-sm" name="guiaremision"
                                                    maxlength="15" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label class="my-0">Tipo de Operación</label>
                                                <select class="form-control form-control-sm" id="cbotipooperacion"
                                                    name="cbotipooperacion">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-3 ">
                                                <label class="my-0">Almacen:</label>
                                                <select class="form-control form-control-sm" id="cboalmacen"
                                                    name="cboalmacen">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-5 ">
                                                <label class="my-0">Comentarios:</label>
                                                <input id="comentarios" type="text" class="form-control form-control-sm"
                                                    name="comentarios" maxlength="150" value="">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <button type="button" id="btnAgregar" name="btnAgregar" value="Agregar"
                                                class="btn btn-outline-primary btn-sm custom-button" data-toggle="modal"
                                                data-target="#modal-agregar-producto" data-blade-id="ingreso">
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
                                                        <FONT SIZE=4>Total <label name="lbl" id="lblmoneda"></label>
                                                        </FONT>
                                                    </strong></label>
                                                <strong>
                                                    <FONT color="#1b5e20" SIZE=4> <input type="text" id="totalSuma"
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
        // Configuración de columnas
        $(document).ready(function () {
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('fechacompra').value = fechaActual;
            listarOpciones("#cboconcepto", "{{ route('listar-ingreso') }}", 'id', 'descripcion', true);
            listarOpciones("#cboserie", "{{ route('obtener-series', ['id' => '38']) }}", 'id', 'serie', true);
            listarOpciones("#cboproveedor", "{{ route('listar-datosproveedor') }}", 'proveedor', 'nombres', true);
            listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
            listarOpciones("#cbodocumentoasociado", "{{ route('obtener-documentos') }}", 'documento', 'Descripcion', true);
            listarOpciones("#cboalmacen", "{{ route('listar-datosalmacen') }}", 'id', 'descripcion', false);
        });

        document.getElementById('cboproveedor').addEventListener('change', function () {
            var select = document.getElementById("cboproveedor");
            var valorSeleccionado = select.value;
            document.getElementById("proveedor").value = valorSeleccionado;
        });

        document.addEventListener('DOMContentLoaded', function () {
            var cboSerie = document.getElementById('cboserie');
            if (cboSerie) {
                cboSerie.addEventListener('change', function () {
                    var valorSeleccionado = this.value;
                    var url = "{{ url('obtener-ultimonumero') }}/38/" + encodeURIComponent(valorSeleccionado);
                    console.log("URL generada: ", url); // Para depuración
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

        function validarFormulario(formularioId, camposFormulario) {
            const configuracionValidacion = configurarValidacion(formularioId, camposFormulario);
            $(formularioId).validate(configuracionValidacion);
            return $(formularioId).valid();
        }

        function modalNuevoRegistro() {
            clearFormInputsSelects('form-ingreso', 'cboconcepto');
            limpiarTablaDataTable('form-ingreso', 'tablaProductos');
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('fechacompra').value = fechaActual;
            document.getElementById("tipodocumento").value = '38';
            document.getElementById('btnAgregar').disabled = false;
            document.getElementById('mensajeAnulado').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('collapseni').classList.add('show');
        });

        document.getElementById('form-cotizacion').addEventListener('submit', function (event) {
            event.preventDefault();
            var tabla = document.getElementById('tablaProductos').getElementsByTagName('tbody')[0];
            var filas = tabla.getElementsByTagName('tr');
            if (filas.length === 0) {
                showErrorMessage('La tabla está vacía. Por favor, agrega productos antes de guardar.');
                return;
            }
            var form = document.getElementById('form-ingreso');
            var formData = new FormData(form);
            var selectElement = document.getElementById('cboserie');
            var textoSelect = selectElement.options[selectElement.selectedIndex].text;
            var inputElement = document.getElementById('iddeldocumento');

            var valorSelect = document.getElementById('cboalmacen').value;
            var valorInput = 'G';
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
                    afectacion: valorInput,
                    id: inputElement.value
                };
                productos.push(producto);
            }
            formData.append('cboserie_text', textoSelect);
            formData.append('productos', JSON.stringify(productos));

            fetch('/guardar-notaingreso', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage('Operación exitosa: ' + (data.message ? data.message : 'Registro guardado correctamente'));
                        var generatedId = data.id;
                        document.getElementById('iddeldocumento').value = generatedId; // Ajusta el ID según tu HTML
                    } else {
                        let errorMessages = formatErrorMessages(data.errors);
                        showErrorMessage('Error al procesar la solicitud. Detalles: ' + errorMessages);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error al procesar la solicitud. Código de estado: ' + error);
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
                            url: '/anularRegistroMov/' + inputElement,
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
                                    document.getElementById('mensajeAnulado').style.display = 'block';
                                });
                            },
                            error: function (xhr) {
                                showErrorMessage('Error al procesar la solicitud: ' + xhr.responseJSON.error);
                            }
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>