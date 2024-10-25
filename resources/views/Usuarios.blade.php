<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Usuarios</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Usuarios</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()"><span
                                class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                class="text">Nuevo</span></button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Usuarios registrados</h6>
                        </div>
                        <div class="card-body">
                            <div id="tablaUsuarios"></div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR  -->
                    <div class="modal fade" id="modalusuarios" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulario"></h6>
                                    </div>
                                    <form id="form-usuarios" method="POST" autocomplete="off"
                                        action="{{ url('guardar-usuarios') }}">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <!--  <div class="form-group col-sm-4 mb-2">
                                         <label>Codigo:</label>  -->
                                                <input id="codigo" type="hidden" class="form-control" name="codigo"
                                                    maxlength="4" readonly="">
                                                <div class="form-group col-sm-4 mb-2">
                                                    <label class="my-0">Usuario:</label>
                                                    <input id="usuario" type="text" class="form-control" name="usuario"
                                                        maxlength="100" required>
                                                </div>                                                                                           
                                            <div class="form-group col-sm-8 mb-2">
                                                <label class="my-0">Nombres:</label>
                                                <input id="nombres" type="text" class="form-control" name="nombres"
                                                    maxlength="50" required>
                                            </div>
                                            </div>
                                            <div  class="row">
                                                <div class="form-group col-sm-6 mb-2">
                                                    <label class="my-0">Correo:</label>
                                                    <input id="correo" type="text" class="form-control" name="correo"
                                                        maxlength="50" value="" required="">
                                                </div>
                                                <div class="form-group col-sm-6 mb-2">
                                                    <label class="my-0">Teléfono:</label>
                                                    <input id="telefono" type="text" class="form-control"
                                                        name="telefono" maxlength="50" value="">
                                                </div>
                                            </div>
                                            <div  class="row">
                                            <div class="form-group  col-sm-6 mb-2">
                                                <label class="my-0">Cargo:</label>
                                                <select class="form-control" name="cargo" id="cargo">
                                                </select>
                                            </div>
                                            <div class="form-group  col-sm-6 mb-2">
                                                <label class="my-0">Empresa:</label>
                                                <select class="form-control" name="empresa" id="empresa">
                                                </select>
                                            </div>
                                            </div>
                                            <div class="form-group ">
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="estado"
                                                        name="estado">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas
        function inicializarTabla(url, columnConfig, tablaId, modalid) {
            hacerConsulta(url, columnConfig, tablaId, modalid);
        }

        $(document).ready(function () {
            cargartabla();
            listarOpciones("#cargo", "{{ route('obtener-cargo') }}", 'id', 'descripcion', true);
        });

        function mostrarModalEdicion(fila, modalid) {
            if (fila.id) {
                $.ajax({
                    url: '/obtener-datosinputsusuarios/' + fila.id,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        $('#codigo').val(response.id);
                        $('#usuario').val(response.usuario);
                        $('#clave').val(response.password);
                        $('#confirmclave').val(response.password);
                        $('#nombres').val(response.name);
                        $('#telefono').val(response.telefono);
                        $('#correo').val(response.email);
                        $('#cargo').val(response.cargo);
                        $('#empresa').val(response.empresa);
                        $('#estado').prop('checked', response.estado);
                        $("#tituloFormulario").html("Modificar datos");
                        $('#' + modalid).modal('show');
                    },
                    error: function (error) {
                        console.log('Error al obtener datos:', error);
                    }
                });
            }
        }

        function modalNuevoRegistro(modalid) {
            $("#tituloFormulario").html("Nuevo registro");
            clearFormInputsSelects('form-usuarios', 'usuario');
            $("#estado").prop("checked", true);
            $('#modalusuarios').modal('show');
        }

        function cargartabla() {
            var columnConfigLaravel = [
                { name: "id", title: "Código", type: "text", align: "left", visible: false },
                { name: "name", title: "<div style='text-align: center;'>Nombres</div>", type: "text" },
                { name: "usuario", title: "Usuario", type: "text", align: "left", width: 60 },
                {
                    name: "estado",
                    title: "<div style='text-align: center;'>Estado</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var badgeClass = data == 1 ? 'badge-success' : 'badge-danger';
                        var estadoTexto = data == 1 ? 'Activo' : 'Inactivo';
                        return '<div class="text-center"><span class="badge ' + badgeClass + '">' + estadoTexto + '</span></div>';
                    }
                },
            ];
            inicializarTabla("{{ route('listar-usuarios') }}", columnConfigLaravel, 'tablaUsuarios', 'modalusuarios');
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
                                cargartabla();                            
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
            formId: 'form-usuarios',
            codigoInputId: 'codigo',
            tablaId: 'tablaUsuarios',
            modificarUrl: "{{ url('modificar-usuarios') }}",
            guardarUrl: "{{ url('guardar-usuarios') }}"
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
        document.getElementById('collapseu').classList.add('show');
    });
    </script>
</body>

</html>