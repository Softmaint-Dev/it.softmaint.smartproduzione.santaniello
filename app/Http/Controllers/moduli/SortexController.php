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

class SortexController extends Controller
{
    public function createView($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.sortex.sortex_create', [
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

        print_r($data);
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
            $htmlString .= '<td>' . ($element["data"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ora"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["corpi"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["raggrizito"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ammuffito"] ?? '') . '</td>';
            $htmlString .= '</tr>';
        }

        $htmlString .= '</tbody>';


        $layout = file_get_contents(public_path('pdf/sortex.html'));

        $refactoring = array(
            '[DA COSTRUIRE]' => $htmlString,
            '[VARIETA]' => $data ["varieta"],
            '[DATA]' => $data['data']

        );
        $data['DA COSTRUIRE'] = $htmlString;
        $formatoOrigine = 'd/m/Y';
        $dateCarbon = Carbon::createFromFormat($formatoOrigine, $data['data']);
        $dataFormattata = $dateCarbon->format('Y-m-d H:i:s');

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHtml($html);


        $binaryPDF = $pdf->output();


        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS( $id,
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO SORTEX',
                "sortex.pdf",
                $dotes,
                $dataFormattata,
                json_encode($data),
                "sortex"
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

        return view('moduli.sortex.sortex_edit', [
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

        print_r($data);
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
            $htmlString .= '<td>' . ($element["data"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ora"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["corpi"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["raggrizito"] ?? '') . '</td>';
            $htmlString .= '<td>' . ($element["ammuffito"] ?? '') . '</td>';
            $htmlString .= '</tr>';
        }

        $htmlString .= '</tbody>';


        $layout = file_get_contents(public_path('pdf/sortex.html'));
        $refactoring = array(
            '[DA COSTRUIRE]' => $htmlString,
            '[DATA]' => $data["data"]
        );
        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');

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
