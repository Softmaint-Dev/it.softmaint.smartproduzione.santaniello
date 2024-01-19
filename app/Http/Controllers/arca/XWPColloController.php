<?php

namespace App\Http\Controllers\arca;

use App\Http\Controllers\Controller;
use App\Models\PRBLAttivita;
use App\Models\PRBLMateriale;
use App\Models\XWPCollo;
use Illuminate\Http\Request;

class XWPColloController extends Controller
{
     function xWPColloByAttivita($id) {
        $attivita =  XWPCollo::where('id_prblattivita', $id)->get();;
        
         if (!$attivita) {
             return response()->json(['error' => 'Modello non trovato'], 404);
         }
 
          $data = $attivita->toArray();
 
          array_walk_recursive($data, function (&$value) {
             if (is_string($value)) {
                 $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
             }
         });

         return response()->json($data);

     }
}
