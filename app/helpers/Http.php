<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Http
{

    const retOK       = 'OK';
    const retError    = "ERROR";
    const retDenyBot  = "NOTAUTH";
    const retNotFound = "NOTFOUND";

    public static function respuesta($resultado, $datos, $cache = false): \Illuminate\Http\JsonResponse
    {
        $codigo = 200;
        /*if (is_null($resultado)){
            $resultado = empty($datos) ? self::retError : self::retOK;
        }

        if ($resultado === self::retError) {
            $codigo = 200;
        }*/
        switch($resultado) {
            case self::retError:
                $codigo = 400;
                break;
            case self::retDenyBot:
                $codigo = 403;
                break;
            case self::retNotFound:
                $codigo = 404;
                break;
        }

        $response = response()->json([
            'resultado' => $resultado,
            'datos' => $datos,
            'entregado' => date('Y-m-d H:i:s e'),
        ], $codigo);

        if ($cache) {
            $response->header('Cache-Control', 'max-age=600, public');
        } else {
            $response->header('Cache-Control', 'no-store');
        }

        return $response;
    }
}