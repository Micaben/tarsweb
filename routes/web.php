<?php

//use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


//AGREGADO PARA USAR EL LOGIN DE LA SB ADMIN
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseCheckController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\NaturalezaController;
use App\Http\Controllers\UmedidaController;
use App\Http\Controllers\LineaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\VendedoresController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\TipocambioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MovalmacenController;
use App\Http\Controllers\VentasCotizacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VentasPedidosController;
use App\Http\Controllers\VentasGuiaremisionController;
use App\Http\Controllers\VentasComprobantesController;
use App\Http\Controllers\CorreoController;

Route::get('/config/defecto', function () {
    return response()->json(json_decode(file_get_contents(public_path('config/defecto.json'))));
});

Route::get('/check-database-connection', [DatabaseCheckController::class, 'checkDatabaseConnection']);

Route::get('/', [AuthController::class, 'showLoginForm'])->name('custom-login-root');

Route::get('/login', 'AuthController@showLoginForm')->name('login');
//Route::post('/login', 'AuthController@login')->name('login.custom');
Route::post('/logout', 'AuthController@logout')->name('logout');

//Ruta para mostrar formulario register
Route::get('/register', function () { return view('register');
})->name('register');
//Formulario reset password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-pass');
//Ruta para guardar usuario
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// Ruta personalizada para volver al inicio de sesión después del register
Route::post('/login-custom', [AuthController::class, 'loginCustom'])->name('login.custom');
// Rutas protegidas por autenticación
Route::get('/menu', [AuthController::class, 'showMenu'])->name('menu')->middleware('auth');
//Forgot para actualizar password
Route::post('/update', [AuthController::class, 'updateuser'])->name('update.user');
//PROFILE
Route::get('/profile', [AuthController::class, 'mostrarProfile'])->name('profile.mantenimiento');

//mantenimiento de productos 
Route::get('/productos', [ProductoController::class, 'mostrarMantenimiento'])->name('productos.mantenimiento');
//cargar sublinea en relacion a otro select
Route::get('/listar-sublineas/{codigoLinea}', 'TuControlador@listarSublineas');
//obtener datos de la naturaleza en un select
Route::get('/obtener-naturaleza', [NaturalezaController::class, 'obtenerNaturaleza'])->name('obtener-naturaleza');
//llena la tabla productos activo
Route::get('/listar-productos-activos',  [ProductoController::class, 'listarActivos'])->name('listar-activos');
//llena la tabla productos inactivos
Route::get('/listar-productos-inactivos',  [ProductoController::class, 'listarInactivos'])->name('listar-inactivos');
//llena la tabla productos todos
Route::get('/listar-todos-los-productos',  [ProductoController::class, 'listarTodos'])->name('listar-todos');
//Guardar productos
Route::post('/guardar-productos', [ProductoController::class, 'guardarProductos']);
Route::post('/modificar-productos/{id}', [ProductoController::class, 'modificarProductos']);
//poner datos en los inputs
Route::get('/obtener-datosinputsproductos/{id}', [ProductoController::class, 'obtenerDatosinputsproductos']);
Route::get('/obtener-productosporvencer', [ProductoController::class, 'obtenerProductosporvencer']);
//Lista stock de los productos

Route::get('/productos/{empresa}{usuario}{producto}{descripcion}{activo}', [ProductoController::class, 'listarStockProductos']);

//muestra la naturaleza
Route::get('/naturaleza', [NaturalezaController::class, 'mostrarMantenimiento'])->name('naturaleza.mantenimiento');
//llena la tabla naturaleza
Route::get('/listar-datosnaturaleza',  [NaturalezaController::class, 'listarDatosNaturaleza'])->name('listar-datosnaturaleza');

