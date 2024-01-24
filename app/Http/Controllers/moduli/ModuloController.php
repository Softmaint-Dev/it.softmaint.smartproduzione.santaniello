<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ModuloController extends Controller
{
    public function getDMS($id)
    {
        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $dms = $dotes->dms();

        return new JsonResponse($dms);
    }

    public function removeDMS($id) {
        DmsDocument::where('column_name', '=', 'value')->delete();

        return true;
    }

    public function showDMS($id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);

        return response($dms->Content)
            ->header('Content-Type', 'application/pdf');
 
    }

    public function createDMS($binaryPDF, $descrizione, $fileName, $dotes, $date)
    {   
        $dataAttuale = Carbon::now();

        try {
            $dms = new DmsDocument();
            $dms->Descrizione = $descrizione;
            $dms->FileName = $fileName;
            $dms->Content = $binaryPDF;
            $dms->EntityTable = 'DOTES';
            $dms->EntityId = intval($dotes->Id_DoTes);
            $dms->Id_DmsClass1 = "1";
            $dms->Id_DmsClass2 = "4";
            $dms->DmsClass3 = "QLT";
            $dms->Cd_DmsType = "01";
            $dms->DocumentDate = $dataAttuale;
            $dms->LinkedToFS = "0";
            $dms->FileSize = strlen($binaryPDF);
            $dms->EntityDescription = "MODULO CALIBRATURA";
            $dms->ComputerName = "";
            $dms->FilePath = "";
            $dms->Note = $dotes->Id_DoTes . '';
            $dms->save();


        } catch (Exception $e) { 
            print_r($e);
            return false;
        }
        return true;
    }
}
