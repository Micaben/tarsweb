<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Registro de Guias</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Guia de remision registradas</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Desde:</label>
                                    <input id="fechadesde" type="date" class="form-control form-control-sm" required=""
                                        name="fechadesde" maxlength="10">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Hasta:</label>
                                    <input id="fechahasta" type="date" class="form-control form-control-sm" required=""
                                        name="fechahasta" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="tablaClientes"></div>
                        </div>
                    </div>
                </div>

                <!-- /.container-fluid -->
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
    <script src="{{ asset('js/tablesview.js') }}"></script>
    <script src="{{ asset('js/selects.js') }}"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas        
        $(document).ready(function () {
            var fechaActual = new Date().toISOString().split('T')[0];
            var fechadesde = document.getElementById('fechadesde').value = fechaActual;
            var fechahasta = document.getElementById('fechahasta').value = fechaActual;
            cargartabla(fechadesde, fechahasta);
            $('#fechadesde, #fechahasta').on('change', function () {
                var nuevaFechaDesde = $('#fechadesde').val();
                var nuevaFechaHasta = $('#fechahasta').val();
                cargartabla(nuevaFechaDesde, nuevaFechaHasta); // Vuelve a cargar la tabla cuando cambian las fechas
            });
        });

        function cargartabla(fechadesde, fechahasta) {
            var columnConfigLaravel = [
                { name: "grm_Fecha", title: "Emision", type: "text" },
                { name: "empresat", title: "Remitente", type: "text" },
                { name: "destinatario", title: "Destinatario", type: "text" },
                { name: "documento", title: "Numero", type: "text" },    
                { name: "descargar", title: "Descargar", type: "button", width:160 },                
                {
                    name: "grm_EstadoRespuesta",
                    title: "<div style='text-align: center;'>Estado</div>",
                    width: 30,
                    render: function (data, type, row) {
                        //console.log(row);
                        var badgeClass, estadoTexto, infoTooltip;
                        var estadoRespuesta = row[5];
                        var mensajeRespuesta = row[6]; 
                        if (data === 'P') {
                            infoTooltip = 'El documento está pendiente de envio.';
                            badgeClass = 'badge-secondary'; // Clase gris para Pendiente
                            estadoTexto = 'Pendiente';
                        } else if (data === '0') {
                            infoTooltip = 'El documento está aceptado.';
                            badgeClass = 'badge-success'; // Clase verde para Aceptado
                            estadoTexto = 'Aceptado';
                        } else {
                            badgeClass = 'badge-danger'; // Clase roja para Error
                            estadoTexto = 'Error';                            
                            infoTooltip = `Estado: ${estadoRespuesta} ${mensajeRespuesta}`;                           
                        }
                        return `
                            <div class="text-center">
                            <span class="badge ${badgeClass}">${estadoTexto}</span>
                                <i class="fas fa-info-circle" style="margin-right: 1px; cursor: pointer;" 
                                title="${infoTooltip}">
                                </i>
                            </div>`;
                    }
                },
                { name: "grm_MensajeRespuesta", title: "<div style='text-align: center;'>Numero</div>", type: "text", visible:false },
            ];
            inicializarTabla("{{ route('listar-registroguiaremision') }}", columnConfigLaravel, 'tablaClientes', 'modalclientes', fechadesde, fechahasta);
        }

        function inicializarTabla(url, columnConfig, tablaId, modalid, fechadesde, fechahasta) {
            hacerConsulta(url, columnConfig, tablaId, modalid, fechadesde, fechahasta);
        }

        function formatErrorMessages(errors) {
            let formattedErrors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }

    </script>
</body>

</html>