//muestra la umedida
Route::get('/umedida', [UmedidaController::class, 'mostrarMantenimiento'])->name('umedida.mantenimiento');
//llena la tabla unidadmedia
Route::get('/listar-datosumedida',  [UmedidaController::class, 'listarDatosumedida'])->name('listar-datosumedida');
//llena la selects unidadmedia
Route::get('/obtener-Umedidafe', [UmedidaController::class, 'obtenerUmedidafe'])->name('obtener-Umedidafe');
Route::get('/obtener-Umedida', [UmedidaController::class, 'obtenerUmedida'])->name('obtener-Umedida');
//Guardar unidad medida
Route::post('/guardar-unidad', [UmedidaController::class, 'guardarUnidad']);
Route::post('/modificar-unidad/{id}', [UmedidaController::class, 'modificarUnidad']);
//obtener datos de tabla UMedida
Route::get('/obtener-datos/{id}', [UmedidaController::class, 'obtenerDatos']);

//muestra la linea
Route::get('/linea', [LineaController::class, 'mostrarMantenimiento'])->name('linea.mantenimiento');
//llena la tabla linea
Route::get('/listar-datoslinea',  [LineaController::class, 'listarDatoslinea'])->name('listar-datoslinea');
//poner datos en los inputs
Route::get('/obtener-datosinputslineas/{id}', [LineaController::class, 'obtenerDatosinputslineas']);
//poner datos en select
Route::get('/obtener-lineas', [LineaController::class, 'obtenerLinea'])->name('obtener-lineas');
//Guardar linea
Route::post('/guardar-linea', [LineaController::class, 'guardarLinea']);
Route::post('/modificar-linea/{id}', [LineaController::class, 'modificarLinea']);

//llena la tabla sublinea
Route::get('/listar-datossublinea/{id}',  [LineaController::class, 'listarDatossublinea'])->name('listar-datossublinea');
//poner datos en los inputs
Route::get('/obtener-datosinputssublineas/{id}', [LineaController::class, 'obtenerDatosinputssublineas']);
//Guardar sublinea
Route::post('/guardar-sublinea', [LineaController::class, 'guardarsubLinea']);
Route::post('/modificar-sublinea/{id}', [LineaController::class, 'modificarsubLinea']);

//llena la tabla color
Route::get('/listar-color',  [UmedidaController::class, 'listarDatosColor'])->name('listar-datoscolor');
//Guardar color
Route::post('/guardar-color', [UmedidaController::class, 'guardarColor']);
Route::post('/modificar-color/{id}', [UmedidaController::class, 'modificarColor']);

//llena la tabla marca
Route::get('/listar-marca',  [UmedidaController::class, 'listarDatosMarca'])->name('listar-datosmarca');
Route::post('/guardar-marca', [UmedidaController::class, 'guardarMarca']);
Route::post('/modificar-marca/{id}', [UmedidaController::class, 'modificarMarca']);

//llena la tabla proveedor
Route::get('/listar-proveedor',  [ProveedorController::class, 'listarDatosProveedor'])->name('listar-datosproveedor');

//almacen
Route::get('/almacen', [AlmacenController::class, 'mostrarMantenimiento'])->name('almacen.mantenimiento');
Route::get('/listar-almacen',  [AlmacenController::class, 'listarDatosAlmacen'])->name('listar-datosalmacen');
Route::post('/guardar-almacen', [AlmacenController::class, 'guardarAlmacen']);
Route::post('/modificar-almacen/{id}', [AlmacenController::class, 'modificarAlmacen']);
//poner datos en los inputs
Route::get('/obtener-datosinputsalmacen/{id}', [AlmacenController::class, 'obtenerDatosinputsalmacen']);

//poner datos cargos
Route::get('/obtener-cargo', [CargoController::class, 'obtenerCargo'])->name('obtener-cargo');

//TRANSPORTISTA
Route::get('/transportista', [AlmacenController::class, 'mostrarMantenimientoTransportista'])->name('transportista.mantenimiento');
Route::get('/listar-transportista',  [AlmacenController::class, 'listarDatosFlota'])->name('listar-datostransportista');
Route::get('/obtener-Empresat', [AlmacenController::class, 'obtenerEmpresat'])->name('obtener-Empresat');
Route::get('/obtener-datosinputsempresat/{id}', [AlmacenController::class, 'obtenerDatosinputsempresat']);
Route::get('/listar-datosempresat/{id}',  [AlmacenController::class, 'listarDatosEmpresat'])->name('listar-datosempresat');
Route::get('/obtener-datosinputstransportista/{id}', [AlmacenController::class, 'obtenerDatosinputstransportista']);
Route::post('/guardar-empresatransporte', [AlmacenController::class, 'guardarEmpresaTransporte']);
Route::post('/modificar-empresatransporte/{id}', [AlmacenController::class, 'modificarEmpresaTransporte']);
Route::post('/guardar-transportista', [AlmacenController::class, 'guardarTransportista']);
Route::post('/modificar-transportista/{id}', [AlmacenController::class, 'modificaTransportista']);

