<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\xDmsFolder;
use App\Models\PRBLAttivita;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Client;

class ModuloController extends Controller
{
    public function getDMS($id)
    {
        /*
        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $dms = $dotes->dms();*/
        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('EntityId', $id);
        $dms = $dms->dms();

        return new JsonResponse($dms);
    }

    public function deleteDMS($id)
    {
        /* non funziona */
        $dms = xDmsFolder::firstWhere('EntityId', $id);
        $bolla = $dms->EntityId;
        try {
            $dms_for_folder = xDmsFolder::find($dms->Id_xDmsFolder);
            if (file_exists('upload/' . $dms_for_folder->EntityId . '/' . $dms_for_folder->Descrizione . ' - ' . $dms_for_folder->Id_xDmsFolder . '.pdf')) {
                unlink('upload/' . $dms_for_folder->EntityId . '/' . $dms_for_folder->Descrizione . ' - ' . $dms_for_folder->Id_xDmsFolder . '.pdf');
            }
            DmsDocument::where('Id_DmsDocument', '=', $dms->Id_DmsDocument)->delete();
            xDmsFolder::where('Id_xDmsFolder', '=', $dms->Id_xDmsFolder)->delete();

        } catch (Exception $e) {
            dd($e);
            return false;
        }
        return '<script type="text/javascript"> top.location.href = "/dettaglio_bolla/' . $bolla . '" </script>';
    }

    public function editDMS($activity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('Id_xDmsFolder', $id);

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
            case 'PMO':
                return redirect()->route('editMDPMO', ['id' => $id, 'activity' => $activity]);
                break;
            case 'MBR1200':
                return redirect()->route('editMBR1200', ['id' => $id, 'activity' => $activity]);
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
        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('Id_xDmsFolder', $id);

        return response($dms->Content)
            ->header('Content-Type', 'application/pdf');

    }

    public function edit($Id_DmsDocument, $binaryPDF, $json)
    {
        try {
            $dms = DmsDocument::find($Id_DmsDocument);
            /* SOSTITUISCO LA VECCHIA GESTIONE */
            $dms = xDmsFolder::find($Id_DmsDocument);
            if ($dms) {
                $dms->Content = $binaryPDF;
                //$dms->FileSize = strlen($binaryPDF);
                $dms->xJson = $json;

                $dms->save();
            }
        } catch (Exception $e) {
            dd($e);
            return false;
        }
        try {
            $dms = xDmsFolder::find($Id_DmsDocument);
            $dms = DmsDocument::find($dms->Id_DmsDocument);
            /* SOSTITUISCO LA VECCHIA GESTIONE */
            if ($dms) {
                $dms->Content = $binaryPDF;
                //$dms->FileSize = strlen($binaryPDF);
                $dms->xJson = $json;

                $dms->save();
            }
        } catch (Exception $e) {
            dd($e);
            return true;
        }
        $client = new Client();
        $dms_for_folder = xDmsFolder::find($Id_DmsDocument);
        if (!is_dir('upload/' . $dms_for_folder->EntityId)) {
            mkdir('upload/' . $dms_for_folder->EntityId);
        }
        $response = $client->request('GET', 'http://192.168.1.210:8081/moduli/show/' . $dms_for_folder->Id_xDmsFolder);
        $body = $response->getBody();
        file_put_contents('upload/' . $dms_for_folder->EntityId . '/' . $dms_for_folder->Descrizione . ' - ' . $dms_for_folder->Id_xDmsFolder . '.pdf', $body);

        return true;
    }

    public function createDMS($id, $binaryPDF, $descrizione, $fileName, $dotes, $date, $json, $type)
    {
        $dataAttuale = Carbon::now();
        $error = 0;

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
            $error = 1;
        }

        if ($error == 0)
            $id_dmsdoc = $dms->Id_DmsDocument;
        else $id_dmsdoc = 0;
        try {
            $dms = new xDmsFolder();
            $dms->Descrizione = $descrizione;
            $dms->FileName = $fileName;
            $dms->Content = $binaryPDF;
            $dms->EntityTable = 'PRBLAttivita';
            $dms->EntityId = intval($id);
            $dms->Id_DmsDocument = $id_dmsdoc;
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
        $client = new Client();
        $dms_for_folder = xDmsFolder::find($dms->Id_xDmsFolder);
        if (!is_dir('upload/' . $dms_for_folder->EntityId)) {
            mkdir('upload/' . $dms_for_folder->EntityId);
        }
        $response = $client->request('GET', 'http://192.168.1.210:8081/moduli/show/' . $dms_for_folder->Id_xDmsFolder);
        $body = $response->getBody();
        file_put_contents('upload/' . $dms_for_folder->EntityId . '/' . $dms_for_folder->Descrizione . ' - ' . $dms_for_folder->Id_xDmsFolder . '.pdf', $body);

        return true;
    }
}
