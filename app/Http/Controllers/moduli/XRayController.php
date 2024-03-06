<?php

namespace App\Http\Controllers\moduli;

use App\Http\Controllers\Controller;
use App\Models\DmsDocument;
use App\Models\PRBLAttivita;
use App\Models\PRRLAttivita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class XRayController extends Controller
{
    public function createViewBR6000($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.xray.xray_xbr-6000_create', [
            'attivity' => $attivity
        ]);
    }

    public function createView400N($id)
    {

        $attivity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $id);

        return view('moduli.xray.xray_400n_create', [
            'attivity' => $attivity
        ]);
    }

    function createPostXBR6000(Request $request, $id)
    {
        if ($this->createPDF($request, $id, "XRAY BR6000")) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }


    function createPost400N(Request $request, $id)
    {


        if ($this->createPDF($request, $id, "XRAY 400N")) {
            return Redirect::to('dettaglio_bolla/' . $id);
        }
        return response('errore!!');
    }

    function createPDF(Request $request, $id, $nameFile)
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
            $htmlString .= '<td>' . (filter_var($element["crystal"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["ceramic"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '</tr>';
            $index++;

        }
        $htmlString .= '</tbody>';


        $layout = file_get_contents(public_path('pdf/xray-br6000.html'));


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
                $nameFile
            );

    }

    public function editView400N($idActivity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $activity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        return view('moduli.xray.xray_400n_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJSON),
            'id' => $id,
        ]);
    }

    public function edit400N($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $oldJson = json_decode($dms->xJSON);
        $activity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

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
            $htmlString .= '<td>' . (filter_var($element["crystal"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["ceramic"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '</tr>';
            $index++;

        }
        $htmlString .= '</tbody>';

        $layout = file_get_contents(public_path('pdf/xray-br6000.html'));
        $refactoring = array(
            '[BODY]' => $htmlString,
        );

        $data['BODY'] = $htmlString;

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

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

    public function editViewBR6000($idActivity, $id)
    {
        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $activity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

        return view('moduli.xray.xray_xbr-6000_edit', [
            'activity' => $activity,
            'json' => json_decode($dms->xJSON),
            'id' => $id,
        ]);
    }


    public function editBR6000($idActivity, $id, Request $request)
    {


        $dms = DmsDocument::firstWhere('Id_DmsDocument', $id);
        $oldJson = json_decode($dms->xJSON);
        $activity = PRRLAttivita::firstWhere('Id_PrBLAttivita', $idActivity);

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
            $htmlString .= '<td>' . (filter_var($element["crystal"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '<td>' . (filter_var($element["ceramic"], FILTER_VALIDATE_BOOLEAN) ? 'X' : '') . '</td>';
            $htmlString .= '</tr>';
            $index++;

        }
        $htmlString .= '</tbody>';

        $layout = file_get_contents(public_path('pdf/xray-br6000.html'));
        $refactoring = array(
            '[BODY]' => $htmlString,
        );

        $data['BODY'] = $htmlString;

        $html = str_replace(array_keys($refactoring), $refactoring, $layout);

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