//MOTIVOS MOVIMIENTO
Route::get('/movimiento', [MovimientoController::class, 'mostrarMantenimiento'])->name('movimiento.mantenimiento');
Route::get('/listar-ingreso',  [MovimientoController::class, 'listarIngreso'])->name('listar-ingreso');
Route::get('/listar-salida',  [MovimientoController::class, 'listarSalida'])->name('listar-salida');
Route::get('/obtener-transaccion', [MovimientoController::class, 'obtenertransaccion'])->name('obtener-transaccion');
Route::get('/obtener-operacion', [MovimientoController::class, 'obteneroperacion'])->name('obtener-operacion');
Route::get('/obtener-concepto', [MovimientoController::class, 'obtenerconcepto'])->name('obtener-concepto');
Route::get('/obtener-motivo', [MovimientoController::class, 'obtenermotivo'])->name('obtener-motivo');
Route::get('/obtener-datosinputsmotivos/{id}', [MovimientoController::class, 'obtenerdatosinputsmotivos']);
Route::post('/guardar-motivos', [MovimientoController::class, 'guardarMotivos']);
Route::post('/modificar-motivos/{id}', [MovimientoController::class, 'modificarMotivos']);

//Vendedores
Route::get('/vendedores', [VendedoresController::class, 'mostrarMantenimiento'])->name('vendedor.mantenimiento');
Route::get('/listar-vendedor', [VendedoresController::class, 'listarVendedor'])->name('listar-vendedor');
Route::get('/obtener-datosinputsvendedor/{id}', [VendedoresController::class, 'obtenerDatosinputsvendedor']);
Route::post('/guardar-vendedor', [VendedoresController::class, 'guardarVendedor']);
Route::post('/modificar-vendedor/{id}', [VendedoresController::class, 'modificarVendedor']);

//COMISION
Route::get('/listar-datoscomision', [VendedoresController::class, 'listarComision'])->name('listar-datoscomision');
Route::get('/obtener-datosinputscomision/{id}', [VendedoresController::class, 'obtenerDatosinputscomision']);
Route::post('/guardar-comision', [VendedoresController::class, 'guardarComision']);
Route::post('/modificar-comision/{id}', [VendedoresController::class, 'modificarComision']);

//Concepto
Route::get('/concepto', [ConceptoController::class, 'mostrarMantenimiento'])->name('concepto.mantenimiento');
Route::get('/listar-concepto', [ConceptoController::class, 'listarConcepto'])->name('listar-concepto');
Route::get('/obtener-datosinputsconcepto/{id}', [ConceptoController::class, 'obtenerDatosinputsconcepto']);
Route::post('/guardar-concepto', [ConceptoController::class, 'guardarConcepto']);
Route::post('/modificar-concepto/{id}', [ConceptoController::class, 'modificarConcepto']);
Route::get('/obtener-afectacion', [ConceptoController::class, 'obtenerAfectacion'])->name('obtener-afectacion');
Route::get('/obtener-nc', [ConceptoController::class, 'obtenerNC'])->name('obtener-nc');
Route::get('/obtener-nd', [ConceptoController::class, 'obtenerND'])->name('obtener-nd');
Route::get('/obtener-operacion', [ConceptoController::class, 'obtenerOperacion'])->name('obtener-operacion');
Route::get('/obtener-tipofactura', [ConceptoController::class, 'obtenerTipofactura'])->name('obtener-tipofactura');

