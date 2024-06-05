<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use PhpOffice\PhpWord\TemplateProcessor;
use Spatie\GoogleCalendar\Event;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * Controller principale del webticket
 * Class HomeController
 * @package App\Http\Controllers
 */
class StampaController extends Controller
{


    public function qualita($Id_xFormQualita)
    {

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
        $form = DB::select('SELECT * from xFormQualita Where Id_xFormQualita=' . $Id_xFormQualita);
        if (sizeof($form) > 0) {
            $form = $form[0];

            $clienti = DB::select('SELECT top 1 CF.* from PRBLAttivita
                LEFT JOIN PROLAttivita ON PROLAttivita.Id_PrOLAttivita = PRBLAttivita.Id_PrOLAttivita
                LEFT Join PrOL On PROLAttivita.Id_PrOL=PrOL.Id_PrOL
                LEFT JOIN PROLDoRig ON PROLDoRig.Id_PrOL = PROL.Id_PrOL
                LEFT JOIN DORig on DORig.Id_DORig = PROLDoRig.Id_DoRig
                LEFT JOIN CF ON CF.Cd_CF = DORig.Cd_CF where PRBLAttivita.Id_PrBLAttivita=' . $form->Id_PrBLAttivita);

            if (sizeof($clienti) > 0) {

                $cliente = $clienti[0];
                $mpdf->showImageErrors = true;
                $mpdf->SetTitle('Modulo di Qualita Bolla ' . $form->Id_PrBLAttivita);
                $html = View::make('stampa.qualita', compact('form', 'cliente'));
                $mpdf->WriteHTML($html);
                $mpdf->Output('test.pdf', 'I');

            }
        }
    }


    public static function stampa_collo_grande($report, $Nr_Collo, $id)
    {
        list($base, $altezza) = explode(';', $report[0]->NoteReport);


        $colli = DB::SELECT('SELECT
                top 1 Prol.Numero as num_bolla,o.Nome as nome_operatore,Ar.xSoffiettoL,ar.xSoffiettoF,ar.xPettina,Ar.xBase,Ar.xAltezza,Ar.xSpessore,xWPCollo.*,PROLAttivita.Cd_PrAttivita,att_next.Id_PrOLAttivita as Id_attivita_next,att_next.Cd_PrAttivita as attivita_next,PrOl.Id_PrOL,xWPCF.Descrizione_CF,AR.Descrizione as Descrizione_AR,isnull(ArcodCF.CodiceAlternativo,AR.Cd_AR) as CodiceArticolo,xWPCollo.Descrizione
            FROM
                xWPCollo
                Left Join PrOL On xWPCollo.IdOrdineLavoro=PrOL.Id_PrOL
                Left Join PROLAttivita On  xWPCollo.IdCodiceAttivita = PrOLAttivita.Id_PrOLAttivita
                LEFT JOIN PROLAttivita att_next ON att_next.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita_Next
                Inner Join AR On AR.Cd_AR = PrOL.Cd_AR
                Left Join PRRisorsa On xWPCollo.Cd_PrRisorsa=PRRisorsa.Cd_PrRisorsa
                left Join xWPCF On xWPCollo.IdCodiceAttivita = xWPCF.Id_PrOLAttivita
                LEFT JOIN Operatore o ON o.Cd_Operatore = xWPcollo.Cd_Operatore
                Inner Join CF On CF.Cd_CF = xWPCF.Cd_CF
                LEFT Join ARCodCF ON ARCodCF.Cd_AR = AR.Cd_AR and ARCodCF.Cd_CF = CF.Cd_CF
        Where  (xWPCollo.Nr_Collo = \'' . $Nr_Collo . '\')');

        if (sizeof($colli) > 0) {
            $collo = $colli[0];
            $html = View::make('stampa.standard_collo_grande', compact('collo'));
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
            $mpdf->SetTitle('Standard Colli Piccolo N.' . $id);
            $mpdf->WriteHTML($html);
            $mpdf->Output('upload/collo_' . $Nr_Collo . '.pdf', 'F');
        }
    }


    public static function stampa_collo_qualita($report, $Nr_Collo, $id)
    {
        list($base, $altezza) = explode(';', $report[0]->NoteReport);

        $colli = DB::SELECT('SELECT
                top 1 Prol.Numero as num_bolla,o.Nome as nome_operatore,Ar.xSoffiettoL,ar.xTrattamento,ar.xSoffiettoF,Ar.xPesoMetro,AR.xPesoBusta,Ar.xBase,Ar.xAltezza,Ar.xSpessore,xWPCollo.*,PROLAttivita.Cd_PrAttivita,att_next.Id_PrOLAttivita as Id_attivita_next,att_next.Cd_PrAttivita as attivita_next,PrOl.Id_PrOL,xWPCF.Descrizione_CF,AR.Descrizione as Descrizione_AR,AR.Cd_AR as CodiceArticolo
            FROM
                xWPCollo
                Left Join PrOL On xWPCollo.IdOrdineLavoro=PrOL.Id_PrOL
                Left Join PROLAttivita On  xWPCollo.IdCodiceAttivita = PrOLAttivita.Id_PrOLAttivita
                LEFT JOIN PROLAttivita att_next ON att_next.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita_Next
                Inner Join AR On AR.Cd_AR = PrOL.Cd_AR
                Left Join PRRisorsa On xWPCollo.Cd_PrRisorsa=PRRisorsa.Cd_PrRisorsa
                left Join xWPCF On xWPCollo.IdCodiceAttivita = xWPCF.Id_PrOLAttivita
                LEFT JOIN Operatore o ON o.Cd_Operatore = xWPcollo.Cd_Operatore
                Inner Join CF On CF.Cd_CF = xWPCF.Cd_CF

         Where  (xWPCollo.Nr_Collo = \'' . $Nr_Collo . '\')');

        if (sizeof($colli) > 0) {
            $collo = $colli[0];
            $html = View::make('stampa.standard_collo_qualita', compact('collo'));
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
            $mpdf->SetTitle('Standard Colli Piccolo N.' . $id);
            $mpdf->WriteHTML($html);
            $mpdf->Output('upload/collo_qualita_' . $Nr_Collo . '.pdf', 'F');

        }
    }


    public static function motore_industry($risorsa, $id_prblattivita, $param1, $tipologia = 0, $codice_stampa = '')
    {

        switch ($risorsa) {
            case 'IMB01':
            case 'IMB02':
            case 'IMB03':
            case 'IMB04':
            case 'IMB.V':
                $stampante = 'stampante1';
                break;
            default:
                $stampante = 'stampante2';
        }

        if (!is_dir('upload/')) {
            mkdir('upload', 0777, true);
        }

        $CF = DB::select('SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                    SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                        SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');


        $ar = DB::select('
            select * From AR Where Cd_AR IN(
                SELECT Cd_AR from PrOLEx Where Id_PrOL IN (
                    SELECT Id_PrOL from PROLAttivitaEX Where Id_PrOLAttivita IN (
                        select Id_PrOLAttivita FROM PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');

        $cd_cf = '';
        $cd_ar = '';
        $cd_prattivita = '';

        if (sizeof($CF) > 0) $cd_cf = $CF[0]->CD_CF;
        if (sizeof($ar) > 0) $cd_ar = $ar[0]->Cd_AR;


        $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita);
        if (sizeof($attivita_bolle) > 0) {
            $attivita_bolla = $attivita_bolle[0];

            $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
            if (sizeof($OLAttivita) > 0) {
                $OLAttivita = $OLAttivita[0];

                $cd_prattivita = $OLAttivita->Cd_PrAttivita;
            }
        }


        if ($codice_stampa != '') {

            $report = DB::select('SELECT * from xSPREport where codice = \'' . $codice_stampa . '\'');

        } else {

            $where_cd_cf = '';
            $where_cd_prattivita = '';
            $where_cd_ar = '';

            if ($cd_cf != '') {
                $where_cd_cf = ' and cd_cf LIKE  \'%' . $cd_cf . '%\' ';
            }

            if ($cd_prattivita != '') {
                $where_cd_prattivita = ' and cd_prattivita LIKE  \'%' . $cd_prattivita . '%\' ';
            }

            if ($cd_ar != '') {
                $where_cd_ar = ' and cd_ar1 LIKE \'%' . $cd_ar . '%\' ';
            }


            $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_prattivita . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_prattivita);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_prattivita . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . '  and cd_ar1 IS NULL and cd_cf IS NULL and cd_prattivita IS NULL');

        }

        $nome_file = '';
        if (sizeof($report) > 0) {
            $report = $report[0];
            $nome_file = strtolower(str_replace(' ', '_', $report->codice)) . '_' . $param1;
            $report->query = str_replace('[param1]', $param1, $report->query);
            $query = DB::select($report->query);

            if (sizeof($query) > 0) {
                $query = $query[0];
                $html = View::make('stampa.modulo', compact('query', 'report'));

                if (sizeof(explode('x', $report->grandezza)) > 1) {
                    list($base, $altezza) = explode('x', $report->grandezza);
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                } else {
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $report->grandezza, 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                }
                $mpdf->SetTitle($report->codice . ' ' . $param1);
                $mpdf->WriteHTML($html);
                $mpdf->Output('upload/' . $stampante . '/' . $nome_file . '.pdf', 'F');
                //$mpdf->Output('upload/' . $nome_file . '.pdf', 'F');
            }

        }

        return $nome_file;

    }
    public static function motore_industry_test($risorsa, $id_prblattivita, $param1, $tipologia = 0, $codice_stampa = '')
    {

        $stampante = 'test';

        if (!is_dir('upload/')) {
            mkdir('upload', 0777, true);
        }

        $CF = DB::select('SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                    SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                        SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');


        $ar = DB::select('
            select * From AR Where Cd_AR IN(
                SELECT Cd_AR from PrOLEx Where Id_PrOL IN (
                    SELECT Id_PrOL from PROLAttivitaEX Where Id_PrOLAttivita IN (
                        select Id_PrOLAttivita FROM PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');

        $cd_cf = '';
        $cd_ar = '';
        $cd_prattivita = '';

        if (sizeof($CF) > 0) $cd_cf = $CF[0]->CD_CF;
        if (sizeof($ar) > 0) $cd_ar = $ar[0]->Cd_AR;


        $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita);
        if (sizeof($attivita_bolle) > 0) {
            $attivita_bolla = $attivita_bolle[0];

            $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
            if (sizeof($OLAttivita) > 0) {
                $OLAttivita = $OLAttivita[0];

                $cd_prattivita = $OLAttivita->Cd_PrAttivita;
            }
        }


        if ($codice_stampa != '') {

            $report = DB::select('SELECT * from xSPREport where codice = \'' . $codice_stampa . '\'');

        } else {

            $where_cd_cf = '';
            $where_cd_prattivita = '';
            $where_cd_ar = '';

            if ($cd_cf != '') {
                $where_cd_cf = ' and cd_cf LIKE  \'%' . $cd_cf . '%\' ';
            }

            if ($cd_prattivita != '') {
                $where_cd_prattivita = ' and cd_prattivita LIKE  \'%' . $cd_prattivita . '%\' ';
            }

            if ($cd_ar != '') {
                $where_cd_ar = ' and cd_ar1 LIKE \'%' . $cd_ar . '%\' ';
            }


            $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_prattivita . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf . ' ' . $where_cd_prattivita);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_prattivita . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_cf);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . ' ' . $where_cd_ar);
            if (sizeof($report) == 0) $report = DB::select('SELECT * from xSPREport where abilitato = 1 and codice != \'standard_collo_piccolo_materiali\' and tipologia = ' . $tipologia . '  and cd_ar1 IS NULL and cd_cf IS NULL and cd_prattivita IS NULL');

        }

        $nome_file = '';
        if (sizeof($report) > 0) {
            $report = $report[0];
            $nome_file = strtolower(str_replace(' ', '_', $report->codice)) . '_' . $param1;
            $report->query = str_replace('[param1]', $param1, $report->query);
            $query = DB::select($report->query);

            if (sizeof($query) > 0) {
                $query = $query[0];
                $html = View::make('stampa.modulo', compact('query', 'report'));

                if (sizeof(explode('x', $report->grandezza)) > 1) {
                    list($base, $altezza) = explode('x', $report->grandezza);
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                } else {
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $report->grandezza, 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                }
                $mpdf->SetTitle($report->codice . ' ' . $param1);
                $mpdf->WriteHTML($html);
                $mpdf->Output('upload/' . $stampante . '/' . $nome_file . '.pdf', 'F');
                $mpdf->Output('upload/' . $nome_file . '.pdf', 'F');
            }

        }

        return $nome_file;

    }

    public static function motore_industry_materiale($risorsa, $id_prblattivita, $param1, $tipologia = 0, $codice_stampa = '')
    {

        switch ($risorsa) {
            case 'IMB01':
            case 'IMB02':
            case 'IMB03':
            case 'IMB04':
            case 'IMB.V':
                $stampante = 'stampante1';
                break;
            default:
                $stampante = 'stampante2';
        }

        if (!is_dir('upload/')) {
            mkdir('upload', 0777, true);
        }

        $CF = DB::select('SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                    SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                        SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');


        $ar = DB::select('
            select * From AR Where Cd_AR IN(
                SELECT Cd_AR from PrOLEx Where Id_PrOL IN (
                    SELECT Id_PrOL from PROLAttivitaEX Where Id_PrOLAttivita IN (
                        select Id_PrOLAttivita FROM PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita . '
                    )
                )
            )
        ');

        $cd_cf = '';
        $cd_ar = '';
        $cd_prattivita = '';

        if (sizeof($CF) > 0) $cd_cf = $CF[0]->CD_CF;
        if (sizeof($ar) > 0) $cd_ar = $ar[0]->Cd_AR;


        $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_prblattivita);
        if (sizeof($attivita_bolle) > 0) {
            $attivita_bolla = $attivita_bolle[0];

            $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
            if (sizeof($OLAttivita) > 0) {
                $OLAttivita = $OLAttivita[0];

                $cd_prattivita = $OLAttivita->Cd_PrAttivita;
            }
        }


        $report = DB::select('SELECT * from xSPREport where codice = \'standard_collo_piccolo_materiali\'');

        $nome_file = '';
        if (sizeof($report) > 0) {
            $report = $report[0];
            $nome_file = strtolower(str_replace(' ', '_', $report->codice)) . '_' . $param1;
            $report->query = str_replace('[param1]', $param1, $report->query);
            $query = DB::select($report->query);

            if (sizeof($query) > 0) {
                $query = $query[0];
                $html = View::make('stampa.modulo', compact('query', 'report'));

                if (sizeof(explode('x', $report->grandezza)) > 1) {
                    list($base, $altezza) = explode('x', $report->grandezza);
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                } else {
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $report->grandezza, 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                }
                $mpdf->SetTitle($report->codice . ' ' . $codice_stampa);
                $mpdf->WriteHTML($html);
                $mpdf->Output('upload/' . $stampante . '/' . $nome_file . '.pdf', 'F');
                //$mpdf->Output('upload/' . $nome_file . '.pdf', 'F');
            }

        }

        return $nome_file;

    }

    public static function compile_string($string, $query)
    {

        if (isset($query->Cd_AR)) $string = str_replace('{cd_ar}', $query->Cd_AR, $string);
        if (isset($query->Descrizione_AR)) $string = str_replace('{descrizione_ar}', $query->Descrizione_AR, $string);
        if (isset($query->Descrizione_Collo)) $string = str_replace('{descrizione_collo}', $query->Descrizione_Collo, $string);
        if (isset($query->Nr_Collo)) $string = str_replace('{nr_collo}', $query->Nr_Collo, $string);
        if (isset($query->IdOrdineLavoro)) $string = str_replace('{lotto}', $query->IdOrdineLavoro, $string);
        if (isset($query->scadenza_lotto)) $string = str_replace('{scadenza_lotto}', $query->scadenza_lotto, $string);
        if (isset($query->Nr_Collo)) $string = str_replace('{nr_collo}', $query->Nr_Collo, $string);
        if (isset($query->Nr_Collo)) $string = str_replace('{code_39(nr_collo)}', '<barcode code="' . $query->Nr_Collo . '" type="C39"/>', $string);
        if (isset($query->Cd_AR)) $string = str_replace('{code_39(Cd_AR)}', '<barcode code="' . $query->Cd_AR . '" type="C39"/>', $string);
        if (isset($query->QtaProdotta)) $string = str_replace('{qtaprodotta}', number_format($query->QtaProdotta, 0, '.', ''), $string);
        if (isset($query->Cd_ARMisura)) $string = str_replace('{cd_armisura}', $query->Cd_ARMisura, $string);
        if (isset($query->Nr_Pedana)) $string = str_replace('{nr_pedana}', $query->Nr_Pedana, $string);
        if (isset($query->Descrizione_CF)) $string = str_replace('{descrizione_cf}', $query->Descrizione_CF, $string);
        if (isset($query->Descrizione)) $string = str_replace('{descrizione}', $query->Descrizione, $string);
        if (isset($query->IdOrdineLavoro)) $string = str_replace('{idordinelavoro}', $query->IdOrdineLavoro, $string);
        if (isset($query->attivita_next)) $string = str_replace('{attivita_next}', $query->attivita_next, $string);
        if (isset($query->Cd_PrAttivita)) $string = str_replace('{cd_prattivita}', $query->Cd_PrAttivita, $string);
        if (isset($query->Cd_Operatore)) $string = str_replace('{cd_operatore}', $query->Cd_Operatore, $string);
        if (isset($query->TimeIns)) $string = str_replace('{timeins}', $query->TimeIns, $string);
        if (isset($query->Cd_PrRisorsa)) $string = str_replace('{cd_prrisorsa}', $query->Cd_PrRisorsa, $string);
        if (isset($query->TimeIns)) $string = str_replace('{timeins_ddmmyy}', date('d/m/Y', strtotime($query->TimeIns)), $string);
        if (isset($query->Cd_DO)) $string = str_replace('{cd_do}', $query->Cd_DO, $string);
        if (isset($query->NumeroDoc)) $string = str_replace('{numerodoc}', $query->NumeroDoc, $string);
        if (isset($query->Lotto)) $string = str_replace('{lotto}', $query->Lotto, $string);
        if (isset($query->NumeroDocRif)) $string = str_replace('{numerodocrif}', $query->NumeroDocRif, $string);
        if (isset($query->NumeroColli)) $string = str_replace('{numerocolli}', $query->NumeroColli, $string);
        if (isset($query->PesoNetto)) $string = str_replace('{pesonetto}', number_format($query->PesoNetto, 2, '.', ''), $string);
        if (isset($query->PesoNettissimo)) $string = str_replace('{pesonettissimo}', number_format($query->PesoNettissimo, 2, '.', ''), $string);
        if (isset($query->colli)) $string = str_replace('{colli}', $query->colli, $string);
        if (isset($query->Indirizzo)) $string = str_replace('{indirizzo}', $query->Indirizzo, $string);
        if (isset($query->Origine)) $string = str_replace('{Origine}', $query->Origine, $string);
        if (isset($query->Anno)) $string = str_replace('{Anno}', $query->Anno, $string);
        if (isset($query->Localita)) $string = str_replace('{localita}', $query->Localita, $string);
        if (isset($query->Cd_Provincia)) $string = str_replace('{cd_provincia}', $query->Cd_Provincia, $string);
        if (isset($query->PesoLordo) && isset($query->NumeroColli) && $query->NumeroColli > 0) $string = str_replace('{peso_lordo_num_colli}', number_format($query->PesoLordo / $query->NumeroColli, 2, '.', ''), $string);
        if (isset($query->PesoLordo) && isset($query->NumeroColli) && $query->NumeroColli == 0) $string = str_replace('{peso_lordo_num_colli}', number_format($query->PesoLordo, 2, '.', ''), $string);
        if (isset($query->Id_PRBLAttivita)) $string = str_replace('{id_prblattivita}', $query->Id_PRBLAttivita, $string);
        if (isset($query->Id_PRBLAttivita)) $string = str_replace('{bc_code123_id_prblattivita}', '<barcode code="' . $query->Id_PRBLAttivita . '" type="C128A"/>', $string);
        if (isset($query->CodiceArticolo)) $string = str_replace('{codicearticolo}', $query->CodiceArticolo, $string);
        if (isset($query->xLotto)) $string = str_replace('{xLotto}', $query->xLotto, $string);

        return $string;

    }

}
