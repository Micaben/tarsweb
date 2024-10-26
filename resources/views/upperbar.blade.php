<meta name="csrf-token" content="{{ csrf_token() }}">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <form class="form-inline">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
    </form>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="{{ route('tipocambio.consulta', ['fecha' => now()->toDateString()])}}" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-money-bill-wave"></i>
                <!-- Counter - Alerts -->

            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Tipo de cambio del dia <span id="fechaActual"></span>
                </h6>
                <form id="consultaForm">
                @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 form-group mb-2">
                                <label class="my-0">Compra:</label>
                                <input class="form-control form-control-sm" id="compratc" name="compratc">
                            </div>
                            <div class="col-sm-4 form-group mb-2">
                                <label class="my-0">Venta:</label>
                                <input class="form-control form-control-sm" id="ventatc" name="ventatc">
                            </div>
                            <div class="col-sm-4 form-group mb-2">
                                <label class="my-0">Comercial:</label>
                                <input class="form-control form-control-sm" id="comercialtc" name="comercialtc">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group">
                            <button type="button" id="btnConsultar" name="btnConsultar"
                                class="btn btn-primary btn-icon-split btn-sm"><span class="icon text-white-50"><i
                                        class="fas fa-question-circle"></i></span><span
                                    class="text">Consultar</span></button>
                        </div>
                        <div class="btn-group">
                            <button type="submit" id="btnRegistrar" name="btnRegistrar"
                                class="btn btn-primary btn-icon-split btn-sm"><span class="icon text-white-50"><i
                                        class="fas fa-save"></i></span><span class="text">Guardar</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span id="totalBadge" class="badge badge-danger badge-counter">0</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">Ver detalle de notificacion</div>
                        <span class="totalProductosBadge"></span>
                        <span class="font-weight-bold"  onclick="mostrarDetalle('')">Productos por vencer</span>
                        
                    </div>
                </a>                                
            </div>
        </li>    

      

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ session('nombre_usuario') }}</span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.mantenimiento') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
   
    </ul>

</nav>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detalleModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document"  style="min-width:90%">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="m-0 font-weight-bold text-primary" id="exampleModalLabel1">Productos por vencer en 30 dias</h6>
              
            </div>
            <div class="modal-body" id="detalleContenido"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>                
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="{{ asset('js/mensajes.js') }}"></script>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
 

    $('#btnConsultar').click(function (e) {
        e.preventDefault();
        var fecha = $("#fechaActual").val(); // Asegúrate de obtener el valor de fecha correctamente
        axios.get('/consultawebTC', {
            params: {
                fecha: fecha
            }
        })
            .then(function (response) {
                console.log(response.data);
                $("#compratc").val(response.data.compra);
                $("#ventatc").val(response.data.venta);
                $("#comercialtc").val(response.data.venta);
            })
            .catch(function (error) {
                console.log("Error en la solicitud: " + error);
            });
    });

    var fechaActualSpan = document.getElementById('fechaActual');
    // Obtener la fecha actual
    var fechaActual = new Date();
    // Formatear la fecha actual como "DD/MM/YYYY" (por ejemplo)
    var formattedFechaActual = fechaActual.getDate() + '/' + (fechaActual.getMonth() + 1) + '/' + fechaActual.getFullYear();
    // Mostrar la fecha actual en el elemento span
    fechaActualSpan.textContent = formattedFechaActual;

    $('#consultaForm').submit(function (e) {
    e.preventDefault();

    var compra = $("#compratc").val();
    var venta = $("#ventatc").val();
    var comercial = $("#comercialtc").val();

    // Obtener la fecha actual desde el elemento span y convertirla al formato yyyy/MM/dd
    var fechaActual = $("#fechaActual").text();
    var fechaActualFormatoCorrecto = convertirFecha(fechaActual);

    // Hacer la solicitud al controlador para validar la existencia de datos
    $.ajax({
    url: '/guardar-tipocambio',
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        fecha: fechaActualFormatoCorrecto, // Aquí debes incluir la fecha que deseas guardar
        compratc: compra,
        ventatc:venta,
        comercialtc:comercial,
    },
    success: function (response) {
        if (response.success) {
            showSuccessMessage( 'Registro guardado correctamente');
        } else {
            showErrorMessage('Ya existe el registro para la fecha ' + fechaActual);
        }
    },
    error: function (xhr, status, error) {
        console.error('Error al guardar el registro: ' + error);
    }
});
});

