<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function getDMS($id) {
        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita =  $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $dms = $dotes->dms();
 
 
    return new JsonResponse($dms);
    }
    
    public function showDMS($id) {
       $dms =  DmsDocument::firstWhere('Id_DmsDocument', $id);
 
        return response($dms->Content)
        ->header('Content-Type', 'application/pdf');
        print_r($dms);
        return response("");
    }
}
