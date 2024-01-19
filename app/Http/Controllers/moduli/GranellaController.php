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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class GranellaController extends Controller
{
 

    public function createView($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.granella.granella_create', [
            'attivity' => $attivity
        ]);
    }


    function create(Request $request, $id)
    {



        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $cf = $dotes->cf;
        $dms = $dotes->dms();

        $data = $request->all();

        $pdf = App::make('dompdf.wrapper');

        $layout = file_get_contents(public_path('pdf/granella.html'));
        $utente = $request->session()->get("utente");


        $refactoring = array(
            '[VARIETA]' => $data['variety'],
            '[LOTTO]' => $data['xwpCollo'],
            '[CALIBRO]' => $data['calibre'],
            '[CLIENTE]' => $cf->Descrizione,
            '[TOTAL_KG]' => 'DA RECUPERARE',
            '[DATE]' => $data['date'],
            '[TIME]' => $data['analysis'],
            '[SAMPLE]' => $data['sample'],
            '[SAMPLE_CALIBRATURA]' => $data['sampleCalibratura'],
            '[UMIDITA]' => $data['moisture'],
            '[SKIN]' => $data['skin'],
            '[TASTE]' => $data['tastAndSmell'],
            '[COLOUR]' => $data['colour'],
            '[OVERSIZE]' => $data['overSize'],
            '[OVERSIZE_PERCENTAGE]' => $data['overSizePercentage'],
            '[CALCULATION]' => $data['calculation'],
            '[CALCULATION_PERCENTAGE]' => $data['calculationPercentage'],
            '[UNDERSIZE]' => $data['underSize'],
            '[UNDERSIZE_PERCENTAGE]' => $data['underSizePercentage'],
            '[TOTAL]' => $data['total'],
            '[OBSERVATIONS]' => $data['observations'],
            '[USER]' => ($utente->Nome) . " " . ($utente->Cognome)
        );

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);

        $binaryPDF = $pdf->output();





        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS(
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO GRANELLA',
                "granella.pdf",
            
                $dotes,
                $data['date']
            );

        if ($complete) {
         
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');

    }
}
