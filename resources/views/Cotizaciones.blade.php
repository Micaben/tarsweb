<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Cotizaciones</title>

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
    <link rel="stylesheet" href="{{ asset('css/inputs.css') }}">
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
                        <h1 class="h3 mb-2 text-gray-800">Cotizaciones</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="row">
                            <div class="col-12">

                                <form id="form-cotizacion">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <x-botones :columns="[
        ['name' => 'Documento', 'align' => 'left'],
        ['name' => 'Fecha', 'align' => 'left'],
        ['name' => 'RUC', 'align' => 'left'],
        ['name' => 'Cliente', 'align' => 'left'],
        ['name' => 'Estado', 'align' => 'center'],
        ['name' => 'MDA', 'align' => 'center'],
        ['name' => 'Total', 'align' => 'right'],
        ['name' => 'id', 'align' => 'center']
    ]"
                                            :url="route('listar-mostrarRegistrosCotizacion')"
                                            :routeName="'registroCotizacion'"
                                            :routeDetalleName="'registroCotizaciondetalle'" :inputs="['cboserie', 'numeronota', 'fechaproceso', 'cboconcepto', 'cboproveedor', 'proveedor', 'cbomoneda', 'vigencia', 'ordencompra', 'cboalmacen', 'comentarios', 'iddeldocumento', 'subtotal', 'inigv', 'igv', 'lblmoneda', 'totalSuma']"
                                            :inputMapping="[
        'cboserie' => 'seriecot',
        'numeronota' => 'numcot',
        'fechaproceso' => 'FechaCot',
        'cboconcepto' => 'Concepto',
        'cboproveedor' => 'ruc',
        'proveedor' => 'ruc',
        'cbomoneda' => 'Moneda',
        'vigencia' => 'Validez',
        'ordencompra' => 'OCompra',
        'cboalmacen' => 'almacen',
        'comentarios' => 'Referencia',
        'iddeldocumento' => 'id',
        'subtotal' => 'baseimp',
        'inigv' => 'in_igv',
        'igv' => 'igv',
        'lblmoneda' => 'Factor2',
        'totalSuma' => 'Total'
    ]" :messageId="'mensajeAnulado'" :showCheckbox="false" :showBuscar="true"
                                            :showImprimir="true" :showTransporte="false" :showAnular="true"
                                            :showAnuladoMessage="true" />

                                    </div>
                                    <div class="card-body">
                                        <x-form-fields :showIdDocumento="true" :showTipoDocumento="true"
                                            :showSerie="true" :showNumero="true" tipoDocumentoValue="C1" :showFechaProceso="true"
                                            :showConcepto="true" :showMoneda="true" :showInIGV="true"
                                            :showProveedor="true" :showRuc="true" :showCondicion="true" :showValidez="true"
                                            :showVendedor="true" :showOrdenCompra="true" :showAlmacen="true"
                                            :showComentarios="true" :showAgregarProductos="true" />
                                        <x-tablaProductos :showEliminar="true" :showTotales="true" :showSubtotal="true"
                                            :showIGV="true" :showTotal="true" />
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
    <script src="{{ asset('js/tablesviewProductos.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="{{ asset('js/formulario-utils.js') }}"></script>
    <script src="{{ asset('js/handleFormSubmit.js') }}"></script>
    <script src="{{ asset('js/imprimir.js') }}"></script>
    <script src="{{ asset('js/anular.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            listarOpciones("#cboconcepto", "{{ route('listar-concepto') }}", 'id', 'descripcion', false);
            listarOpciones("#cboserie", "{{ route('obtener-series', ['id' => 'C1']) }}", 'id', 'serie', true);
            listarOpciones("#cboproveedor", "{{ route('listar-datosclientes') }}", 'ruc', 'razonsocial', true);
            listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
            listarOpciones("#cboalmacen", "{{ route('listar-datosalmacen') }}", 'id', 'descripcion', false);
            listarOpciones("#cbocondicion", "{{ route('listar-condicion') }}", 'id', 'descripcion', false);
            listarOpciones("#cbovendedor", "{{ route('listar-vendedor') }}", 'id', 'nombres', false);
        });
        
        document.getElementById('btnRegistrar').addEventListener('click', function (event) {
            // Llamar a la función registrar manualmente
            registrar(event);
        });

        function formatErrorMessages(errors) {
            if (Array.isArray(errors)) {
                return errors.join(', ');
            } else {
                return 'Error desconocido';
            }
        }

        function modalNuevoRegistro() {
            clearFormInputsSelects('form-cotizacion', 'cboserie');
            limpiarTablaDataTable('form-cotizacion', 'tablaProductos');
            $("#inigv").prop("checked", true);
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('mensajeAnulado').style.display = 'none';
            document.getElementById("tipodocumento").value = 'C1';
            document.getElementById("vigencia").value = '30';
            document.getElementById('btnAgregar').disabled = false;

            document.getElementById('lblmoneda').innerText = 'S/';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('collapseThree').classList.add('show');
        });

        handleFormSubmit({
    formId: 'form-cotizacion',
    tableId: 'tablaProductos',
    serieSelectId: 'cboserie',
    almacenSelectId: 'cboalmacen',
    documentoId: 'iddeldocumento',
    submitUrl: '/guardar-cotizacion',
   
});

document.getElementById('btnAnular').addEventListener('click', function(event) {
    anularRegistro('tablaIngresos', 'iddeldocumento', '/anularRegistroCotizacion', 'No hay documentos para anular.');
});

        document.addEventListener('DOMContentLoaded', function () {
            const igvCheckbox = $('#inigv'); // Usando jQuery para asegurar que funcione correctamente
            igvCheckbox.change(function () {
                var checkIgv = $(this).prop('checked'); // Usar 'this' para obtener el estado del checkbox
                console.log('Estado del checkbox:', checkIgv);
                actualizarTotal('tablaIngresos', checkIgv);  // Recalcular cuando se cambia el checkbox del IGV
            });
        });

        document.getElementById('btnImprimir').addEventListener('click', function () {
    imprimirReporte({
        tablaId: 'tablaIngresos', // ID de la tabla
        inputDocumentoId: 'iddeldocumento', // ID del input del documento
        ruta: '{{ route("imprimir.reporte", ":id") }}', // Ruta específica para esta vista
        mensajeError: 'No hay ningún documento seleccionado para imprimir.' // Mensaje personalizado
    });
});

        document.addEventListener('DOMContentLoaded', function () {
            enableEnterNavigationForForms(); // Activar la funcionalidad al cargar la página
        });
    </script>
</body>

</html>