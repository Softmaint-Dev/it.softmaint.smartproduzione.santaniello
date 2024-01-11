<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\View; 
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\App;

use Mpdf\Mpdf;


/**
 * Controller principale del webticket
 * Class HomeController
 * @package App\Http\Controllers
 * 
 */

// use Predis;

// require_once __DIR__ . '/vendor/autoload.php';
use PDF as p;

class QualitaController extends Controller {

    public function generateAndSavePdf(Request $request) {
        $pdf = App::make('dompdf.wrapper');
        $bodyContent = $request->getContent();

       $pdf->loadHTML($bodyContent);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $output = $pdf->output();
        file_put_contents('Brochure.pdf', $output);
        $pdf->output();
       
       return response( $pdf->stream('output.pdf', array('Attachment' => 0)));


     }

    public function confezionamento(Request $request)
    {
        return View::make('qualita.confezionamento');
    }
    public function confezionamento2(Request $request)
    {
        return View::make('qualita.confezionamento2');
    }
    public function selezionatriceAccensione(Request $request)
    {
        return View::make('qualita.selezionatrice_accensione');
    }
    public function selezionatriceControlloProdotto(Request $request)
    {
        return View::make('qualita.selezionatrice_controllo_prodotto');
    }
    public function tostatura(Request $request)
    {
        return View::make('qualita.tostatura');
    }
    public function tostatura2(Request $request)
    {
        return View::make('qualita.tostatura_2');
    }
    public function tritatura(Request $request)
    {
        return View::make('qualita.tritatura');
    }

}
