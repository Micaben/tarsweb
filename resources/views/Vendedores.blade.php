<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Vendedores</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Vendedores</h1>
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
                                                aria-controls="custom-tabs-one-home" aria-selected="true">Vendedor</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-one-profile" role="tab"
                                                aria-controls="custom-tabs-one-profile"
                                                aria-selected="false">Comisiones</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-home-tab">
                                            <div class="card-body">
                                                <div id="tablaVendedor"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-one-profile-tab">
                                            <label id="traz" class="col-sm-0 control-label"></label>

                                            <input id="codigolinea" type="hidden" name="codigolinea" maxlength="11"
                                                readonly="">
                                            <div class="card-body">
                                                <div id="tablaComision"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR VENDEDOR  -->
                    <div class="modal fade" id="modalvendedor" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulariovendedor">
                                        </h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-vendedor" method="POST" action="{{ url('guardar-vendedor') }}">
                                        @csrf
                                        <div class="card-body">
                                            <!-- /<div class="form-group mb-2">                                    
                                       <label>Código:</label>  -->
                                            <input id="codigo" type="hidden" class="form-control custom-button"
                                                name="codigo" maxlength="4" value="" readonly="">
                                            <div class="form-group mb-2">
                                                <label class="my-0">Nombres:</label>
                                                <input id="nombres" type="text" class="form-control custom-button"
                                                    required="" name="nombres" maxlength="100" value="">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="my-0">Direccion:</label>
                                                <input id="direccion" type="text" class="form-control custom-button"
                                                    name="direccion" maxlength="100" value="">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Dni:</label>
                                                    <input id="dni" type="text" class="form-control custom-button"
                                                        name="dni" maxlength="100" value="">
                                                </div>
                                                <div class="form-group col-7 mb-2">
                                                    <label class="my-0">Telefono:</label>
                                                    <input id="telefono" type="text" class="form-control custom-button"
                                                        name="telefono" maxlength="100" value="">
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="my-0">Correo:</label>
                                                <input id="email" type="text" class="form-control custom-button"
                                                    name="email" maxlength="100" value="">
                                            </div>
                                            <div class="form-group col-2 mb-2">
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado"
                                                        checked>
                                                    <label class="custom-control-label" for="estado">Activo?</label>
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

                    <!-- /.MODAL GUARDAR COMISION  -->
                    <div class="modal fade" id="modalcomision" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="titulosubFormulario"></h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-comision" method="POST" action="{{ url('guardar-comision') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="codigocomision" type="hidden" class="form-control"
                                                name="codigocomision" readonly="">                                           
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Desripcion:</label>
                                                    <input id="descripcioncomision" type="text"
                                                        class="form-control custom-button" name="descripcioncomision" value="">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Porcentaje:</label>
                                                    <input id="porcentajecomision" type="text"
                                                        class="form-control custom-button" name="porcentajecomision"
                                                        maxlength="10" value="">
                                                </div>                                                
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevocomision" name="btnNuevocomision"
                                                    onclick="modalNuevoRegistro()"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarcomision" name="btnRegistrarcomision"
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas         
        $(document).ready(function () {
            cargarTabla();
            cargarTablacomision();
        });

        function cargarTabla() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "nombres", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left" },
                { name: "telefono", title: "<div style='text-align: center;'>Telefono</div>", type: "text", className: "text-left" },
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
            hacerConsulta("{{ route('listar-vendedor') }}", columnConfigLaravel, 'tablaVendedor');
        }

        function cargarTablacomision() {           
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left" },
                { name: "porcent", title: "<div style='text-align: center;'>Porcentaje</div>", type: "text", className: "text-left" },               
            ];
            hacerConsulta("{{ url('listar-datoscomision') }}/" , columnConfigLaravel, 'tablaComision');
        }

        function mostrarModalEdicion(row) {
            var id = row.id;
            var pestañaActiva = $('.nav-tabs .nav-link.active').attr('id');
            if (pestañaActiva === 'custom-tabs-one-home-tab') {
                if (id) {
                    $.ajax({
                        url: '/obtener-datosinputsvendedor/' + id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response)
                            $('#codigo').val(response.id);
                            $('#nombres').val(response.Nombres);
                            $('#direccion').val(response.Direccion);
                            $('#dni').val(response.DNI);
                            $('#telefono').val(response.Telefono);
                            $('#email').val(response.Email);
                            $('#estado').prop('checked', response.Activo);
                            $("#tituloFormulariovendedor").html("Modificar datos");
                            $('#modalvendedor').modal('show');
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
                    $.ajax({
                        url: '/obtener-datosinputscomision/' + id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response)
                            $('#descripcioncomision').val(response.Descripcion);
                            $('#porcentajecomision').val(response.Porcent);
                            $('#codigocomision').val(response.id);
                            $("#titulosubFormulario").html("Modificar registros");
                            $('#modalcomision').modal('show');
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
                $("#tituloFormulariovendedor").html("Nuevo registro");
                clearFormInputs('form-vendedor', 'nombres');
                 $("#estado").prop("checked", true);
                $('#modalvendedor').modal('show');
            } else {
                $("#titulosubFormulario").html("Nuevo registro");
                clearFormInputs('form-comision', 'codigocomision', 'descripcioncomision', 'porcentajecomision');
                $('#modalcomision').modal('show');
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
                            if (tablaId === 'tablaVendedor') {
                                cargarTabla();
                            } else if (tablaId === 'tablaComision') {
                                cargarTablacomision();
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

        document.getElementById('form-vendedor').addEventListener('submit', function (e) {
            e.preventDefault();
            var formId = 'form-vendedor';
            var codigoInputId = 'codigo';
            var tablaId = 'tablaVendedor';
            var id = document.getElementById('codigo').value;
            var action = id ? "{{ url('modificar-vendedor') }}/" + id : "{{ url('guardar-vendedor') }}";
            var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
            var errorMessage = 'Error al procesar la solicitud';

            console.log(id + "id");
            submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId, '');
        });

        document.getElementById('form-comision').addEventListener('submit', function (e) {
            e.preventDefault();
            var formId = 'form-comision';
            var codigoInputId = 'codigocomision';
            var tablaId = 'tablaComision';
            var id = document.getElementById('codigocomision').value;
            var action = id ? "{{ url('modificar-comision') }}/" + id : "{{ url('guardar-comision') }}";
            var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
            var errorMessage = 'Error al procesar la solicitud';

            console.log(id + "id");
            submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId);
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
        document.getElementById('collapsev').classList.add('show');
    });
    </script>
</body>

</html>