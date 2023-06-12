<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\Cliente;
use App\Models\Productos;
use App\Models\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentasController extends Controller
{
    public function ventasProducto(Request $request){
       $validator = Validator::make($request->all(), [
            'cantidad' => 'required|integer',
            'producto_id' => 'integer',
            'cliente_id' => 'integer'
       ]);

       if ($validator->fails()) {
        return Http::respuesta(http::retError,$validator->errors());
       }

       $cliente = Cliente::find($request->cliente_id);
       if (!$cliente) {
         return http::respuesta(http::retNotFound, "no se encontro el cliente");
       }

       $producto = Productos::find($request->producto_id);

       Ventas::create([
        'cantidad' => $request->cantidad,
        'producto_id' => $request->producto_id,
        'cliente_id' => $request->cliente_id
       ]);

       $producto->cantidad -= $request->cantidad;//disminuye la cantidad de producto, es decir se actualiza la tabla ventas al guardar la cantidad de producto en esta tabla y se actualiza la cantidad en la tabla clintes
       $producto->save();//cant existente menos ella misma menos la cantidad del request

       return http::respuesta(http::retOK, "venta realizada exitosamente");
    }
}
