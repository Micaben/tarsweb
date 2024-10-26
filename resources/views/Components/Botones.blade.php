{{-- resources/views/components/botones.blade.php --}}
<div>
    {{-- Botón Nuevo --}}
    @if($showNuevo ?? true) {{-- Este botón se mostrará por defecto a menos que se especifique lo contrario --}}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group" style="margin-top: 10px;">
            <button class="btn btn-primary btn-icon-split btn-sm" onclick="modalNuevoRegistro()">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Nuevo</span>
            </button>
        </div>
    @endif

    {{-- Botón Buscar --}}
    {{-- Botón Buscar --}}
    @if($showBuscar ?? true)
        <button class="btn btn-primary btn-icon-split btn-sm" style="margin-top: 10px;" id="btnBuscar" data-toggle="modal"
            data-target="#modal-mostrar-registros" data-columns='@json($columns)' {{-- Convertir array a JSON --}}
            data-url="{{ $url }}" data-route-name="{{ $routeName }}" data-routedetalle-name="{{ $routeDetalleName }}"
            data-inputs='@json($inputs)' {{-- Convertir array a JSON --}} data-input-mapping='@json($inputMapping)' {{--
            Convertir array a JSON --}} data-message-id="mensajeAnulado">
            <span class="icon text-white-50">
                <i class="fas fa-search"></i>
            </span>
            <span class="text">Buscar</span>
        </button>
    @endif

    {{-- Botón Guardar --}}
    @if($showGuardar ?? true)
        <div class="btn-group" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary btn-icon-split btn-sm" id="btnRegistrar" name="btnRegistrar">
                <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                <span class="text">Guardar</span>
            </button>
        </div>
    @endif

    {{-- Botón Imprimir --}}
    @if($showImprimir ?? true)
        <div class="btn-group" style="margin-top: 10px;">
            <button class="btn btn-primary btn-icon-split btn-sm" id="btnImprimir" name="btnImprimir"
                type="button">
                <span class="icon text-white-50"><i class="fas fa-print"></i></span>
                <span class="text">Imprimir</span>
            </button>
        </div>
    @endif

    @if($showTransporte ?? true)
        <div class="btn-group" style="margin-top: 10px;">
            <button class="btn btn-primary btn-icon-split btn-sm" data-toggle="modal"
                data-target="#modal-agregar-transporte" data-blade-id="transporte"><span class="icon text-white-50"><i
                        class="fa fa-truck"></i></span><span class="text">Transporte</span></button>
        </div>
    @endif

    {{-- Botón Anular --}}
    @if($showAnular ?? true)
        <div class="btn-group" style="margin-top: 10px;">
        <button type="button" class="btn btn-primary btn-icon-split btn-sm" 
                id="btnAnular" >
            <span class="icon text-white-50"><i class="fas fa-times"></i></span>
            <span class="text">Anular</span>
        </button>
        </div>
    @endif

    {{-- Checkbox Opcional --}}
    @if($showCheckbox ?? false)
        <div class="form-check" style="margin-top: 10px;">
            <input class="form-check-input" type="checkbox" id="customCheckbox">
            <label class="form-check-label" for="customCheckbox">Opción adicional</label>
        </div>
    @endif

    {{-- Mensaje de Anulado --}}
    @if($showAnuladoMessage ?? true)
        <div id="mensajeAnulado" class="estilo-texto" style="display: none;">A N U L A D O</div>
    @endif
</div>