<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Comprobantes</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Comprobantes</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-container">
                                    
                                    <form id="form-documentos">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <x-Botones :columns="[
                                                ['name' => 'Documento', 'align' => 'left'],                                            
                                                ['name' => 'Fecha', 'align' => 'left'],
                                                ['name' => 'RUC', 'align' => 'left'],
                                                ['name' => 'Cliente', 'align' => 'left'],
                                                ['name' => 'Estado', 'align' => 'center'],
                                                ['name' => 'MDA', 'align' => 'center'],
                                                ['name' => 'Total', 'align' => 'right'],
                                                ['name' => 'id', 'align' => 'center']
                                                ]"
                                            :url="route('listar-mostrarRegistrosComprobantes')"
                                            :routeName="'registroComprobantes'"
                                            :routeDetalleName="'registroComprobantesdetalle'" :inputs="['cbocomprobante','cboserie', 'numeronota', 'fechaproceso', 'cboconcepto', 'cboproveedor', 'proveedor', 'cbocondicion','plazo','fechav','cbomoneda', 'cbovendedor', 'ordencompra', 'cboalmacen','direccion','tipod','ubigeo','tipodocumento','codafec','tipoperacion','typecod','tipomoneda','totalin','mtoretencion','netopagar','inafecto', 'comentarios', 'iddeldocumento', 'subtotal', 'conretencion','inigv', 'igv', 'lblmoneda', 'totalSuma']"
                                            :inputMapping="[
                                            'direccion' => 'direccion',
                                            'tipod' => 'TipoDocIdR', 
                                            'ubigeo' => 'ubigeo',    
                                            'tipodocumento'=> 'TipoDocumento',
                                            'codafec'=> 'Cod_AfectaIGV',
                                            'tipoperacion'=> 'TipOperacion',
                                            'typecod'=> 'com_InvoiceTypeCode',
                                            'tipomoneda'=> 'tipomoneda',
                                            'totalin'=> 'ImporteTotal',
                                            'mtoretencion'=> 'Mto_Retencion',
                                            'netopagar'=> 'com_NetoPagar',
                                            'inafecto'=> 'inafecto',
                                            'cbocomprobante' => 'TipoDocumento',
                                            'cboserie' => 'serie',
                                            'numeronota' => 'numero',
                                            'fechaproceso' => 'FechaEmision',
                                            'cboconcepto' => 'Concepto',
                                            'cboproveedor' => 'ruc',
                                            'proveedor' => 'ruc',
                                            'cbocondicion' => 'condicion',
                                            'plazo' => 'plazo',
                                            'fechav' => 'FechaV',
                                            'cbomoneda' => 'Moneda',
                                            'ordencompra' => 'NumOCompra',
                                            'cboalmacen' => 'almacen',
                                            'cbovendedor' => 'vendedor',     
                                            'comentarios' => 'Referencia',
                                            'iddeldocumento' => 'id',
                                            'subtotal' => 'BaseImponible',
                                            'conretencion' => 'com_Retencion',
                                            'inigv' => 'com_IGV',
                                            'igv' => 'IGV',
                                            'lblmoneda' => 'Factor2',
                                            'totalSuma' => 'ImporteTotal'
                                            ]" :messageId="'mensajeAnulado'"  :showTransporte="false"  
                                             :showAnuladoMessage="true" />
                                        </div>
                                        <div class="card-body">
                                        <x-form-fields 
                                        :showComprobantefactura="false" :showSerieFactura="false"   
                                        :showNumeroFactura="false" :showFechaFactura="false"  
                                            />
                                            <x-TablaProductos
                                                :showEliminar="true"
                                                :showTotales="true"
                                                :showSubtotal="true"
                                                :showIGV="true"
                                                :showTotal="true"
                                            />
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
                @include('modalListaCuotas')
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
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/handleFormSubmit.js') }}"></script>
        <script src="{{ asset('js/imprimir.js') }}"></script>
        <script src="{{ asset('js/anular.js') }}"></script>
        <script src="{{ asset('js/calculos.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {                
                var fechaActual = new Date().toISOString().split('T')[0];
                document.getElementById('fechaproceso').value = fechaActual;
                document.getElementById('fechav').value = fechaActual;
                document.getElementById('fechacuota').value = fechaActual;
                listarOpciones("#cbocomprobante", "{{ route('listar-documentosfac') }}", 'documento', 'descripcion', true);
                listarOpciones("#cboconcepto", "{{ route('listar-concepto') }}", 'id', 'descripcion', true);
                listarOpciones("#cboproveedor", "{{ route('listar-datosclientes') }}", 'ruc', 'razonsocial', true);
                listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', true);
                listarOpciones("#cboalmacen", "{{ route('listar-datosalmacen') }}", 'id', 'descripcion', false);
                listarOpciones("#cbocondicion", "{{ route('listar-condicion') }}", 'id', 'descripcion', true);
                listarOpciones("#cbovendedor", "{{ route('listar-vendedor') }}", 'id', 'nombres', true);
               
            });

            function actualizarCbocomprobanteDesdeJson(valorRespuesta) {
                var cbocomprobante = document.getElementById('cbocomprobante');
                if (cbocomprobante) {
                    cbocomprobante.value = valorRespuesta;
                    cbocomprobante.dispatchEvent(new Event('change'));
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                var cbocomprobante = document.getElementById('cbocomprobante');
                var cboserie = document.getElementById('cboserie');

                if (cbocomprobante) {
                    cbocomprobante.addEventListener('change', function () {
                        var valorSeleccionado = this.value;
                        var url = "{{ route('obtener-series', ['id' => '__ID__']) }}".replace('__ID__', encodeURIComponent(valorSeleccionado));
                        listarOpciones("#cboserie", url, 'id', 'serie', true);
                        document.getElementById("numeronota").value = '';
                        document.getElementById("tipodocumento").value = valorSeleccionado;
                    });
                } else {
                    console.error("Elemento con ID 'cbocomprobante' no encontrado.");
                }

                if (cboserie) {
                    cboserie.addEventListener('change', function () {
                        
                        var selectedOption = this.options[this.selectedIndex];
                        var valorSeleccionado = this.value;
                        var customAttribute = selectedOption.getAttribute('data-custom-attribute');
                        var url = "{{ url('obtener-ultimonumero') }}/" + encodeURIComponent(customAttribute) + "/" + encodeURIComponent(valorSeleccionado);
                        obtenerultimoNumero("#numeronota", url);
                    });
                } else {
                    console.error("Elemento con ID 'cboserie' no encontrado.");
                }
            });

            document.getElementById('cboconcepto').addEventListener('change', function () {
                actualizarTotalesInafecto();
            });
            

            function formatErrorMessages(errors) {
                if (Array.isArray(errors)) {
                    return errors.join(', ');
                } else {
                    return 'Error desconocido';
                }
            }

            function modalNuevoRegistro() {
                clearFormInputsSelects('form-documentos', 'cbocomprobante');
                limpiarTablaDataTable('form-documentos', 'tablaProductos');
                $("#inigv").prop("checked", false);
                $("#conretencion").prop("checked", false);
                $("#iskardex").prop("checked", false);
                $("#iscortesia").prop("checked", false);
                var fechaActual = new Date().toISOString().split('T')[0];
                document.getElementById('fechaproceso').value = fechaActual;
                document.getElementById('fechav').value = fechaActual;
                document.getElementById('mensajeAnulado').style.display = 'none';
                document.getElementById('btnAgregar').disabled = false;
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('collapseThree').classList.add('show');
            });

            function getSelectText(id) {
                var selectElement = document.getElementById(id);
                return selectElement.options[selectElement.selectedIndex].text;
            }

            handleFormSubmit({
                formId: 'form-documentos',
                tableId: 'tablaProductos',
                serieSelectId: 'cboserie',
                almacenSelectId: 'cboalmacen',
                documentoId: 'iddeldocumento',
                submitUrl: '/guardar-comprobantes',
                
            });        

            document.getElementById('btnAnular').addEventListener('click', function(event) {
                anularRegistro('tablaIngresos', 'iddeldocumento', '/anularRegistroComprobante', 'No hay documentos para anular.');
            });

            document.addEventListener('DOMContentLoaded', function() {
                inicializarCalculoTotales();
            });

            function validarFormulario(formularioId, camposFormulario) {
                const configuracionValidacion = configurarValidacion(formularioId, camposFormulario);
                $(formularioId).validate(configuracionValidacion);
                return $(formularioId).valid();
            }

            document.getElementById('btnImprimir').addEventListener('click', function () {
                imprimirReporte({
                    tablaId: 'tablaIngresos', // ID de la tabla
                    inputDocumentoId: 'iddeldocumento', // ID del input del documento
                    ruta: '{{ route("imprimir.reporte", ":id") }}', // Ruta específica para esta vista
                    mensajeError: 'No hay ningún documento seleccionado para imprimir.' // Mensaje personalizado
                });
            });

        </script>
</body>

</html>