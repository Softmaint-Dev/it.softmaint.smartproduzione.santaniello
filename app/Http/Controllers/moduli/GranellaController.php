<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\Dorig;
use App\Models\Dotes;
use App\Models\PRBLAttivita;
use App\Models\PROLAttivita;
use App\Models\PROLDoRig;
use App\Models\PRRLAttivita;
use App\Models\xDmsFolder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class GranellaController extends Controller
{


    public function createView($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.granella.granella_create', [
            'attivity' => $attivity
        ]);
    }


    function create(Request $request, $id)
    {


        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        if ($prolAttivita->prolDoRig != null) {
            $prolDorig = $prolAttivita->prolDoRig;
            $dorig = $prolDorig->dorig;
            $dotes = $dorig->dotes;
            $cf = $dotes->cf;
            $dms = $dotes->dms();
        }
        $data = $request->all();
        $pdf = App::make('dompdf.wrapper');
        $layout = file_get_contents(public_path('pdf/granella.html'));
        $utente = $request->session()->get("utente");


        $refactoring = array(
            '[VARIETA]' => $data['variety'],
            '[LOTTO]' => $data['xwpCollo'],
            '[CALIBRO]' => $data['calibre'],
            '[CLIENTE]' => (isset($cf)) ? $cf->Descrizione : '',
            '[TOTAL_KG]' => number_format($prblAttivita->Quantita, 2),
            '[DATE]' => $data['date'],
            '[TIME]' => $data['analysis'],
            '[SAMPLE]' => $data['sample'],
            '[SAMPLE_CALIBRATURA]' => $data['sampleCalibratura'],
            '[UMIDITA]' => $data['moisture'],
            '[SKIN]' => (filter_var($data['skin'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[TASTE]' => (filter_var($data['tastAndSmell'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[COLOUR]' => (filter_var($data['colour'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[OVERSIZE]' => $data['overSize'],
            '[OVERSIZE_PERCENTAGE]' => $data['overSizePercentage'],
            '[CALCULATION]' => $data['calculation'],
            '[CALCULATION_PERCENTAGE]' => $data['calculationPercentage'],
            '[UNDERSIZE]' => $data['underSize'],
            '[UNDERSIZE_PERCENTAGE]' => $data['underSizePercentage'],
            '[TOTAL]' => $data['total'],
            '[OBSERVATIONS]' => $data['observations'],
            '[USER]' => ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome)
        );

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);

        $binaryPDF = $pdf->output();

        $data['USER'] = ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome);
        $data['CLIENTE'] = (isset($cf)) ? $cf->Descrizione : '';
        $data['TOTAL_KG'] = number_format($prblAttivita->Quantita, 2);


        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS($id,
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO GRANELLA',
                "granella.pdf",
                $dotes,
                $data['date'],
                json_encode($data),
                "granella"
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

        return view('moduli.granella.granella_edit', [
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
        $layout = file_get_contents(public_path('pdf/granella.html'));
        $data['CLIENTE'] = $oldJson->CLIENTE;
        $data['TOTAL_KG'] = $oldJson->TOTAL_KG;
        $data['USER'] = $oldJson->USER;
        $refactoring = array(
            '[VARIETA]' => $data['variety'],
            '[LOTTO]' => $data['xwpCollo'],
            '[CALIBRO]' => $data['calibre'],
            '[CLIENTE]' => $oldJson->CLIENTE,
            '[TOTAL_KG]' => number_format(floatval($oldJson->TOTAL_KG), 2),
            '[DATE]' => $data['date'],
            '[TIME]' => $data['analysis'],
            '[SAMPLE]' => $data['sample'],
            '[SAMPLE_CALIBRATURA]' => $data['sampleCalibratura'],
            '[UMIDITA]' => $data['moisture'],
            '[SKIN]' => (filter_var($data['skin'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[TASTE]' => (filter_var($data['tastAndSmell'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[COLOUR]' => (filter_var($data['colour'], FILTER_VALIDATE_BOOLEAN) ? 'X' : ''),
            '[OVERSIZE]' => $data['overSize'],
            '[OVERSIZE_PERCENTAGE]' => $data['overSizePercentage'],
            '[CALCULATION]' => $data['calculation'],
            '[CALCULATION_PERCENTAGE]' => $data['calculationPercentage'],
            '[UNDERSIZE]' => $data['underSize'],
            '[UNDERSIZE_PERCENTAGE]' => $data['underSizePercentage'],
            '[TOTAL]' => $data['total'],
            '[OBSERVATIONS]' => $data['observations'],
            '[USER]' => $oldJson->USER,
        );
        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');

        $binaryPDF = $pdf->output();

        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->edit($id, DB::raw("0x" . bin2hex($binaryPDF)), json_encode($data));

        if ($complete) {
            return Redirect::to('dettaglio_bolla/' . $idActivity);
        }

        return response('errore!!');
    }
}
