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
    <!-- *************  MODAL UBIGEO ********************* -->
    <div class="modal fade" id="modal-mostrar-ubigeo">
        <div class="modal-dialog modal-lg" style="min-width:50%;">
            <div class="modal-content">
                <div class="card card-info">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ubigeos</h6>
                    </div>
                    <form id="form-ubigeos">
                        <div class="card-body">
                            <input class="form-control" type="hidden" id="idubigeo" name="idubigeo" readonly="">
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
                                    <input class="form-control" id="ubiselec" name="ubiselec" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" id="btnAceptarubigeo" name="btnAceptarubigeo" class="btn btn-primary"
                                data-dismiss="modal"><span class="fa fa-check"></span> Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        let inputDestino = null; // Variable para guardar el input al que se enviará el valor

        // Al abrir el modal, capturamos qué botón lo activó
        $('#modal-mostrar-ubigeo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            inputDestino = button.data('input-id'); // Guardamos el id del input destino
            guardarValoresModal();
        });

        // Al hacer clic en "Confirmar" en el modal
        document.getElementById('btnAceptarubigeo').addEventListener('click', function () {
            var valorSeleccionado = document.getElementById('ubiselec').value;

            if (inputDestino && valorSeleccionado) {
                // Asigna el valor seleccionado al input correspondiente
                document.getElementById(inputDestino).value = valorSeleccionado;
            }

            // Cierra el modal
            $('#modal-mostrar-ubigeo').modal('hide');
        });
    </script>
</body>

</html>