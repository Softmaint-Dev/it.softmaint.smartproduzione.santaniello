<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use App\Models\PRRLAttivita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\xDmsFolder;

class TostaturaController extends Controller
{
    public function createView($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.tostatura.tostatura_create', [
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

        // dd($data);
        // return response("");
        $groupedData = [];

        foreach ($data as $key => $value) {
            // Utilizziamo espressioni regolari per trovare i numeri nelle chiavi come "lotto1", "cesta1", ecc.
            if (preg_match('/^(\D+)(\d+)$/', $key, $matches)) {
                $prefix = $matches[1]; // "lotto", "cesta", ecc.
                $index = $matches[2]; // Numero

                // Inizializza l'array se non esiste
                if (!isset($groupedData[$index])) {
                    $groupedData[$index] = [];
                }

                // Aggiungi l'elemento all'array raggruppato
                $groupedData[$index][$prefix] = $value;
            }
        }


        $htmlString = '<tbody>';

        foreach ($groupedData as $element) {
            $htmlString .= '<tr>';
            $htmlString .= '<td>' . ($element["lotto"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["cesta"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ricetta"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["rhin"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["rhout"] ?? '') . '</td>';
            $htmlString .= '</tr>';
        }
        $htmlString .= '</tbody>';

        $layout = file_get_contents(public_path('pdf/tostatura.html'));

        $refactoring = array(
            '[USER]' => ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome),
            '[DA COSTRUIRE]' => $htmlString,
            '[DATA]' => $data["data"]

        );


        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $binaryPDF = $pdf->output();
        $data['DA COSTRUIRE'] = $htmlString;


        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS($id,
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO TOSTATURA',
                "tostatura.pdf",

                $dotes,
                $data['data'],
                json_encode($data),
                'tostatura'
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

        return view('moduli.tostatura.tostatura_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJSON),
            'id' => $id,
        ]);
    }

    public function edit($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);

        /* SOSTITUISCO LA VECCHIA GESTIONE */
        $dms = xDmsFolder::firstWhere('Id_xDmsFolder', $id);
        $oldJson = json_decode($dms->xJSON);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        $data = $request->all();
        $groupedData = [];

        foreach ($data as $key => $value) {
            // Utilizziamo espressioni regolari per trovare i numeri nelle chiavi come "lotto1", "cesta1", ecc.
            if (preg_match('/^(\D+)(\d+)$/', $key, $matches)) {
                $prefix = $matches[1]; // "lotto", "cesta", ecc.
                $index = $matches[2]; // Numero

                // Inizializza l'array se non esiste
                if (!isset($groupedData[$index])) {
                    $groupedData[$index] = [];
                }

                // Aggiungi l'elemento all'array raggruppato
                $groupedData[$index][$prefix] = $value;
            }
        }


        $htmlString = '<tbody>';

        foreach ($groupedData as $element) {
            $htmlString .= '<tr>';
            $htmlString .= '<td>' . ($element["lotto"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["cesta"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ricetta"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["rhin"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["rhout"] ?? '') . '</td>';
            $htmlString .= '</tr>';
        }
        $htmlString .= '</tbody>';

        $pdf = App::make('dompdf.wrapper');
        $layout = file_get_contents(public_path('pdf/tostatura.html'));
        $refactoring = array(
            '[USER]' => ($request->session()->get("utente")->Nome) . " " . ($request->session()->get("utente")->Cognome),
            '[DA COSTRUIRE]' => $htmlString,
            '[DATA]' => $data["data"]
        );
        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $data['DA COSTRUIRE'] = $htmlString;

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');

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
