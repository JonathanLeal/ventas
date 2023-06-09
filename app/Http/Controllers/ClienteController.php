<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function clienteSave(Request $request){
        $validator = Validator::make($request->all(), [ //que en validator me almacene la validacion que hace de todo lo que viene del request
            'nombre_cli' => 'required|string|unique:clientes,nombre_cli'//que el campo nombre_cli sea requerido, que sea tipo string y que la tabla clientes y el campo nombre_cli sean unicos
        ]);
        if($validator->fails()){// si $validator encuentra fallos que me envie una respuesta del error que encontro
            return http::respuesta(http::retError,$validator->errors());
        }

        DB::beginTransaction();//inicia una transaccion, que intente guardar un nuevo cliente
        try{
            $cliente = new Cliente();
            $cliente->nombre_cli = $request->nombre_cli;
            $cliente->save();//si solo ocupo save guarda todo lo del array no importando si hay error
        } catch(\Throwable $th){
            DB::rollBack();//rollBack, que evalue lo del try, si hay error que envie un mensaje por medio de la variable $th
            http::respuesta(http::retError,['error en catch' => $th->getMessage()]);
        }
        DB::commit();//si no encontro error, el commit guarda todo en la base de datos, y devuelve un mensaje de guardado con exito.
        return http::respuesta(http::retOK, "Bodega guardada con exito");

    }

    public function listaClientes(Request $request){
     $cliente = Cliente::all($request);

     return $cliente;
   }
}
