<?php

namespace App\Http\Controllers\moduli;

use App\Models\xDmsFolder;
use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
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

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

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
        if ($prolAttivita->prolDoRig != null) {
            $prolDorig = $prolAttivita->prolDoRig;
            $dorig = $prolDorig->dorig;
            $dotes = $dorig->dotes;
            $cf = $dotes->cf;
            $dms = $dotes->dms();
        }


        $pdf = App::make('dompdf.wrapper');

        $layout = file_get_contents(public_path('pdf/raffinatrice.html'));


        $dateCarbon = Carbon::createFromFormat('d/m/Y', $data['data']);

        $giorno = $dateCarbon->day;   // Ottieni il giorno
        $mese = $dateCarbon->month;   // Ottieni il mese
        $anno = $dateCarbon->year;    // Ottieni l'anno

        print_r($data);
        $refactoring = array(
            '[INTEGRITA]' => isset($data['integrita']) ? "X" : "",
            '[INTEGRITA_DESCRIZIONE]' => $data['integrita_nota'],
            '[PULIZIA_CARICO]' => isset($data['pulizia_carico']) ? "X" : "",
            '[PULIZIA_CARICO_DESCRIZIONE]' => $data['pulizia_carico_nota'],
            '[PULIZIA_USCITA]' => isset($data['pulizia_uscita']) ? "X" : "",
            '[PULIZIA_USCITA_DESCRIZIONE]' => $data['pulizia_uscita_note'],
            '[NOTE]' => $data['note'],
            '[GIORNO]' => $giorno,
            '[MESE]' => $mese,
            '[ANNO]' => $anno,
            '[LOTTO MP]' => $data['lotto_mp'],
            '[LOTTO PF]' => $data['lotto_pf'],
        );

        $data['giorno'] = $giorno;
        $data['mese'] = $mese;
        $data['anno'] = $anno;

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);

        $binaryPDF = $pdf->output();


        $formatoOrigine = 'd/m/Y';

        $dateCarbon = Carbon::createFromFormat($formatoOrigine, $data['data']);
        $dataFormattata = $dateCarbon->format('Y-m-d H:i:s');

        $complete = App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS($id,
                DB::raw("0x" . bin2hex($binaryPDF)),
                'MODULO RAFFINATRICE',
                "RAFFINATRICE.pdf",
                $dotes,
                $dataFormattata,
                json_encode($data),
                "raffina"
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

        return view('moduli.raffinatrice.raffinatrice_edit', [
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

        $pdf = App::make('dompdf.wrapper');
        $layout = file_get_contents(public_path('pdf/raffinatrice.html'));

        $refactoring = array(
            '[INTEGRITA]' => isset($data['integrita']) ? "X" : "",
            '[INTEGRITA_DESCRIZIONE]' => $data['integrita_nota'],
            '[PULIZIA_CARICO]' => isset($data['pulizia_carico']) ? "X" : "",
            '[PULIZIA_CARICO_DESCRIZIONE]' => $data['pulizia_carico_nota'],
            '[PULIZIA_USCITA]' => isset($data['pulizia_uscita']) ? "X" : "",
            '[PULIZIA_USCITA_DESCRIZIONE]' => $data['pulizia_uscita_note'],
            '[NOTE]' => $data['note'],
            '[GIORNO]'  => $oldJson->giorno,
            '[MESE]'    => $oldJson->mese,
            '[ANNO]'    => $oldJson->anno,
            '[LOTTO MP]' => $data['lotto_mp'],
            '[LOTTO PF]' => $data['lotto_pf'],
        );
        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
          $data['giorno'] = $oldJson->giorno;
          $data['mese'] = $oldJson->mese;
          $data['anno'] = $oldJson->anno;
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
