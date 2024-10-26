<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Productos</title>

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
                        <h1 class="h3 mb-2 text-gray-800">Productos</h1>
                        <button class="btn btn-primary btn-icon-split btn-sm"
                            onclick="modalNuevoRegistro('modalProductos')"><span class="icon text-white-50"><i
                                    class="fas fa-plus"></i></span><span class="text">Nuevo</span></button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <select class="form-control form-control-sm bg-gray-100" id="seleccionProductos"
                                name="seleccionProductos">
                                <option value="1">Productos Activos</option>
                                <option value="2">Productos Inactivos</option>
                                <option value="3">Todos</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <div id="tablaProductos"></div>
                        </div>
                    </div>

                    <!--MODAL PRODUCTOS-->
                    <div class="modal fade" id="modalProductos" role="dialog" aria-labelledby="modalProductosLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormulario"></h6>
                                    </div>
                                    <form id="form-productosf" method="POST" action="{{ url('guardar-productos') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <input id="iddelproducto" name="iddelproducto" type="hidden" readonly="">
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Naturaleza:</label>
                                                    <select class="form-control form-control-sm" id="naturaleza"
                                                        name="naturaleza" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Linea:</label>
                                                    <i class="fas fa-plus-circle" id="lineadialog"
                                                        data-toggle="modal"></i>
                                                    <select class="form-control form-control-sm" id="idlinea"
                                                        name="idlinea" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Sublinea:</label>
                                                    <i class="fas fa-plus-circle" id="sublineadialog"
                                                        data-toggle="modal"></i>
                                                    <select class="form-control form-control-sm" id="idsublinea"
                                                        name="idsublinea">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Código:</label>
                                                    <input id="codigo" type="text" required
                                                        class="form-control form-control-sm" name="codigo"
                                                        maxlength="10">
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Descripción:</label>
                                                    <input id="descripcion" type="text"
                                                        class="form-control form-control-sm" name="descripcion"
                                                        maxlength="150" required value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">R.U.C:</label>
                                                    <input id="rucproveedor" type="text"
                                                        class="form-control form-control-sm" name="rucproveedor"
                                                        maxlength="12" required readonly="">
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Proveedor:</label>
                                                    <select class="form-control form-control-sm" id="proveedor" required
                                                        onchange="consulta()" name="proveedor">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Código sunat (UNSPSC):</label>
                                                    <input id="codigosunat" type="text"
                                                        class="form-control form-control-sm" name="codigosunat"
                                                        maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Numero de serie:</label>
                                                    <input id="serie" type="text" class="form-control form-control-sm"
                                                        name="serie" maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Código de barras:</label>
                                                    <input id="barras" type="text" class="form-control form-control-sm"
                                                        name="barras" maxlength="10" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Presentación:</label>
                                                    <i class="fas fa-plus-circle" id="umedidadialog"
                                                        data-toggle="modal"></i>
                                                    <select class="form-control form-control-sm" id="idumedida"
                                                        name="idumedida" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Marca:</label>
                                                    <i class="fas fa-plus-circle" id="marcadialog"
                                                        data-toggle="modal"></i>
                                                    <select class="form-control form-control-sm" id="idmarca"
                                                        name="idmarca">
                                                    </select>
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Color:</label>
                                                    <i class="fas fa-plus-circle" id="colordialog"
                                                        data-toggle="modal"></i>
                                                    </label>
                                                    <select class="form-control form-control-sm" id="idcolor"
                                                        name="idcolor">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Afectacion:</label>
                                                    <select class="form-control form-control-sm" id="afectacion"
                                                        name="afectacion">
                                                        <option value="G">GRAVADO</option>
                                                        <option value="I">INAFECTO</option>
                                                        <option value="E">EXONERADO</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Principio:</label>
                                                    <input id="principio" type="text"
                                                        class="form-control form-control-sm" name="principio"
                                                        maxlength="100" value="">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Fecha de V:</label>
                                                    <input id="vencimiento" type="date"
                                                        class="form-control form-control-sm" name="vencimiento"
                                                        maxlength="10" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Ancho:</label>
                                                    <input id="ancho" type="text" class="form-control form-control-sm"
                                                        name="ancho" maxlength="5" value="0.00">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Peso:</label>
                                                    <input id="peso" type="text" class="form-control form-control-sm"
                                                        name="peso" maxlength="5" value="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Precio:</label>
                                                    <input id="precio" type="number"
                                                        class="form-control form-control-sm" name="precio"
                                                        maxlength="10" value="0.00">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Cantidad:</label>
                                                    <input id="cantidad" type="text"
                                                        class="form-control form-control-sm" name="cantidad"
                                                        maxlength="10" value="0.00">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Saldo Actual:</label>
                                                    <input id="saldo" type="number" class="form-control form-control-sm"
                                                        name="saldo" maxlength="10" value="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Ultima modificacion:</label>
                                                    <input id="usercrea" type="text"
                                                        class="form-control form-control-sm" name="usercrea"
                                                        maxlength="50" readonly="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-2 ">
                                                    <div
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input" id="estado"
                                                            name="estado" checked>
                                                        <label class="custom-control-label" for="estado">Activo?</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-2">
                                                    <div
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input" id="afecto"
                                                            name="afecto">
                                                        <label class="custom-control-label" for="afecto">Afecto?</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-2">
                                                    <div
                                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input"
                                                            nme="servicio" id="servicio">
                                                        <label class="custom-control-label"
                                                            for="servicio">Servicio?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="previsualizacion" class="text-center">
                                                <img id="imagenPrevisualizada" src="" alt="Vista Previa de la Imagen"
                                                    style="display: none; max-width: 200px; max-height: 300px;">
                                            </div>
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="form-control form-control-sm custom-file-input" id="imagen"
                                                    name="imagen" onchange="mostrarImagenPrevisualizada(this)">
                                                <label class="custom-file-label">Elija una imagen</label>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevo" name="btnNuevo"
                                                    onclick="modalNuevoRegistro('modalProductos')"
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
                                                        class="text">Cancelar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalcolor" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioColor"></h6>
                                    </div>
                                    <form id="form-color" method="POST" action="{{ url('guardar-color') }}"
                                        autocomplete="off">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <input id="codigocolor" type="hidden" readonly name="codigocolor">
                                                <div class="form-group col-8 mb-2">
                                                    <label class="my-0">Descripcion:</label>
                                                    <input id="descripcioncolor" type="text" required autocomplete="off"
                                                        class="form-control form-control-sm" name="descripcioncolor">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="tablaColor"></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevocolor" name="btnNuevocolor"
                                                    onclick="modalNuevoRegistro('modalcolor')"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarcolor" name="btnRegistrarcolor"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Cancelar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalmarca" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioMarca"></h6>
                                    </div>
                                    <form id="form-marca" method="POST" action="{{ url('guardar-marca') }}"
                                        autocomplete="off">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <input id="codigomarca" type="hidden" readonly
                                                    class="form-control form-control-sm" name="codigomarca">
                                                <div class="form-group col-8 mb-2">
                                                    <label class="my-0">Descripcion:</label>
                                                    <input id="descripcionmarca" type="text" required autocomplete="off"
                                                        class="form-control form-control-sm" name="descripcionmarca">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="tablaMarca"></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevomarca" name="btnNuevomarca"
                                                    onclick="modalNuevoRegistro('modalmarca')"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarmarca" name="btnRegistrarmarca"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Cancelar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalumedida" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioUmedida"></h6>
                                    </div>
                                    <form id="form-umedida" method="POST" action="{{ url('guardar-unidad') }}"
                                        autocomplete="off">>
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <input id="codigoumedida" type="hidden" readonly name="codigoumedida">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">U. Medida:</label>
                                                    <input id="unidadmedida" type="text" class="form-control"
                                                        required="" name="unidadmedida" maxlength="4">
                                                </div>
                                                <div class="form-group col-9 mb-2">
                                                    <label class="my-0">Descripcion:</label>
                                                    <input id="descripcionumedida" type="text" required
                                                        autocomplete="off" class="form-control"
                                                        name="descripcionumedida">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Código sunat:</label>
                                                    <select class="form-control" id="umedida" name="umedida">
                                                    </select>
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Código F.E:</label>
                                                    <select class="form-control " id="umedidafe" name="umedidafe">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="tablaUmedida"></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevoumedida" name="btnNuevoumedida"
                                                    onclick="modalNuevoRegistro('modalumedida')"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarumedida"
                                                    name="btnRegistrarumedida"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Cancelar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.MODAL GUARDAR LINEA  -->
                    <div class="modal fade" id="modallinea" tabindex="-1" role="dialog"
                        aria-labelledby="tituloFormularioLinea" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioLinea"></h6>
                                    </div>
                                    <form id="form-linea" method="POST" autocomplete="off"
                                        action="{{ url('guardar-linea') }}">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <input id="codigolinea" type="hidden" class="form-control"
                                                    name="codigolinea" readonly maxlength="4">
                                                <div class="form-group col-8 mb-2">
                                                    <label class="my-0">Linea:</label>
                                                    <input id="descripcionlinea" type="text" autocomplete="off"
                                                        class="form-control " name="descripcionlinea" maxlength="100"
                                                        value="" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Cuenta Ventas:</label>
                                                    <input id="cuentaventas" type="text" class="form-control"
                                                        name="cuentaventas" maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Cuenta Compras:</label>
                                                    <input id="cuentacompras" type="text" class="form-control"
                                                        name="cuentacompras" maxlength="10" value="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-4 mb-2">
                                                    <label class="my-0">Costo Ventas:</label>
                                                    <input id="costoventas" type="text" class="form-control"
                                                        name="costoventas" maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-sm-4 mb-2">
                                                    <label class="my-0">Cuenta Mercaderia:</label>
                                                    <input id="mercaderia" type="text" class="form-control"
                                                        name="mercaderia" maxlength="10" value="">
                                                </div>
                                                <div class="form-group col-sm-4 mb-2">
                                                    <label class="my-0">Cuenta Existencia:</label>
                                                    <input id="existencia" type="text" class="form-control"
                                                        name="existencia" maxlength="10" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="tablaLinea"></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevo" name="btnNuevo"
                                                    onclick="modalNuevoRegistro('modallinea')"
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
                                                        class="text">Cancelar</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.MODAL GUARDAR SUBLINEA  -->
                    <div class="modal fade" id="modalsublinea" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Change modal-lg to desired size: modal-sm, modal-md, modal-lg, modal-xl -->
                            <div class="modal-content">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloFormularioSublinea">
                                        </h6>
                                    </div>
                                    <form id="form-sublinea" method="POST" autocomplete="off"
                                        action="{{ url('guardar-sublinea') }}">
                                        @csrf
                                        <div class="card-body">
                                            <input id="codigosublinea" type="hidden" class="form-control"
                                                name="codigosublinea" readonly="">
                                            <div class="form-group mb-2">
                                                <label class="my-0">Linea:</label>
                                                <select class="form-control" name="cbolinea" id="cbolinea"
                                                    onchange="cargartablaSublinea(this.value)">
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 mb-2">
                                                    <label class="my-0">Sublinea:</label>
                                                    <input id="descripcionsublinea" type="text" required
                                                        class="form-control custom-button" name="descripcionsublinea"
                                                        maxlength="100" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Cuenta Ventas:</label>
                                                    <input id="sublineacuentaventas" type="text"
                                                        class="form-control custom-button" name="sublineacuentaventas"
                                                        maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Cuenta Compras:</label>
                                                    <input id="sublineacuentacompras" type="text"
                                                        class="form-control custom-button" name="sublineacuentacompras"
                                                        maxlength="10" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Costo Ventas:</label>
                                                    <input id="sublineacostoventas" type="text"
                                                        class="form-control custom-button" name="sublineacostoventas"
                                                        maxlength="10" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                                <div class="form-group col-3 mb-2">
                                                    <label class="my-0">Cuenta Mercaderia:</label>
                                                    <input id="sublineamercaderia" type="text" class="form-control"
                                                        name="sublineamercaderia" maxlength="10" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="tablaSublinea"></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group">
                                                <button type="button" id="btnNuevosub" name="btnNuevosub"
                                                    onclick="modalNuevoRegistro('modalsublinea')"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-plus"></i></span><span
                                                        class="text">Nuevo</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="submit" id="btnRegistrarsub" name="btnRegistrarsub"
                                                    class="btn btn-primary btn-icon-split btn-sm"><span
                                                        class="icon text-white-50"><i
                                                            class="fas fa-save"></i></span><span
                                                        class="text">Guardar</span></button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-icon-split btn-sm"
                                                    data-dismiss="modal"><span class="icon text-white-50"><i
                                                            class="fas fa-times"></i></span><span
                                                        class="text">Cancelar</span></button>
                                            </div>
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
    <script src="{{ asset('js/mensajes.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de columnas
        function inicializarTabla(url, columnConfig, tablaId, modalid) {
            hacerConsulta(url, columnConfig, tablaId, modalid);
        }

        $(document).ready(function () {
            cargartablaProductos();
            listarOpciones("#naturaleza", "{{ route('obtener-naturaleza') }}", 'CodCategoria', 'Descripcion', true);
            listarOpciones("#idlinea", "{{ route('obtener-lineas') }}", 'id', 'Descripcion', true);
            listarOpciones("#idcolor", "{{ route('listar-datoscolor') }}", 'id', 'descripcion', true);
            listarOpciones("#idumedida", "{{ route('listar-datosumedida') }}", 'umedida', 'descripcion', true);
            listarOpciones("#idmarca", "{{ route('listar-datosmarca') }}", 'id', 'descripcion', true);
            listarOpciones("#proveedor", "{{ route('listar-datosproveedor') }}", 'proveedor', 'nombres', true);
        });

        function mostrarModalEdicion(fila, modalid) {
            if (modalid === 'modalcolor') {
                $("#tituloFormularioColor").html("Modificar color");
                $('#codigocolor').val(fila.id);
                $('#descripcioncolor').val(fila.descripcion);
                $('#' + modalid).modal('show');
            } else if (modalid === 'modalmarca') {
                $("#tituloFormularioMarca").html("Modificar marca");
                $('#codigomarca').val(fila.id);
                $('#descripcionmarca').val(fila.descripcion);
                $('#' + modalid).modal('show');
            } else if (modalid === 'modalumedida') {
                $("#tituloFormularioUmedida").html("Modificar Unidad de medida");
                $('#codigoumedida').val(fila.id);
                if (fila.id) {
                    $.ajax({
                        url: '/obtener-datos/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            $('#unidadmedida').val(response.Umedida);
                            $('#descripcionumedida').val(response.Descripcion);
                            $('#umedida').val(response.CodSunat);
                            $('#umedidafe').val(response.cod_int);
                            $('#' + modalid).modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                } else {
                    console.log('La fila no contiene datos.');
                }
            } else if (modalid === 'modallinea') {
                $("#tituloFormularioLinea").html("Modificar linea");
                $('#codigolinea').val(fila.id);
                $('#descripcionlinea').val(fila.descripcion);
                $('#cuentaventas').val(fila.ctaVentas);
                $('#cuentacompras').val(fila.ctaCompras);
                $('#mercaderia').val(fila.ctaMercaderias);
                $('#costoventas').val(fila.costoVentas);
                $('#existencia').val(fila.ctaExistencias);
                $('#' + modalid).modal('show');
            } else if (modalid === 'modalsublinea') {
                if (fila.id) {
                    $.ajax({
                        url: '/obtener-datosinputssublineas/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            $('#codigosublinea').val(response.id);
                            $('#cbolinea').val(response.CodigoLinea);
                            $('#descripcionsublinea').val(response.Descripcion);
                            $('#sublineacuentaventas').val(response.CuentaVentas);
                            $('#sublineacuentacompras').val(response.CuentaCompras);
                            $('#sublineacostoventas').val(response.CuentaMerca);
                            $('#sublineamercaderia').val(response.CostVentas);
                            $("#tituloFormularioSublinea").html("Modificar registro sublinea");
                            $('#' + modalid).modal('show');
                        },
                        error: function (error) {
                            console.log('Error al obtener datos:', error);
                        }
                    });
                }
            } else if (modalid === 'modalProductos') {
                if (fila.id) {
                    $.ajax({
                        url: '/obtener-datosinputsproductos/' + fila.id,
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            cargarSublineas(response.IDLinea);
                            $('#iddelproducto').val(response.id);
                            $('#naturaleza').val(response.IDCategoria);
                            $('#idlinea').val(response.IDLinea);
                            $('#idsublinea').val(response.IDSublinea);
                            $('#codigo').val(response.CodProducto);
                            $('#descripcion').val(response.Descripcion);
                            $('#rucproveedor').val(response.CodProveedor);
                            $('#proveedor').val(response.CodProveedor);
                            $('#codigosunat').val(response.CodSunat);
                            $('#serie').val(response.Serie);
                            $('#barras').val(response.CodBarras);
                            $('#idumedida').val(response.IdUMedida);
                            $('#idmarca').val(response.IDMarca);
                            $('#idcolor').val(response.IdColor);
                            $('#afectacion').val(response.TipoAfectacionIGV);
                            $('#principio').val(response.PrincipioAct);
                            $('#vencimiento').val(response.FechaVencimiento);
                            $('#ancho').val(response.Ancho);
                            $('#precio').val(response.PrecioVenta);
                            $('#peso').val(response.Peso);
                            $('#cantidad').val(response.cantidadMinima);
                            $('#saldo').val(response.SaldoActual);
                           
                            var urlImagen = '/storage/productos/' + encodeURIComponent(response.NomImagen);
                            var urlImagenPorDefecto = '/storage/productos/1717805254_oip.jpg'; 
                            console.log(urlImagen);

                            $('#imagenPrevisualizada').attr('src', urlImagen).on('error', function () {
                                console.log("La imagen no se encuentra en la ruta especificada. Usando imagen por defecto.");
                                $(this).attr('src', urlImagenPorDefecto);
                            }).show();
                            var fechaFormateada = moment(response.updated_at).format('DD/MM/YYYY HH:mm:ss');
                            $('#usercrea').val("por: " + response.UsuarioReg + "  el: " + fechaFormateada);
                            $('#estado').prop('checked', response.Activo);
                            $('#servicio').prop('checked', response.Servicio);
                            $('#afecto').prop('checked', response.Afecto);
                            $("#tituloFormulario").html("Modificar registro productos");
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
            if (modalid === 'modalProductos') {
                $("#tituloFormulario").html("Nuevo registro");
                clearFormInputsSelects('form-productosf', 'naturaleza');
                document.getElementById("idsublinea").innerHTML = "";
                $('#imagenPrevisualizada').attr('src', ''); 
                $("#estado").prop("checked", true);
                $("#servicio").prop("checked", false);
                $("#afecto").prop("checked", true);
                var fechaActual = new Date().toISOString().split('T')[0];
                document.getElementById('vencimiento').value = fechaActual;
                $('#modalProductos').modal('show');
            } else if (modalid === 'modalcolor') {
                $("#tituloFormularioColor").html("Nuevo registro");
                clearFormInputs('form-color', 'descripcioncolor');
                $('#modalcolor').modal('show');
            } else if (modalid === 'modalmarca') {
                $("#tituloFormularioMarca").html("Nuevo registro");
                clearFormInputs('form-marca', 'descripcionmarca');
                $('#modalmarca').modal('show');
            } else if (modalid === 'modalumedida') {
                $("#tituloFormularioUmedida").html("Nuevo registro");
                clearFormInputs('form-umedida', 'codigoumedida', 'descripcionumedida');
                $('#modalumedida').modal('show');
            } else if (modalid === 'modallinea') {
                $("#tituloFormularioLinea").html("Nuevo registro");
                clearFormInputs('form-linea', 'descripcionlinea');
                $('#modallinea').modal('show');
            } else if (modalid === 'modalsublinea') {
                $("#tituloFormularioSublinea").html("Nuevo registro");
                clearFormInputs('form-sublinea', 'descripcionsublinea');
                $('#modalsublinea').modal('show');
            }
        }

        function cargarSublineas(codigoLinea) {
            $.ajax({
                url: '/listar-datossublinea/' + codigoLinea,
                method: 'GET',
                success: function (data) {
                    $('#idsublinea').empty();
                    console.log(data);
                    $.each(data, function (key, value) {
                        $('#idsublinea').append('<option value="' + value.codsulinea + '">' + value.descripcion + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error al cargar sublíneas:', error);
                }
            });
        }

        $(document).ready(function () {
            $('#idlinea').on('change', function () {
                var codigoLinea = $(this).val();
                cargarSublineas(codigoLinea);
            });
        });

        $(document).ready(function () {
            // Función común para abrir un modal y realizar acciones específicas
            function abrirModal(config) {
                $(config.trigger).on("click", function () {
                    $(config.modal).modal("show");

                    $(config.tituloFormulario).html(config.titulo);
                    config.clearFunction(); // Llamar a la función para limpiar el formulario

                    if (config.cargarTablaFunction) {
                        config.cargarTablaFunction(); // Llamar a la función para cargar la tabla
                    } else if (config.listarOpcionesFunction) {
                        config.listarOpcionesFunction(); // Llamar a la función para listar opciones
                    }
                });

                $(config.modal).on("hidden.bs.modal", function () {
                    $("#modalProductos").modal("show");
                    // Restaurar la clase modal-open y el padding-right
                    $("body").addClass("modal-open");
                    $("body").css("padding-right", "15px"); // Ajusta este valor según sea necesario
                });
            }

            // Configuración para el diálogo de colores
            abrirModal({
                trigger: "#colordialog",
                modal: "#modalcolor",
                tituloFormulario: "#tituloFormularioColor",
                titulo: "Colores registrados",
                clearFunction: function () {
                    clearFormInputs('form-color', 'descripcioncolor');
                },
                cargarTablaFunction: cargartablaColor
            });

            // Configuración para el diálogo de líneas
            abrirModal({
                trigger: "#lineadialog",
                modal: "#modallinea",
                tituloFormulario: "#tituloFormularioLinea",
                titulo: "Lineas registradas",
                clearFunction: function () {
                    clearFormInputs('form-linea', 'descripcionlinea');
                },
                cargarTablaFunction: cargartablaLinea

            });

            // Configuración para el diálogo de sublíneas
            abrirModal({
                trigger: "#sublineadialog",
                modal: "#modalsublinea",
                tituloFormulario: "#tituloFormularioSublinea",
                titulo: "Sublíneas registradas",
                clearFunction: function () {
                    clearFormInputs('form-sublinea', 'descripcionsublinea');
                },
                listarOpcionesFunction: function () {
                    listarOpciones("#cbolinea", "{{ route('obtener-lineas') }}", 'id', 'Descripcion', true);
                }
            });

            // Configuración para el diálogo de marcas
            abrirModal({
                trigger: "#marcadialog",
                modal: "#modalmarca",
                tituloFormulario: "#tituloFormularioMarca",
                titulo: "Marcas registradas",
                clearFunction: function () {
                    clearFormInputs('form-marca', 'descripcionmarca');
                },
                cargarTablaFunction: cargartablaMarca
            });

            // Configuración para el diálogo de unidades de medida
            abrirModal({
                trigger: "#umedidadialog",
                modal: "#modalumedida",
                tituloFormulario: "#tituloFormularioUmedida",
                titulo: "Unidades de medida registradas",
                clearFunction: function () {
                    clearFormInputs('form-umedida', 'descripcionumedida');
                    listarOpciones("#umedida", "{{ route('obtener-Umedida') }}", 'cod', 'deascripcion', true);
                    listarOpciones("#umedidafe", "{{ route('obtener-Umedidafe') }}", 'cod', 'deascripcion', true);
                },
                cargarTablaFunction: cargartablaUmedida
                // Agrega las funciones adicionales necesarias para listar opciones
            });
        });

        function cargartablaProductos() {
            var columnConfigLaravel = [
                { name: "id", title: "id", type: "text", visible: false },
                { name: "codproducto", title: "<div style='text-align: center;'>Codigo</div>", type: "text", align: "left", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text" },
                { name: "linea_des", title: "<div style='text-align: center;'>Linea</div>", type: "text", align: "left", width: 150 },
                { name: "color_des", title: "<div style='text-align: center;'>Color</div>", type: "text", width: 80 },
            ];
            inicializarTabla("{{ route('listar-activos') }}", columnConfigLaravel, 'tablaProductos', 'modalProductos');
            $('#seleccionProductos').on('change', function () {
                var estado = $(this).val();
                var url;

                if (estado == 1) {
                    url = "{{ route('listar-activos') }}";
                } else if (estado == 2) {
                    url = "{{ route('listar-inactivos') }}";
                } else if (estado == 3) {
                    url = "{{ route('listar-todos') }}";
                }
                inicializarTabla(url, columnConfigLaravel, 'tablaProductos', 'modalProductos');
            });
        }

        function cargartablaLinea() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left" },
                { name: "ctaVentas", title: "<div style='text-align: center;'>Cta Ventas</div>", type: "text", className: "text-left" },
                { name: "ctaCompras", title: "<div style='text-align: center;'>Cta Compras</div>", type: "text", className: "text-left" },
                { name: "ctaMercaderias", title: "<div style='text-align: center;'>Cta Mercaderias</div>", type: "text", className: "text-left" },
                { name: "costoVentas", title: "<div style='text-align: center;'>Costo ventas</div>", type: "text", className: "text-left" },
                { name: "ctaExistencias", title: "<div style='text-align: center;'>Cta Existencias</div>", type: "text", className: "text-left" },
            ];
            inicializarTabla("{{ route('listar-datoslinea') }}", columnConfigLaravel, 'tablaLinea', 'modallinea');
        }

        function cargartablaSublinea(id) {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left" },
                { name: "cuentaVentas", title: "<div style='text-align: center;'>Cta Ventas</div>", type: "text", className: "text-left" },
                { name: "cuentaCompras", title: "<div style='text-align: center;'>Cta Compras</div>", type: "text", className: "text-left" },
                { name: "cuentaMerca", title: "<div style='text-align: center;'>Cta Mercaderias</div>", type: "text", className: "text-left" },
                { name: "costVenta", title: "<div style='text-align: center;'>Costo ventas</div>", type: "text", className: "text-left" },
            ];
            inicializarTabla("{{ url('listar-datossublinea') }}/" + id, columnConfigLaravel, 'tablaSublinea', 'modalsublinea');
            clearFormInputs('form-sublinea', 'descripcionsublinea');
        }

        function cargartablaColor() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", align: "left", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left", width: 60 },
            ];
            inicializarTabla("{{ route('listar-datoscolor') }}", columnConfigLaravel, 'tablaColor', 'modalcolor');
        }

        function cargartablaMarca() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", align: "left", width: 60 },
                { name: "descripcion", title: "<div style='text-align: center;'>Descripcion</div>", type: "text", align: "left", width: 60 },
            ];
            inicializarTabla("{{ route('listar-datosmarca') }}", columnConfigLaravel, 'tablaMarca', 'modalmarca');
        }

        function cargartablaUmedida() {
            var columnConfigLaravel = [
                { name: "id", title: "<div style='text-align: center;'>Codigo</div>", type: "text", width: 60 },
                { name: "umedida", title: "<div style='text-align: center;'>U Medida</div>", type: "text", align: "left" },
                { name: "descripcion", title: "<div style='text-align: center;'>Descrpicion</div>", type: "text", className: "text-left" },
            ];
            inicializarTabla("{{ route('listar-datosumedida') }}", columnConfigLaravel, 'tablaUmedida', 'modalumedida');
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
                            if (tablaId === 'tablaColor') {
                                cargartablaColor();
                                listarOpciones("#idcolor", "{{ route('listar-datoscolor') }}", 'Id', 'descripcion', true);
                            } else if (tablaId === 'tablaMarca') {
                                listarOpciones("#idmarca", "{{ route('listar-datosmarca') }}", 'id', 'descripcion', true);
                                cargartablaMarca();
                            } else if (tablaId === 'tablaUmedida') {
                                listarOpciones("#idumedida", "{{ route('listar-datosumedida') }}", 'id', 'descripcion', true);
                                cargartablaUmedida();
                            } else if (tablaId === 'tablaLinea') {
                                listarOpciones("#idlinea", "{{ route('listar-datoslinea') }}", 'id', 'descripcion', true);
                                cargartablaLinea();
                            } else if (tablaId === 'tablaSublinea') {
                                cargartablaSublinea(cbo);
                            } else if (tablaId === 'tablaProductos') {
                                cargartablaProductos();
                            }
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
                var cboValue = config.cboId ? document.getElementById(config.cboId).value : null;
                var action = id ? config.modificarUrl + '/' + id : config.guardarUrl;
                var successMessage = id ? 'Registro modificado correctamente' : 'Registro guardado correctamente';
                var errorMessage = 'Error al procesar la solicitud';

                console.log(id + "id");
                submitForm(action, successMessage, errorMessage, id, config.formId, config.codigoInputId, config.tablaId, cboValue);
            });
        }

        // Configuración para el formulario de colores
        gestionarFormulario({
            formId: 'form-color',
            codigoInputId: 'codigocolor',
            tablaId: 'tablaColor',
            modificarUrl: "{{ url('modificar-color') }}",
            guardarUrl: "{{ url('guardar-color') }}"
        });

        // Configuración para el formulario de marcas
        gestionarFormulario({
            formId: 'form-marca',
            codigoInputId: 'codigomarca',
            tablaId: 'tablaMarca',
            modificarUrl: "{{ url('modificar-marca') }}",
            guardarUrl: "{{ url('guardar-marca') }}"
        });

        // Configuración para el formulario de unidades de medida
        gestionarFormulario({
            formId: 'form-umedida',
            codigoInputId: 'codigoumedida',
            tablaId: 'tablaUmedida',
            modificarUrl: "{{ url('modificar-unidad') }}",
            guardarUrl: "{{ url('guardar-unidad') }}"
        });

        // Configuración para el formulario de líneas
        gestionarFormulario({
            formId: 'form-linea',
            codigoInputId: 'codigolinea',
            tablaId: 'tablaLinea',
            modificarUrl: "{{ url('modificar-linea') }}",
            guardarUrl: "{{ url('guardar-linea') }}"
        });

        // Configuración para el formulario de sublíneas
        gestionarFormulario({
            formId: 'form-sublinea',
            codigoInputId: 'codigosublinea',
            tablaId: 'tablaSublinea',
            cboId: 'cbolinea',
            modificarUrl: "{{ url('modificar-sublinea') }}",
            guardarUrl: "{{ url('guardar-sublinea') }}"
        });

        gestionarFormulario({
            formId: 'form-productosf',
            codigoInputId: 'iddelproducto',
            tablaId: 'tablaProductos',
            modificarUrl: "{{ url('modificar-productos') }}",
            guardarUrl: "{{ url('guardar-productos') }}"
        });

        function consulta() {
            document.getElementById("rucproveedor").value = document.getElementById("proveedor").value;
        }

        function formatErrorMessages(errors) {
            let formattedErrors = '<ul>';
            $.each(errors, function (key, value) {
                formattedErrors += '<li>' + value.join('</li><li>') + '</li>';
            });
            formattedErrors += '</ul>';
            return formattedErrors;
        }

        function mostrarImagenPrevisualizada(input) {
            var imagenPrevisualizada = document.getElementById('imagenPrevisualizada');
            var customFileLabel = document.querySelector('.custom-file-label');

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    imagenPrevisualizada.src = e.target.result;
                    imagenPrevisualizada.style.display = 'block';

                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        var maxWidth = 200; // Ancho máximo deseado
                        var maxHeight = 300; // Alto máximo deseado
                        var width = image.width;
                        var height = image.height;

                        if (width > maxWidth || height > maxHeight) {
                            if (width / maxWidth > height / maxHeight) {
                                height *= maxWidth / width;
                                width = maxWidth;
                            } else {
                                width *= maxHeight / height;
                                height = maxHeight;
                            }
                        }
                        imagenPrevisualizada.style.width = width + 'px';
                        imagenPrevisualizada.style.height = height + 'px';
                    };

                    // Mostrar el nombre de la imagen en el label
                    customFileLabel.textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Agrega la clase 'show' al submenu "Mantenimiento" para mantenerlo visible
            document.getElementById('collapsep').classList.add('show');
        });
    </script>
</body>

</html>