//DOCUMENTOS
Route::get('/documentos', [DocumentosController::class, 'mostrarMantenimiento'])->name('documentos.mantenimiento');
Route::get('/listar-documentos', [DocumentosController::class, 'listarDocumentos'])->name('listar-documentos');
Route::get('/listar-documentosfac', [DocumentosController::class, 'listarDocumentosFac'])->name('listar-documentosfac');
Route::get('/obtener-documentos', [DocumentosController::class, 'obtenerDocumentos'])->name('obtener-documentos');
Route::get('/obtener-datosinputsdocumentos/{id}', [DocumentosController::class, 'obtenerDatosinputsdocumentos']);
Route::post('/guardar-documentos', [DocumentosController::class, 'guardarDocumentoo']);
Route::post('/modificar-documentos/{id}', [DocumentosController::class, 'modificarDocumentos']);

//SERIES
Route::get('/obtener-series/{id}', [SeriesController::class, 'obtenerSeries'])->name('obtener-series');
Route::get('/obtener-datosinputsseries/{id}', [SeriesController::class, 'obtenerDatosinputsseries']);
Route::post('/guardar-series', [SeriesController::class, 'guardarSeries']);
Route::post('/modificar-series/{id}', [SeriesController::class, 'modificarSeries']);
Route::get('/obtener-ultimonumero/{td}/{id}', [SeriesController::class, 'obtenerNumeros']);

//USUARIOS
Route::get('/usuarios', [AuthController::class, 'mostrarMantenimiento'])->name('usuarios.mantenimiento');
Route::get('/listar-usuarios', [AuthController::class, 'listarDatosUsuarios'])->name('listar-usuarios');
Route::get('/obtener-usuarios', [AuthController::class, 'obtenerUsuarioss'])->name('obtener-usuarios');
Route::get('/obtener-datosinputsusuarios/{id}', [AuthController::class, 'obtenerDatosinputsusuarios']);
Route::post('/guardar-usuarios', [AuthController::class, 'guardarUsuarios']);
Route::post('/modificar-usuarios/{id}', [AuthController::class, 'modificarUsuarios']);

//CLIENTES
Route::get('/clientes', [ClientesController::class, 'mostrarMantenimiento'])->name('clientes.mantenimiento');
Route::get('/listar-datosclientes', [ClientesController::class, 'listarDatosClientes'])->name('listar-datosclientes');
Route::get('/obtener-clientes', [ClientesController::class, 'obtenerClientess'])->name('obtener-clientes');
Route::get('/obtener-datosinputsclientes/{id}', [ClientesController::class, 'obtenerDatosinputsclientes']);
Route::get('/obtener-datosinputsclientesRUC/{id}', [ClientesController::class, 'obtenerDatosinputsclientesRUC']);
Route::post('/guardar-clientes', [ClientesController::class, 'guardarClientes']);
Route::post('/modificar-clientes/{id}', [ClientesController::class, 'modificarClientes']);
Route::get('/obtener-tipop', [ClientesController::class, 'obtenerTipop'])->name('obtener-tipop');
Route::get('/obtener-tipod', [ClientesController::class, 'obtenerTipod'])->name('obtener-tipod');
Route::get('/obtener-moneda', [ClientesController::class, 'obtenerMoneda'])->name('obtener-moneda');
Route::get('/obtener-departamento', [ClientesController::class, 'obtenerDepartamento'])->name('obtener-departamento');
Route::get('/obtener-provincia', [ClientesController::class, 'obtenerProvincia'])->name('obtener-provincia');
Route::get('/obtener-distrtito', [ClientesController::class, 'obtenerDistrito'])->name('obtener-distrito');
Route::get('/obtener-ubigeo', [ClientesController::class, 'obtenerUbigeo'])->name('obtener-ubigeo');
Route::get('/buscar-ruc/{ruc}', [ClientesController::class, 'buscarRuc'])->name('buscar-ruc');
Route::get('/buscar-dni/{dni}', [ClientesController::class, 'buscarDni'])->name('buscar-dni');

