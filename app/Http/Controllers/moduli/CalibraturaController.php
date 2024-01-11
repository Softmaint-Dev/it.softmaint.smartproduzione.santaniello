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
use App\Models\xCalibratura;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Redirect;

class CalibraturaController extends Controller
{
    public function showAll()
    {

        return view('moduli.calibratura.calibratura', [
            'calibrature' => xCalibratura::all()
        ]);
    }


    public function createView($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.calibratura.calibratura_create', [
            'attivity' => $attivity
        ]);
    }


    function create(Request $request, $id)
    {

   

        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $dotes = $prblAttivita;
        $prolAttivita =  $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;
        $cf = $dotes->cf;
        $dms = $dotes->dms();

 
         

         $data = $request->all();

         $pdf = App::make('dompdf.wrapper');

         $layout = file_get_contents(public_path('pdf/calibratura2.html'));


         $refactoring = array(
             '[ID_REV]' => '1',
             '[ID_DATE]' => 'Mio Id',
             '[VARIETA]' => $data['variety'],
             '[LOTTO]' => 'Da  Recuperare',
             '[CALIBRO]' => $data['calibre'],
             '[CLIENTE]' => $cf->Descrizione,
             '[TOTAL_KG]' => 'DA RECUPERARE',
             '[DATE]' => $data['date'],
             '[TIME]' => $data['analysis'],
             '[SAMPLE]'       => $data['sample'],
             '[SAMPLE_CALIBRATURA]'       => $data['sampleCalibratura'],       
             '[UMIDITA]' =>         $data['moisture'],
             '[SKIN]' =>            $data['skin'],
             '[TASTE]' =>           $data['tastAndSmell'],
             '[COLOUR]' =>          $data['colour'],
             '[OVERSIZE]' =>                        $data['overSize'],
             '[OVERSIZE_PERCENTAGE]' =>             $data['overSizePercentage'],
             '[CALCULATION]' =>              $data['calculation'],
             '[CALCULATION_PERCENTAGE]' =>              $data['calculationPercentage'],
             '[UNDERSIZE]' =>              $data['underSize'],
             '[UNDERSIZE_PERCENTAGE]' =>              $data['underSizePercentage'],
             '[TOTAL]' =>              $data['total'],
            '[OBSERVATIONS]' =>              $data['observations'],
         );

         $html =  str_replace(array_keys($refactoring), $refactoring, $layout);
         
         $pdf->loadHtml($html);
         $pdf->save(public_path("ciao.pdf"));
 
         $binaryPDF = $pdf->output();
          try {


   
         $dms = new DmsDocument();
         $dms->Descrizione = 'MODULO CALIBRATURA';
         $dms->FileName = "Calibratura.pdf";
         $dms->Content = DB::raw("0x" . bin2hex($binaryPDF));
            $dms->EntityTable = 'DOTES';
            $dms->EntityId = intval($dotes->Id_DoTes);
            $dms->Id_DmsClass1 = "1";
            $dms->Id_DmsClass2 = "4";
            $dms->DmsClass3 = "QLT";
            $dms->Cd_DmsType = "01";
            $dms->DocumentDate =  $data['date'];
            $dms->LinkedToFS = "0";
            $dms->FileSize = strlen($binaryPDF);
            $dms->EntityDescription = "MODULO CALIBRATURA";
            $dms->ComputerName = "";
            $dms->FilePath = "";
            $dms->Note = $dotes->Id_DoTes . '';
         $dms->save();

        
    } catch (Exception $e) {
        $message = $e->getMessage();       
        return response($message);
        // Output o registrazione delle informazioni sull'errore
        // ...
    }

    //return response($dotes->Id_DoTes);
    return Redirect::to('dettaglio_bolla/' . $id);
    // return view('moduli.calibratura.calibratura_create', [
        //     'attivity' => 2
        // ]);;
    }
}
