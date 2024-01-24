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

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

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
            '[USER]' => ( $request->session()->get("utente")->Nome) . " " . ( $request->session()->get("utente")->Cognome)
        );

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHtml($html);

        $binaryPDF = $pdf->output();



 

        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS(
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO FARINA',
                "farina.pdf",
            
                $dotes,
                date("Y-m-d H:i:s")
            );

        if ($complete) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }
}
