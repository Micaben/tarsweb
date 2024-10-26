<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Proveedor</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Proveedor</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()"><span
                                class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                class="text">Nuevo</span></button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Proveedor registrados</h6>
                        </div>
                        <div class="card-body">
                            <div id="tablaProveedor"></div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR  -->
                    <div class="modal fade" id="modalproveedor" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulario"></h6>
                                    </div>
                                    <form id="form-proveedor" method="POST" autocomplete="off"
                                        action="{{ url('guardar-proveedor') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="iddelproveedor" name="iddelproveedor" type="hidden" readonly="">
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Tipo Persona:</label>
                                                    <select class="form-control" name="cbotipopersona"
                                                        id="cbotipopersona" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Tipo Documento:</label>
                                                    <select class="form-control" name="cbotipodocumento"
                                                        id="cbotipodocumento" required>
                                                    </select>
                                                </div>
                                                <div class="col 6">
                                                    <div style="text-align: right;"
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input" id="estado"
                                                            name="estado" checked>
                                                        <label class="custom-control-label" for="estado">Activo?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-3 mb-2">
                                                    <label class="my-0">Número de documento:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" required=""
                                                            name="numero" id="numero" maxlength="11" value="">
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-primary btn-flat "
                                                                onclick="buscarRuc()" id="btnbuscaruc"
                                                                name="btnbuscaruc"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Razon social:</label>
                                                    <input id="razonsocial" type="text" class="form-control"
                                                        name="razonsocial" maxlength="150" required="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-10 mb-2">
                                                    <label class="my-0">Direccion:</label>
                                                    <input id="direccion" type="text" class="form-control"
                                                        name="direccion" maxlength="150" value="">
                                                </div>
                                                <div class="form-group col-2 mb-2">
                                                    <label class="my-0">Ubigeo:</label>
                                                    <input id="ubigeo" type="text" class="form-control" name="ubigeo"
                                                        maxlength="6" value=""
                                                        oninput="this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Nombre contacto:</label>
                                                    <input id="contacto" type="text" class="form-control"
                                                        name="contacto" maxlength="150" value="">
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Cargo contacto:</label>
                                                    <input id="cargocontacto" type="text" class="form-control"
                                                        name="cargocontacto" maxlength="100" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-5 mb-2">
                                                    <label class="my-0">Correo:</label>
                                                    <input id="correo" type="email" class="form-control" name="correo"
                                                        maxlength="50" value="">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Telefono:</label>
                                                    <input id="telefono" type="text" class="form-control"
                                                        name="telefono" maxlength="40" value="">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Pais:</label>
                                                    <input id="pais" type="text" class="form-control" name="pais"
                                                        maxlength="40" value="">
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
            listarOpciones("#cbotipopersona", "{{ route('obtener-tipop') }}", 'cod', 'deascripcion', true);
            listarOpciones("#cbotipodocumento", "{{ route('obtener-tipod') }}", 'cod', 'deascripcion', true);
        });

        function mostrarModalEdicion(fila, modalid) {
            if (fila.id) {
                console.log(fila.id);
                $.ajax({
                    url: '/obtener-datosinputsproveedor/' + fila.id,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        $('#iddelproveedor').val(response.id);
                        $('#numero').val(response.Proveedor);
                        $('#razonsocial').val(response.Nombres);
                        $('#direccion').val(response.Direccion);
                        $('#cbotipodocumento').val(response.Tipod);
                        $('#cbotipopersona').val(response.Tipop);
                        $('#correo').val(response.Correo);
                        $('#telefono').val(response.Telefono);
                        $('#contacto').val(response.Contacto);
                        $('#cargocontacto').val(response.Cargocontacto);
                        $('#pais').val(response.Pais);
                        $('#estado').prop('check', response.estado)
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
            clearFormInputsSelects('form-proveedor', 'cbotipopersona');
            $('#modalproveedor').modal('show');
        }

        function cargartabla() {
            var columnConfigLaravel = [
                { name: "id", title: "codigo", type: "text", visible: false },
                { name: "proveedor", title: "Ruc", type: "text", align: "left", width: 40 },
                { name: "nombres", title: "<div style='text-align: center;'>Razon social</div>", type: "text" },
                { name: "direccion", title: "<div style='text-align: center;'>Direccion</div>", type: "text" },
                { name: "telefono", title: "<div style='text-align: center;'>Telefono</div>", type: "text", width: 60 },
            ];
            inicializarTabla("{{ route('listar-datosproveedor') }}", columnConfigLaravel, 'tablaProveedor', 'modalproveedor');
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
            formId: 'form-proveedor',
            codigoInputId: 'iddelproveedor',
            tablaId: 'tablaProveedor',
            modificarUrl: "{{ url('modificar-proveedor') }}",
            guardarUrl: "{{ url('guardar-proveedor') }}"
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
            document.getElementById('collapsepr').classList.add('show');
        });
    </script>
</body>

</html>