//CONDICION DE VENTA
Route::get('/condicion', [CondicionController::class, 'mostrarMantenimiento'])->name('condicion.mantenimiento');
Route::get('/listar-condicion', [CondicionController::class, 'listarDatosCondicion'])->name('listar-condicion');
Route::get('/obtener-condicion', [CondicionController::class, 'obtenerCondicion'])->name('obtener-condicion');
Route::get('/obtener-datosinputscondicion/{id}', [CondicionController::class, 'obtenerDatosinputscondicion']);
Route::post('/guardar-condicion', [CondicionController::class, 'guardarCondicion']);
Route::post('/modificar-condicion/{id}', [CondicionController::class, 'modificarCondicion']);

//PROVEEDORES
Route::get('/proveedor', [ProveedorController::class, 'mostrarMantenimiento'])->name('proveedor.mantenimiento');
Route::get('/listar-datosproveedor', [ProveedorController::class, 'listarDatosProveedor'])->name('listar-datosproveedor');
Route::get('/obtener-proveedor', [ProveedorController::class, 'obtenerdatosProveedor'])->name('obtener-proveedor');
Route::get('/obtener-datosinputsproveedor/{id}', [ProveedorController::class, 'obtenerDatosinputsproveedor']);
Route::post('/guardar-proveedor', [ProveedorController::class, 'guardarProveedor']);
Route::post('/modificar-proveedor/{id}', [ProveedorController::class, 'modificarProveedor']);

//TIPOCAMBIO
Route::get('/tipocambio', [TipocambioController::class, 'mostrarMantenimiento'])->name('tipocambio.mantenimiento');
Route::get('/listar-datostipocambio', [TipocambioController::class, 'listarDatosTipocambio'])->name('listar-datostipocambio');
Route::get('/obtener-tipocambio', [TipocambioController::class, 'obtenerdatosTipocambio'])->name('obtener-tipocambio');
Route::get('/obtener-datosinputstipocambio/{id}', [TipocambioController::class, 'obtenerDatosinputstipocambio']);
Route::post('/guardar-tipocambio', [TipocambioController::class, 'guardarTipocambio']);
Route::post('/modificar-tipocambio/{id}', [TipocambioController::class, 'modificarTipocambio']);
Route::get('/consultawebTC', [TipoCambioController::class, 'consultawebTC'])->name('consultawebTC');
Route::post('/validarDatos', [TipocambioController::class, 'validarDatos'])->name('validarDatos');
Route::get('/tipocambio/consulta/{fecha}', [TipocambioController::class, 'mostrarConsulta'])->name('tipocambio.consulta');

//EVENTOS
Route::post('/guardar-evento', [EventoController::class, 'guardarEvento']);
Route::post('/modificar-evento/{id}', [EventoController::class, 'modificarEvento']);
Route::get('/obtener-eventos', [EventoController::class, 'obtenerEventos'])->name('obtener-eventos');
Route::post('/descartar-evento/{id}', [EventoController::class, 'descartarEvento']);
Route::get('/verificar-evento-descartado/', [EventoController::class, 'verificarEventoDescartado']);

//MOV ALMACEN 
Route::get('/notaingreso', [MovalmacenController::class, 'mostrarMantenimiento'])->name('notaingreso.mantenimiento');
Route::post('/guardar-notaingreso', [MovalmacenController::class, 'guardardeNotaingreso']);
Route::get('/mostrarRegistros', [MovalmacenController::class, 'mostrarRegistrosIngreso'])->name('listar-mostrarRegistros');
Route::get('/buscarRegistro/{id}', [MovalmacenController::class, 'buscarregistroIngreso'])->name('buscar-registroingreso');
Route::get('/buscarDetalle/{id}', [MovalmacenController::class, 'buscardetalleIngreso'])->name('buscar-detalleingreso');
Route::post('/anularRegistroMov/{id}', [MovalmacenController::class, 'anularRegistrosMovAlmacen'])->name('anular-Registros');

