<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\View; 
use Illuminate\Http\Request;   
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

class QualitaController extends Controller
{

    public function generateAndSavePdf(Request $request)
{


    $mpdf = new \Mpdf\Mpdf();

    // Incorpora il tuo HTML, che include Bootstrap
    $html = '<html><head><link rel="stylesheet" href="path-to-bootstrap/bootstrap.min.css"></head><body>Your content here...</body></html>';
    
    // Scrivi l'HTML nel documento PDF
    $mpdf->WriteHTML($html);
    $mpdf->Output('upload/test' . 10 . '.pdf', 'F');

    
      // $htmlBody = $request->getContent();

         //   $pdf = p::loadHTML($htmlBody);
         //$pdf->save(public_path('nome_file.pdf'));
         //return $pdf->stream('nome_file.pdf');

        // $pdfPath = public_path('tes2t.pdf');

        // $pdf = SnappyPdf::loadHTML($htmlBody)
        // ->setOption('disable-external-links', false)
        // ->setOption('enable-local-file-access', true)
        // ->setOption('enable-internal-links' , true)
        // ->save($pdfPath);

        // $pdf->download('document.pdf');



       // dispatch(new loadQualitaPDF("ciao"));
      // require_once '../vendor/autoload.php';
      
      // HTML da convertire in PDF
    //    $html = $htmlBody; // '<h1>Ciao, mondo!</h1><p>Questo è un documento PDF generato con mPDF.</p>';
    //    require_once '../vendor/autoload.php';
    //    $mpdf = new \Mpdf\Mpdf(); //use this customization
    //    $mpdf->SetTitle('Standard Colli Piccolo N.' . 10);
       
 
    //    $mpdf->WriteHTML($html, 2);
    //    $mpdf->Output('upload/collo_' . 10 . '.pdf', 'F');
        
       return response("ciao");

    // $pdf = app('dompdf.wrapper');  
 
    // $pdf->loadHTML($htmlBody);

    // $pdf->stream('nome_file.pdf');

    

     //return response("pdf");
        
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
