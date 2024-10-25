<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Transportista</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Empresa de transporte</h1>
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
                                                aria-controls="custom-tabs-one-home" aria-selected="true">Empresa de transporte</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-one-profile" role="tab"
                                                aria-controls="custom-tabs-one-profile" aria-selected="false">Flota</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-home-tab">
                                            <div class="card-body">
                                                <div id="tablaEmpresat"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-profile-tab">
                                            <label id="traz" class="col-sm-0 control-label"></label>

                                            <input id="codigolinea" type="hidden" name="codigolinea" maxlength="11"
                                                readonly="">
                                            <select class="form-control" id="empresat"
                                                onchange="cargartablaTransportista(this.value)" name="empresat">
                                            </select>
                                            <div class="card-body">
                                                <div id="tablaFlota"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR Empresa transporte  -->
                    <div class="modal fade" id="modalempresat" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioempresat">
                                        </h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-empresat" method="POST" action="{{ url('guardar-empresatransporte') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="codigoempresat" type="hidden" class="form-control"
                                                name="codigoempresat" readonly="">
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">RUC:</label>
                                                    <input id="rucempresat" type="text" required
                                                        class="form-control custom-button" name="rucempresat"
                                                        maxlength="11" value="">
                                                </div>
                                                <div class="form-group col-8 mb-2">
                                                    <label class="my-0">Razon social:</label>
                                                    <input id="razonsocial" type="text" required
                                                        class="form-control custom-button" name="razonsocial">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevosub" name="btnNuevosub"
                                                    onclick="modalNuevoRegistro('modalempresat')"
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

                    <!-- /.MODAL GUARDAR FLOTA  -->
                    <div class="modal fade" id="modaltransportista" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary"
                                            id="tituloFormulariotransportista"></h6>
                                    </div>
                                    <form id="form-transportista" method="POST" autocomplete="off"
                                        action="{{ url('guardar-transportista') }}">
                                        @csrf
                                        <div class="card-body">
                                        <div class="form-group mb-2">
                                                <label class="my-0">Empresa:</label>
                                                <select class="form-control form-control-sm"required name="cboempresa" id="cboempresa">
                                                </select>
                                            </div>
                                            <div class="row">
                                                <input id="codigotransportista" type="hidden" class="form-control"
                                                    name="codigotransportista" maxlength="5" value="" readonly="">
                                                <div class="form-group col-5 mb-2">
                                                    <label class="my-0">Nombres:</label>
                                                    <input id="nombre" type="text" class="form-control form-control-sm"
                                                        required="" name="nombre" maxlength="100">
                                                </div>
                                                <div class="form-group col-7 mb-2">
                                                <label class="my-0">Apellidos:</label>
                                                <input id="apellido" type="text" class="form-control form-control-sm"
                                                    required="" name="apellido" maxlength="100">
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="form-group col-5 mb-2">
                                                <label class="my-0">DNI:</label>
                                                <input id="dni" type="text" class="form-control form-control-sm"
                                                    name="dni" maxlength="100" required>
                                            </div>
                                            <div class="form-group col-5 mb-2">
                                                <label class="my-0">Licencia:</label>
                                                <input id="licencia" type="text" class="form-control form-control-sm"
                                                    name="licencia" maxlength="100" required>
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="form-group col-5 mb-2">
                                                <label class="my-0">Unidad:</label>
                                                <input id="unidad" type="text" class="form-control form-control-sm"
                                                    name="unidad" maxlength="100">
                                            </div>
                                            <div class="form-group col-5 mb-2">
                                                <label class="my-0">Placa:</label>
                                                <input id="placa" type="text" class="form-control form-control-sm"
                                                    name="placa" maxlength="100" required>
                                            </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-2 mb-2">
                                                    <div
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input" id="estado"
                                                            name="estado" checked>
                                                        <label class="custom-control-label" for="estado">Activo?</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
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
                                                        class="text">Cancelar</span></button>
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
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas
        function inicializarTabla(url, columnConfig, tablaId, modalid) {
            hacerConsulta(url, columnConfig, tablaId, modalid);
        }

        $(document).ready(function () {
            cargartablaEmpresat();
        });

        function mostrarModalEdicion(fila, modalid) {
            if (modalid === 'modalempresat') {
                if (fila.id) {
                    $.ajax({
                        url: '/obtener-datosinputsempresat/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            $('#codigoempresat').val(response.id);
                            $('#rucempresat').val(response.Ruc);
                            $('#razonsocial').val(response.Empresa);
                            $("#tituloFormularioempresat").html("Modificar datos");
                            $('#' + modalid).modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                }
            }else if (modalid === 'modaltransportista'){
                if (fila.id) {
                    listarOpciones("#cboempresa", "{{ route('obtener-Empresat') }}", 'ruc', 'empresa', true);
                    $.ajax({
                        url: '/obtener-datosinputstransportista/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            var datosTransportista = response[0];
                            $('#codigotransportista').val(datosTransportista.id);
                            $('#cboempresa').val(datosTransportista.empresa);
                            $('#nombre').val(datosTransportista.nombres);
                            $('#apellido').val(datosTransportista.apellidos);
                            $('#dni').val(datosTransportista.dni);
                            $('#licencia').val(datosTransportista.licencia);
                            $('#unidad').val(datosTransportista.unidad);
                            $('#placa').val(datosTransportista.placa);
                            $('#estado').prop('checked', datosTransportista.activo);
                            $("#tituloFormulariotransportista").html("Modificar datos");
                            $('#' + modalid).modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                }
            }
        }

        function modalNuevoRegistro() {
            console.log('serty');
            var pestañaActiva = $('.nav-tabs .nav-link.active').attr('id');
            if (pestañaActiva === 'custom-tabs-one-home-tab') {
                $("#tituloFormularioempresat").html("Nuevo registro");
                clearFormInputs('form-empresat', 'rucempresat');
                $('#modalempresat').modal('show');
            } else {
                $("#tituloFormulariotransportista").html("Nuevo registro");
                listarOpciones("#cboempresa", "{{ route('obtener-Empresat') }}", 'ruc', 'empresa', true);
                clearFormInputs('form-transportista', 'nombre');
                clearFormInputsSelects('form-transportista', 'cboempresa');
                $("#estado").prop("checked", true);
                $('#modaltransportista').modal('show');
            }
        }

        function cargartablaEmpresat() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "ruc", title: "<div style='text-align: center;'>RUC</div>", type: "text", align: "left" },
                { name: "empresa", title: "<div style='text-align: center;'>Razon social</div>", type: "text", className: "text-left" },
            ];
            inicializarTabla("{{ route('listar-datostransportista') }}", columnConfigLaravel, 'tablaEmpresat', 'modalempresat');
            clearFormInputsSelects('form-transportista', 'cboempresa');
            listarOpciones("#empresat", "{{ route('obtener-Empresat') }}", 'ruc', 'empresa', true);
        }

        function cargartablaTransportista(id) {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "nombres", title: "<div style='text-align: center;'>Nombre</div>", type: "text", align: "left" },
                { name: "apellidos", title: "<div style='text-align: center;'>Apellido</div>", type: "text", className: "text-left" },
                { name: "dni", title: "<div style='text-align: center;'>DNI</div>", type: "text", className: "text-left" },
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
            inicializarTabla("{{ url('listar-datosempresat') }}/" + id, columnConfigLaravel, 'tablaFlota', 'modaltransportista');
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
                            if (tablaId === 'tablaEmpresat') {
                                cargartablaEmpresat();
                            }else if (tablaId === 'tablaFlota') {
                                cargartablaTransportista(cbo);
                                select1 = document.getElementById("empresat").value=cbo;
                                select = document.getElementById("cboempresa").value=cbo;                             
                            }
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
                var cboValue = config.cboId ? document.getElementById(config.cboId).value : null;
                var id = document.getElementById(config.codigoInputId).value;
                var action = id ? config.modificarUrl + '/' + id : config.guardarUrl;
                var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
                var errorMessage = 'Error al procesar la solicitud';

                console.log(id + "id");
                submitForm(action, successMessage, errorMessage, id, config.formId, config.codigoInputId, config.tablaId, cboValue);
            });
        }

        // Configuración para el formulario de colores
        gestionarFormulario({
            formId: 'form-empresat',
            codigoInputId: 'codigoempresat',
            tablaId: 'tablaEmpresat',
            modificarUrl: "{{ url('modificar-empresatransporte') }}",
            guardarUrl: "{{ url('guardar-empresatransporte') }}"
        });

        gestionarFormulario({
            formId: 'form-transportista',
            codigoInputId: 'codigotransportista',
            tablaId: 'tablaFlota',
            cboId: 'cboempresa',
            modificarUrl: "{{ url('modificar-transportista') }}",
            guardarUrl: "{{ url('guardar-transportista') }}"
        });

        function formatErrorMessages(errors) {
            let formattedErrors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
        // Agrega la clase 'show' al submenu "Mantenimiento" para mantenerlo visible
        document.getElementById('collapsea').classList.add('show');
    });
    </script>
</body>

</html>