function convertirFecha(fecha) {
    // Convertir la fecha de dd/MM/yyyy a yyyy/MM/dd
    var partes = fecha.split('/');
    return partes[2] + '/' + partes[1] + '/' + partes[0];
}

$(document).ready(function() {
    obtenerRecuentoProductos();
    $('#alertsDropdown').click(function(event) {
        event.preventDefault(); // Evita que el enlace se comporte como un enlace normal

        // Obtiene la URL del enlace
        var url = $(this).attr('href');

        // Realiza una solicitud AJAX para obtener los datos del tipo de cambio
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Actualiza los inputs con los datos recibidos
                $('#compratc').val(response.Compra);
                $('#ventatc').val(response.Venta);
                $('#comercialtc').val(response.Comercial);                
            },
            error: function(xhr, status, error) {
                console.error(error);
                showErrorMessage('Error al obtener los datos del tipo de cambio');
            }
        });
    });
});


function mostrarDetalle(titulo) {
        // Crear el contenido del modal dinámicamente        
        $.ajax({
        url: '/obtener-productosporvencer',
        type: 'GET',
        success: function (response) {
            console.log(response);
            if (response && response.totalProductos > 0) {
                // Procesar las líneas de productos
                if (response.productos && response.productos.length > 0) {                             
                    var contenido = '<p><strong>' + titulo + '</strong></p>' +
                                    '<table class="table">' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th style="text-align: center">Código</th>' +
                                    '<th style="text-align: center">Descripción</th>' +
                                    '<th style="text-align: center">Unidad</th>' +
                                    '<th style="text-align: center">Cantidad</th>' +
                                    '<th style="text-align: center">Fecha de Vencimiento</th>' +
                                    '<th style="text-align: center">Dias restantes</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';

                    // Iterar sobre cada producto
                    response.productos.forEach(function(producto) {
                        var fechaFormateada = formatearFecha(producto.fechavencimiento);
                        contenido += '<tr>' +
                                     '<td>' + producto.codproducto + '</td>' +
                                     '<td>' + producto.descripcion + '</td>' +
                                     '<td class=\"text-center\">' + producto.idumedida + '</td>' +
                                     '<td class=\"text-center\">' + producto.saldoactual + '</td>' +
                                     '<td class=\"text-center\">' + fechaFormateada + '</td>' +
                                     '<td class=\"text-right\">' + producto.diasrestantes + '</td>' +
                                     '</tr>';
                    });

                    contenido += '</tbody>' +
                                 '</table>';

                    // Actualizar el contenido del modal
                    $('#detalleContenido').html(contenido);

                    // Mostrar el modal
                    $('#detalleModal').modal('show');
                } else {
                    console.error('No se encontraron líneas de productos para mostrar.');
                }
            } else {
                console.error('No se encontraron productos para mostrar.');
            }
        },
        error: function (error) {
            console.error('Error al hacer la llamada AJAX:', error);
        }
    });
    }

    function obtenerRecuentoProductos() {
    $.ajax({
        url: '/obtener-productosporvencer',
        type: 'GET',
        success: function (response) {
            console.log(response);
            if (response && response.totalProductos > 0) {
                // Mostrar el recuento de productos
                $(".totalProductosBadge").text(response.totalProductos);
                $("#totalBadge").text(response.totalProductos);
            }
        },
        error: function (error) {
            console.error('Error al obtener el recuento de productos:', error);
        }
    });
}


</script>