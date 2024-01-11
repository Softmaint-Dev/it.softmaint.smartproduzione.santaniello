<?php

namespace App\Http\Controllers\arca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CF;

class CFController extends Controller
{
    function findAll(Request $request)
    {


        $paramDescr = $request->input('q');
        $customers = null;
        if ($paramDescr != null) {
           $customers = CF::where('descrizione', 'like', '%' . $paramDescr . '%')
                ->where('TipoCF', 'C')
                ->orderByDesc('Id_CF')
                ->take(100)
                ->get();

        } else {
            $customers = CF::orderByDesc('Id_CF')
            ->where('TipoCF', 'C')
            ->take(100)
            ->get();

        }
        
        $toReturn = $customers->map(function ($customer) {
            return array_map('utf8_encode', $customer->toArray());
        });

        return response()->json(['data' => $toReturn]);
    }

}
