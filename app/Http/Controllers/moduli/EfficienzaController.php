<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\PRRLAttivita;
use Illuminate\Http\Request; 
use App\Models\PRBLAttivita; 
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class EfficienzaController extends Controller
{
   
    public function createView($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.efficienza.efficienza_create', [
            'attivity' => $attivity
        ]);
    }

    function create(Request $request, $id)
    {

        $data = $request->all();

        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $cf = $dotes->cf;
        $dms = $dotes->dms();
        
         $pdf = App::make('dompdf.wrapper');

         $layout = file_get_contents(public_path('pdf/efficienza.html'));

        // $refactoring = array();

        $dateCarbon = Carbon::createFromFormat('d/m/Y', $data['data']);

        $giorno = $dateCarbon->day;   // Ottieni il giorno
        $mese = $dateCarbon->month;   // Ottieni il mese
        $anno = $dateCarbon->year;    // Ottieni l'anno

        print_r($data);
          $refactoring = array(
           '[GIORNO]' => $giorno,
           '[MESE]' =>  $mese, 
           '[ANNO]' =>  $anno,
           '[ORA_INIZIO]' => $data['oraInizio'], 
           '[ORA_FINE]' => $data['oraFine'], 
           '[LOTTO]' => 'DA INSERIRE',
           '[ALLARME]' => $data['allarme'] == "on" ? "X" : "",
           '[ALLARME_DESCRIZIONE]' => $data['allarme_nota'],
           '[RICETTA]' => $data['ricetta'] == "on" ? "X" : "",
           '[RICETTA_DESCRIZIONE]' => $data['ricetta_nota'],
           '[FILTRO_ACQUA]' => $data['filtro_acqua'] == "on" ? "X" : "",
           '[FILTRO_ACQUA_DESCRIZIONE]' => $data['filtro_acqua_note'],
           '[ELETTROVALVOLE]' => $data['elettrovalvole'] == "on" ? "X" : "",
           '[ELETTROVALVOLE_DESCRIZIONE]' => $data['elettrovalvole_note'],
           '[CALIBRAZIONE]' => $data['calibrazione'] == "on" ? "X" : "",
           '[CALIBRAZIONE_DESCRIZIONE]' => $data['calibrazione_note'],
           '[NOTE]' => $data['note'],
         );

    

         $html = str_replace(array_keys($refactoring), $refactoring, $layout);

         $pdf->loadHtml($html);

         $binaryPDF = $pdf->output();


         $formatoOrigine = 'd/m/Y';

         $dateCarbon = Carbon::createFromFormat($formatoOrigine,  $data['data']);
         $dataFormattata = $dateCarbon->format('Y-m-d H:i:s');


          $complete = App::make('App\Http\Controllers\moduli\ModuloController')
              ->createDMS(
                  DB::raw("0x" . bin2hex($binaryPDF)),
                  'MODULO EFFICIENZA',
                  "efficienza.pdf",
                  $dotes,
                  $dataFormattata
              );

          if ($complete) {
              return Redirect::to('dettaglio_bolla/' . $id);
          }
        
        return response('errore!!');

    }
}
