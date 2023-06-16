<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function productoSave(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_pro'=>'required|string|unique:productos,nombre_pro'
        ]);
        if($validator->fails()){
            return http::respuesta(http::retError,$validator->errors());
        }
        DB::beginTransaction();
        try{
            $producto = new Productos();
            $producto ->nombre_pro = $request->nombre_pro;
            $producto ->cantidad = $request->cantidad;
            $producto ->precio = $request->precio;
            $producto ->bodegas_id = $request->bodegas_id;
            $producto->save();
        }catch(\Throwable $th){
            DB::rollBack();
            http::respuesta(http::retError,['error en catch' => $th->getMessage()]);
        }
         DB::commit();
         return http::respuesta(http::retOK, "Producto guardado con exito");


    }

    public function productosNom_bodega(){
         $sql=DB::table('productos AS p')
                ->join('bodegas AS b', 'p.bodegas_id', '=', 'b.id')
                ->select('p.nombre_pro' ,'p.cantidad','p.precio','b.nombre_bo')
                ->get();
         return  $sql;
    }

    public function bodegaTres(){
        $bodega=DB::table('productos AS p')
                ->join('bodegas AS b', 'p.bodegas_id', '=', 'b.id')
                ->select('p.nombre_pro','b.id')
                ->where('b.id','=','3')
                ->get();
                return $bodega;
    }

    public function productoBo($nombre_pro){


        $nombreBo = DB::table('bodegas AS b')
                ->join('productos AS p','b.id','=','p.bodegas_id')
                ->select('b.nombre_bo')
                ->where('p.nombre_pro','=',$nombre_pro)
                ->first();//si es get me devuelve un arreglo, si es first solo devuelve el json

                if(!$nombre_pro){
                    return response()->json(['mensaje'=>'no se encuentra ese producto']);
                }
        return $nombreBo;

    }
}
