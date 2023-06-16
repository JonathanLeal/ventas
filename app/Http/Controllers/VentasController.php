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

    public function clienteProducto($id){
        $idCliente=Cliente::find($id);
        if(!$idCliente){
            return response()->json(['mensaje'=>'El id no se encuentra']);
        }
        $sql=DB::table('ventas AS v')
                ->join('clientes AS c','v.cliente_id','=','c.id')
                ->join('productos AS p','v.producto_id','=','p.id')
                ->select('v.cliente_id','c.nombre_cli','v.cantidad','p.nombre_pro')
                ->where('c.id','=',$id)
                ->get();
                return $sql;
    }

    public function venta(Request $request){
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|integer',
            'ventas' => 'array',//indico que sea un arreglo
            'ventas.*.producto_id' => 'required|integer',//que en el arreglo ventas quiero el campo producto_id
            'ventas.*.cantidad' => 'required|integer'//cantidad es otro campo del arreglo
        ]);

        if ($validator->fails()) {
            return http::respuesta(http::retError, $validator->errors());
        }

        foreach ($request->ventas as $ven) {//que recorra el arreglo y lo llamo $ven
            $producto = Productos::find($ven['producto_id']);//que en el arreglo me encuentre el producto_id ya existente
            $venta = new Ventas();
            $venta->cliente_id = $request->cliente_id;
            $venta->cantidad = $ven['cantidad'];//la cantidad que viene del arreglo $ven se almacena en cantidad de la instancia de venta que ya creé
            $venta->producto_id = $ven['producto_id'];
            $venta->save();

            $producto->cantidad -= $ven['cantidad'];//que reste a la cantidad de producto, la cantidad que viene del arreglo
            $producto->save();
        }

        return http::respuesta(http::retOK, "venta realizada con exito");
    }

   
}
