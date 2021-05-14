<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Convertion;

class ExchangeController extends Controller
{
    public function index() {
        $convertions = Convertion::orderBy('created_at', 'DESC')->paginate(10);
        return $convertions->toJson();
    }

    public function create(Request $request){
        $convertion = new Convertion();
        $convertion->currency_from = $request->currency_from;
        $convertion->currency_to = $request->currency_to;
        $convertion->cambio = $request->cambio;
        $index = $convertion->save();

        if (! $index) {
            return response()->json([
                'result' => false,
                'message' => 'Ocorreu um erro no gravação de conversão',
            ]);
        }
        return response()->json([
            'result' => true,
            'message' => 'Conversão gravada com sucesso',
        ]);
    }
}
