<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Tipo de cambio</title>

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
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-2 text-gray-800">Tipo de cambio</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()"><span
                                class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                class="text">Nuevo</span></button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tipo de cambio registrado</h6>
                        </div>
                        <div class="card-body">
                            
                                <div class="row">
                                    <div class="form-group col-2">
                                        <label class="my-0">Mes:</label>
                                        <select id="mes" class="form-control form-control-sm  custom-button" name="mes"
                                            onchange="buscar()">
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-2">
                                        <label class="my-0">Año:</label>
                                        <select id="anio" class="form-control form-control-sm  custom-button"
                                            name="anio" onchange="buscar()"></select>
                                    </div>
                                </div>                               
                                    <div id="tablaTipocambio"></div>
                                    </div>                            
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR  -->
                    <div class="modal fade" id="modaltipocambio" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                            <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulario"></h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-tipocambio" method="POST" action="{{ url('guardar-tipocambio') }}">
                                        @csrf
                                        <div class="card-body">
                                        <input id="codigo" type="hidden" class="form-control" name="codigo" readonly="">                                            
                                        <div class="row">
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Fecha:</label>
                                                <input id="fecha" type="date" class="form-control"
                                                    name="fecha" required>
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Compra:</label>
                                                <input id="compratc" type="text" class="form-control"
                                                    name="compratc" required>
                                            </div>
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Venta:</label>
                                                <input id="ventatc" type="text" class="form-control"
                                                    name="ventatc" required>
                                            </div>
                                            <div class="form-group col-sm-3 mb-2">
                                                <label class="my-0">Comercial:</label>
                                                <input id="comercialtc" type="text" class="form-control"
                                                    name="comercialtc" required>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevosub" name="btnNuevosub"
                                                    onclick="modalNuevoRegistro()"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarsub" name="btnRegistrarsub"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Cerrar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
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
    <script src="{{ asset('js/mensajes.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas
        function inicializarTabla(url, columnConfig, tablaId, modalid) {
            hacerConsulta(url, columnConfig, tablaId, modalid);
        }

        $(document).ready(function () {
            obtenerMesAnio('mes', 'anio');
            var mesSeleccionado = $('#mes').val();
            var anioSeleccionado = $('#anio').val();
            cargartabla(mesSeleccionado,anioSeleccionado);
        });

        function mostrarModalEdicion(fila, modalid) {
            if (fila.id) {
                $.ajax({
                    url: '/obtener-datosinputstipocambio/' + fila.id,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        $('#codigo').val(response.id);
                        $('#fecha').val(response.Fecha);
                        $('#compratc').val(response.Compra);
                        $('#ventatc').val(response.Venta);
                        $('#comercialtc').val(response.Comercial);                        
                        $("#tituloFormulario").html("Modificar datos");
                        $('#' + modalid).modal('show');
                    },
                    error: function (error) {
                        console.log('Error al obtener datos:', error);
                    }
                });
            }
        }

        function modalNuevoRegistro() {
            $("#tituloFormulario").html("Nuevo registro");
            clearFormInputsSelects('form-tipocambio', 'compratc');
            obtenerFechaTraslado("fecha");
            $('#modaltipocambio').modal('show');
        }

        function cargartabla(mes, anio) {   
            var columnConfigLaravel = [
                { name: "id", title: "Código", type: "text", align: "left", visible: false },
                { name: "fecha_formateada", title: "Fecha", type: "text", align: "center", itemTemplate: function (value) {
                    var dateParts = value.split('-');
                        if (dateParts.length === 3) {
                            var newDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
                            return newDate;
                        } else {
                            return value;
                        }
                     }
                },
                { name: "compra", title: "<div style='text-align: center;'>Compra</div>", type: "text", align: "right" },
                { name: "venta", title: "<div style='text-align: center;'>Venta</div>", type: "text", align: "right" },
                { name: "comercial", title: "<div style='text-align: center;'>Comercial</div>", type: "text", align: "right" },
            ];
            var url = "{{ route('listar-datostipocambio') }}?mes=" + mes + "&anio=" + anio;
            inicializarTabla(url, columnConfigLaravel, 'tablaTipocambio', 'modaltipocambio');
        }

        function buscar() {
            var mesSeleccionado = $('#mes').val();
            var anioSeleccionado = $('#anio').val();
            cargartabla(mesSeleccionado, anioSeleccionado);
        }
          
        function obtenerDatos1() {
            obtenerMesAnio('mes', 'anio');
            cargartabla();
        }

        function submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId) {
            var form = document.getElementById(formId);
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', action, true);

            // Include CSRF token in headers
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.content);
            } else {
                console.error('CSRF token meta tag not found');
            }

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showSuccessMessage(successMessage);
                            var generatedId = response.id;
                            document.getElementById(codigoInputId).value = generatedId;
                            obtenerMesAnio('mes', 'anio');
                            var mesSeleccionado = $('#mes').val();
                            var anioSeleccionado = $('#anio').val();
                            cargartabla(mesSeleccionado,anioSeleccionado);
                        } else {
                            let errorMessages = formatErrorMessages(response.errors);
                            showErrorMessage('Error al procesar la solicitud. Detalles: ' + errorMessages);
                        }
                    } else if (xhr.status === 400) {
                        // Estado 400 - Error de validación
                        var response = JSON.parse(xhr.responseText);
                        let errorMessages = formatErrorMessages(response.errors);
                        showErrorMessage('Error de validación. ' + response.error + ' Detalles: ' + response.details + ' Errores: ' + errorMessages);
                    } else {
                        showErrorMessage('Error al procesar la solicitud. Código de estado: ' + xhr.status);
                    }
                }
            };
            xhr.send(formData);
        }

        // Función común para manejar el envío de formularios
        function gestionarFormulario(config) {
            document.getElementById(config.formId).addEventListener('submit', function (e) {
                e.preventDefault();
                var id = document.getElementById(config.codigoInputId).value;
                var action = id ? config.modificarUrl + '/' + id : config.guardarUrl;
                var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
                var errorMessage = 'Error al procesar la solicitud';

                console.log(id + "id");
                submitForm(action, successMessage, errorMessage, id, config.formId, config.codigoInputId, config.tablaId);
            });
        }

        // Configuración para el formulario de colores
        gestionarFormulario({
            formId: 'form-tipocambio',
            codigoInputId: 'codigo',
            tablaId: 'tablaTipocambio',
            modificarUrl: "{{ url('modificar-tipocambio') }}",
            guardarUrl: "{{ url('guardar-tipocambio') }}"
        });

        function formatErrorMessages(errors) {
            let formattedErrors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Agrega la clase 'show' al submenu "Mantenimiento" para mantenerlo visible
            document.getElementById('collapsetc').classList.add('show');
        });
    </script>
</body>

</html>