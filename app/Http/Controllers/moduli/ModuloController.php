<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

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

    public function removeDMS($id)
    {
        DmsDocument::where('column_name', '=', 'value')->delete();

        return true;
    }

    public function editDMS($activity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $activity);
        switch (str_replace(' ', '', $dms->xType)) {
            case 'efficienza':
                return redirect()->route('editEfficienza', ['id' => $id, 'activity' => $activity]);
                break;
            case 'granella':
                return redirect()->route('editGranella', ['id' => $id, 'activity' => $activity]);
                break;
            case 'raffina':
                return redirect()->route('editRaffinatrice', ['id' => $id, 'activity' => $activity]);
                break;
            case 'farina':
                return redirect()->route('editFarina', ['id' => $id, 'activity' => $activity]);
                break;
            case 'tostatura':
                return redirect()->route('editTostatura', ['id' => $id, 'activity' => $activity]);
                break;
            case 'XRAY400N':
                return redirect()->route('edit400N', ['id' => $id, 'activity' => $activity]);
                break;
            case 'XRAYBR6000':
                return redirect()->route('editBR6000', ['id' => $id, 'activity' => $activity]);
                break;
            case 'sortex':
                return redirect()->route('editSortex', ['id' => $id, 'activity' => $activity]);
                break;
            case '2':
                $dms->xType = '1';
                break;
        }

        return response("");

    }


    public function showDMS($id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);

        return response($dms->Content)
            ->header('Content-Type', 'application/pdf');

    }

    public function edit($Id_DmsDocument, $binaryPDF, $json)
    {
        try {
            $dms = DmsDocument::find($Id_DmsDocument);
            if ($dms) {
                $dms->Content = $binaryPDF;
                //$dms->FileSize = strlen($binaryPDF);
                $dms->xJSON = $json;

                $dms->save();
            }
        } catch (Exception $e) {
            dd($e);
            return false;
        }
        return true;
    }

    public function createDMS($binaryPDF, $descrizione, $fileName, $dotes, $date, $json, $type)
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
            $dms->xJSON = $json;
            $dms->xType = $type;
            $dms->save();


        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }
        return true;
    }
}
