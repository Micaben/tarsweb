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
    <div class="modal fade" id="modal-agregar-producto" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-lg" style="min-width:80%;">
            <div class="modal-content">
                <div class="card card-info">
                    <div class="card-header ">
                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioSublinea">Lista de Productos
                        </h6>
                    </div>
                    
                        <div class="modal-body">
                            
                            <div class="row">
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Código:</label>
                                    <input id="codproducto" type="text" class="form-control custom-button"
                                        name="codproducto" maxlength="10" required="">
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label class="my-0">Descripcion:</label>
                                    <input id="descripcion" type="text" class="form-control custom-button"
                                        name="descripcion" maxlength="10" required="">
                                </div>
                                <div class="form-group col-sm-2 mb-2">
                                    <label class="my-0">Codigo de barras:</label>
                                    <input id="codbarras" type="text" class="form-control custom-button"
                                        name="codbarras" maxlength="10" required="">
                                </div>
                            </div>                           
                                <div class="card">
                                    <div id="tablaListaProductos"></div>
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
    <script src="{{ asset('js/tablesviewProductos.js') }}"></script>
    <script>

        let IdTabla, alm, idtablapr, detalleg, inigv, subtot, base, grav, tigv, tot, idd, boton;

        $(document).ready(function () {
            
            $('#modal-agregar-producto').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var bladeId = button.data('blade-id'); // Extraer el ID del blade
                var empresa = "{{ session('empresa') }}";
                var usuario = ""; // Ajusta según tu lógica
                var producto = "";
                var descripcion = ""; // Ajusta según tu lógica
                var activo = 1; // Ajusta según tu lógica
                var url = '/productos/' + empresa + usuario + producto + descripcion + activo;
                var checkIgv = $('#inigv').prop('checked');     
                // Llamar a la función para cargar la tabla con el bladeId
                cargartabla(url, bladeId,checkIgv);
            });
        });

        function cargartabla(url, bladeId,checkIgv) {
            var columnConfigLaravel = [
                { name: "codProducto", title: "Codigo", type: "text", align: "left", width: 70 },
                { name: "Descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text" },
                { name: "Unidad", title: "<div style='text-align: center;'>U.M</div>", type: "text" ,align: "center"},
                { name: "Color", title: "<div style='text-align: center;'>Color</div>", type: "text" },
                { name: "Ancho", title: "<div style='text-align: center;'>Ancho</div>", type: "text", width: 20 , align: "right"},
                { name: "saldo", title: "<div style='text-align: center;'>Stock</div>", type: "text", align: "right"},
            ];
            inicializarTabla(url, columnConfigLaravel, 'tablaListaProductos', 'modal-agregar-producto', bladeId,checkIgv);
        }

        function inicializarTabla(url, columnConfig, tablaID, modalID, bladeId,checkIgv) {
            hacerConsulta(url, columnConfig, tablaID, modalID, bladeId,checkIgv);
        }

        function mostrarProductos() {
            $("#modal-agregar-producto").modal('show');
            $('#modal-agregar-producto').on('shown.bs.modal', function () {
                $('#codproducto').focus();
            });
        }

        function consultaStockProducto() {
            var select = document.getElementById("cboproducto");
            var valorSeleccionado = select.value;
            var selectedOption = select.options[select.selectedIndex];
            document.getElementById("codproducto").value = valorSeleccionado;
            document.getElementById("umedida").value = selectedOption.getAttribute("data-um");
            document.getElementById("color").value = selectedOption.getAttribute("data-color");
            document.getElementById("ancho").value = selectedOption.getAttribute("data-ancho");
            listarStockP(valorSeleccionado)
                .then(result => {
                    $('#stockproducto').val(result.stock);
                    $('#comprometido').val(result.comprometido);
                    $('#saldo').val(result.saldo);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function listarStockP(producto) {
            var empresa = "{{ session('empresa') }}";
            var usuario = ""; // Ajusta según tu lógica
            var descripcion = ""; // Ajusta según tu lógica
            var activo = 1; // Ajusta según tu lógica

            var url = '/productos/' + empresa + usuario + producto + descripcion + activo;
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        var result = {
                            stock: parseFloat(data.stock),
                            comprometido: parseFloat(data.pedido),
                            saldo: parseFloat(data.saldo)
                        };
                        result.stock = isNaN(result.stock) ? 0.00 : result.stock;
                        result.comprometido = isNaN(result.comprometido) ? 0.00 : result.comprometido;
                        result.saldo = isNaN(result.saldo) ? 0.00 : result.saldo;
                        resolve(result);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la llamada AJAX:", error);
                        reject(error);
                    }
                });
            });
        }

    </script>
</body>

</html>