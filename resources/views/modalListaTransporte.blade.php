<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo de jsGrid</title>
    <!-- Incluir CSS de jsGrid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid-theme.min.css" />
</head>

<body>
    <!-- *************  MODAL AGREGAR PRODUCTO ********************* -->
    <div class="modal fade" id="modal-agregar-transporte">
        <div class="modal-dialog modal-lg notice" style="min-width:80%;">
            <div class="modal-content">
                <div class="card card-info">
                    <div class="card-header ">
                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioSublinea">Empresa de
                            Transporte
                        </h6>
                    </div>
                    <form id="form-productos">
                        <div class="card-body">
                            <input id="numitem" type="hidden">
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Tipo de transporte:</label>
                                    <select class="form-control form-control-sm" required="" id="cbotipotransporte"
                                        name="cbotipotransporte">
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Fecha de traslado:</label>
                                    <input id="fechatraslado" type="date" class="form-control form-control-sm"
                                        required="" name="fechatraslado" maxlength="10">
                                </div>
                                <div class="form-group col-5 mb-2">
                                    <label class="my-0">Empresa de transporte:</label>
                                    <select class="form-control form-control-sm" required="" id="cboempresatransporte"
                                        name="cboempresatransporte">
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Ruc:</label>
                                    <input id="ructransporte" type="text" class="form-control form-control-sm"
                                        required="" name="ructransporte" readonly maxlength="11">
                                </div>
                            </div>
                            <div class="mb-2">
                                <h7 style="margin-bottom: 0;">Datos del transportista</h7>
                                <hr class="hr hr-blurry" style="margin-top: 0.2rem;" />
                            </div>
                            <div class="row">
                                <div class="form-group col-4 mb-2">
                                    <label class="my-0">Chofer:</label>
                                    <select class="form-control form-control-sm" required="" id="cbochofer"
                                        name="cbochofer">
                                    </select>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label class="my-0">Documento:</label>
                                    <select class="form-control form-control-sm" required="" id="cbodocumento"
                                        name="cbodocumento">
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Nombres:</label>
                                    <input id="nombres" type="text" class="form-control form-control-sm" required=""
                                        name="nombres">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Apellidos:</label>
                                    <input id="apellidos" type="text" class="form-control form-control-sm" required=""
                                        name="apellidos">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">DNI:</label>
                                    <input id="dni" type="text" class="form-control form-control-sm" required=""
                                        name="dni">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Licencia:</label>
                                    <input id="licencia" type="text" class="form-control form-control-sm" required=""
                                        name="licencia">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Unidad:</label>
                                    <input id="unidad" type="text" class="form-control form-control-sm" required=""
                                        name="unidad">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Placa:</label>
                                    <input id="placa" type="text" class="form-control form-control-sm" required=""
                                        name="placa">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Peso(KG):</label>
                                    <input id="peso" type="text" class="form-control form-control-sm" required=""
                                        name="peso">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Bultos:</label>
                                    <input id="bultos" type="number" class="form-control form-control-sm" required=""
                                        name="bultos">
                                </div>
                            </div>
                            <div class="mb-2">
                                <h7 style="margin-bottom: 0;">Punto de partida</h7>
                                <hr class="hr hr-blurry" style="margin-top: 0.2rem;" />
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Ubigeo:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" required=""
                                            name="ubigeopartida" id="ubigeopartida" maxlength="6" value=""
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <span class="input-group-append">
                                            <button type="button" id="busubigeopartida" data-input-id="ubigeopartida"
                                                class="btn btn-primary btn-flat btn-sm"><i
                                                    class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 mb-2">
                                    <label class="my-0">Direccion:</label>
                                    <input id="direccionpartida" type="text" class="form-control form-control-sm"
                                        required="" name="direccionpartida">
                                </div>
                            </div>
                            <div class="mb-2">
                                <h7 style="margin-bottom: 0;">Punto de llegada</h7>
                                <hr class="hr hr-blurry" style="margin-top: 0.2rem;" />
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Ubigeo:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" required=""
                                            name="ubigeollegada" id="ubigeollegada" maxlength="6" value=""
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <span class="input-group-append">
                                            <button type="button" id="busubigeollegada" data-input-id="ubigeollegada"
                                                class="btn btn-primary btn-flat btn-sm"><i
                                                    class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 mb-2">
                                    <label class="my-0">Direccion:</label>
                                    <input id="direccionllegada" type="text" class="form-control form-control-sm"
                                        required="" name="direccionllegada">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('modalListaUbigeo')  
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/selects.js') }}"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script>

        $(document).ready(function () {
            $('#modal-agregar-transporte').on('show.bs.modal', function (event) {
                const id = document.getElementById('ructransporte');
                var fechaActual = new Date().toISOString().split('T')[0];
                document.getElementById('fechatraslado').value = fechaActual;
                var button = $(event.relatedTarget); // Botón que activó el modal
                var bladeId = button.data('blade-id'); // Extraer el ID del blade
                listarOpciones("#cbotipotransporte", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
                listarOpciones("#cboempresatransporte", "{{ route('obtener-Empresat') }}", 'ruc', 'empresa', true, '10426058500');
                document.getElementById('ructransporte').value = '10426058500';
            });
        });

        document.getElementById('cboempresatransporte').addEventListener('change', function () {
            var select = document.getElementById("cboempresatransporte");
            var valorSeleccionado = select.value;
            document.getElementById("ructransporte").value = valorSeleccionado;
            listarOpcionesChofer(
                "#cbochofer",
                "{{ route('listar-datosempresat', '') }}/" + valorSeleccionado,
                'id',
                'nombres',
                ['apellidos'], // Campos adicionales a concatenar
                true
            );
            listarOpciones("#cbodocumento", "{{ route('obtener-tipod') }}", 'cod', 'deascripcion', false,'1');
        });

        document.getElementById('cbochofer').addEventListener('change', function () {
            var select = document.getElementById("cbochofer");
            var valorSeleccionado = select.value;
            if (valorSeleccionado) {
                // Realiza la petición AJAX a la ruta con el id seleccionado
                fetch(`/obtener-datosinputstransportista/${valorSeleccionado}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length >= 0) {
                            console.log(data);
                            const transportista = data[0];
                            // Llena los inputs con los datos recibidos
                            document.getElementById('nombres').value = transportista.nombres || '';
                            document.getElementById('apellidos').value = transportista.apellidos || '';
                            document.getElementById('dni').value = transportista.dni || '';
                            document.getElementById('licencia').value = transportista.licencia || '';
                            document.getElementById('unidad').value = transportista.unidad || '';
                            document.getElementById('placa').value = transportista.placa || '';
                            // Añade más inputs si es necesario
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener datos del transportista:', error);
                    });
            } else {
                // Si no hay un valor seleccionado, limpia los inputs
                document.getElementById('nombres').value = '';
                document.getElementById('apellidos').value = '';
                document.getElementById('dni').value = '';
            }
        });

        $(document).ready(function () {
            var lastInputId;

            function abrirModal(config) {
                $(config.trigger).on("click", function () {
                    lastInputId = $(this).data('input-id');
                    $(config.modal).modal("show");

                    $("#modal-agregar-transporte").modal("hide");
                    listarOpciones("#departamento", "{{ route('obtener-departamento') }}", '', 'Ubi_Departamento', true);

                    // Resetear el valor del input de selección del Ubigeo
                    $("#ubiselec").val("");

                    // Limpiar las opciones de provincia y distrito
                    document.getElementById('provincia').innerHTML = '';
                    document.getElementById('distrito').innerHTML = '';
                });

                $(config.modal).on("hidden.bs.modal", function () {
                    // Volver a mostrar el modal de transporte cuando el modal de Ubigeo se cierre
                    $("#modal-agregar-transporte").modal("show");

                    // Capturar el valor del Ubigeo seleccionado y ponerlo en el input que llamó al modal
                    capturarValor(lastInputId);
                });

                // Cuando el modal de transporte se cierra, remover el scroll modal
                $("#modal-agregar-transporte").on("hidden.bs.modal", function () {
                    $("body").removeClass("modal-open");
                    $("body").css("padding-right", "0px");
                });
            }

            // Función para capturar el valor seleccionado y ponerlo en el input correspondiente
            function capturarValor(inputId) {
                var ubigeoSeleccionado = $("#ubiselec").val(); // Valor seleccionado en el modal de Ubigeo
                $("#" + inputId).val(ubigeoSeleccionado); // Colocar el valor en el input que llamó al modal
            }

            // Inicializar la apertura del modal de Ubigeo desde los botones de partida o llegada
            abrirModal({
                trigger: "#busubigeopartida, #busubigeollegada", // Los dos botones que abren el modal
                modal: "#modal-mostrar-ubigeo", // El modal que se abrirá
            });
        });

    
    </script>
</body>

</html>