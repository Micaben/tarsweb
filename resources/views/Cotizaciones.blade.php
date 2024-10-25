<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Cotizaciones</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mensajeAnulado.css') }}">
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
                        <h1 class="h3 mb-2 text-gray-800">Cotizaciones</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="row">
                            <div class="col-12">
                                <form id="form-cotizacion" method="POST" action="{{ url('guardar-cotizacion') }}">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <x-Botones :columns="[
                                                ['name' => 'Documento', 'align' => 'left'],                                            
                                                ['name' => 'Fecha', 'align' => 'left'],
                                                ['name' => 'RUC', 'align' => 'left'],
                                                ['name' => 'Cliente', 'align' => 'left'],
                                                ['name' => 'Estado', 'align' => 'center'],
                                                ['name' => 'MDA', 'align' => 'center'],
                                                ['name' => 'Total', 'align' => 'right'],
                                                ['name' => 'id', 'align' => 'center']
                                                ]"
                                            :url="route('listar-mostrarRegistrosCotizacion')"
                                            :routeName="'registroCotizacion'"
                                            :routeDetalleName="'registroCotizaciondetalle'" :inputs="['cboserie', 'numeronota', 'fechaproceso', 'cboconcepto', 'cboproveedor', 'proveedor', 'cbomoneda', 'vigencia', 'ordencompra', 'cboalmacen', 'comentarios', 'iddeldocumento', 'subtotal', 'inigv', 'igv', 'lblmoneda', 'totalSuma']"
                                            :inputMapping="[
                                            'cboserie' => 'seriecot',
                                            'numeronota' => 'numcot',
                                            'fechaproceso' => 'FechaCot',
                                            'cboconcepto' => 'Concepto',
                                            'cboproveedor' => 'ruc',
                                            'proveedor' => 'ruc',
                                            'cbomoneda' => 'Moneda',
                                            'vigencia' => 'plazo',
                                            'ordencompra' => 'OCompra',
                                            'cboalmacen' => 'almacen',
                                            'comentarios' => 'Referencia',
                                            'iddeldocumento' => 'id',
                                            'subtotal' => 'baseimp',
                                            'inigv' => 'in_igv',
                                            'igv' => 'igv',
                                            'lblmoneda' => 'Factor2',
                                            'totalSuma' => 'Total'
                                            ]" :messageId="'mensajeAnulado'" :showCuotas="false"  :showTransporte="false"  
                                             :showAnuladoMessage="true"  :showCortesia="false" :showRetencion="false" :showKardex="false"/>
                                        </div>
                                        <div class="card-body">
                                        <x-form-fields                                                 
                                                :showComprobante="false"  
                                                :showFechav="false"                                               
                                            />
                                            <x-TablaProductos
                                                :showEliminar="true"
                                                :showTotales="true"
                                                :showSubtotal="true"
                                                :showIGV="true"
                                                :showTotal="true"
                                            />
                                        </div>
                                </form>
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
            @include('modalListaProductos')  
            @include('modalListaRegistros')                       
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
    <script src="{{ asset('js/selects.js') }}"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            listarOpciones("#cboconcepto", "{{ route('listar-concepto') }}", 'id', 'descripcion', true);
            listarOpciones("#cboserie", "{{ route('obtener-series', ['id' => 'C1']) }}", 'id', 'serie', true);
            listarOpciones("#cboproveedor", "{{ route('listar-datosclientes') }}", 'ruc', 'razonsocial', true);
            listarOpciones("#cbomoneda", "{{ route('obtener-moneda') }}", 'cod', 'deascripcion', false);
            listarOpciones("#cboalmacen", "{{ route('listar-datosalmacen') }}", 'id', 'descripcion', false);
            document.getElementById('lblmoneda').innerText = 'S/';
        });

        document.getElementById('cboproveedor').addEventListener('change', function () {
            var select = document.getElementById("cboproveedor");
            var valorSeleccionado = select.value;
            document.getElementById("proveedor").value = valorSeleccionado;
        });

        document.addEventListener('DOMContentLoaded', function () {
            var cboSerie = document.getElementById('cboserie');
            if (cboSerie) {
                cboSerie.addEventListener('change', function () {
                    var valorSeleccionado = this.value;
                    var url = "{{ url('obtener-ultimonumero') }}/C1/" + encodeURIComponent(valorSeleccionado);
                    console.log("URL generada: ", url); // Para depuración
                    obtenerultimoNumero("#numeronota", url);
                });
            } else {
                console.error("Elemento con ID 'cboserie' no encontrado.");
            }
        });

        function formatErrorMessages(errors) {
            if (Array.isArray(errors)) {
                return errors.join(', ');
            } else {
                return 'Error desconocido';
            }
        }

        function modalNuevoRegistro() {
            clearFormInputsSelects('form-cotizacion', 'cboserie');
            limpiarTablaDataTable('form-cotizacion', 'tablaProductos');
            $("#inigv").prop("checked", true);
            var fechaActual = new Date().toISOString().split('T')[0];
            document.getElementById('fechaproceso').value = fechaActual;
            document.getElementById('mensajeAnulado').style.display = 'none';
            document.getElementById("tipodocumento").value = 'C1';
            document.getElementById("vigencia").value = '30';
            document.getElementById('btnAgregar').disabled = false;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('collapseThree').classList.add('show');
        });

        document.getElementById('form-cotizacion').addEventListener('submit', function (event) {
            event.preventDefault();
            var tabla = document.getElementById('tablaProductos').getElementsByTagName('tbody')[0];
            var filas = tabla.getElementsByTagName('tr');
            if (filas.length === 0) {
                showErrorMessage('La tabla está vacía. Por favor, agrega productos antes de guardar.');
                return;
            }
            var form = document.getElementById('form-cotizacion');
            var formData = new FormData(form);
            var selectElement = document.getElementById('cboserie');
            var textoSelect = selectElement.options[selectElement.selectedIndex].text;
            var inputElement = document.getElementById('iddeldocumento');

            var valorSelect = document.getElementById('cboalmacen').value;
            var productos = [];
            for (var i = 0; i < filas.length; i++) {
                var columnas = filas[i].getElementsByTagName('td');
                var producto = {
                    item: columnas[0].textContent.trim(),
                    codigo: columnas[1].textContent.trim(),
                    descripcion: columnas[2].textContent.trim(),
                    unidad: columnas[3].textContent.trim(),
                    cantidad: columnas[4].getElementsByTagName('input')[0].value,
                    precio: columnas[5].getElementsByTagName('input')[0].value,
                    total: columnas[6].textContent.trim(),
                    almacen: valorSelect,
                    id: inputElement.value
                };
                productos.push(producto);
            }
            formData.append('cboserie_text', textoSelect);
            formData.append('productos', JSON.stringify(productos));
            $('input[type=checkbox], input[type=radio]').each(function () {
                let inputinigv = $(this).attr('id');
                let inputChecked = $(this).is(':checked');
                formData.append(inputinigv, inputChecked);
            });

            fetch('/guardar-cotizacion', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage('Operación exitosa: ' + (data.message ? data.message : 'Registro guardado correctamente'));
                        var generatedId = data.id;
                        document.getElementById('iddeldocumento').value = generatedId; // Ajusta el ID según tu HTML
                    } else {
                        let errorMessages = formatErrorMessages(data.errors);
                        showErrorMessage('Error al procesar la solicitud. ' + errorMessages);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error al procesar la solicitud. Código de estado: ' + error);
                });
        });

        function anular(event) {
            const table = document.getElementById('tablaIngresos'); // Reemplaza 'tablaProductos' con el id real de tu tabla
            var inputElement = document.getElementById('iddeldocumento').value;
            if (table.rows.length <= 0) {
                showErrorMessage('No hay ningún documento seleccionado para anular.');
                return;
            } else {
                Swal.fire({
                    title: '¿Desea anular el documento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/anularRegistro/' + inputElement,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Token CSRF para Laravel
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Documento Anulado',
                                    text: 'Anulación: Documento anulado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                }).then(() => {
                                    // Después de cerrar el Swal de éxito, muestra el mensaje "ANULADO"
                                    document.getElementById('mensajeAnulado').style.display = 'block';
                                });
                            },
                            error: function (xhr) {
                                showErrorMessage('Error al procesar la solicitud: ' + xhr.responseJSON.error);
                            }
                        });
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const igvCheckbox = document.getElementById('inigv');
            const IGV_RATE = 0.18;

            const subtotalInput = document.getElementById('subtotal');
            const igvInput = document.getElementById('igv');
            const totalSumaInput = document.getElementById('totalSuma');

            // Función para calcular IGV y actualizar los campos
            function actualizarTotales() {
                let subtotal = parseFloat(subtotalInput.value) || 0;
                let igv = 0;
                let total = subtotal;

                if (igvCheckbox.checked) {
                    igv = subtotal * IGV_RATE;
                    total += igv;
                }
                subtotalInput.value = subtotal.toFixed(2);
                igvInput.value = igv.toFixed(2);
                totalSumaInput.value = total.toFixed(2);
            }

            igvCheckbox.addEventListener('change', function () {
                actualizarTotales();
            });

            actualizarTotales();
        });

        function validarFormulario(formularioId, camposFormulario) {
            const configuracionValidacion = configurarValidacion(formularioId, camposFormulario);
            $(formularioId).validate(configuracionValidacion);
            return $(formularioId).valid();
        }

        function imprimirReporte() {
            const table = document.getElementById('tablaIngresos'); // Reemplaza 'tablaProductos' con el id real de tu tabla
            if (table.rows.length <= 0) {
                showErrorMessage('No hay ningún documento seleccionado para imprimir.');
                return;
            } else {
                var id = document.getElementById('iddeldocumento').value; // Obtener el valor del input
                var url = '{{ route("imprimir.reporte", ":id") }}'; // Ruta de Laravel con placeholder
                url = url.replace(':id', id); // Reemplazar el placeholder con el id real
                window.open(url, '_blank'); // Abrir en una nueva pestaña
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const igvCheckbox = $('#inigv'); // Usando jQuery para asegurar que funcione correctamente
            igvCheckbox.change(function () {
                var checkIgv = $(this).prop('checked'); // Usar 'this' para obtener el estado del checkbox
                console.log('Estado del checkbox:', checkIgv);
                actualizarTotal('tablaIngresos', checkIgv);  // Recalcular cuando se cambia el checkbox del IGV
            });

        });
    </script>
</body>

</html>