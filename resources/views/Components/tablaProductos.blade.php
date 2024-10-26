{{-- resources/views/components/productos.blade.php --}}
<div class="card">
    <div class="table-responsive">
        <table
            class="table table-bordered table-striped dataTable table-hover clase_table table-sm"
            id="tablaProductos" class="display">
            <thead class="thead-light">
                <tr>
                    <th style="width: 40px; text-align: center;">Item</th>
                    <th style="text-align: center;">Código</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;">U.M</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: center;">Precio</th>
                    <th style="text-align: center;">Total</th>
                    @if($showEliminar ?? true) {{-- Mostrar columna Eliminar opcionalmente --}}
                        <th style="width: 65px; text-align: center">Eliminar</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tablaIngresos">
                {{-- Aquí puedes agregar el código para mostrar dinámicamente los productos en la tabla --}}
            </tbody>
        </table>
    </div>
</div>

{{-- Footer para mostrar subtotal, IGV y total --}}
@if($showTotales ?? true)
    <div class="box-footer">
        <div class="col-sm-12">
            @if($showSubtotal ?? true)
                <label style="text-align: right" class="col-sm-9">
                    <strong>
                        <font size=4>Subtotal <label name="lblsubtotal" id="lblsubtotal"></label></font>
                    </strong>
                </label>
                <strong>
                    <font color="#1b5e20" size=4>
                        <input type="text" name="subtotal" id="subtotal"
                               style="text-align: right; width:130px; border: none;" readonly>
                    </font>
                </strong>
            @endif

            @if($showIGV ?? true)
                <label style="text-align: right" class="col-sm-9">
                    <strong>
                        <font size=4>IGV 18% <label name="lbligv" id="lbligv"></label></font>
                    </strong>
                </label>
                <strong>
                    <font color="#1b5e20" size=4>
                        <input type="text" name="igv" id="igv"
                               style="text-align: right; width:130px; border: none;" readonly>
                    </font>
                </strong>
            @endif

            @if($showTotal ?? true)
                <label style="text-align: right" class="col-sm-9">
                    <strong>
                        <font size=4>Total <label name="lblmoneda" id="lblmoneda"></label></font>
                    </strong>
                </label>
                <strong>
                    <font color="#1b5e20" size=4>
                        <input type="text" name="totalSuma" id="totalSuma"
                               style="text-align: right; width:130px; border: none;" readonly>
                    </font>
                </strong>
            @endif
        </div>
    </div>
@endif