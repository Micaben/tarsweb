<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Documentos</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Documentos y series</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()"><span
                                class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                class="text">Nuevo</span></button>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-info card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                                href="#custom-tabs-one-home" role="tab"
                                                aria-controls="custom-tabs-one-home" aria-selected="true">Documentos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-one-profile" role="tab"
                                                aria-controls="custom-tabs-one-profile" aria-selected="false">Series</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-home-tab">
                                            <div class="card-body">
                                                <div id="tablaDocumentos"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-profile-tab">
                                            <label id="traz" class="col-sm-0 control-label"></label>

                                            <input id="codigolinea" type="hidden" name="codigolinea" maxlength="11"
                                                readonly="">
                                            <select class="form-control" id="cbodocumentos"
                                                onchange="cargarTablaseries(this.value)" name="cbodocumentos">
                                            </select>
                                            <div class="card-body">
                                                <div id="tablaSeries"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR DOCUMENTOS  -->
                    <div class="modal fade" id="modaldocumentos" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulariodocumentos">
                                        </h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-documentos" method="POST" action="{{ url('guardar-documentos') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="codigo" type="hidden" class="form-control" name="codigo"
                                                maxlength="4" value="" readonly="">
                                            <!-- </div>-->
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Documento:</label>
                                                    <input id="tipodocumento" type="text"
                                                        class="form-control form-control-sm" name="tipodocumento" required
                                                        maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Descripcion:</label>
                                                    <input id="documento" type="text" required
                                                        class="form-control form-control-sm" name="documento"
                                                        maxlength="50" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Abrev:</label>
                                                    <input id="abrev" type="text" class="form-control form-control-sm"
                                                        name="abrev" maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0"> Factor:</label>
                                                    <input id="factor" type="text" class="form-control form-control-sm"
                                                        name="factor" maxlength="15" value="">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Cuenta:</label>
                                                    <input id="cuenta" type="text" class="form-control form-control-sm"
                                                        name="cuenta" maxlength="10" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="facturable"
                                                        checked>
                                                    <label class="custom-control-label"
                                                        for="facturable">Facturable?</label>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevo" name="btnNuevo"
                                                    onclick="modalNuevoRegistro()"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrar" name="btnRegistrar"
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

                    <!-- /.MODAL GUARDAR SERIES  -->
                    <div class="modal fade" id="modalseries" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="titulosubFormularioseries">
                                        </h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-series" method="POST" action="{{ url('guardar-series') }}">
                                        @csrf
                                        <div class="card-body">
                                       
                                            <input id="iddeserie" type="text" name="iddeserie" readonly="">
                                            
                                            <div class="form-group mb-2">
                                                <label class="my-0">Documento:</label>
                                                <select id="tdocumento" class="form-control form-control-sm"
                                                    name="tdocumento" required></select>
                                            </div>
                                            <input id="tddedocumento" type="hidden" name="tddedocumento" readonly="">

                                            <div class="row">
                                                <div class="form-group col-sm-3 mb-2">
                                                    <label class="my-0">Serie:</label>
                                                    <input id="serie" class="form-control form-control-sm" name="serie"
                                                        maxlength="4" required>
                                                </div>
                                                <div class="form-group col-sm-6 mb-2">
                                                    <label class="my-0">Ultimo:</label>
                                                    <input id="ultimo" type="text" class="form-control form-control-sm"
                                                        name="ultimo" maxlength="8" required
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="my-0">Diario:</label>
                                                    <input id="diario" type="text" class="form-control form-control-sm"
                                                        name="diario" maxlength="5" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="estado"
                                                        name="estado" checked>
                                                    <label class="custom-control-label" for="estado">Activo?</label>
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
            cargarTabla();

        });

        function cargarTabla() {
            var columnConfigLaravel = [
                { name: "id", title: "id", type: "text", width: 30, visible: false },
                { name: "documento", title: "TD", type: "text", align: "left", width: 20 },
                { name: "descripcion", title: "<div style='text-align: center;'>Documento</div>", type: "text" },
                { name: "abrev", title: "Abr", type: "text", align: "left", width: 20 },
                { name: "factor", title: "<div style='text-align: center;'>Factor</div>", type: "text", width: 2 },
                { name: "cuentas", title: "Cuenta", type: "text", align: "left", width: 30 },
                {
                    name: "factbol",
                    title: "<div style='text-align: center;'>Estado</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var badgeClass = data == 1 ? 'badge-success' : 'badge-danger';
                        var estadoTexto = data == 1 ? 'Si' : 'No';
                        return '<div class="text-center"><span class="badge ' + badgeClass + '">' + estadoTexto + '</span></div>';
                    }
                },
            ];
            hacerConsulta("{{ route('listar-documentos') }}", columnConfigLaravel, 'tablaDocumentos');
            listarOpciones("#cbodocumentos", "{{ route('obtener-documentos') }}", 'documento', 'Descripcion', true);
        }

        function cargarTablaseries(id) {
            console.log("Opción seleccionada: " + id);
            var columnConfigLaravel = [
                { name: "id", title: "id", type: "text", width: 30, visible: false },
                { name: "serie", title: "<div style='text-align: center;'>Serie</div>", type: "text" },
                { name: "ultimo", title: "<div style='text-align: center;'>Ultimo</div>", type: "text", width: 30 },
                {
                    name: "activo",
                    title: "<div style='text-align: center;'>Estado</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var badgeClass = data == 1 ? 'badge-success' : 'badge-danger';
                        var estadoTexto = data == 1 ? 'Activo' : 'Inactivo';
                        return '<div class="text-center"><span class="badge ' + badgeClass + '">' + estadoTexto + '</span></div>';
                    }
                },
            ];
            hacerConsulta("{{ url('obtener-series') }}/" + id, columnConfigLaravel, 'tablaSeries');
        }

        function mostrarModalEdicion(row) {
            var id = row.id;
            var pestañaActiva = $('.nav-tabs .nav-link.active').attr('id');
            if (pestañaActiva === 'custom-tabs-one-home-tab') {
                if (id) {
                    $.ajax({
                        url: '/obtener-datosinputsdocumentos/' + id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response)
                            $('#codigo').val(response.id);
                            $('#tipodocumento').val(response.documento);
                            $('#documento').val(response.Descripcion);
                            $('#abrev').val(response.abrev);
                            $('#factor').val(response.factor);
                            $('#cuenta').val(response.cuentas);
                            $('#facturable').prop('checked', response.FactBol);
                            $("#tituloFormulariodocumentos").html("Modificar datos");
                            $('#modaldocumentos').modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                } else {
                    console.log('La fila no contiene datos.');
                }
            } else {
                if (id) {
                    listarOpciones("#tdocumento", "{{ route('obtener-documentos') }}", 'documento', 'Descripcion', true);
                    $.ajax({
                        url: '/obtener-datosinputsseries/' + id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response)

                            $('#iddeserie').val(response.id);
                            $('#tddedocumento').val(response.TD);
                            $('#tdocumento').val(response.TD);
                            $('#serie').val(response.Serie);
                            $('#ultimo').val(response.Ultimo);
                            $('#diario').val(response.Diario);
                            $('#estado').prop('checked', response.Activo);
                            $("#titulosubFormularioseries").html("Modificar datos");
                            $('#modalseries').modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                }
            }
        }

        function modalNuevoRegistro() {
            var pestañaActiva = $('.nav-tabs .nav-link.active').attr('id');
            if (pestañaActiva === 'custom-tabs-one-home-tab') {
                $("#tituloFormulariodocumentos").html("Nuevo registro");
                clearFormInputs('form-documentos', 'tipodocumento');
                $('#modaldocumentos').modal('show');
            } else {
                $("#titulosubFormularioseries").html("Nuevo registro");
                listarOpciones("#tdocumento", "{{ route('obtener-documentos') }}", 'documento', 'Descripcion', true);
                clearFormInputs('form-series', 'tdocumento');
                $('#modalseries').modal('show');
            }
        }

        function submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId, cbo) {
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
                            if (tablaId === 'tablaDocumentos') {
                                cargarTabla();
                            } else if (tablaId === 'tablaSeries') {
                                cargarTablaseries(cbo);
                                var select = document.getElementById("tdocumento");
                                select.value = cbo;
                            }
                        } else {
                            let errorMessages = formatErrorMessages(response.errors);
                            showErrorMessage('Error al procesar la solicitud. Detalles: ' + errorMessages);
                        }
                    } else {
                        showErrorMessage('Error al procesar la solicitud. Código de estado: ' + xhr.status);
                    }
                }
            };
            xhr.send(formData);
        }

        document.getElementById('form-documentos').addEventListener('submit', function (e) {
            e.preventDefault();
            var formId = 'form-documentos';
            var codigoInputId = 'codigo';
            var tablaId = 'tablaDocumentos';
            var id = document.getElementById('codigo').value;
            var action = id ? "{{ url('modificar-documentos') }}/" + id : "{{ url('guardar-documentos') }}";
            var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
            var errorMessage = 'Error al procesar la solicitud';
            console.log(id + "id");
            submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId, '');
        });

        document.getElementById('form-series').addEventListener('submit', function (e) {
            e.preventDefault();
            var formId = 'form-series';
            var codigoInputId = 'iddeserie';
            var tablaId = 'tablaSeries';
            var cbo = document.getElementById('tdocumento').value;
            var id = document.getElementById('iddeserie').value;
            var action = id ? "{{ url('modificar-series') }}/" + id : "{{ url('guardar-series') }}";
            var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
            var errorMessage = 'Error al procesar la solicitud';

            console.log(id + "id");
            submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId, cbo);
        });

        function formatErrorMessages(errors) {
            lermaors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }

        document.addEventListener('DOMContentLoaded', function() {
        // Agrega la clase 'show' al submenu "Mantenimiento" para mantenerlo visible
        document.getElementById('collapsed').classList.add('show');
    });
    </script>
</body>

</html>