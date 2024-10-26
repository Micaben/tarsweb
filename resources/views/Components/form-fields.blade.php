<div class="form-row">

    @if($showIddeldocumento ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="iddeldocumento" name="iddeldocumento" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showDireccion ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="direccion" name="direccion" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showTipod ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="tipod" name="tipod" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showUbigeo ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="ubigeo" name="ubigeo" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showTipodocumento ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="tipodocumento" name="tipodocumento" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showCodafec ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="codafec" name="codafec" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showTipoperacion ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="tipoperacion" name="tipoperacion" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showTypecod ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="typecod" name="typecod" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showTipomoneda ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="tipomoneda" name="tipomoneda" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showMontoRetencion ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="mtoretencion" name="mtoretencion" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showInafecto ?? true)
        <div class="form-group col-sm-2 mb-2">
            <input id="inafecto" name="inafecto" type="text" class="form-control form-control-sm" readonly="">
        </div>
    @endif

    @if($showComprobante ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Comprobante:</label>
            <select class="form-control form-control-sm" required="" id="cbocomprobante" name="cbocomprobante">
            </select>
        </div>
    @endif

    @if($showSerie ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Serie:</label>
            <select class="form-control form-control-sm" required="" id="cboserie" name="cboserie"></select>
        </div>
    @endif

    @if($showNumero ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Numero:</label>
            <input id="numeronota" type="text" class="form-control form-control-sm" required="" name="numeronota"
                maxlength="8" readonly>
        </div>
    @endif

    @if($showFechaProceso ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Fecha de proceso:</label>
            <input id="fechaproceso" type="date" class="form-control form-control-sm" required="" name="fechaproceso"
                maxlength="10">
        </div>
    @endif

    @if($showConcepto ?? true)
        <div class="form-group col-sm-3 mb-2">
            <label class="my-0">Concepto:</label>
            <select class="form-control form-control-sm" required="" id="cboconcepto" name="cboconcepto"></select>
        </div>
    @endif
</div>
<div class="form-row">
    @if($showProveedor ?? true)
        <div class="form-group col-5 mb-2">
            <label class="my-0">Proveedor:</label>
            <select class="form-control form-control-sm" required="" id="cboproveedor" name="cboproveedor"></select>
        </div>
    @endif

    @if($showRuc ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Ruc:</label>
            <input id="proveedor" type="text" class="form-control form-control-sm" required="" name="proveedor" readonly
                maxlength="11">
        </div>
    @endif

    @if($showCondicion ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Condicion:</label>
            <select id="cbocondicion" class="form-control form-control-sm" name="cbocondicion"></select>
        </div>
    @endif

    @if($showPlazo ?? true)
        <div class="form-group col-md-1 mb-2">
            <label class="my-0">Plazo</label>
            <input type="text" id="plazo" class="form-control form-control-sm" name="plazo"
                oninput="actualizarFecha(this.value,'fechav')">
        </div>
    @endif

    @if($showFechav ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Fecha de V:</label>
            <input id="fechav" type="date" class="form-control form-control-sm" required="" name="fechav" maxlength="10">
        </div>
    @endif
</div>

<div class="form-row">
    @if($showVendedor ?? true)
        <div class="form-group col-sm-3">
            <label class="my-0">Vendedor:</label>
            <select class="form-control form-control-sm" id="cbovendedor" name="cbovendedor"></select>
        </div>
    @endif

    @if($showMoneda ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Moneda:</label>
            <select class="form-control form-control-sm" id="cbomoneda" name="cbomoneda"
                onchange="cambiaMoneda('lblmoneda', this.value)"></select>
        </div>
    @endif

    @if($showOrdenCompra ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">O/Compra:</label>
            <input id="ordencompra" type="text" class="form-control form-control-sm" name="ordencompra" maxlength="20"
                value="">
        </div>
    @endif

    @if($showAlmacen ?? true)
        <div class="form-group col-sm-2">
            <label class="my-0">Almacen:</label>
            <select class="form-control form-control-sm" id="cboalmacen" name="cboalmacen"></select>
        </div>
    @endif

    @if($showComentarios ?? true)
        <div class="form-group col-sm-3">
            <label class="my-0">Comentarios:</label>
            <input id="comentarios" type="text" class="form-control form-control-sm" name="comentarios" maxlength="150"
                value="">
        </div>
    @endif
</div>

<div class="mb-2">
    @if($showAgregarProductos ?? true)
        <button type="button" id="btnAgregar" name="btnAgregar" value="Agregar"
            class="btn btn-outline-primary btn-sm custom-button" data-toggle="modal" data-target="#modal-agregar-producto"
            data-blade-id="ingreso">
            <span class="fa fa-plus"></span> Agregar Productos
        </button>
    @endif
</div>