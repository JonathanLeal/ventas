<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\Bodega;
use Illuminate\Http\Request;

class BodegaController extends Controller
{
    public function obtenerBodegas() {
        $bodegas = Bodega::all();
        if (count($bodegas) == 0) {
            return Http::respuesta(http::retNotFound, "No hay bodegas");
        }
        return http::respuesta(http::retOK, $bodegas);
    }
}
