<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\Bodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BodegaController extends Controller
{
    public function obtenerBodegas() {
        $bodegas = Bodega::all();
        if (count($bodegas) == 0) {
            return Http::respuesta(http::retNotFound, "No hay bodegas");
        }
        return http::respuesta(http::retOK, $bodegas);
    }

    public function obtenerBodegaId($id){
        $idBodega = Bodega::find($id);
        if (!$idBodega) {
            return http::respuesta(http::retNotFound, "No se encontro el ID de labodega");
        }
        return http::respuesta(http::retOK, $idBodega);
    }

    public function guardarBodega(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_bo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return http::respuesta(http::retError, $validator->errors());
        }

        DB::beginTransaction();
        try {
            $bodega = new Bodega();
            $bodega->nombre_bo = $request->nombre_bo;
            $bodega->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            http::respuesta(http::retError, ['error en cacth' => $th->getMessage()]);
        }
        DB::commit();
        return http::respuesta(http::retOK, "Bodega guardada con exito");
    }
}
