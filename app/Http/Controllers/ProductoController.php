<?php

namespace App\Http\Controllers;


use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function mostrarMantenimiento()
    {
        return view('productos');
    }

    public function listarTodos()
    {
        $productos = DB::table('productos')
            ->join('linea', 'productos.idLinea', '=', 'linea.id')
            ->join('color', 'productos.idColor', '=', 'color.id')
            ->select([
                'productos.id',
                'productos.codproducto',
                'productos.descripcion',
                'linea.descripcion as linea_des',
                'color.descripcion as color_des'
            ])
            ->get();
        return response()->json($productos);
    }

    public function listarInactivos()
    {
        $productos = DB::table('productos')
            ->join('linea', 'productos.idLinea', '=', 'linea.id')
            ->join('color', 'productos.idColor', '=', 'color.id')
            ->select([
                'productos.id',
                'productos.codproducto',
                'productos.descripcion',
                'linea.descripcion as linea_des',
                'color.descripcion as color_des'
            ])
            ->where('activo', 0)
            ->get();
        return response()->json($productos);
    }

    public function listarActivos()
    {
        $productos = DB::table('productos')
            ->join('linea', 'productos.idLinea', '=', 'linea.id')
            ->leftjoin('color', 'productos.idColor', '=', 'color.id')
            ->leftjoin('unidadmed', 'productos.idumedida', '=', 'unidadmed.umedida')
            ->select([
                'productos.id',
                'productos.codproducto',
                'productos.descripcion',
                'linea.descripcion as linea_des',
                'color.descripcion as color_des',
                'unidadmed.umedida as unidad_des',
                'productos.ancho'
            ])
            ->where('activo', 1)
            ->get();
        return response()->json($productos);
    }

    public function guardarProductos(Request $request)
    {
        try {
            $request->validate([
                'naturaleza' => 'required',
                'idlinea' => 'required',
                'idsublinea' => '',
                'codigo' => 'required',
                'descripcion' => 'required',
                'rucproveedor' => 'required',
                'codigosunat' => '',
                'serie' => '',
                'barras' => '',
                'idumedida' => '',
                'idmarca' => '',
                'idcolor' => '',
                'afectacion' => '',
                'principio' => '',
                'vencimiento' => '',
                'ancho' => '',
                'peso' => '',
                'precio' => '',
                'cantidad' => '',
                'saldo' => '',
                'usercrea' => '',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo de reglas para la imagen

            ]);


            $nombreUsuario = Auth::user()->name;
            $producto = new Producto;
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');

                // Generar un nombre único para la imagen
                $nombreOriginal = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                $nombreImagen = time() . '_' . Str::slug($nombreOriginal) . '.' . $imagen->getClientOriginalExtension();

                // Almacenar la imagen
                $imagen->storeAs('public/productos', $nombreImagen);

                // Asignar la referencia de la imagen al modelo
                $producto->nomimagen = $nombreImagen;

            }
            $producto->iDCategoria = $request->input('naturaleza');
            $producto->iDLinea = $request->input('idlinea');
            $producto->iDSublinea = $request->input('idsublinea');
            $producto->codProducto = $request->input('codigo');
            $producto->descripcion = $request->input('descripcion');
            $producto->codproveedor = $request->input('rucproveedor');
            $producto->codSunat = $request->input('codigosunat');
            $producto->serie = $request->input('serie');
            $producto->codbarras = $request->input('barras');
            $producto->idUmedida = $request->input('idumedida');
            $producto->iDMarca = $request->input('idmarca');
            $producto->idColor = $request->input('idcolor');
            $producto->tipoAfectacionIGV = $request->input('afectacion');
            $producto->principioAct = $request->input('principio');
            $producto->fechaVencimiento = $request->input('vencimiento');
            $producto->ancho = $request->input('ancho');
            $producto->peso = $request->input('peso');
            $producto->precioVenta = $request->input('precio');
            $producto->cantidadMinima = $request->input('cantidad');
            $producto->saldoActual = $request->input('saldo');
            $producto->usuarioReg = $nombreUsuario;
            $producto->activo = $request->has('estado');
            $producto->servicio = $request->has('servicio');
            $producto->afecto = $request->has('afecto');

            // Guardar en la base de datos
            $existingProduct = Producto::where('codproducto', $producto->codProducto)->first();


            if (!$existingProduct) {
                // No existe, entonces se puede realizar la inserción
                $producto->save();
                $id = $producto->id;

                return response()->json(['success' => true, 'id' => $id]);
            } else {
                // Ya existe un registro con el mismo valor
                return response()->json(['error' => 'Ya existe un producto con el mismo código.'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarProductos(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'naturaleza' => 'required',
                'idlinea' => 'required',
                'idsublinea' => 'required',
                'codigo' => 'required',
                'descripcion' => 'required',
                'rucproveedor' => 'required',
                'codigosunat' => '',
                'serie' => '',
                'barras' => '',
                'idumedida' => '',
                'idmarca' => '',
                'idcolor' => '',
                'afectacion' => '',
                'principio' => '',
                'vencimiento' => '',
                'ancho' => '',
                'peso' => '',
                'precio' => '',
                'cantidad' => '',
                'saldo' => '',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo de reglas para la imagen

            ]);

            $nombreUsuario = Auth::user()->name;
            $producto = Producto::find($id);
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                // Generar un nombre único para la imagen
                $nombreOriginal = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                $nombreImagen = time() . '_' . Str::slug($nombreOriginal) . '.' . $imagen->getClientOriginalExtension();
                // Almacenar la imagen
                $imagen->storeAs('public/productos', $nombreImagen);
                // Asignar la referencia de la imagen al modelo
                $producto->nomimagen = $nombreImagen;

            }
            // Verificar si el registro existe
            if (!$producto) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $producto->iDCategoria = $request->input('naturaleza');
            $producto->iDLinea = $request->input('idlinea');
            $producto->iDSublinea = $request->input('idsublinea');
            $producto->codProducto = $request->input('codigo');
            $producto->descripcion = $request->input('descripcion');
            $producto->codproveedor = $request->input('rucproveedor');
            $producto->codSunat = $request->input('codigosunat');
            $producto->serie = $request->input('serie');
            $producto->codbarras = $request->input('barras');
            $producto->idUmedida = $request->input('idumedida');
            $producto->iDMarca = $request->input('idmarca');
            $producto->idColor = $request->input('idcolor');
            $producto->tipoAfectacionIGV = $request->input('afectacion');
            $producto->principioAct = $request->input('principio');
            $producto->fechaVencimiento = $request->input('vencimiento');
            $producto->ancho = $request->input('ancho');
            $producto->peso = $request->input('peso');
            $producto->precioVenta = $request->input('precio');
            $producto->cantidadMinima = $request->input('cantidad');
            $producto->saldoActual = $request->input('saldo');
            $producto->usuarioReg = $nombreUsuario;
            $producto->activo = $request->has('estado');
            $producto->servicio = $request->has('servicio');
            $producto->afecto = $request->has('afecto');
            $producto->save();
            $id = $producto->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsproductos($id)
    {
        $datos = Producto::find($id);
        return response()->json($datos);
    }

    public function obtenerProductosporvencer()
    {
        try {
            $productos = DB::table('productos as a')
                ->select('a.codproducto', 'a.descripcion', 'a.idumedida', 'a.fechavencimiento', 'st.saldoactual', DB::raw("DATEDIFF(CURDATE(), a.fechavencimiento) AS diasrestantes"))
                ->leftjoin('stockactual as st', 'st.cod_producto', '=', 'a.codproducto')
                ->leftJoin('color as c', 'a.idcolor', '=', 'c.id')
                ->leftJoin('unidadmed as u', 'u.umedida', '=', 'a.id')
                ->where('st.saldoactual', '>', 0)
                ->where('a.Activo', 1)
                ->whereRaw('DATEDIFF(CURDATE(), a.fechavencimiento) < 30')
                ->get();

            $totalProductos = $productos->count();

            return response()->json([
                'productos' => $productos,
                'totalProductos' => $totalProductos
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            \Log::error('Error al intentar obtener la lista de productos para eventos: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listarStockProductos($empresa, $usuario = null, $producto = null, $descripcion = null, $activo)
    {
        try {
            $usuario = $usuario ?: '';
            $producto = $producto ?: '';
            $descripcion = $descripcion ?: '';

            $resultados = DB::select('CALL ABListaProductos(?, ?, ?, ?, ?)', [
                $empresa,
                $usuario,
                $producto,
                $descripcion,
                $activo
            ]);
            if (count($resultados) > 0) {
                return response()->json($resultados); // Asegúrate de devolver los resultados completos
            } else {
                return response()->json(['error' => 'No se encontraron datos'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error al listar stock de productos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la solicitud'], 500);
        }
    }
}
