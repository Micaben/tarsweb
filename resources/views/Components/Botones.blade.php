{{-- Botón Nuevo --}}
@if($showNuevo ?? true)
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group mr-2" style=" margin-top: 10px;">
        <button type="button" class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Nuevo</span>
        </button>
    </div>
@endif

{{-- Botón Buscar --}}
@if($showBuscar ?? true)
    <div class="btn-group mr-2">
        <button class="btn btn-primary btn-icon-split btn-sm" type="button" style="margin-top: 10px;" id="btnBuscar"
            data-toggle="modal" data-target="#modal-mostrar-registros" data-columns='@json($columns)' {{-- Convertir array a
            JSON --}} data-url="{{ $url }}" data-route-name="{{ $routeName }}"
            data-routedetalle-name="{{ $routeDetalleName }}" data-inputs='@json($inputs)' {{-- Convertir array a JSON --}}
            data-input-mapping='@json($inputMapping)' {{-- Convertir array a JSON --}} data-message-id="mensajeAnulado">
            <span class="icon text-white-50">
                <i class="fas fa-search"></i>
            </span>
            <span class="text">Buscar</span>
        </button>
    </div>
@endif

{{-- Botón Guardar --}}
@if($showGuardar ?? true)
    <div class="btn-group mr-2" style="margin-top: 10px;">
        <button type="submit" class="btn btn-primary btn-icon-split btn-sm" id="btnRegistrar" name="btnRegistrar">
            <span class="icon text-white-50"><i class="fas fa-save"></i></span>
            <span class="text">Guardar</span>
        </button>
    </div>
@endif

{{-- Botón Imprimir --}}
@if($showImprimir ?? true)
    <div class="btn-group mr-2" style="margin-top: 10px;">
        <button class="btn btn-primary btn-icon-split btn-sm" type="button" id="btnImprimir" name="btnImprimir">
            <span class="icon text-white-50"><i class="fas fa-print"></i></span>
            <span class="text">Imprimir</span>
        </button>
    </div>
@endif

{{-- Botón Transporte --}}
@if($showTransporte ?? true)
    <div class="btn-group mr-2" style="margin-top: 10px;">
        <button data-input="proveedor" data-iddocumento="iddeldocumento" type="button"
            class="btn btn-primary btn-icon-split btn-sm" data-toggle="modal" data-target="#modal-agregar-transporte"
            data-blade-id="transporte"><span class="icon text-white-50"><i class="fa fa-truck"></i></span><span
                class="text">Transporte</span></button>
    </div>
@endif

{{-- Botón Cuotas --}}
@if($showCuotas ?? true)
    <div class="btn-group mr-2" style="margin-top: 10px;">
        <button type="button" data-input="proveedor" class="btn btn-primary btn-icon-split btn-sm" data-toggle="modal"
            data-target="#modal-mostrar-cuotas" data-blade-id="transporte" id="btnCuotas" name="btnCuotas"><span
                class="icon text-white-50"><i class="far fa-file"></i></span><span class="text">Cuotas</span></button>
    </div>
@endif

{{-- Botón Anular --}}
@if($showAnular ?? true)
    <div class="btn-group mr-2" style="margin-top: 10px;">
        <button class="btn btn-primary btn-icon-split btn-sm" type="button" id="btnAnular" name="btnAnular">
            <span class="icon text-white-50"><i class="fas fa-times"></i></span>
            <span class="text">Anular</span>
        </button>
    </div>
@endif

{{-- Checkbox Cortesia --}}
@if($showCortesia ?? true)
    <div class="ml-auto" style="margin-right: 5px;">
        <div style="text-align: right;"
            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            <input type="hidden" name="iscortesia" value="0">
            <input type="checkbox" class="custom-control-input" id="iscortesia" name="iscortesia">
            <label class="custom-control-label" for="iscortesia">Cortesia?</label>
        </div>
    </div>
@endif

{{-- Checkbox Retencion --}}
@if($showRetencion ?? true)
    <div class="ml-auto" style="margin-right: 5px;">
        <div style="text-align: right;"
            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            <input type="hidden" name="conretencion" value="0">
            <input type="checkbox" class="custom-control-input" id="conretencion" name="conretencion">
            <label class="custom-control-label" for="conretencion">Ag.
                Retencion</label>
        </div>
    </div>
@endif

{{-- Checkbox Kardex --}}
@if($showKardex ?? true)
    <div class="ml-auto" style="margin-right: 5px;">
        <div style="text-align: right;"
            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            <input type="hidden" name="iskardex" value="0">
            <input type="checkbox" class="custom-control-input" id="iskardex" name="iskardex" >
            <label class="custom-control-label" for="iskardex">Gen.
                Kardex?</label>
        </div>
    </div>
@endif

{{-- Checkbox In igv --}}
@if($showInigv ?? true)
    <div class="ml-auto" style="margin-right:10px;">
        <div style="text-align: right;"
            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            <input type="hidden" name="inigv" value="0">
            <input type="checkbox" class="custom-control-input" id="inigv" name="inigv" value="1" >
            <label class="custom-control-label" for="inigv">In igv?</label>
        </div>
    </div>
@endif

{{-- Mensaje de Anulado --}}
@if($showAnuladoMessage ?? true)
    <div id="mensajeAnulado" class="estilo-texto" style="display: none;">A N U L A D O</div>
@endif