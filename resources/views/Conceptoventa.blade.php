<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Concepto de venta</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Concepto de venta</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()"><span
                                class="icon text-white-50"><i class="fas fa-plus"></i></span><span
                                class="text">Nuevo</span></button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Concepto de venta</h6>
                        </div>
                        <div class="card-body">
                            <div id="tablaConcepto"></div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR CONCEPTO VENTA  -->
                    <div class="modal fade" id="modalconcepto" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioconcepto">
                                        </h6>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="form-concepto" method="POST" action="{{ url('guardar-concepto') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="codigo" type="hidden" class="form-control" name="codigo"
                                                maxlength="4" value="" readonly="">
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Descripción:</label>
                                                <input id="tdescripcion" type="text" class="form-control"
                                                    name="tdescripcion">
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Comprobante:</label>
                                                <select class="form-control" name="comprobante" required=""
                                                    id="comprobante">
                                                </select>
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Cuenta:</label>
                                                <input id="tcuenta" type="text" class="form-control" name="tcuenta"
                                                    maxlength="10" value=""
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Código Afectación:</label>
                                                <select id="tafectacion" class="form-control" name="tafectacion">
                                                </select>
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Tipo NC/ND:</label>
                                                <select id="tncnd" class="form-control" name="tncnd">
                                                </select>
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Tipo Operación:</label>
                                                <select id="toperacion" class="form-control" name="toperacion">
                                                </select>
                                            </div>
                                            <div class="form-group col-10 mb-2">
                                                <label class="my-0">Tipo Factura:</label>
                                                <select id="ttipofactura" class="form-control" name="ttipofactura">
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group col-2 mb-2">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="tkardex" name="tkardex" checked>
                                                            <label class="custom-control-label"
                                                                for="tkardex">Kardex?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group col-2 mb-2">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="tcortesia" name="tcortesia" checked>
                                                            <label class="custom-control-label"
                                                                for="tcortesia">Cortesia?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group col-2 mb-2">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="tdetalle" name="tdetalle" checked>
                                                            <label class="custom-control-label"
                                                                for="tdetalle">Detalle?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group col-2 mb-2">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="tgravada" name="tgravada" checked>
                                                            <label class="custom-control-label"
                                                                for="tgravada">Gravada?</label>
                                                        </div>
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
            listarOpciones("#comprobante", "{{ route('obtener-documentos') }}", 'documento', 'Descripcion', true);
            listarOpciones("#tafectacion", "{{ route('obtener-afectacion') }}", 'cod', 'deascripcion', true);           
            listarOpciones("#toperacion", "{{ route('obtener-operacion') }}", 'cod', 'deascripcion', true);
            listarOpciones("#ttipofactura", "{{ route('obtener-tipofactura') }}", 'cod', 'deascripcion', true);
        });

        document.getElementById('comprobante').addEventListener('change', function () {
            var value = this.value;

            if (value === '07') {
                listarOpciones("#tncnd", "{{ route('obtener-nc') }}", 'cod', 'deascripcion', true);
            } else if (value === '08') {
                listarOpciones("#tncnd", "{{ route('obtener-nd') }}", 'cod', 'deascripcion', true);
            }
        });

        function cargarTabla() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left" },
                { name: "nombre_comprobante", title: "<div style='text-align: center;'>Comprobante</div>", type: "text", className: "text-left" },
                {
                    name: "cortesia",
                    title: "<div style='text-align: center;'>Cortesia</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var checkboxHtml = '<div class="text-center"><input type="checkbox" disabled ';
                        if (data == 1) {
                            checkboxHtml += 'checked';
                        }
                        checkboxHtml += '></div>';
                        return checkboxHtml;
                    }
                },
                {
                    name: "detalle",
                    title: "<div style='text-align: center;'>Detalle</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var checkboxHtml = '<div class="text-center"><input type="checkbox" disabled ';
                        if (data == 1) {
                            checkboxHtml += 'checked';
                        }
                        checkboxHtml += '></div>';
                        return checkboxHtml;
                    }
                },
                {
                    name: "kardex",
                    title: "<div style='text-align: center;'>Kardex</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var checkboxHtml = '<div class="text-center"><input type="checkbox" disabled ';
                        if (data == 1) {
                            checkboxHtml += 'checked';
                        }
                        checkboxHtml += '></div>';
                        return checkboxHtml;
                    }
                },
                {
                    name: "nogravada",
                    title: "<div style='text-align: center;'>Gravada</div>",
                    width: 20,
                    render: function (data, type, row) {
                        var checkboxHtml = '<div class="text-center"><input type="checkbox" disabled ';
                        if (data == 1) {
                            checkboxHtml += 'checked';
                        }
                        checkboxHtml += '></div>';
                        return checkboxHtml;
                    }
                },
            ];
            hacerConsulta("{{ route('listar-concepto') }}", columnConfigLaravel, 'tablaConcepto');
        }

        function mostrarModalEdicion(row) {
            if (row.id) {
                $.ajax({
                    url: '/obtener-datosinputsconcepto/' + row.id,
                    type: 'GET',
                    success: function (response) {
                        console.log(response)
                        $('#codigo').val(response.id);
                        $('#tdescripcion').val(response.Descripcion);
                        $('#comprobante').val(response.Comprobante);
                        $('#tcuenta').val(response.cuenta);
                        $('#tafectacion').val(response.CodAfec);
                        $('#tncnd').val(response.CodNCND);
                        $('#toperacion').val(response.Email);
                        $('#ttipofactura').val(response.Email);
                        $('#tkardex').prop('checked', response.kardex);
                        $('#tdetalle').prop('checked', response.detalle);
                        $('#tgravada').prop('checked', response.nogravada);
                        $('#tcortesia').prop('checked', response.cortesia);
                        $("#tituloFormularioconcepto").html("Modificar datos");
                        $('#modalconcepto').modal('show');
                    },
                    error: function (error) {
                        console.log('Error al obtener datos:', error);
                    }
                });
            }
        }

        function modalNuevoRegistro() {
            $("#tituloFormularioconcepto").html("Nuevo registro");
            clearFormInputs('form-concepto', 'tdescripcion');
            clearFormInputsSelects('form-concepto','comprobante');
            $("#tkardex").prop("checked", false);
            $("#tdetalle").prop("checked", false);
            $("#tcortesia").prop("checked", false);
            $("#tgravada").prop("checked", false);
            $('#modalconcepto').modal('show');
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
                            cargarTabla();
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

        document.getElementById('form-concepto').addEventListener('submit', function (e) {
            e.preventDefault();
            var formId = 'form-concepto';
            var codigoInputId = 'codigo';
            var tablaId = 'tablaConcepto';
            var id = document.getElementById('codigo').value;
            var action = id ? "{{ url('modificar-concepto') }}/" + id : "{{ url('guardar-concepto') }}";
            var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
            var errorMessage = 'Error al procesar la solicitud';

            console.log(id + "id");
            submitForm(action, successMessage, errorMessage, id, formId, codigoInputId, tablaId, '');
        });

        function formatErrorMessages(errors) {
            lermaors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Agrega la clase 'show' al submenu "Mantenimiento" para mantenerlo visible
            document.getElementById('collapsev').classList.add('show');
        });
    </script>
</body>

</html>