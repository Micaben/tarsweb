<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Repositorios
{
    public static function spUpdateNumero($empresa, $td, $serie, $numero, $len)
    {
        try {
            $resultados = DB::select('CALL spp_SetNumeroDoc(?, ?, ?, ?, ?)', [
                $empresa,
                $td,
                $serie,
                $numero,
                $len
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

    public static function spUpdateSumStock($idalmacen, $item, $empresa, $almacen, $producto, $cantidad, $cantidadp)
    {
        try {
            $resultados = DB::select('CALL sp_UpDate_StockActual(?, ?, ?, ?, ?, ?, ?)', [
                $idalmacen,
                $item,
                $empresa,
                $almacen,
                $producto,
                $cantidad,
                $cantidadp
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

    public static function spRestoreStock($id, $table, $columns, $tablajoin,  $isNegative = true)
    {
        
        $colId = 'id';
        $colItem = $columns['item'];
        $colProducto = $columns['producto'];
        $colCantidad = $columns['cantidad'];
        $colAlmacen = $columns['almacen'];
        $colEmpresa = $columns['empresa'];

        $productos = DB::select("
        SELECT d.$colId AS id, d.$colItem AS item, d.$colProducto AS producto, d.$colCantidad AS cantidad, d.$colAlmacen AS almacen, c.$colEmpresa AS empresa
        FROM $table d
        INNER JOIN $tablajoin c ON d.id = c.id
        WHERE d.$colId = ?", [$id]);

        foreach ($productos as $producto) {
            $cantidad = $isNegative ? -$producto->cantidad : $producto->cantidad;
            DB::statement('CALL sp_UpDate_StockActual(?, ?, ?, ?, ?, ?, ?)', [
                $producto->id,
                $producto->item,
                $producto->empresa,
                $producto->almacen,
                $producto->producto,
                $cantidad,
                0
            ]);
        }
    }

    public static function eliminaDet($table, $id)
    {
        try {
            DB::beginTransaction();

            DB::table($table)->where('id', $id)->delete();

            DB::commit();
            return ['success' => true, 'message' => 'Registro eliminado correctamente'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function anularRegistros($id, $usuario)
    {
        $mensaje = '';

        try {
            DB::beginTransaction();
            Log::info('Llamando a spu_AnularMovCabAlmacen con id: ' . $id . ' y usuario: ' . $usuario);

            // Llamar al procedimiento almacenado
            DB::statement('CALL spu_AnularMovCabAlmacen(?, ?, @rsp_Mensaje)', [$id, $usuario]);
            Log::info('Procedimiento almacenado llamado.');
            // Obtener el mensaje de respuesta
            $result = DB::select('SELECT @rsp_Mensaje as mensaje');
            if (!empty($result)) {
                $mensaje = $result[0]->mensaje;
                Log::info('Mensaje del SP: ' . $mensaje);
            } else {
                Log::info('No se recibió ningún mensaje del SP.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en anularRegistros: ' . $e->getMessage());
            throw new \Exception('Error al anular el registro: ' . $e->getMessage());
        }

        return $mensaje;
    }

    public static function upRestaStock(array $detalles, int $id)
    {
        DB::beginTransaction();

        try {
            foreach ($detalles as $material) {
                // Ejecutar el procedimiento almacenado
                DB::statement('CALL sp_UpDate_StockActual(?, ?, ?, ?, ?, ?, ?)', [
                    $id,
                    $material['item'],         // Asumimos que este es el 'item' del producto
                    $material['empresa'],      // La empresa del producto
                    $material['idalmacen'],    // El id del almacén
                    $material['producto'],     // El código o ID del producto
                    -$material['cantidad'],    // Restamos la cantidad
                    0                          // Parámetro final (0) como en el método Java
                ]);
            }
            DB::commit(); // Confirma la transacción

        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer si ocurre un error
            throw $e; // Lanza la excepción para manejarla externamente si es necesario
        }
    }

    
}