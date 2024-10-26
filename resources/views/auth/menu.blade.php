<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Menu</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <!-- Texto de ventas del mes -->
                                            <div
                                                class="text-xs font-weight-bold text-primary text-uppercase mb-1 text-center">
                                                Ventas del mes (dolares)
                                            </div>
                                            <!-- Input de tipo month, centrado debajo del texto -->
                                            <div class="text-center">
                                                <input type="month" id="inputMesdolares" class="form-control"
                                                    style="width: 200px; border: none; background: none; font-size: 1.0em;">
                                            </div>
                                            <!-- Total de ventas -->
                                            <div id="ventasMesdolares"
                                                class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                0.00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-left">
                                        <div class="col mr-1">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                                Ventas del mes </br>(Soles)</div>
                                            <div id="ventasMessoles" class="h5 mb-0 font-weight-bold text-gray-800">0.00
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Input de tipo month, visible -->
                                            <input type="month" id="inputMessoles" class="form-control" value=""
                                                style="width: 160px; border: none; background: none; font-size: 0.8em;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Guias por Facturar
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 80%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Server Migration <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales Tracking <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="...">
                                    </div>
                                    <p>Add some quality, svg illustrations to your project courtesy of <a
                                            target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                        constantly updated collection of beautiful svg images that you can use
                                        completely free and without attribution!</p>
                                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                        unDraw &rarr;</a>
                                </div>
                            </div>


                            <!-- Approach                            
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                                </div>
                                <div class="card-body">
                                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                        CSS bloat and poor page performance. Custom CSS classes are used to create
                                        custom components and custom utility classes.</p>
                                    <p class="mb-0">Before working with this theme, you should become familiar with the
                                        Bootstrap framework, especially the utility classes.</p>
                                </div>
                            </div>
                             -->
                        </div>
                    </div>

                    <div id="calendar">

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Mycsfot 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

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
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Obtener la fecha actual
                const inputMesdolares = document.getElementById('inputMesdolares');
                const inputMessoles = document.getElementById('inputMessoles');
                const fechaActual = new Date();

                // Formatear la fecha en formato YYYY-MM para que se muestre en los input type="month"
                const mesActual = fechaActual.toISOString().slice(0, 7); // Obtiene YYYY-MM
                inputMesdolares.value = mesActual;
                inputMessoles.value = mesActual;

                // Manejar el cambio de mes seleccionado para dólares
                inputMesdolares.addEventListener('change', function () {
                    const mesSeleccionado = inputMesdolares.value; // Formato YYYY-MM

                    // Realizar una petición al servidor con el mes seleccionado para dólares
                    fetch(`/ventas-mes/dolares/${mesSeleccionado}`)
                        .then(response => response.json())
                        .then(data => {
                            // Verificar si data.totalVentasMoneda02 tiene valor
                            const totalVentasDolares = data.totalVentasMoneda01 || 0; // Si es undefined, usa 0
                            document.getElementById('ventasMesdolares').textContent = `${data.totalVentasMoneda02.toFixed(2)}`;
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Manejar el cambio de mes seleccionado para soles
                inputMessoles.addEventListener('change', function () {
                    const mesSeleccionado = inputMessoles.value; // Formato YYYY-MM

                    // Realizar una petición al servidor con el mes seleccionado para soles
                    fetch(`/ventas-mes/soles/${mesSeleccionado}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            const totalVentasDolares = data.totalVentasMoneda02 || 0;
                            document.getElementById('ventasMessoles').textContent = `${data.totalVentasMoneda01.toFixed(2)}`;
                        })
                        .catch(error => console.error('Error:', error));
                });
            });


            $(function () {
                function ini_events(ele) {
                    ele.each(function () {
                        var eventObject = {
                            title: $.trim($(this).text()) // use the element's text as the event title
                        }
                        $(this).data('eventObject', eventObject)
                        $(this).draggable({
                            zIndex: 1070,
                            revert: true,
                            revertDuration: 0
                        })
                    })
                }

                ini_events($('#external-events div.external-event'))
                var Calendar = FullCalendar.Calendar;
                var Draggable = FullCalendar.Draggable;
                var containerEl = document.getElementById('external-events');
                var checkbox = document.getElementById('drop-remove');
                var calendarEl = document.getElementById('calendar');

                calendar = new Calendar(calendarEl, {

                    editable: true,

                    dateClick: function (info) {
                        var day = info.date.getDate();
                        var month = info.date.getMonth() + 1; // Los meses en JavaScript van de 0 a 11, por lo que se suma 1
                        var year = info.date.getFullYear();
                        var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

                        Swal.fire({
                            title: 'Programar evento',
                            text: 'Ingrese los detalles del evento:',
                            icon: 'info',
                            html: `
                    <input type="text" id="event-title" class="swal2-input" placeholder="Título del evento">
                    <input type="time" id="event-time" class="swal2-input" placeholder="Hora del evento">`,
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar'
                        }).then(function (result) {
                            var title = $('#event-title').val();
                            var time = $('#event-time').val();

                            if (title.trim() === '' || time.trim() === '') {
                                Swal.fire('Error', 'Por favor, ingrese el título y la hora del evento.', 'error');
                                return; // Detener la ejecución si la validación falla
                            }

                            var formattedDateTime = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day + 'T' + time + ':00';

                            // Utiliza dateTimeString directamente como la propiedad 'start' para incluir la hora
                            calendar.addEvent({
                                title: title,
                                start: formattedDateTime,
                                allDay: false // Puedes cambiar esto según tus necesidades

                            });

                            $.ajax({
                                url: '/guardar-evento',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    title: title,
                                    time: time,
                                    clickedDate: formattedDate,
                                },
                                success: function (response) {
                                    if (response && response.success) {
                                        Swal.fire({
                                            text: 'Evento guardados !!',
                                            icon: 'success'
                                        });
                                    } else {
                                        console.error('Respuesta inesperada del servidor:', response);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log('Error:', jqXHR);
                                    console.log('Texto del estado:', textStatus);
                                    console.log('Error arrojado:', errorThrown);
                                    if (textStatus === 'parsererror') {
                                        console.warn('La respuesta no es JSON. Puede ser una cadena de texto.');
                                        // Aquí puedes manejar el caso de error cuando la respuesta no es JSON
                                    } else {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Hubo un error al guardar el evento. !!'
                                        });
                                    }
                                }
                            });

                        });
                    },
                    eventContent: function (arg) {
                        return {
                            html: '<div class="fc-event"  style="text-align: center">' + arg.event.title + '<br>' + formatDate(arg.event.start) + '</div>',
                        };
                    }, eventClick: function (info) {
                        var event = info.event;
                        // Mostrar el SweetAlert para editar o eliminar el evento
                        mostrarSweetAlertParaEditarOEliminar(event);
                    },
                });    // Manejar evento 'eventAdd' (evento agregado)
                calendar.on('eventAdd', function (info) {
                    verificarFechaHoraEvento(info.event);
                });

                // Manejar evento 'eventChange' (evento cambiado)
                calendar.on('eventChange', function (info) {
                    verificarFechaHoraEvento(info.event);
                });

                // Manejar evento 'eventRemove' (evento eliminado)
                calendar.on('eventRemove', function (info) {
                    // Puedes implementar lógica adicional si lo necesitas
                })
                calendar.render();
                buscarEventos();
            })

            function formatDate(date) {
                var options = {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: false,
                    timeZone: 'America/Bogota' // O 'America/Lima'
                };
                return new Intl.DateTimeFormat('es-ES', options).format(date);
            }

            function mismaZonaHoraria(fecha1, fecha2) {
                // Obtenemos el desplazamiento de zona horaria en minutos para cada fecha
                var offset1 = fecha1.getTimezoneOffset();
                var offset2 = fecha2.getTimezoneOffset();

                // Si los desplazamientos de zona horaria son iguales, están en el mismo huso horario
                return offset1 === offset2;
            }

            function verificarFechaHoraEvento(evento) {
                console.log(evento.id);

                var now = new Date();
                var timeUntilEvent = evento.start - now;

                if (timeUntilEvent <= 0) {
                    $.ajax({
                        url: '/verificar-evento-descartado/',
                        method: 'GET',
                        success: function (response) {
                            var eventosDescartados = response.eventos.filter(function (eventoItem) {
                                return eventoItem.descartado === null;
                            });
                            if (response.hasOwnProperty('eventos') && Array.isArray(response.eventos)) {
                                // Filtrar solo los eventos que tengan descartado: null
                                var eventosNoDescartados = response.eventos.filter(function (eventoItem) {
                                    return eventoItem.descartado === null;
                                });

                                // Verificar si hay eventos no descartados
                                if (eventosNoDescartados.length > 0) {
                                    Swal.fire({
                                        title: '¡Es hora del evento!',
                                        text: 'El evento ' + eventosNoDescartados[0].descripcion + ' ha llegado.',
                                        icon: 'info',
                                        showCancelButton: true,
                                        cancelButtonText: 'Descartar',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Posponer',
                                        confirmButtonColor: '#3085d6',
                                        showLoaderOnConfirm: true,
                                        preConfirm: () => {
                                            // Aquí puedes realizar alguna operación antes de posponer el evento
                                            return new Promise(resolve => {
                                                // Simulando una operación de posponer el evento (puedes modificar esta parte según tus necesidades)
                                                setTimeout(() => {
                                                    resolve();
                                                }, 1000);
                                            });
                                        },
                                        allowOutsideClick: () => !Swal.isLoading()
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Aquí puedes realizar alguna acción después de posponer el evento
                                            var newStartTime = new Date(evento.start);
                                            newStartTime.setMinutes(newStartTime.getMinutes() + 1);
                                            evento.setStart(newStartTime);

                                            // Mostrar un mensaje de confirmación
                                            Swal.fire('Evento pospuesto', 'El evento ha sido pospuesto por 1 minuto.', 'success');

                                            // Renderizar nuevamente el calendario para reflejar los cambios
                                            calendar.render();
                                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                                            // Aquí puedes realizar alguna acción si el usuario descarta el evento
                                            console.log(eventosDescartados[0].id);
                                            $.ajax({
                                                url: '/descartar-evento/' + eventosDescartados[0].id,
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function (response) {
                                                    // Manejar la respuesta del servidor
                                                    if (response.success) {
                                                        // Actualización exitosa
                                                        Swal.fire('Evento descartado', 'El evento ha sido descartado.', 'success');
                                                        buscarEventos();
                                                    } else {
                                                        // Manejar errores, si los hay
                                                        Swal.fire('Error', 'Hubo un problema al actualizar el evento.', 'error');
                                                    }
                                                },
                                                error: function (jqXHR, textStatus, errorThrown) {
                                                    console.error('Error en la solicitud AJAX:', errorThrown);
                                                    Swal.fire('Error', 'Hubo un problema al actualizar el evento.', 'error');
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    console.log('No hay eventos no descartados.');
                                }
                            } else {
                                console.log('No se encontraron eventos en la respuesta.');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error en la solicitud AJAX:', errorThrown);
                            Swal.fire('Error', 'Hubo un problema al obtener los eventos.', 'error');
                        }
                    });
                }
            }


            function mostrarSweetAlertParaEditarOEliminar(event) {
                var title = event.title;

                Swal.fire({
                    title: 'Editar evento',
                    icon: 'info',
                    html:
                        `<input type="text" id="edited-event-title" class="swal2-input" value="${title}">
             <input type="time" id="edited-event-time" class="swal2-input" value="${formatTime(event.start)}">`,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        document.getElementById('edited-event-title').focus();
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        var eventId = event.id;
                        var newTitle = document.getElementById('edited-event-title').value;
                        var newTime = document.getElementById('edited-event-time').value;
                        // Enviar los datos al servidor para actualizar el evento
                        $.ajax({
                            url: '/modificar-evento/' + eventId,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                title: newTitle,
                                time: newTime,
                                // Puedes agregar más campos según tus necesidades
                            },
                            success: function (response) {
                                // Manejar la respuesta del servidor
                                if (response.success) {
                                    // Actualización exitosa
                                    Swal.fire('Evento actualizado', 'El evento ha sido actualizado con éxito.', 'success');
                                    buscarEventos();
                                } else {
                                    // Manejar errores, si los hay
                                    Swal.fire('Error', 'Hubo un problema al actualizar el evento.', 'error');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.error('Error en la solicitud AJAX:', errorThrown);
                                Swal.fire('Error', 'Hubo un problema al actualizar el evento.', 'error');
                            }
                        });
                    }
                });
            }

            function formatTime(date) {
                return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
            }

            // Función para eliminar el evento del calendario
            function eliminarEvento(event) {
                Swal.fire({
                    title: '',
                    text: `¿Está seguro de que desea eliminar el evento: ${event.title}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: '#d33'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // Eliminar el evento del calendario
                        event.remove();
                        // Realizar la lógica para eliminar el evento en tu sistema
                        // Puedes realizar una solicitud AJAX aquí si es necesario
                        Swal.fire('Evento eliminado', 'El evento ha sido eliminado con éxito.', 'success');
                    }
                });
            }

            function mostrarSweetAlertParaEditar(info) {
                if (info) {
                    var titulo = escape(info.title);
                    var start = info.start;

                    var formattedDateTime = start.toLocaleString();
                    console.log(info.title);
                    console.log(info.start);
                    Swal.fire({
                        title: 'Editar evento',
                        icon: 'info',
                        html:
                            ` <input type="text" id="edited-event-title" class="swal2-input"  value="${info.title}">
                <input type="time" id="edited-event-time" class="swal2-input" placeholder="Nueva hora del evento"  value="${formattedDateTime}">>`,
                        showCancelButton: true,
                        confirmButtonText: 'Guardar',
                        cancelButtonText: 'Cancelar'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            // Obtener la nueva hora del input
                            var newTime = $('#edited-event-time').val();

                            // Realizar la lógica para actualizar la hora del evento en tu sistema
                            // Puedes realizar una solicitud AJAX aquí si es necesario
                            // Actualizar el evento en el calendario
                            info.event.setProp('start', new Date(start.getFullYear(), start.getMonth(), start.getDate(), newTime.split(':')[0], newTime.split(':')[1]));
                            Swal.fire('Evento actualizado', 'La hora del evento ha sido actualizada con éxito.', 'success');
                        }
                    });
                } else {
                    console.error('El objeto de evento es undefined. Asegúrate de estar asignando eventos correctamente en tu calendario.');
                }
            }

            function buscarEventos() {
                $.ajax({
                    url: '/obtener-eventos',
                    method: 'GET',
                    data: {
                        mes: calendar.view.currentStart.getMonth() + 1,
                        anio: calendar.view.currentStart.getFullYear()
                    },
                    success: function (data) {
                        // Iterar sobre los eventos actuales en el calendario
                        calendar.getEvents().forEach(function (eventoExistente) {
                            // Encontrar el evento correspondiente en la respuesta del servidor
                            var eventoActualizado = data.find(function (evento) {
                                return evento.id === eventoExistente.id;
                            });

                            // Si el evento no está en la respuesta del servidor, eliminarlo
                            if (!eventoActualizado) {
                                eventoExistente.remove();
                            } else {
                                // Actualizar el evento existente con la nueva información
                                eventoExistente.setProp('title', eventoActualizado.descripcion);
                                eventoExistente.setStart(eventoActualizado.fin + ' ' + eventoActualizado.hora);
                            }
                        });

                        // Agregar nuevos eventos que no existían previamente
                        data.forEach(function (evento) {
                            var eventoExistente = calendar.getEventById(evento.id);
                            if (!eventoExistente) {
                                calendar.addEvent({
                                    id: evento.id,
                                    title: evento.descripcion,
                                    start: evento.fin + ' ' + evento.hora
                                });
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error al obtener eventos del servidor:', errorThrown);
                    }
                });
            }
        </script>

</body>

</html>