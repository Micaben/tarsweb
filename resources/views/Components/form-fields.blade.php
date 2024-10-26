<div class="row">
    @if($showIdDocumento ?? true)
        <input id="iddeldocumento" name="iddeldocumento" type="text" class="form-control form-control-sm" readonly="">
    @endif
    
    @if($showTipoDocumento ?? true)
        <input id="tipodocumento" name="tipodocumento" type="text" class="form-control form-control-sm" value="{{ $tipoDocumentoValue }}" readonly="">
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
            <input id="numeronota" type="text" class="form-control form-control-sm" required="" name="numeronota" maxlength="8" readonly>
        </div>
    @endif

    @if($showFechaProceso ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Fecha de proceso:</label>
            <input id="fechaproceso" type="date" class="form-control form-control-sm" required="" name="fechaproceso" maxlength="10">
        </div>
    @endif

    @if($showConcepto ?? true)
        <div class="form-group col-sm-3 mb-2">
            <label class="my-0">Concepto:</label>
            <select class="form-control form-control-sm" required="" id="cboconcepto" name="cboconcepto"></select>
        </div>
    @endif

    @if($showMoneda ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Moneda:</label>
            <select class="form-control form-control-sm" id="cbomoneda" name="cbomoneda" onchange="cambiaMoneda('lblmoneda', this.value)"></select>
        </div>
    @endif

    @if($showInIGV ?? true)
        <div class="ml-auto" style="margin-right: 5px;">
            <div style="text-align: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="inigv" name="inigv" checked>
                <label class="custom-control-label" for="inigv">In igv?</label>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if($showProveedor ?? true)
        <div class="form-group col-5 mb-2">
            <label class="my-0">Proveedor:</label>
            <select class="form-control form-control-sm" required="" id="cboproveedor" name="cboproveedor"></select>
        </div>
    @endif

    @if($showRuc ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Ruc:</label>
            <input id="proveedor" type="text" class="form-control form-control-sm" required="" name="proveedor" readonly maxlength="11">
        </div>
    @endif

    @if($showCondicion ?? true)
        <div class="form-group col-sm-3 mb-2">
            <label class="my-0">Condicion:</label>
            <select id="cbocondicion" class="form-control form-control-sm" name="cbocondicion"></select>
        </div>
    @endif

    @if($showValidez ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">Validez:</label>
            <input id="validez" class="form-control form-control-sm" name="validez">
        </div>
    @endif
</div>

<div class="row">
    @if($showVendedor ?? true)
        <div class="form-group col-sm-3">
            <label class="my-0">Vendedor:</label>
            <select class="form-control form-control-sm" id="cbovendedor" name="cbovendedor"></select>
        </div>
    @endif

    @if($showOrdenCompra ?? true)
        <div class="form-group col-sm-2 mb-2">
            <label class="my-0">O/Compra:</label>
            <input id="ordencompra" type="text" class="form-control form-control-sm" name="ordencompra" maxlength="20" value="">
        </div>
    @endif

    @if($showAlmacen ?? true)
        <div class="form-group col-sm-3">
            <label class="my-0">Almacen:</label>
            <select class="form-control form-control-sm" id="cboalmacen" name="cboalmacen"></select>
        </div>
    @endif

    @if($showComentarios ?? true)
        <div class="form-group col-sm-4">
            <label class="my-0">Comentarios:</label>
            <input id="comentarios" type="text" class="form-control form-control-sm" name="comentarios" maxlength="150" value="">
        </div>
    @endif
</div>

<div class="mb-2">
    @if($showAgregarProductos ?? true)
        <button type="button" id="btnAgregar" name="btnAgregar" value="Agregar" class="btn btn-outline-primary btn-sm custom-button" data-toggle="modal" data-target="#modal-agregar-producto" data-blade-id="ingreso">
            <span class="fa fa-plus"></span> Agregar Productos
        </button>
    @endif
</div>