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
     $cliente = Cliente::all($request);//$cliente me almacena todo lo que viene del request
     if(count($cliente)==0){//si la catidad de clientes es igual a cero me envia un return de que no hay clientes
        return Http::respuesta(http::retNotFound,"No hay clientes");
     }

     return http::respuesta(http::retOK,$cliente);//me devuelve todos los clientes que mando el request
   }

   public function obtenerClienteId($id){//voy a obtener un cliente por su id
    $idCliente = Cliente::find($id);//$idCliente guarda el id que encontró en el modelo Cliente
    if(!$idCliente){//el signo ! quiere decir que es lo contrario, osea si no encontro ese idCliente, me devuelve un mensaje que no lo encontró
        return http::respuesta(http::retNotFound, "No se encontro el ID de cliente");
        }
        return http::respuesta(http::retOK,$idCliente);//devuelve el cliente encontrado
    }

    public function editarCliente(Request $request){
        $validator = Validator::make($request->all(),[//que me haga la validacion de lo que viene de request
            'nombre_cli'=>'required|string|unique:clientes, nombre_cli']);

            $idCliente = Cliente::find($request->id);//que encuentre un id en el request
            if(!$idCliente){//si no encuentra el id, me envia un mensaje de que el id no se encuentra
                return http::respuesta(http::retNotFound,"no se encuentra el id de cliente");
            }
            if($validator->fails()){//si encuentra fallos me devuelve un mensaje de error
                return http::respuesta(http::retError,$validator->errors());
            }
            DB::beginTransaction();//inicia una transaccion
            try{
                $idCliente->nombre_cli = $request->nombre_cli;//el nombre_cli del request q sea igual al nombre_cli que almacena $idCliente
                $idCliente->save();
            }catch(\Throwable $th){
                DB::rollBack();
                return http::respuesta(http::retError,['error en catch'=>$th->getMessage()]);
            }
            DB::commit();
            return http::respuesta(http::retOK,"Cliente editado con exito");
    }

}