//COTIZACIONES 
Route::get('/cotizaciones', [VentasCotizacionController::class, 'mostrarMantenimiento'])->name('cotizaciones.mantenimiento');
Route::post('/guardar-cotizacion', [VentasCotizacionController::class, 'guardarCotizaciones']);
Route::get('/mostrarRegistrosCot', [VentasCotizacionController::class, 'mostrarRegistrosCotizaciones'])->name('listar-mostrarRegistrosCotizacion');
Route::get('/buscarRegistroCot/{id}', [VentasCotizacionController::class, 'buscarregistroCotizacion'])->name('buscar-registrocotizacion');
Route::get('/buscarDetalleCot/{id}', [VentasCotizacionController::class, 'buscardetalleCotizaciones'])->name('buscar-detallecot');
Route::post('/anularRegistro/{id}',  [VentasCotizacionController::class, 'anularRegistro'])->name('anularRegistro');

Route::get('/imprimir-reporte/{id}', [ReporteController::class, 'generarReporte'])->name('imprimir.reporte');
Route::get('/imprimir-reportepedido/{id}', [ReporteController::class, 'generarReportePedido'])->name('imprimir.reportepedido');
Route::get('/imprimir-reporteguia/{id}', [ReporteController::class, 'generarReporteGuia'])->name('imprimir.reporteguia');

//NOTA DE PEDIDO
Route::get('/notapedido', [VentasPedidosController::class, 'mostrarNotapedidoMantenimiento'])->name('notapedido.mantenimiento');
Route::post('/guardar-pedidos', [VentasPedidosController::class, 'guardarPedidos']);
Route::get('/mostrarRegistrosPedidos', [VentasPedidosController::class, 'mostrarRegistrosPedidos'])->name('listar-mostrarRegistrosPedidos');
Route::get('/buscarRegistroPedido/{id}', [VentasPedidosController::class, 'buscarregistroPedido'])->name('buscar-registroPedido');
Route::get('/buscarDetallePedido/{id}', [VentasPedidosController::class, 'buscardetallePedido'])->name('buscar-detallePedido');
Route::post('/anularRegistro/{id}',  [VentasPedidosController::class, 'anularRegistro'])->name('anularRegistro');

//GUIA DE REMISION
Route::get('/guiaremision', [VentasGuiaremisionController::class, 'mostrarGuiaremisionMantenimiento'])->name('guiaremision.mantenimiento');
Route::post('/guardar-guiaremision', [VentasGuiaremisionController::class, 'guardarGuiaremision']);
Route::get('/mostrarRegistrosGuia', [VentasGuiaremisionController::class, 'mostrarRegistrosGuia'])->name('listar-mostrarRegistrosGuia');
Route::get('/buscarRegistroGuia/{id}', [VentasGuiaremisionController::class, 'buscarregistroGuia'])->name('buscar-registroGuia');
Route::get('/buscarDetalleGuia/{id}', [VentasGuiaremisionController::class, 'buscardetalleGuia'])->name('buscar-detalleGuia');
Route::post('/anularRegistroGuia/{id}',  [VentasGuiaremisionController::class, 'anularRegistroGuia'])->name('anularRegistroGuia');
Route::get('/obtener-datosinputsTransportistaId/{id}', [VentasGuiaremisionController::class, 'buscarregistroTransportista']);
Route::get('/registroguiaremision', [VentasGuiaremisionController::class, 'mostrarRegistroGuia'])->name('registroguiaremision.mantenimiento');
Route::get('/listar-registroguiaremision', [VentasGuiaremisionController::class, 'listarregistroguiaremision'])->name('listar-registroguiaremision');

//COMPROBANTES
Route::get('/comprobantes', [VentasComprobantesController::class, 'mostrarComprobantesMantenimiento'])->name('comprobantes.mantenimiento');
Route::post('/guardar-comprobantes', [VentasComprobantesController::class, 'guardarComprobantes']);
//NOTA DE CREDITO
Route::get('/notacredito', [VentasComprobantesController::class, 'mostrarNotacreditoMantenimiento'])->name('notacredito.mantenimiento');

//NOTA DE DEBITO
Route::get('/notadebito', [VentasComprobantesController::class, 'mostrarNotadebitoMantenimiento'])->name('notadebito.mantenimiento');

Route::post('/enviar-documento-por-correo', [CorreoController::class, 'enviarDocumentoPorCorreo']);