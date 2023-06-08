<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function clienteSave(Request $request){
        $cliente = new Cliente();
            $cliente->id = $request->id;
            $cliente->nombre_cli = $request->nombre_cli;
            $cliente->save();

            return $cliente;

    }

    public function listaClientes(Request $request){
     $cliente = Cliente::all($request);

     return $cliente;
   }
}
