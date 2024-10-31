<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuotas</title>
    <!-- Incluir CSS de jsGrid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsgrid/dist/jsgrid-theme.min.css" />
</head>

<body>
    <!-- *************  MODAL AGREGAR CUOTAS ********************* -->
    <div class="modal fade" id="modal-mostrar-cuotas" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="min-width:90%;">
            <div class="modal-content">
                <div class="card card-info">
                    <div class="card-header ">
                        <h6 class="m-0 font-weight-bold text-primary" id="modalLabel">Cuotas</h6>
                    </div>
                    <div class="modal-body">
                        <div class="form-row align-items-end">
                            <div class="form-group col-sm-1 mb-2">
                                <label class="my-0"># Cuota:</label>
                                <input class="form-control form-control-sm" type="number" required="" value="1" min="1"
                                    max="15" id="numerocuota" name="numerocuota">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Fecha:</label>
                                <input id="fechacuota" type="date" class="form-control form-control-sm" required=""
                                    name="fechacuota" maxlength="10">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Total:</label>
                                <input class="form-control form-control-sm" id="total" readonly name="total">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Retencion:</label>
                                <input class="form-control form-control-sm" id="retencioncuota" readonly
                                    name="retencioncuota">
                            </div>
                            <div class="form-group col-sm-2 mb-2">
                                <label class="my-0">Neto a pagar:</label>
                                <input class="form-control form-control-sm" required="" id="netoapagar" readonly
                                    name="netoapagar">
                            </div>
                            <div class="mb-2">
                                <button type="button" id="btnAgregarCuota" name="btnAgregarCuota" value="Agregar"
                                    class="btn btn-primary btn-sm custom-button">
                                    <span class="fa fa-plus"></span> Agregar </button>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th># de Cuota</th>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaListaCuotas">
                                        <!-- Las cuotas se agregarán aquí dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-2">
                            <button type="button" id="btnAceptarCuota" name="btnAceptarCuota"
                                class="btn btn-primary btn-sm custom-button">
                                <span class="fa fa-check"></span> Aceptar </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script>
        var cuotasGuardadas = [];
        document.getElementById('numerocuota').addEventListener('input', generarCuotas);
        document.getElementById('btnAgregarCuota').addEventListener('click', generarCuotas);

        function generarCuotas() {
            var numeroCuotas = parseInt(document.getElementById('numerocuota').value, 10);
            var totalcuota = parseFloat(document.getElementById('total').value);
            var montoretencion = parseInt(document.getElementById('retencioncuota').value, 10);
            var netopagar = parseFloat(document.getElementById('netoapagar').value);
            var fechaCuota = document.getElementById('fechacuota').value;

            if (!isNaN(numeroCuotas) && !isNaN(totalcuota) && fechaCuota) {
                // Limpiar la tabla antes de agregar nuevas filas
                var tablaCuotas = document.getElementById('tablaListaCuotas');
                tablaCuotas.innerHTML = '';

                // Calcular el monto por cuota
                var montoPorCuota = (netopagar / numeroCuotas).toFixed(2);
                cuotasGuardadas = [];
                // Generar las filas de la tabla
                for (var i = 1; i <= numeroCuotas; i++) {
                    var fila = document.createElement('tr');

                    // Columna de número de cuota
                    var celdaNumero = document.createElement('td');
                    var numeroFormateado = String(i).padStart(3, '0'); 
                    celdaNumero.textContent = numeroFormateado;
                    fila.appendChild(celdaNumero);

                    // Columna de fecha (editable)
                    var celdaFecha = document.createElement('td');
                    var inputFecha = document.createElement('input');
                    inputFecha.type = 'date';
                    inputFecha.className = 'form-control form-control-sm';
                    inputFecha.value = fechaCuota;
                    celdaFecha.appendChild(inputFecha);
                    fila.appendChild(celdaFecha);

                    // Columna de monto por cuota (editable)
                    var celdaMonto = document.createElement('td');
                    var inputMonto = document.createElement('input');
                    inputMonto.type = 'number';
                    inputMonto.className = 'form-control form-control-sm';
                    inputMonto.value = montoPorCuota;
                    celdaMonto.appendChild(inputMonto);
                    fila.appendChild(celdaMonto);

                    // Agregar la fila a la tabla
                    tablaCuotas.appendChild(fila);

                    var cuota = {
                        numeroCuota: i,
                        fecha: fechaCuota,
                        monto: montoPorCuota,                                                
                    };
                    cuotasGuardadas.push(cuota);
                }
            } else {
                alert('Por favor ingrese valores válidos en el número de cuota, monto, y fecha.');
            }
        }

    </script>
</body>

</html>