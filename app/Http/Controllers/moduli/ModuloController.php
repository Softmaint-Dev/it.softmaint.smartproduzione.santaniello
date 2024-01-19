<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        print_r("=====");
        print_r($dotes);
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
            $dms->DocumentDate = $date;
            $dms->LinkedToFS = "0";
            $dms->FileSize = strlen($binaryPDF);
            $dms->EntityDescription = "MODULO CALIBRATURA";
            $dms->ComputerName = "";
            $dms->FilePath = "";
            $dms->Note = $dotes->Id_DoTes . '';
            $dms->save();


        } catch (Exception $e) {
            print_r($binaryPDF);
            // print_r($descrizione);
            // print_r($fileName);
            // print_r($dotes);
            // print_r($date);
            print_r($e);
            return false;
        }
        return true;
    }
}
