<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
   public function listaClientes(Request $request){
     $cliente = Cliente::all($request);
     
     return ;
   }
}
