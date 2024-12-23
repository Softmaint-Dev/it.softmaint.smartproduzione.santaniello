<?php

namespace App\Http\Controllers\moduli;


use App\Models\xDmsFolder;
use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\Dorig;
use App\Models\Dotes;
use App\Models\PRBLAttivita;
use App\Models\PROLAttivita;
use App\Models\PROLDoRig;
use App\Models\PRRLAttivita;
use App\Models\XWPCollo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FarinaController extends Controller
{


    public function createView($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.farina.farina_create', [
            'attivity' => $attivity
        ]);
    }


    function create(Request $request, $id)
    {


        $prblAttivita = PRBLAttivita::with(['materiale.ar', 'materiale.arlotto'])->firstWhere('id_prblattivita', $id);
        $prblMateriale = $prblAttivita->materiale;
        $ar = $prblMateriale[0]->ar;
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $cf = $dotes->cf;
        $dms = $dotes->dms();
        $data = $request->all();
        $qty = number_format($prblAttivita->Quantita, 2, '.', '');


        $layout = file_get_contents(public_path('pdf/farina.html'));



        $refactoring = array(
            '[VARIETA]' => $data['variety'],
            '[LOTTO]' => $data['xwpCollo'],
            '[CALIBRO]' => $data['caliber'],
            '[CLIENTE]' => $cf->Descrizione,
            '[TOTAL_KG]' => number_format($prblAttivita->Quantita, 2),
            '[DATE]' => $data['simpleDate'],
            '[TIME]' => $data['analysisTime'],
            '[SAMPLE]' => $data['sample'],
            '[UMIDITA]' => $data['moisture'],
            '[COLOR_NATURAL]' => isset($data['colorNatural'] ) ? "X" : "",
            '[COLOR_ROASTED]' => isset($data['colorRoasted'] ) ? "" : "",
            '[TAS_NATURAL]' => isset($data['tasNatural']) ? "X" : "",
            '[TAS_ROASTED]' => isset($data['tasRoasted']) ? "" : "",
            '[ANALISYS_%]' => $data['analisys_calibratura'],
            '[VALUE_1]' => $data['value1'],
            '[VALUE_2]' => $data['value2'],
            '[VALUE_3]' => $data['value3'],
            '[VALUE_TOT]' => $data['valueTot'],
            '[VALUE_1_PERCENTAGE]' => $data['value1Percentage'],
            '[VALUE_2_PERCENTAGE]' => $data['value2Percentage'],
            '[VALUE_3_PERCENTAGE]' => $data['value3Percentage'],
            '[VALUE_TOT_PERCENTAGE]' => $data['valueTotPercentage'],
            '[OBSERVATIONS]' => $data['observations'],
            '[SAMPLE_CALIBRATURA]' => $data['sampleCalibratura'],
            '[USER]' => ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome)
        );

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHtml($html);

        $binaryPDF = $pdf->output();

        $data['USER'] = ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome);
        $data['TOTAL_KG'] = number_format($prblAttivita->Quantita, 2);
        $data['CLIENTE'] = $cf->Descrizione;


        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS($id,
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO FARINA',
                "farina.pdf",
                $dotes,
                date("Y-m-d H:i:s"),
                json_encode($data),
                'farina'
            );

        if ($complete) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }


    public function editView($idActivity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);

        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('Id_xDmsFolder', $id);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        return view('moduli.farina.farina_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJson),
            'id' => $id,
        ]);
    }

    public function edit($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);

        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('Id_xDmsFolder', $id);
        $oldJson = json_decode($dms->xJson);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        $data = $request->all();

        $pdf = App::make('dompdf.wrapper');
        $layout = file_get_contents(public_path('pdf/farina.html'));

        $refactoring = array(
            '[VARIETA]' => $data['variety'],
            '[LOTTO]' => $data['xwpCollo'],
            '[CALIBRO]' => $data['caliber'],
            '[CLIENTE]' => $oldJson->CLIENTE,
            '[TOTAL_KG]' => number_format(floatval($oldJson->TOTAL_KG), 2),
            '[DATE]' => $data['simpleDate'],
            '[TIME]' => $data['analysisTime'],
            '[SAMPLE]' => $data['sample'],
            '[UMIDITA]' => $data['moisture'],
            '[COLOR_NATURAL]' => $data['colorNatural'],
            '[COLOR_ROASTED]' => $data['colorRoasted'],
            '[TAS_NATURAL]' => $data['tasNatural'],
            '[TAS_ROASTED]' => $data['tasRoasted'],
            '[ANALISYS_%]' => $data['analisys_calibratura'],
            '[VALUE_1]' => $data['value1'],
            '[VALUE_2]' => $data['value2'],
            '[VALUE_3]' => $data['value3'],
            '[VALUE_TOT]' => $data['valueTot'],
            '[VALUE_1_PERCENTAGE]' => $data['value1Percentage'],
            '[VALUE_2_PERCENTAGE]' => $data['value2Percentage'],
            '[VALUE_3_PERCENTAGE]' => $data['value3Percentage'],
            '[VALUE_TOT_PERCENTAGE]' => $data['valueTotPercentage'],
            '[OBSERVATIONS]' => $data['observations'],
            '[SAMPLE_CALIBRATURA]' => $data['sampleCalibratura'],
            '[USER]' => $oldJson->USER,
        );
        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');

        $data['CLIENTE'] = $oldJson->CLIENTE;
        $data['USER'] = $oldJson->USER;
        $data['TOTAL_KG'] = $oldJson->TOTAL_KG;

        $binaryPDF = $pdf->output();

        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->edit($id, DB::raw("0x" . bin2hex($binaryPDF)), json_encode($data));

        if ($complete) {
            return Redirect::to('dettaglio_bolla/' . $idActivity);
        }

        return response('errore!!');

        //edit($id, $binaryPDF, $json)
        //return Redirect::to('dettaglio_bolla/' . $idActivity);
    }
}
