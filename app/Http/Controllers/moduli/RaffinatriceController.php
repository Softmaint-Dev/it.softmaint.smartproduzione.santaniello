<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\PRBLAttivita;
use App\Models\PRRLAttivita;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RaffinatriceController extends Controller
{
    public function createView($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);
       
        return view('moduli.raffinatrice.raffinatrice_create', [
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

         $layout = file_get_contents(public_path('pdf/raffinatrice.html'));

        

        $dateCarbon = Carbon::createFromFormat('d/m/Y', $data['data']);

        $giorno = $dateCarbon->day;   // Ottieni il giorno
        $mese = $dateCarbon->month;   // Ottieni il mese
        $anno = $dateCarbon->year;    // Ottieni l'anno

        print_r($data);
          $refactoring = array(
           '[INTEGRITA]' =>  isset($data['integrita']) ? "X" : "",
           '[INTEGRITA_DESCRIZIONE]' =>  $data['integrita_nota'],
           '[PULIZIA_CARICO]' =>  isset($data['pulizia_carico']) ? "X" : "",
           '[PULIZIA_CARICO_DESCRIZIONE]' => $data['pulizia_carico_nota'],
           '[PULIZIA_USCITA]' => isset($data['pulizia_uscita']) ? "X" : "",
           '[PULIZIA_USCITA_DESCRIZIONE]' => $data['pulizia_uscita_note'],
           '[NOTE]' =>  $data['note'],
           '[GIORNO]' =>$giorno,
           '[MESE]' => $mese,
           '[ANNO]' =>  $anno,
           '[LOTTO MP]' =>$data['lotto_mp'],
           '[LOTTO PF]' => $data['lotto_pf'],
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
                  'MODULO RAFFINATRICE',
                  "RAFFINATRICE.pdf",
                  $dotes,
                  $dataFormattata
              );

          if ($complete) {
              return Redirect::to('dettaglio_bolla/' . $id);
          }
        
        return response('errore!!');
 
    }
}
