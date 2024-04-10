<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRRLAttivita;
use Illuminate\Http\Request;
use App\Models\PRBLAttivita;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class MetalDetectorController extends Controller
{
    public function createViewMBR1200($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.metal_detector.md-mbr-1200_create', [
            'attivity' => $attivity
        ]);
    }

    public function createViewPMO($id)
    {

        $attivity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.metal_detector.md-pmo', [
            'attivity' => $attivity
        ]);
    }

    function createPostMBR1200(Request $request, $id)
    {
        if ($this->createPDF($request, $id, "METAL DETECTOR MBR1200", "md-mbr1200.html")) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }


    function createPostPMO(Request $request, $id)
    {


        if ($this->createPDF($request, $id, "METAL DETECTOR PMO", "md-pmo.html")) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }

    function createPDF(Request $request, $id, $nameFile, $pathHTML)
    {
        $data = $request->all();

        $prblAttivita = PRBLAttivita::firstWhere('id_prblattivita', $id);
        $prolAttivita = $prblAttivita->prolAttivita;
        $prolDorig = $prolAttivita->prolDoRig;
        $dorig = $prolDorig->dorig;
        $dotes = $dorig->dotes;

        $groupedData = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^(\D+)(\d+)$/', $key, $matches)) {
                $prefix = $matches[1];
                $index = $matches[2];
                if (!isset($groupedData[$index])) {
                    $groupedData[$index] = [];
                }
                $groupedData[$index][$prefix] = $value;
            }
        }

        $htmlString = '<tbody>';

        $index = 1;

        foreach ($groupedData as $element) {
            $htmlString .= '<tr>';
            $htmlString .= '<td>' . $index . ' ' . 'conn. ore ' . $element["ore"] . 'Lotto ' . $element["lotto"] . '</td>';
            $htmlString .= '<td>' . (filter_var($element["fe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["nofe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["stainless"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>  </td>';
            $htmlString .= '</tr>';
            $htmlString .= '</tbody>';
            $index++;

        }

        $layout = file_get_contents(public_path('pdf/' . ($pathHTML)));

        $refactoring = array(
            '[BODY]' => $htmlString,
        );

        $data['BODY'] = $htmlString;

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');


        return App::make('App\Http\Controllers\moduli\ModuloController')
            ->createDMS(
                DB::raw("0x" . bin2hex($pdf->output())),
                $nameFile,
                date("Y-m-d H:i:s") . ".pdf",
                $dotes,
                date("Y-m-d H:i:s"),
                json_encode($data),
                substr(str_replace('METAL DETECTOR', '', $nameFile), 0, 10),
            );

    }


    public function editViewPMO($idActivity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        return view('moduli.metal_detector.md-pmo_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJSON),
            'id' => $id,
        ]);
    }

    public function editPMO($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $oldJson = json_decode($dms->xJSON);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        $data = $request->all();

        $pdf = App::make('dompdf.wrapper');
        $groupedData = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^(\D+)(\d+)$/', $key, $matches)) {
                $prefix = $matches[1];
                $index = $matches[2];
                if (!isset($groupedData[$index])) {
                    $groupedData[$index] = [];
                }
                $groupedData[$index][$prefix] = $value;
            }
        }

        $htmlString = '<tbody>';

        $index = 1;

        foreach ($groupedData as $element) {
            $htmlString .= '<tr>';
            $htmlString .= '<td>' . $index . ' ' . 'conn. ore ' . $element["ore"] . 'Lotto ' . $element["lotto"] . '</td>';
            $htmlString .= '<td>' . (filter_var($element["fe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["nofe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["stainless"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>  </td>';
            $htmlString .= '</tr>';
            $htmlString .= '</tbody>';
            $index++;

        }

        $layout = file_get_contents(public_path('pdf/md-pmo.html'));

        $refactoring = array(
            '[BODY]' => $htmlString,
        );


        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');
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

    public function editViewMBR1200($idActivity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        return view('moduli.metal_detector.md-mbr-1200_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJSON),
            'id' => $id,
        ]);
    }

    public function editMBR1200($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $oldJson = json_decode($dms->xJSON);
        $activity = PRBLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        $data = $request->all();

        $pdf = App::make('dompdf.wrapper');

        $pdf = App::make('dompdf.wrapper');
        $groupedData = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^(\D+)(\d+)$/', $key, $matches)) {
                $prefix = $matches[1];
                $index = $matches[2];
                if (!isset($groupedData[$index])) {
                    $groupedData[$index] = [];
                }
                $groupedData[$index][$prefix] = $value;
            }
        }

        $htmlString = '<tbody>';

        $index = 1;

        foreach ($groupedData as $element) {
            $htmlString .= '<tr>';
            $htmlString .= '<td>' . $index . ' ' . 'conn. ore ' . $element["ore"] . 'Lotto ' . $element["lotto"] . '</td>';
            $htmlString .= '<td>' . (filter_var($element["fe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["nofe"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["stainless"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>  </td>';
            $htmlString .= '</tr>';
            $htmlString .= '</tbody>';
            $index++;

        }

        $layout = file_get_contents(public_path('pdf/md-mbr1200.html'));

        $refactoring = array(
            '[BODY]' => $htmlString,
        );

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);
        $pdf = App::make('dompdf.wrapper');
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

