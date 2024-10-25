<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Clientes</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Clientes</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm"
                            onclick="modalNuevoRegistro('modalclientes')"><span class="icon text-white-50"><i
                                    class="fas fa-plus"></i></span><span class="text">Nuevo</span></button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Clientes registradas</h6>
                        </div>
                        <div class="card-body">
                            <div id="tablaClientes"></div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR ALMACEN  -->
                    <div class="modal fade" id="modalclientes" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioclientes">
                                        </h6>
                                    </div>
                                    <form id="form-clientes" method="POST" autocomplete="off"
                                        action="{{ url('guardar-clientes') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="iddelcliente" name="iddelcliente" type="hidden" readonly="">
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Tipo Persona:</label>
                                                    <select class="form-control form-control-sm" name="cbotipopersona"
                                                        required="" id="cbotipopersona">
                                                    </select>
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Tipo Documento:</label>
                                                    <select class="form-control form-control-sm" name="cbotipodocumento"
                                                        required="" id="cbotipodocumento">
                                                    </select>
                                                </div>
                                                <div class="col 6">
                                                    <div style="text-align: right;"
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input" id="estado"
                                                            name="estado" checked>
                                                        <label class="custom-control-label" for="estado">Activo?</label>
                                                    </div>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <div class="form-group ">
                                                    <div style="text-align: right;"
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="agretencion" name="agretencion" checked>
                                                        <label class="custom-control-label" for="agretencion">Ag
                                                            retencion?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-3 mb-2">
                                                    <label class="my-0">Número de documento:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm"
                                                            required="" name="numeroruc" id="numeroruc" maxlength="11"
                                                            value="">
                                                        <span class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-primary btn-flat btn-sm"
                                                                onclick="buscarRuc()" id="btnbuscaruc"
                                                                name="btnbuscaruc"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Razon social:</label>
                                                    <input id="razonsocial" type="text"
                                                        class="form-control form-control-sm" required=""
                                                        name="razonsocial" maxlength="150">
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="my-0">Nombre comercial:</label>
                                                <input id="nombrecomercial" type="text"
                                                    class="form-control form-control-sm" name="nombrecomercial"
                                                    maxlength="150" value="">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Direccion:</label>
                                                    <input id="direccion" type="text"
                                                        class="form-control form-control-sm" required=""
                                                        name="direccion" maxlength="150">
                                                </div>
                                                <div class="form-group col-sm-3 mb-2">
                                                    <label class="my-0">Ubigeo:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm"
                                                            required="" name="ubigeo" id="ubigeo" maxlength="6" value=""
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                        <span class="input-group-append">
                                                            <button type="button" id="busubigeo"
                                                                class="btn btn-primary btn-flat btn-sm"><i
                                                                    class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Nombre contacto:</label>
                                                    <input id="nombrecontacto" type="text"
                                                        class="form-control form-control-sm" name="nombrecontacto"
                                                        maxlength="150" value="">
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Cargo contacto:</label>
                                                    <input id="cargocontacto" type="text"
                                                        class="form-control form-control-sm" name="cargocontacto"
                                                        maxlength="100" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Correo:</label>
                                                    <input id="correo" type="email" class="form-control form-control-sm"
                                                        name="correo" maxlength="50" value="">
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Telefono:</label>
                                                    <input id="telefono" type="text"
                                                        class="form-control form-control-sm" name="telefono"
                                                        maxlength="40" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Condicion de venta:</label>
                                                    <select class="form-control form-control-sm" name="cbocondicion"
                                                        required="" autofocus="" id="cbocondicion">
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Vendedor:</label>
                                                    <select class="form-control form-control-sm" name="cbovendedor"
                                                        required="" autofocus="" id="cbovendedor">
                                                    </select>
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Moneda:</label>
                                                    <select class="form-control form-control-sm" name="cbomoneda"
                                                        autofocus="" id="cbomoneda" required="">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5 mb-2">
                                                    <label class="my-0">Ultima modificacion:</label>
                                                    <input id="usercrea" type="text"
                                                        class="form-control form-control-sm" name="txtUsercrea"
                                                        maxlength="50" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevo" name="btnNuevo"
                                                    onclick="modalNuevoRegistro('modalclientes')"
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

                    <div class="modal fade" id="modal-mostrar-ubigeo">
                        <div class="modal-dialog modal-lg" style="min-width:50%;">
                            <div class="modal-content">
                                <div class="card card-info">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Ubigeos</h6>
                                    </div>
                                    <form id="form-ubigeos">
                                        <div class="card-body">
                                            <input class="form-control" type="hidden" id="idubigeo" name="idubigeo"
                                                readonly="">
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Departamento:</label>
                                                    <select class="form-control" name="departamento" id="departamento">
                                                    </select>
                                                </div>
                                                <div class="form-group col-5 mb-2">
                                                    <label class="my-0">Provincia:</label>
                                                    <select class="form-control" name="provincia" id="provincia">
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Distrito:</label>
                                                    <select class="form-control" name="distrito" id="distrito">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Ubigeo:</label>
                                                    <input class="form-control" id="ubiselec" name="ubiselec"
                                                        readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" id="btnAceptarubigeo" name="btnAceptarubigeo"
                                                class="btn btn-primary" data-dismiss="modal"><span
                                                    class="fa fa-check"></span> Aceptar</button>
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
            $('#departamento').change(function () {
                var departamento = $(this).val();
                $.ajax({
                    url: '{{ route('obtener-provincia') }}',
                    method: 'GET',
                    data: {
                        departamento: departamento
                    },
                    success: function (data) {
                        $('#provincia').empty();
                        $('#provincia').append('<option value="">Seleccione</option>');
                        $.each(data, function (index, provincia) {
                            $('#provincia').append('<option value="' + provincia.ubi_Provincia + '">' + provincia.ubi_Provincia + '</option>');
                        });
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#provincia').change(function () {
                var provincia = $(this).val();
                $.ajax({
                    url: '{{ route('obtener-distrito') }}',
                    method: 'GET',
                    data: {
                        provincia: provincia
                    },
                    success: function (data) {
                        $('#distrito').empty();
                        $('#distrito').append('<option value="">Seleccione</option>');
                        $.each(data, function (index, distrtito) {
                            $('#distrito').append('<option value="' + distrtito.Ubi_Distrito + '">' + distrtito.Ubi_Distrito + '</option>');
                        });
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#distrito').change(function () {
                var provincia = $(this).val();
                $.ajax({
                    url: '{{ route('obtener-ubigeo') }}',
                    method: 'GET',
                    data: {
                        provincia: provincia
                    },
                    success: function (data) {
                        if (data.length > 0) {
                            $('#ubiselec').val(data[0].Ubi_Codigo);
                        }
                    }
                });
            });
        });

        $(document).ready(function () {
            cargartabla();
            listarOpciones("#cbotipopersona", "{{ route('obtener-tipop') }}", 'cod', 'deascripcion', true);
            listarOpciones("#cbotipodocumento", "{{ route('obtener-tipod') }}", 'cod', 'deascripcion', true);
            listarOpciones("#cbovendedor", "{{ route('listar-vendedor') }}", 'id', 'nombres', true);
            listarOpciones("#cbocondicion", "{{ route('listar-condicion') }}", 'id', 'descripcion', true);
            listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
        });

        function buscarRuc() {
            if ($("#numeroruc").val() !== "") {
                // Verifica el tipo de documento
                if ($("#cbotipodocumento").val() === "6") {
                    $.ajax({
                        url: '/buscar-ruc/' + $('#numeroruc').val(),
                        dataType: 'json',
                        success: function (data) {
                            $("#razonsocial").val(data.razonSocial);
                            $("#direccion").val(data.direccion + data.departamento + data.provincia + data.distrito);
                            $("#ubigeo").val(data.ubigeo);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                } else if ($("#cbotipodocumento").val() === "1") {
                    $.ajax({
                        url: '/buscar-dni/' + $('#numeroruc').val(),
                        dataType: 'json',
                        success: function (data) {
                            $("#razonsocial").val(data.apellidoPaterno + data.apellidoMaterno + data.nombres);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                }
            } else {
                showErrorMessage('Ingrese un numero !!', '');
            }
        }

        function mostrarModalEdicion(fila, modalid) {
            if (modalid === 'modalclientes') {
                if (fila.id) {
                    $.ajax({
                        url: '/obtener-datosinputsclientes/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            $('#iddelcliente').val(response.id);
                            $('#numeroruc').val(response.ruc);
                            $('#razonsocial').val(response.RazonSocial);
                            $('#nombrecomercial').val(response.Nomcomercial);
                            $('#direccion').val(response.Direccion);
                            $('#ubigeo').val(response.Ubigeo);
                            $('#cbotipodocumento').val(response.TipoD);
                            $('#cbotipopersona').val(response.TipoP);
                            $('#correo').val(response.Correo);
                            $('#telefono').val(response.Telefono);
                            $('#cbovendedor').val(response.Vendedor);
                            $('#cbocondicion').val(response.Condicion);
                            $('#cbomoneda').val(response.Moneda);
                            $('#nombrecontacto').val(response.NomContacto);
                            $('#cargocontacto').val(response.CargoContacto);
                            var fechaHoraFormateada = new Date(response.updated_at).toLocaleString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });
                            $('#usercrea').val(response.UsuarioCrea + " " + fechaHoraFormateada);
                            $('#estado').prop('checked', response.Activo);
                            $('#agretencion').prop('checked', response.AgRetencion);
                            $("#tituloFormularioclientes").html("Modificar datos");
                            $('#' + modalid).modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                }
            }
        }

        function modalNuevoRegistro(modalid) {
            if (modalid === 'modalclientes') {
                $("#tituloFormularioclientes").html("Nuevo registro");
                clearFormInputsSelects('form-clientes', 'cbotipopersona');
                $("#estado").prop("checked", true);
                $("#agretencion").prop("checked", false);
                $('#modalclientes').modal('show');
            }
        }

        function cargartabla() {
            var columnConfigLaravel = [
                { name: "id", title: "Codigo", type: "text", visible: false },
                { name: "ruc", title: "Ruc", type: "text", align: "left", width: 40 },
                { name: "razonsocial", title: "<div style='text-align: center;'>Cliente</div>", type: "text" },
                { name: "direccion", title: "<div style='text-align: center;'>Direccion</div>", type: "text" },
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
            inicializarTabla("{{ route('listar-datosclientes') }}", columnConfigLaravel, 'tablaClientes', 'modalclientes');
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
            formId: 'form-clientes',
            codigoInputId: 'iddelcliente',
            tablaId: 'tablaClientes',
            modificarUrl: "{{ url('modificar-clientes') }}",
            guardarUrl: "{{ url('guardar-clientes') }}"
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
            document.getElementById('collapsec').classList.add('show');
        });

        $(document).ready(function () {
            // Define la función para abrir el modal
            function abrirModal(config) {
                $(config.trigger).on("click", function () {
                    // Muestra el modal modal-mostrar-ubigeo
                    $(config.modal).modal("show");
                    // Oculta el modal modalclientes
                    $("#modalclientes").modal("hide");
                    listarOpciones("#departamento", "{{ route('obtener-departamento') }}", '', 'Ubi_Departamento', true);
                    $("#ubiselec").val("");
                    document.getElementById('provincia').innerHTML = '';
                    document.getElementById('distrito').innerHTML = '';
                });
                // Maneja la acción cuando se oculta el modal modal-mostrar-ubigeo
                $(config.modal).on("hidden.bs.modal", function () {
                    // Muestra nuevamente el modal modalclientes
                    $("#modalclientes").modal("show");
                    capturarValor("ubigeo");
                });
                // Maneja la acción cuando se oculta el modal modalclientes
                $("#modalclientes").on("hidden.bs.modal", function () {
                    $("body").removeClass("modal-open");
                    $("body").css("padding-right", "0px");
                });
            }

            abrirModal({
                trigger: "#busubigeo",
                modal: "#modal-mostrar-ubigeo",
            });

            // Función para capturar el valor de ubiselec y pasarlo a otro input
            function capturarValor(inputId) {
                var ubiselecValue = $("#ubiselec").val();
                $("#" + inputId).val(ubiselecValue);
            }
        });
    </script>
</body>

</html>