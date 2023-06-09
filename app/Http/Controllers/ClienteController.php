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
        $validator = Validator::make($request->all(), [
            'nombre_cli' => 'required|string|unique:clientes,nombre_cli'
        ]);
        if($validator->fails()){
            return http::respuesta(http::retError,$validator->errors());
        }

        DB::beginTransaction();
        try{
            $cliente = new Cliente();
            $cliente->nombre_cli = $request->nombre_cli;
            $cliente->save();
        } catch(\Throwable $th){
            DB::rollBack();
            http::respuesta(http::retError,['error en catch' => $th->getMessage()]);
        }
        DB::commit();
        return http::respuesta(http::retOK, "Bodega guardada con exito");

    }

    public function listaClientes(Request $request){
     $cliente = Cliente::all($request);

     return $cliente;
   }
}
