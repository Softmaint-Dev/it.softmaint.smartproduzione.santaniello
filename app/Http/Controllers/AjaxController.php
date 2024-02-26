<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * Controller principale del webticket
 * Class HomeController
 * @package App\Http\Controllers
 */
class AjaxController extends Controller
{
    public function cambia_armisura($Id_PrBLAttivita, $cd_armisura)
    {
        $colli = DB::select('SELECT * from xWPCollo where Id_PrBLAttivita = ' . $Id_PrBLAttivita);
        if (sizeof($colli) > 0) {
            DB::UPDATE('UPDATE xWPCollo set Cd_ARMisura = \'' . $cd_armisura . '\' where Id_PrBLAttivita = \'' . $Id_PrBLAttivita . '\'');
        }
    }

    public function check_bolla($id_PrBlAttivita)
    {
        $bolla = DB::select('SELECT QuantitaDaProdurre + QuantitaProdotta as Quantita,Cd_ARMisura from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_PrBlAttivita);
        $versamenti = DB::select('SELECT Sum(Quantita) as Quantita from PrVRAttivita Where Id_PrBLAttivita = ' . $id_PrBlAttivita);
        if (sizeof($versamenti) > 0) {
            if (sizeof($bolla) > 0) {
                if ($bolla[0]->Quantita <= $versamenti[0]->Quantita)
                    return '<b>Stai versando ' . $versamenti[0]->Quantita . ' ' . $bolla[0]->Cd_ARMisura . ' su ' . $bolla[0]->Quantita . ' ' . $bolla[0]->Cd_ARMisura . ' richiesti</b>';
            }
        }
    }

    public function lista_versamenti($id_PrBlAttivita)
    {
        $versamenti = DB::select('SELECT * from PrVRAttivitaEx Where Id_PrBLAttivita = ' . $id_PrBlAttivita);
        return View::make('backend.ajax.lista_versamenti', compact('versamenti', 'id_PrBlAttivita'));
    }

    public function dettagli_versamenti($id_PrVrAttivita)
    {
        $versamenti = DB::select('SELECT * from PrVRAttivitaEx Where Id_PrVRAttivita = ' . $id_PrVrAttivita);
        if (sizeof($versamenti) > 0) {
            $versamento = $versamenti[0];
            return View::make('backend.ajax.dettagli_versamento', compact('versamento'));
        }
    }

    public function controlla_lotto($lotto)
    {
        ?>
        <script type="text/javascript">
            $('#articoli_lotto').html('');
            $('#magazzini_lotto').html('');
            $('#articoli_um').html('');
        </script>
        <?php
        $articoli = DB::select('SELECT AR.Cd_AR,AR.Descrizione from AR JOIN ARLotto ON AR.Cd_AR = ARLotto.Cd_AR and ARLotto.Cd_ARLotto = \'' . $lotto . '\'');
        if (sizeof($articoli) == 0) { ?>
            <script type="text/javascript">
                $('#articoli_lotto').append('<option value="">Inserire Lotto</option>');
                $('#magazzini_lotto').append('<option value="">Magazzini Lotto</option>');
                $('#articoli_um').append('<option value="">Inserire Lotto</option>');
            </script>
        <?php } else {

            $ararmisura = DB::select('SELECT * from ARARMisura Where Cd_AR  = \'' . $articoli[0]->Cd_AR . '\''); ?>
            <script type="text/javascript">
                <?php foreach ($ararmisura as $misura) { ?>
                $('#articoli_um').append('<option value="<?php echo $misura->Cd_ARMisura ?>" <?php echo ($misura->DefaultMisura == 1) ? 'selected' : '' ?>><?php echo $misura->Cd_ARMisura ?></option>');
                <?php } ?>
            </script>

            <?php foreach ($articoli as $a) { ?>
                <script type="text/javascript">
                    $('#articoli_lotto').append('<option value="<?php echo $a->Cd_AR ?>"><?php echo $a->Cd_AR ?> - <?php echo $a->Descrizione ?></option>');
                    $('#inserisci_tipo_materiale').val(2)
                </script>
            <?php }

            $magazzini = DB::select('SELECT distinct Cd_MG from MGMov Where Cd_ARLotto = \'' . $lotto . '\'');
            foreach ($magazzini as $m) { ?>
                <script type="text/javascript">
                    $('#magazzini_lotto').append('<option value="<?php echo $m->Cd_MG ?>" <?php echo ($m->Cd_MG == '00009') ? 'selected' : '' ?>><?php echo $m->Cd_MG ?></option>');
                </script>
            <?php }

        }


        $colli = DB::select('SELECT * from xWPCollo Where Nr_Collo = \'' . $lotto . '\'');
        if (sizeof($colli) > 0) { ?>
            <script type="text/javascript">

                $('#articoli_lotto').html('');
                $('#magazzini_lotto').html('');
                $('#articoli_um').html('');

                $('#articoli_lotto').append('<option value="">SemiLavorato</option>');
                $('#magazzini_lotto').append('<option value="">SemiLavorato</option>');
                $('#articoli_um').append('<option value="<?php echo $colli[0]->Cd_ARMisura ?>"><?php echo $colli[0]->Cd_ARMisura ?></option>');


                $('#quantita_inserisci_materiale').val(<?php echo $colli[0]->QtaProdotta ?>)
                $('#inserisci_tipo_materiale').val(3)
            </script>
        <?php }
    }

    public function controlla_lotto_mod($lotto, $Id_PrBLMateriale)
    {
        ?>
        <script type="text/javascript">
            $('#articoli_lotto_<?php echo $Id_PrBLMateriale; ?>').html('');
            $('#magazzini_lotto_<?php echo $Id_PrBLMateriale; ?>').html('');
            $('#articoli_um_<?php echo $Id_PrBLMateriale; ?>').html('');
        </script>
        <?php
        $articoli = DB::select('SELECT AR.Cd_AR,AR.Descrizione from AR JOIN ARLotto ON AR.Cd_AR = ARLotto.Cd_AR and ARLotto.Cd_ARLotto = \'' . $lotto . '\'');
        if (sizeof($articoli) == 0) { ?>
            <script type="text/javascript">
                $('#articoli_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="">Inserire Lotto</option>');
                $('#magazzini_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="">Magazzini Lotto</option>');
                $('#articoli_um_<?php echo $Id_PrBLMateriale; ?>').append('<option value="">Inserire Lotto</option>');
            </script>
        <?php } else {

            $ararmisura = DB::select('SELECT * from ARARMisura Where Cd_AR  = \'' . $articoli[0]->Cd_AR . '\''); ?>
            <script type="text/javascript">
                <?php foreach ($ararmisura as $misura) { ?>
                $('#articoli_um_<?php echo $Id_PrBLMateriale; ?>').append('<option value="<?php echo $misura->Cd_ARMisura ?>" <?php echo ($misura->DefaultMisura == 1) ? 'selected' : '' ?>><?php echo $misura->Cd_ARMisura ?></option>');
                <?php } ?>
            </script>

            <?php foreach ($articoli as $a) { ?>
                <script type="text/javascript">
                    $('#articoli_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="<?php echo $a->Cd_AR ?>"><?php echo $a->Cd_AR ?> - <?php echo $a->Descrizione ?></option>');
                    $('#inserisci_tipo_materiale_<?php echo $Id_PrBLMateriale; ?>').val(2)
                </script>
            <?php }

            $magazzini = DB::select('SELECT distinct Cd_MG from MGMov Where Cd_ARLotto = \'' . $lotto . '\'');
            foreach ($magazzini as $m) { ?>
                <script type="text/javascript">
                    $('#magazzini_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="<?php echo $m->Cd_MG ?>" <?php echo ($m->Cd_MG == '00009') ? 'selected' : '' ?>><?php echo $m->Cd_MG ?></option>');
                </script>
            <?php }

        }


      /*

       $colli = DB::select('SELECT * from xWPCollo Where Nr_Collo = \'' . $lotto . '\'');
        if (sizeof($colli) > 0) { ?>
            <script type="text/javascript">

                $('#articoli_lotto_<?php echo $Id_PrBLMateriale; ?>').html('');
                $('#magazzini_lotto_<?php echo $Id_PrBLMateriale; ?>').html('');
                $('#articoli_um_<?php echo $Id_PrBLMateriale; ?>').html('');

                $('#articoli_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="">SemiLavorato</option>');
                $('#magazzini_lotto_<?php echo $Id_PrBLMateriale; ?>').append('<option value="">SemiLavorato</option>');
                $('#articoli_um_<?php echo $Id_PrBLMateriale; ?>').append('<option value="<?php echo $colli[0]->Cd_ARMisura ?>"><?php echo $colli[0]->Cd_ARMisura ?></option>');


                $('#quantita_inserisci_materiale_<?php echo $Id_PrBLMateriale; ?>').val(<?php echo $colli[0]->QtaProdotta ?>)
                $('#inserisci_tipo_materiale_<?php echo $Id_PrBLMateriale; ?>').val(3)
            </script>
        <?php }
      */
    }

    public function visualizza_file($id_dms)
    {

        $dms = DB::select('SELECT * from DmsDocument Where Id_DmsDocument = ' . $id_dms);
        if (sizeof($dms) > 0) {
            $path = $dms[0]->FilePath . '\\' . $dms[0]->FileName;
            if ($path) {
                header("Content-type: application/pdf");
                header("Content-Disposition: inline; filename=filename.pdf");
                @readfile($path);
            }
        }
    }

    public function load_colli($attivita_bolla, $cd_ar)
    {
        $attivita_bolla = DB::SELECT('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita =' . $attivita_bolla)[0];
        $attivita_bolla->pedane = DB::select('SELECT p.*,AR.PesoNetto as peso_pedana from xWPPD p LEFT JOIN AR ON AR.Cd_AR = p.Cd_xPD Where p.Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by p.Id_xWPPD DESC');
        $attivita_bolla->colli = DB::select('SELECT TOP 100 * from xWPCollo Where Id_PrBLAttivita =  ' . $attivita_bolla->Id_PrBLAttivita . ' order by Id_xWPCollo DESC');

        $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
        if (sizeof($OLAttivita) > 0) {
            $OLAttivita = $OLAttivita[0];

            $articoli = DB::select('SELECT * from AR where cd_ar = \'' . $cd_ar . '\'');
            if (sizeof($articoli) > 0) {
                $articolo = $articoli[0];
                $articolo->UM = DB::select('SELECT * from ARARMisura Where Cd_AR = \'' . $articolo->Cd_AR . '\'');

                return View::make('backend.ajax.colli_bolla', compact('attivita_bolla', 'OLAttivita', 'articolo'));
            }
        }


    }

    public function load_tutti_colli($attivita_bolla, $cd_ar)
    {
        $attivita_bolla = DB::SELECT('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita =' . $attivita_bolla)[0];
        $attivita_bolla->pedane = DB::select('SELECT p.*,AR.PesoNetto as peso_pedana from xWPPD p LEFT JOIN AR ON AR.Cd_AR = p.Cd_xPD Where p.Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by p.Id_xWPPD DESC');
        $attivita_bolla->colli = DB::select('SELECT * from xWPCollo Where Id_PrBLAttivita =  ' . $attivita_bolla->Id_PrBLAttivita . ' order by Id_xWPCollo DESC');

        $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
        if (sizeof($OLAttivita) > 0) {
            $OLAttivita = $OLAttivita[0];

            $articoli = DB::select('SELECT * from AR where cd_ar = \'' . $cd_ar . '\'');
            if (sizeof($articoli) > 0) {
                $articolo = $articoli[0];
                $articolo->UM = DB::select('SELECT * from ARARMisura Where Cd_AR = \'' . $articolo->Cd_AR . '\'');

                return View::make('backend.ajax.colli_bolla', compact('attivita_bolla', 'OLAttivita', 'articolo'));
            }
        }

    }

    public function crea_collo($id, $qta, $esemplari, $cd_armisura, $nr_pedana, $rif1, $rif2)
    {

        $utente = session('utente');


        $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
        if (sizeof($attivita_bolle) > 0) {
            $attivita_bolla = $attivita_bolle[0];
            while ($esemplari > 0) {
                $colli = DB::select('SELECT * from xWPCollo Where NC = 0 and IdCodiceAttivita = ' . $attivita_bolla->Id_PrOLAttivita . ' order by Id_xWPCollo DESC');

                if (sizeof($colli) == 0) {
                    $insert_collo['Nr_Collo'] = $id . '.1';
                    $insert_collo['Descrizione'] = '1';
                } else {

                    $numero = strval(intval($colli[0]->Descrizione) + 1);
                    $insert_collo['Nr_Collo'] = $id . '.' . $numero;
                    $insert_collo['Descrizione'] = $numero;
                }

                $insert_collo['Rif_Nr_Collo'] = ($rif1 != 'undefined') ? $rif1 : '';
                $insert_collo['Rif_Nr_Collo2'] = ($rif2 != 'undefined') ? $rif2 : '';
                $insert_collo['IdOrdineLavoro'] = $attivita_bolla->Id_PrOL;
                $insert_collo['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                $insert_collo['IdCodiceAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                $insert_collo['QtaProdotta'] = $qta;
                $insert_collo['QtaProdottaUmFase'] = $qta;
                $insert_collo['Cd_Operatore'] = $utente->Cd_Operatore;
                $insert_collo['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                $insert_collo['Cd_ARMisura'] = $cd_armisura;
                $insert_collo['Copie'] = 1;
                $insert_collo['NC'] = 0;
                $insert_collo['Nr_Pedana'] = ($nr_pedana != 'undefined') ? $nr_pedana : '';

                $bolle = DB::select('SELECT * from PrBLEx Where Id_PrBL = ' . $attivita_bolla->Id_PrBL);
                if (sizeof($bolle) > 0) {
                    $bolla = $bolle[0];
                    $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                    if (sizeof($ordini) > 0) {
                        $ordine = $ordini[0];
                        $articoli = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'');
                        if (sizeof($articoli) > 0) {
                            $articolo = $articoli[0];
                            $insert_collo['Cd_AR'] = $articolo->Cd_AR;

                            $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $cd_armisura . '\'');
                            if (sizeof($umfatt) > 0) {
                                $umfatt = $umfatt[0]->UMFatt;
                                $insert_collo['QtaProdottaUmFase'] = $qta * $umfatt;
                            }
                        }
                    }
                }


                DB::table('xWPCollo')->insert($insert_collo);

                if (isset($dati['Nr_Pedana'])) {
                    DB::update('
                            update xWPPD
                            Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                            ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                            where Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\'');
                }

                $esemplari--;
            }

        }
    }

    public function chiudi_collo($id, $qta, $esemplari, $cd_armisura, $nr_pedana, $rif1, $rif2)
    {

        $utente = session('utente');


        $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
        if (sizeof($attivita_bolle) > 0) {
            $attivita_bolla = $attivita_bolle[0];


            $nomi_colli = array();

            $colli = DB::select('SELECT * from xWPCollo where QtaVersata < QtaProdotta and Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' order by Nr_Collo ASC');


            $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
            if (sizeof($OLAttivita) > 0) {
                $OLAttivita = $OLAttivita[0];

                if (sizeof($colli) == $esemplari) {

                    /**
                     * Forzatura All Packaging
                     * Stampa la qualità piccola se in fase di saldatura
                     * Altrimenti Stampa la Qualità Grande per il resto delle fasi
                     *
                     **/


                    if ($OLAttivita->Cd_PrAttivita == 'SALDATURA') {

                        $nome_file = StampaController::motore_industry($id, $colli[0]->Nr_Collo, 3);
                        if ($nome_file != '') {
                            array_push($nomi_colli, $nome_file);
                        }

                    } else {

                        $nome_file = StampaController::motore_industry($id, $colli[0]->Nr_Collo, 1);
                        if ($nome_file != '') {
                            array_push($nomi_colli, $nome_file);
                        }
                    }
                }
            }


            /**
             * Forzatura All Packaging
             * Di Default prova a stampare collo grande tipologia 0
             * se la fase è la saldatura stampa collo piccolo tipologia 2
             * se la fase è quella prima dell'imballaggio stampa il collo anonimo tipologia 4
             **/

            $tipologia = 0;
            $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
            if (sizeof($OLAttivita) > 0) {
                $OLAttivita = $OLAttivita[0];

                if ($OLAttivita->Cd_PrAttivita == 'SALDATURA') {
                    $tipologia = 2;
                } else if ($OLAttivita->Id_PrOLAttivita_Next != '') {
                    $OLAttivitaNext = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $OLAttivita->Id_PrOLAttivita_Next);
                    if ((sizeof($OLAttivitaNext) > 0 && $OLAttivitaNext[0]->Cd_PrAttivita == 'IMBALLAGGIO')) {
                        $tipologia = 4;
                    }
                }
            }


            foreach ($colli as $c) {

                if ($c->Stampato == 0) {

                    $nome_file = StampaController::motore_industry($id, $c->Nr_Collo, $tipologia);
                    if ($nome_file != '') {

                        DB::update('update xWPCollo set Stampato = 1 where Nr_Collo = ' . $c->Nr_Collo);

                        $dati['Copie'] = 1;
                        while ($dati['Copie'] > 0) {
                            array_push($nomi_colli, $nome_file);
                            $dati['Copie'] -= 1;
                        }
                    }

                }

            }


            ?>

            <script type="text/javascript">

                pdf = [
                    <?php foreach ($nomi_colli as $st) echo "'" . URL::asset('upload/' . $st . '.pdf') . "'," ?>
                ];

                stampav2(0);
            </script>

            <?php

        }
    }

    public function load_tracciabilita($id_prol)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prol1 = $id_prol;

        $id_prol = DB::SELECT('SELECT PRBLAttivita.Id_PrBLAttivita,PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,xwpGruppiLavoro.Cd_Operatore as Assistente,PROLAttivita .*,PRBLAttivita.* FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN xwpGruppiLavoro ON xwpGruppiLavoro.Id_PrblAttivita = PrblAttivita.Id_PrblAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');

        $id_prbl = '';

        foreach ($id_prol as $i) {
            $id_prbl .= $i->Id_PrBLAttivita . ',';
        }

        $id_prbl = substr($id_prbl, 0, (strlen($id_prbl) - 1));

        $versamenti = DB::SELECT('SELECT SUM(QtaProdotta) as Qta_TOT,IdCodiceAttivita,Cd_ARMisura FROM xWPcollo where IdOrdineLavoro = \'' . $id_prol1 . '\' and Nr_Collo not like \'-%\' Group By IdCodiceAttivita,Cd_ARMisura ORDER BY Cd_ARMisura DESC  ');

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PrBLAttivita in( ' . $id_prbl . ')')[0]->Id_PrBLAttivita;


        $fermi = DB::select('SELECT * FROM PRRLAttivita
                LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
                WHERE PRBLAttivita.Id_PrBLAttivita in( ' . $id_prbl . ') and PRRLAttivita.TipoRilevazione = \'F\' and InizioFine = \'I\' ');

        $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita in (' . $id_prbl . ') ');

        $fermi1 = '';
        $segnalazioni1 = '';

        $note_prvr = DB::SELECT('SELECT NotePRVRATTIVITA,Cd_Operatore FROM PRVRAttivita where Id_PrBlAttivita = \'' . $id_prblattivita . '\' and NotePRVRAttivita = \'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio\' ');


        foreach ($fermi as $f)
            $fermi1 .= '<tr><td> FERMO </td><td>' . $f->DataOra . '</td> <td></td> <td>' . $f->Cd_Operatore . '</td> <td>' . $f->Terminale . '</td></tr>';

        foreach ($segnalazioni as $s)
            $segnalazioni1 .= '<tr><td> SEGNALAZIONE </td> <td>' . $s->TimeIns . ' </td><td> ' . $s->Messaggio . '</td><td> ' . $s->Cd_Operatore . ' </td><td> ' . $s->Cd_PrRisorsa . '</td></tr>';

        ?><h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
        : <?php echo $base->Cd_AR; ?> <strong
            style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
        <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
            style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
    </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Collo/Bobina</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php foreach ($id_prol as $i) { ?>
                <tr onclick="<?php echo ($i->Cd_PrRisorsa != 'IMBALLATRICI') ? 'cerca1(' . $i->Id_PrOLAttivita . ')' : 'cercaimballo(' . $i->Id_PrOLAttivita . ')' ?>">
                    <td>
                        <?php echo $i->Id_PrOLAttivita ?>
                    </td>
                    <td>
                        <?php echo $i->Cd_Operatore;
                        if ($i->Assistente != '') echo ' / ' . $i->Assistente;
                        if (sizeof($note_prvr) > 0 && str_contains($i->Cd_PrRisorsa, 'ST')) echo ' / ' . $note_prvr[0]->Cd_Operatore; ?>
                    </td>
                    <td>
                        <?php echo $i->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($i->DataOra != '') echo date('d/m/Y', strtotime($i->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($i->DataOra != '') echo date('H:i:s', strtotime($i->DataOra)); ?>
                    </td>
                    <td style="text-align: center">

                    </td>
                    <td>
                        <?php foreach ($versamenti as $v) {
                            if ($v->IdCodiceAttivita == $i->Id_PrOLAttivita) {
                                echo $v->Cd_ARMisura;
                            }
                        }
                        if ($i->Cd_PrRisorsa == 'IMBALLATRICI') {
                            echo 'KG';
                        } ?>
                    </td>

                    <td>
                        <?php foreach ($versamenti as $v) {
                            if ($v->IdCodiceAttivita == $i->Id_PrOLAttivita) {
                                echo number_format($v->Qta_TOT, 2, ',', '');
                                $quantita_fase = $v->Qta_TOT;
                            }
                        }
                        if ($i->Cd_PrRisorsa == 'IMBALLATRICI') {
                            $versamenti_imb = DB::SELECT('SELECT SUM(Quantita) as quantita from PRVRAttivita where Id_PRBLAttivita = \'' . $i->Id_PrBLAttivita . '\' ');
                            echo number_format($versamenti_imb[0]->quantita, 2, ',', '');
                        } ?>
                    </td>
                    <td>
                        <?php
                        $quantita = (isset($quantita_fase) and $quantita_fase != 0) ? $quantita_fase : 0;
                        foreach ($versamenti as $v) {
                            if ($v->IdCodiceAttivita == $i->Id_PrOLAttivita) {
                                if ($v->Cd_ARMisura == 'mt') $quantita = ($quantita * $i->xDB_Grammatura) / 1000;
                                if ($v->Cd_ARMisura == 'pz') $quantita = ($quantita * $i->xDB_Pesobusta) / 1000;
                            }
                        }
                        if ($i->Cd_PrRisorsa == 'IMBALLATRICI') {
                            $versamenti_imb = DB::SELECT('SELECT SUM(Quantita) as quantita from PRVRAttivita where Id_PRBLAttivita = \'' . $i->Id_PrBLAttivita . '\' ');
                            $quantita = $versamenti_imb[0]->quantita;
                        }


                        echo number_format($quantita, 2, ',', ''); ?>
                    </td>

                    <!--<td><span class="badge bg-<?php /* echo $color ?>"><?php echo $percent */ ?>%</span></td>-->


                </tr>
                <?php /* $collo = DB::SELECT('SELECT * FROM xWPCollo WHERE IdOrdineLavoro = \''.$i->Id_PrOL.'\' and IdCodiceAttivita = \''.$i->Id_PrOLAttivita.'\' and Rif_Nr_Collo = \'\'');foreach($collo as $c){?>
                    <tr>
                        <td style="text-align: center">
                            <?php echo $c->Nr_Collo ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $c->Cd_Operatore ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $c->Cd_PrRisorsa ?>
                        </td>
                        <td style="text-align: center">
                            <?php if($i->TimeIns     != '')echo date('d/m/Y',strtotime($i->TimeIns) );?>
                        </td>
                        <td style="text-align: center">
                            <?php if($i->TimeIns != '')echo date('H:i:s',strtotime($i->TimeIns) );?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $c->Nr_Pedana ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $c->Cd_ARMisura ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo number_format($c->QtaProdotta,2) ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo number_format($c->QtaProdottaUmFase,2) ?>
                        </td>
                    </tr>
                    <?php $collo1 = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \''.$c->Nr_Collo.'\'');foreach($collo1 as $c1){ ?>
                        <tr>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Collo ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_Operatore ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_PrRisorsa ?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('d/m/Y',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('H:i:s',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Pedana ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo $c1->Cd_ARMisura ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdotta,2) ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdottaUmFase,2) ?>
                            </td>
                        </tr>
                    <?php }
                }*/
            }
            ?>
            </tbody>
        </table>
        <?php
        if ($fermi1 != '' || $segnalazioni1 != '')
            echo '<table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
                            <thead>
                                <tr>
                                    <th>
                                        Segnalazione/Fermo
                                    </th>
                                    <th>
                                        Orario
                                    </th>
                                    <th>
                                        Messaggio
                                    </th>
                                    <th>
                                        Operatore
                                    </th>
                                    <th>
                                        Risorsa
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            ' . $fermi1 . $segnalazioni1 . '
                            </tbody>
                        </table>';
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[0, 'desc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_tracciabilita1($id_prol, $prol_attivita)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PROLAttivita = \'' . $prol_attivita . '\' ')[0]->Id_PrBLAttivita;

        $primo = DB::SELECT('SELECT * FROM PROLAttivita WHERE Id_PROL = \'' . $id_prol . '\' ORDER BY Id_PROlAttivita DESC')[0];
        if ($primo->Cd_PrAttivita != 'ESTRUSIONE')
            $lotto = 1;
        else
            $lotto = 0;
        $fermi = DB::select('SELECT * FROM PRRLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
        WHERE PRBLAttivita.Id_PrBLAttivita = ' . $id_prblattivita . ' and PRRLAttivita.TipoRilevazione = \'F\'');

        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.*,xwpGruppiLavoro.Cd_Operatore as Assistente FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN xwpGruppiLavoro ON PRBLAttivita.Id_PrBLAttivita = xwpGruppiLavoro.Id_PrBLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');
        ?>
        <h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
            : <?php echo $base->Cd_AR; ?> <strong
                style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
            <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
                style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
        </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item active"><a onclick="cerca1()">Colli</a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Collo/Bobina</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody>
            <?php
            $tot = 0;
            $tot_nc = 0;
            $tot_KG = 0;
            $tot_KG_nc = 0;

            $collo = DB::SELECT('SELECT * FROM xWPCollo WHERE IdOrdineLavoro = \'' . $id_prol[0]->Id_PrOL . '\' and IdCodiceAttivita = \'' . $prol_attivita . '\' ');

            $rif_madre = DB::SELECT('SELECT * FROM xWPCollo WHERE Nr_Collo in (SELECT Rif_Nr_Collo FROM xWPCollo WHERE IdOrdineLavoro = \'' . $id_prol[0]->Id_PrOL . '\' and IdCodiceAttivita = \'' . $prol_attivita . '\')');

            $conversione = DB::SELECT('SELECT * FROM PROLAttivita where Id_ProlAttivita = \'' . $prol_attivita . '\'');

            $note_prvr = DB::SELECT('SELECT NotePRVRATTIVITA,Cd_Operatore FROM PRVRAttivita where Id_PrBlAttivita = \'' . $id_prblattivita . '\' and NotePRVRAttivita = \'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio\' ');

            foreach ($collo as $c) {
                ?>
                <tr>
                    <td>
                        <button type="button" style="width:40%;height: 80%;border: none"
                                onclick="cerca_dietro(<?php echo $prol_attivita . ',' . $c->Rif_Nr_Collo . ',';
                                if ($c->Rif_Nr_Collo2 != null) echo $c->Rif_Nr_Collo2; else echo '0'; ?>)"><i
                                class="fa fa-arrow-left"></i></button>

                        <?php $risorsa = DB::SELECT('SELECT (SELECT top 1 descrizione from PRReparto where Cd_PRReparto = PrRisorsa.Cd_PRReparto) as Cd_PRrisorsa FROM PrRisorsa where Cd_PrRisorsa = \'' . $c->Cd_PrRisorsa . '\'')[0]->Cd_PRrisorsa; ?>
                        <?php if ($lotto == 1) { ?>
                            <?php if ($c->Rif_Nr_Collo != '') { ?>
                                <?php $rif_ol = DB::SELECT('SELECT * FROM xWPCollo WHERE Nr_Collo = \'' . $c->Rif_Nr_Collo . '\'');
                                if (sizeof($rif_ol) > 0)
                                    $rif_ol = $rif_ol[0]->IdOrdineLavoro;
                                else
                                    $rif_ol = '';
                                ?>
                                <a style="text-align: left"><?php echo '(' . $c->Rif_Nr_Collo . '<strong> ' . $rif_ol . '</strong>)' ?></a>
                                <a style="text-align: center">DCF:<?php $dcf = DB::select('SELECT * FROM DORIG WHERE Cd_DO = \'DCF\' and Cd_ARLotto = ' . $c->Rif_Nr_Collo . ' ');
                                    if (sizeof($dcf) > 0) echo $dcf[0]->NumeroDoc; ?> - </a>
                                <a style="text-align: right"><?php echo $c->Nr_Collo ?></a>

                            <?php } else { ?>

                                <?php $rif_ol = DB::SELECT('SELECT * FROM xWPCollo WHERE Nr_Collo = \'' . $c->Nr_Collo . '\'');
                                if (sizeof($rif_ol) > 0)
                                    $rif_ol = $rif_ol[0]->IdOrdineLavoro;
                                else
                                    $rif_ol = '';
                                ?>
                                <a style="text-align: left"><?php echo '(' . $c->Rif_Nr_Collo . '<strong> ' . $rif_ol . '</strong>)' ?></a>
                                <a style="text-align: center">DCF:<?php $dcf = DB::select('SELECT * FROM DORIG WHERE Cd_DO = \'DCF\' and Cd_ARLotto = \'' . $c->Rif_Nr_Collo . '\' ');
                                    if (sizeof($dcf) > 0) echo $dcf[0]->NumeroDoc; ?> - </a>
                                <a style="text-align: right"><?php echo $c->Nr_Collo ?></a>

                            <?php }
                        } else { ?>
                            <a style="text-align: center"><?php echo $c->Nr_Collo ?> </a>
                        <?php } ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php echo $c->Cd_Operatore;
                        if ($id_prol[0]->Assistente != '' && str_contains($c->Cd_PrRisorsa, 'ES')) echo ' / ' . $id_prol[0]->Assistente;
                        if (sizeof($note_prvr) > 0 && str_contains($c->Cd_PrRisorsa, 'ST')) echo ' / ' . $note_prvr[0]->Cd_Operatore; ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php echo $c->Cd_PrRisorsa; ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('d/m/Y', strtotime($c->TimeIns)); ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('H:i:s', strtotime($c->TimeIns)); ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php echo $c->Nr_Pedana ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php echo $c->Cd_ARMisura ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php echo number_format($c->QtaProdotta, 2, ',', '');
                        if (substr($c->Nr_Collo, 0, 1) == '-') $tot_nc = $tot_nc + $c->QtaProdotta;
                        else $tot = $tot + $c->QtaProdotta;
                        ?>
                    </td>
                    <td onclick="cerca2(<?php echo $prol_attivita . ',' . $c->Nr_Collo; ?>)" style="text-align: center">
                        <?php
                        $quantita = $c->QtaProdotta;
                        if ($c->Cd_ARMisura == 'mt') $quantita = ($quantita * $conversione[0]->xDB_Grammatura) / 1000;
                        if ($c->Cd_ARMisura == 'pz') $quantita = ($quantita * $conversione[0]->xDB_Pesobusta) / 1000;
                        echo number_format($quantita, 2, ',', '');
                        if (substr($c->Nr_Collo, 0, 1) == '-') $tot_KG_nc = $tot_KG_nc + $quantita;
                        else $tot_KG = $tot_KG + $quantita;
                        ?>
                    </td>
                </tr>

                <?php
            } ?>
            <?php foreach ($fermi as $f) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = ' . $id_prblattivita . ' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Colli: </strong><?php echo number_format($tot, 2, ',', ''); ?></h5>

            <h5><strong>Totali Colli Kg: </strong><?php echo number_format($tot_KG, 2, ',', ''); ?></h5>
            <?php if ($tot_nc > 0) { ?><h5><strong>Totali Colli N.C.
                : </strong><?php echo number_format($tot_nc, 2, ',', ''); ?></h5><?php } ?>
            <?php if ($tot_KG_nc > 0) { ?><h5><strong>Totali Colli N.C.
                Kg: </strong><?php echo number_format($tot_KG_nc, 2, ',', ''); ?></h5> <?php } ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_imballo($id_prol, $prol_attivita)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PROLAttivita = \'' . $prol_attivita . '\' ')[0]->Id_PrBLAttivita;
        $fermi = DB::select('SELECT * FROM PRRLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
        WHERE PRBLAttivita.Id_PrBLAttivita = ' . $id_prblattivita . ' and PRRLAttivita.TipoRilevazione = \'F\'');
        $tot_Kg = 0;
        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.* FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');
        ?>
        <h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
            : <?php echo $base->Cd_AR; ?> <strong
                style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
            <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
                style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?> </strong>: <?php echo $base1->NumeroDoc ?>
        </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item active"><a onclick="">Pedane</a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Numero Colli</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php /* foreach ($id_prol as $i){ ?>
                <tr>
                    <td>
                        <?php echo $i->Id_PrOLAttivita ?>
                    </td>
                    <td>
                        <?php echo $i->Cd_Operatore ?>
                    </td>
                    <td>
                        <?php echo $i->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if($i->DataOra != '')echo date('d/m/Y',strtotime($i->DataOra) );?>
                    </td>
                    <td style="text-align: center">
                        <?php if($i->DataOra != '')echo date('H:i:s',strtotime($i->DataOra) );?>
                    </td>
                    <td style="text-align: center">

                    </td>
                    <td>
                        <?php echo $i->Cd_ARMisura ?>
                    </td>
                    <td>
                        <?php echo number_format($i->Quantita,2) ?>
                    </td>
                    <td>
                        <?php echo number_format($i->Quantita,2) ?>
                    </td>
                    <!--<td><span class="badge bg-<?php /* echo $color ?>"><?php echo $percent *//*?>%</span></td>-->


                </tr>
                <?php */
            $collo = DB::SELECT('SELECT PRVRAttivita.Cd_Operatore,PRVRAttivita.Cd_PRRisorsa,* FROM xWPPD
                LEFT JOIN PRVRAttivita ON PRVRAttivita.Id_PRVRAttivita = xWPPD.Id_PrVrAttivita
                WHERE xWPPD.Id_PrOL = \'' . $id_prol[0]->Id_PrOL . '\' ');
            foreach ($collo as $c) {
                ?>
                <tr onclick="cercaimballo2('<?php echo $prol_attivita ?>','<?php echo $c->Nr_Pedana; ?>')">
                    <td style="text-align: center">
                        <?php echo $c->NumeroColli ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Cd_PRRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('d/m/Y', strtotime($c->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('H:i:s', strtotime($c->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo 'KG'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($c->QuantitaProdotta, 2, ',', '') ?>
                        <?php $tot_Kg = $tot_Kg + $c->QuantitaProdotta; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($c->QuantitaProdotta, 2, ',', '') ?>
                        <?php //echo number_format($c->QtaProdottaUmFase,2)
                        ?>
                    </td>
                </tr>
                <?php /*$collo1 = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \''.$c->Nr_Collo.'\'');foreach($collo1 as $c1){ ?>
                        <tr>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Collo ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_Operatore ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_PrRisorsa ?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('d/m/Y',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('H:i:s',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Pedana ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo $c1->Cd_ARMisura ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdotta,2) ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdottaUmFase,2) ?>
                            </td>
                        </tr>
                    <?php }
            }*/
            }
            foreach ($fermi as $f) {
                ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = ' . $id_prblattivita . ' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Pedane Kg: </strong><?php echo number_format($tot_Kg, 2, ',', ''); ?></h5>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_imballo2($id_prol, $prol_attivita, $Nr_Pedana)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PROLAttivita = \'' . $prol_attivita . '\' ')[0]->Id_PrBLAttivita;
        $fermi = DB::select('SELECT * FROM PRRLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
        WHERE PRBLAttivita.Id_PrBLAttivita = ' . $id_prblattivita . ' and PRRLAttivita.TipoRilevazione = \'F\'');

        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.* FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');
        $tot_KG = 0;
        ?>
        <h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
            : <?php echo $base->Cd_AR; ?> <strong
                style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
            <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
                style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?> </strong>: <?php echo $base1->NumeroDoc ?>
        </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item"><a onclick="cercaimballo('<?php echo $id_prol[0]->Id_PrOLAttivita ?>')">Pedane</a>
                </li>
                <li class="breadcrumb-item active"><a onclick="">Colli sulla Pedana (<?php echo $Nr_Pedana ?>)</a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Numero Colli</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php $tot = 0;
            $tot_KG = 0;
            $collo = DB::SELECT('SELECT * FROM xWPCollo WHERE Nr_Pedana = \'' . $Nr_Pedana . '\'');
            foreach ($collo as $c) {
                $conversione = DB::SELECT('SELECT * FROM PROLAttivita where Id_ProlAttivita = \'' . $c->IdCodiceAttivita . '\''); ?>
                <tr <?php // onclick="cerca2(<?php // echo $prol_attivita.','.$c->Nr_Collo;)"
                ?>>
                    <td style="text-align: center">
                        <?php echo $c->Nr_Collo ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('d/m/Y', strtotime($c->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c->TimeIns != '') echo date('H:i:s', strtotime($c->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($c->QtaProdotta, 2, ',', '');
                        $tot = $tot + $c->QtaProdotta ?>
                    </td>
                    <td style="text-align: center">
                        <?php
                        if ($c->Cd_ARMisura == 'mt') $quantita = ($c->QtaProdotta * $conversione[0]->xDB_Grammatura) / 1000;
                        if ($c->Cd_ARMisura == 'pz') $quantita = ($c->QtaProdotta * $conversione[0]->xDB_Pesobusta) / 1000;
                        echo number_format($quantita, 2, ',', '');
                        $tot_KG = $tot_KG + $quantita; ?>
                    </td>
                </tr>
                <?php /*$collo1 = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \''.$c->Nr_Collo.'\'');foreach($collo1 as $c1){ ?>
                        <tr>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Collo ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_Operatore ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Cd_PrRisorsa ?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('d/m/Y',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: center">
                                <?php if($c1->TimeIns != '')echo date('H:i:s',strtotime($c1->TimeIns) );?>
                            </td>
                            <td style="text-align: right">
                                <?php echo $c1->Nr_Pedana ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo $c1->Cd_ARMisura ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdotta,2) ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($c1->QtaProdottaUmFase,2) ?>
                            </td>
                        </tr>
                    <?php }
            }*/
            } ?>

            <?php
            foreach ($fermi as $f) {
                ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = ' . $id_prblattivita . ' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Colli: </strong><?php echo number_format($tot, 2, ',', ''); ?></h5>

            <h5><strong>Totali Colli Kg: </strong><?php echo number_format($tot_KG, 2, ',', ''); ?></h5>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_tracciabilita2($id_prol, $prol_attivita, $Nr_Collo)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PROLAttivita = \'' . $prol_attivita . '\' ')[0]->Id_PrBLAttivita;

        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.*,xwpGruppiLavoro.Cd_Operatore as Assistente FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN xwpGruppiLavoro ON PRBLAttivita.Id_PrBLAttivita = xwpGruppiLavoro.Id_PrBLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');


        $note_prvr = DB::SELECT('SELECT NotePRVRATTIVITA,Cd_Operatore FROM PRVRAttivita where Id_PrBlAttivita = \'' . $id_prblattivita . '\' and NotePRVRAttivita = \'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio\' ');


        ?><h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
        : <?php echo $base->Cd_AR; ?> <strong
            style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
        <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
            style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
    </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item"><a onclick="cerca1(<?php echo $prol_attivita ?>)">Colli</a></li>
                <li class="breadcrumb-item active"><a onclick="cerca2(<?php echo $prol_attivita . ',' . $Nr_Collo ?>)">Da
                        Collo Madre (<?php echo $Nr_Collo ?>) </a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Collo/Bobina</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php $tot = 0;
            $tot_KG = 0;
            $collo1 = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \'' . $Nr_Collo . '\'');
            foreach ($collo1 as $c1) {
                $conversione = DB::SELECT('SELECT * FROM PROLAttivita where Id_ProlAttivita = \'' . $c1->IdCodiceAttivita . '\''); ?>

                <tr>
                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php echo $c1->Nr_Collo ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo $c1->Cd_Operatore;
                        if ($id_prol[0]->Assistente != '' && str_contains($c1->Cd_PrRisorsa, 'ES')) echo ' / ' . $id_prol[0]->Assistente;
                        if (sizeof($note_prvr) > 0 && str_contains($c1->Cd_PrRisorsa, 'ST')) echo ' / ' . $note_prvr[0]->Cd_Operatore; ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo $c1->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c1->TimeIns != '') echo date('d/m/Y', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($c1->TimeIns != '') echo date('H:i:s', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo $c1->Nr_Pedana ?>
                    </td>

                    <td style="text-align: right">
                        <?php echo $c1->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo number_format($c1->QtaProdotta, 2, ',', '');
                        $tot = $tot + $c1->QtaProdotta ?>
                    </td>
                    <td style="text-align: right">
                        <?php $quantita = $c1->QtaProdotta;
                        if ($c1->Cd_ARMisura == 'mt') $quantita = ($quantita * $conversione[0]->xDB_Grammatura) / 1000;
                        if ($c1->Cd_ARMisura == 'pz') $quantita = ($quantita * $conversione[0]->xDB_Pesobusta) / 1000;
                        echo $quantita;

                        $tot_KG = $tot_KG + $quantita ?>
                    </td>
                </tr>
            <?php } ?>


            <?php
            $id_prblattivita = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \'' . $Nr_Collo . '\'');
            if (sizeof($id_prblattivita) > 0) $id_prblattivita = $id_prblattivita[0]->Id_PRBLAttivita; else $id_prblattivita = '';
            $fermi = DB::select('SELECT * FROM PRRLAttivita
                    LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
                    WHERE PRBLAttivita.Id_PrBLAttivita = \'' . $id_prblattivita . '\' and PRRLAttivita.TipoRilevazione = \'F\''); ?>

            <?php foreach ($fermi as $f) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = \'' . $id_prblattivita . '\' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Colli: </strong><?php echo number_format($tot, 2, ',', ''); ?></h5>

            <h5><strong>Totali Colli Kg: </strong><?php echo number_format($tot_KG, 2, ',', ''); ?></h5>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_tracciabilita_dietro($id_prol, $prol_attivita, $Rif_Nr_Collo, $Rif_Nr_Collo2)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prblattivita = DB::SELECT('SELECT * FROM PRBLAttivita WHERE Id_PROLAttivita = \'' . $prol_attivita . '\' ')[0]->Id_PrBLAttivita;

        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.*,xwpGruppiLavoro.Cd_Operatore as Assistente FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN xwpGruppiLavoro ON PRBLAttivita.Id_PrBLAttivita = xwpGruppiLavoro.Id_PrBLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');


        $note_prvr = DB::SELECT('SELECT NotePRVRATTIVITA,Cd_Operatore FROM PRVRAttivita where Id_PrBlAttivita = \'' . $id_prblattivita . '\' and NotePRVRAttivita = \'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio\' ');

        $Nr_Collo = DB::SELECT('SELECT * FROM xWPCollo WHERE Rif_Nr_Collo = \'' . $Rif_Nr_Collo . '\'');
        if (sizeof($Nr_Collo) > 0) {
            $Nr_Collo = $Nr_Collo[0]->Nr_Collo;
        } else {
            $Nr_Collo = '';
        }
        ?><h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
        : <?php echo $base->Cd_AR; ?> <strong
            style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
        <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
            style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
    </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item"><a onclick="cerca1(<?php echo $prol_attivita ?>)">Colli</a></li>
                <li class="breadcrumb-item active"><a onclick="cerca2(<?php echo $prol_attivita . ',' . $Nr_Collo ?>)">Da
                        Collo Figlio (<?php echo $Nr_Collo ?>) </a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Collo/Bobina</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php $tot = 0;
            $tot_KG = 0;
            $collo1 = DB::SELECT('SELECT * FROM xWPCollo WHERE  Nr_Collo in (\'' . $Rif_Nr_Collo . '\',\'' . $Rif_Nr_Collo2 . '\')');
            foreach ($collo1 as $c1) {
                $conversione = DB::SELECT('SELECT * FROM PROLAttivita where Id_ProlAttivita = \'' . $c1->IdCodiceAttivita . '\''); ?>
                <tr>
                    <td style="text-align: right">
                        <button type="button" style="width:40%;height: 80%;border: none"
                                onclick="cerca_dietro(<?php echo $prol_attivita . ',' . $c1->Rif_Nr_Collo . ',';
                                if ($c1->Rif_Nr_Collo2 != null) echo $c1->Rif_Nr_Collo2; else echo 0; ?>)"><i
                                class="fa fa-arrow-left"></i></button>
                        <?php $vecchio_prol = DB::SELECT('SELECT Id_PRol from PRolAttivita where Id_PROLAttivita = ' . $c1->IdCodiceAttivita);
                        if (sizeof($vecchio_prol) > 0) echo $vecchio_prol[0]->Id_PRol . ' - ' . $c1->Nr_Collo; else echo $c1->Nr_Collo; ?>
                    </td>
                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php echo $c1->Cd_Operatore;
                        if ($id_prol[0]->Assistente != '' && str_contains($c1->Cd_PrRisorsa, 'ES')) echo ' / ' . $id_prol[0]->Assistente;
                        if (sizeof($note_prvr) > 0 && str_contains($c1->Cd_PrRisorsa, 'ST')) echo ' / ' . $note_prvr[0]->Cd_Operatore; ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo $c1->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php if ($c1->TimeIns != '') echo date('d/m/Y', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: center"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php if ($c1->TimeIns != '') echo date('H:i:s', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php echo $c1->Nr_Pedana ?>
                    </td>

                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php echo $c1->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php echo number_format($c1->QtaProdotta, 2, ',', '');
                        $tot = $tot + $c1->QtaProdotta ?>
                    </td>
                    <td style="text-align: right"
                        onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>','<?php echo $prol_attivita; ?>')">
                        <?php $quantita = $c1->QtaProdotta;
                        if ($c1->Cd_ARMisura == 'mt') $quantita = ($quantita * $conversione[0]->xDB_Grammatura) / 1000;
                        if ($c1->Cd_ARMisura == 'pz') $quantita = ($quantita * $conversione[0]->xDB_Pesobusta) / 1000;
                        echo $quantita;

                        $tot_KG = $tot_KG + $quantita ?>
                    </td>
                </tr>
            <?php } ?>


            <?php /*
            $id_prblattivita = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \'' . $Rif_Nr_Collo . '\'');
            if (sizeof($id_prblattivita) > 0) $id_prblattivita = $id_prblattivita[0]->Id_PRBLAttivita; else $id_prblattivita = '';
            $fermi = DB::select('SELECT * FROM PRRLAttivita
                    LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
                    WHERE PRBLAttivita.Id_PrBLAttivita = \'' . $id_prblattivita . '\' and PRRLAttivita.TipoRilevazione = \'F\''); ?>

            <?php foreach ($fermi as $f) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = \'' . $id_prblattivita . '\' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } */ ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Colli: </strong><?php echo number_format($tot, 2, ',', ''); ?></h5>

            <h5><strong>Totali Colli Kg: </strong><?php echo number_format($tot_KG, 2, ',', ''); ?></h5>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public function load_cerca_collo($id_prol, $prol_attivita, $Nr_Collo)
    {

        $base = DB::SELECT('SELECT * FROM PRol where Id_PROl = \'' . $id_prol . '\'')[0];

        $base1 = DB::SELECT('SELECT PROLDoRig.*,DORig.NumeroDoc,CF.Descrizione,DORig.Cd_DO
        FROM PROLDoRig
        LEFT JOIN DORig ON PROLDoRig.Id_DoRig = DORIG.Id_DORig
        LEFT JOIN CF    ON CF.Cd_CF = DORig.Cd_CF
        where PROLDoRig.Id_PrOL = \'' . $id_prol . '\'')[0];

        $id_prol = DB::SELECT('SELECT PRRLAttivita.DataOra,PRRLAttivita.Cd_Operatore,PROLAttivita .*,PRBLAttivita.*,xwpGruppiLavoro.Cd_Operatore as Assistente FROM PROLAttivita
        LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
        LEFT JOIN xwpGruppiLavoro ON PRBLAttivita.Id_PrBLAttivita = xwpGruppiLavoro.Id_PrBLAttivita
        LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and DataOra = (SELECT  MIN(DataOra)  FROM PRRLAttivita WHERE PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\')
        WHERE Id_PRol =  \'' . $id_prol . '\' ORDER BY PROLAttivita .Id_PrOLAttivita DESC ');
        $ordine = DB::SELECT('SELECT IdCodiceAttivita,Id_PRBLAttivita FROM xWPCollo WHERE  Nr_Collo = \'' . $Nr_Collo . '\'');

        if (sizeof($ordine) > 0) {
            $collo2 = DB::SELECT('Select * from xWPCollo WHERE Rif_Nr_Collo = \'' . $Nr_Collo . '\'');
            $note_prvr = DB::SELECT('SELECT NotePRVRATTIVITA,Cd_Operatore FROM PRVRAttivita where Id_PrBlAttivita = \'' . $ordine[0]->Id_PRBLAttivita . '\' and NotePRVRAttivita = \'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio\' ');
        }/* else {
            $collo2 = [];
            $note_prvr = [];
        }*/
        ?>
        <h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
            : <?php echo $base->Cd_AR; ?> <strong
                style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
            <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
                style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
        </h3><br><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
                <li class="breadcrumb-item"><a onclick="cerca1(<?php echo $prol_attivita ?>)">Colli</a></li>
                <li class="breadcrumb-item active"><a onclick="cerca2(<?php echo $prol_attivita . ',' . $Nr_Collo ?>)">Da
                        Collo Madre (<?php echo $Nr_Collo ?>) </a></li>
                <li class="breadcrumb-item active" href="#"> Da Collo Figlio Precedente</a></li>
            </ol>
        </nav>
        <table class="table table-bordered dataTable" id="ciao" style="width:100%;font-size:20px;">
            <thead>
            <tr>
                <th style="width:50px;text-align: center">Collo/Bobina</th>
                <th style="width:50px;text-align: center">Operatore / Assistente</th>
                <th style="width:50px;text-align: center">Risorsa</th>
                <th style="width:50px;text-align: center">Data</th>
                <th style="width:50px;text-align: center">Ora</th>
                <th style="width:50px;text-align: center">Id_Pedana</th>
                <th style="width:50px;text-align: center">Misura</th>
                <th style="width:50px;text-align: center">Qta</th>
                <th style="width:50px;text-align: center">QtaKG</th>
                <!--  <th style="width:50px;text-align: center">QtaEffettiva</th>-->
            </tr>
            </thead>
            <tbody><?php $tot = 0;
            $tot_KG = 0;

            foreach ($collo2 as $c1) {
                $conversione = DB::SELECT('SELECT * FROM PROLAttivita where Id_ProlAttivita = \'' . $c1->IdCodiceAttivita . '\''); ?>

                <tr>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo $c1->Nr_Collo ?>
                    </td>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo $c1->Cd_Operatore;
                        if ($id_prol[0]->Assistente != '' && str_contains($c1->Cd_PrRisorsa, 'ES')) echo ' / ' . $id_prol[0]->Assistente;
                        if (sizeof($note_prvr) > 0 && str_contains($c1->Cd_PrRisorsa, 'ST')) echo ' / ' . $note_prvr[0]->Cd_Operatore; ?>
                    </td>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo $c1->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php if ($c1->TimeIns != '') echo date('d/m/Y', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: center" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php if ($c1->TimeIns != '') echo date('H:i:s', strtotime($c1->TimeIns)); ?>
                    </td>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo $c1->Nr_Pedana ?>
                    </td>

                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo $c1->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php echo number_format($c1->QtaProdotta, 2, ',', '');
                        $tot = $tot + $c1->QtaProdotta ?>
                    </td>
                    <td style="text-align: right" onclick="cerca_collo('<?php echo $c1->Nr_Collo; ?>')">
                        <?php $quantita = $c1->QtaProdotta;
                        if ($c1->Cd_ARMisura == 'mt') $quantita = ($quantita * $conversione[0]->xDB_Grammatura) / 1000;
                        if ($c1->Cd_ARMisura == 'pz') $quantita = ($quantita * $conversione[0]->xDB_Pesobusta) / 1000;
                        echo $quantita;

                        $tot_KG = $tot_KG + $quantita ?>
                    </td>
                </tr>
            <?php } ?>


            <?php
            $id_prblattivita = DB::SELECT('SELECT * FROM xWPCollo WHERE  Rif_Nr_Collo = \'' . $Nr_Collo . '\'');
            if (sizeof($id_prblattivita) > 0) $id_prblattivita = $id_prblattivita[0]->Id_PRBLAttivita; else $id_prblattivita = '';
            $fermi = DB::select('SELECT * FROM PRRLAttivita
                    LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrBLAttivita = PRRLAttivita.Id_PrBLAttivita
                    WHERE PRBLAttivita.Id_PrBLAttivita = \'' . $id_prblattivita . '\' and PRRLAttivita.TipoRilevazione = \'F\''); ?>

            <?php foreach ($fermi as $f) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo ($f->InizioFine == 'I') ? 'Inizio Fermo Macchina' : 'Fine Fermo Macchina' ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $f->Terminale ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('d/m/Y', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($f->DataOra != '') echo date('H:i:s', strtotime($f->DataOra)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php // echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita, 2, ',', '') ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($f->Quantita_Scar, 2, ',', '') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php $segnalazioni = DB::select('SELECT * FROM xWPSegnalazione WHERE Id_PrBLAttivita = \'' . $id_prblattivita . '\' '); ?>

            <?php foreach ($segnalazioni as $s) { ?>
                <tr onclick="">
                    <td style="text-align: center">
                        <?php echo 'Segnalazione'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_Operatore ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Cd_PrRisorsa ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('d/m/Y', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php if ($s->TimeIns != '') echo date('H:i:s', strtotime($s->TimeIns)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $s->Messaggio;// echo $c->Nr_Pedana ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo $c->Cd_ARMisura ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita,2) ?>
                    </td>
                    <td style="text-align: center">
                        <?php //echo number_format($s->Quantita_Scar,2) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="text-align: right">
            <h5><strong>Totali Colli: </strong><?php echo number_format($tot, 2, ',', ''); ?></h5>

            <h5><strong>Totali Colli Kg: </strong><?php echo number_format($tot_KG, 2, ',', ''); ?></h5>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#ciao').DataTable({"order": [[3, 'asc'], [4, 'asc']], "pageLength": 50});
            });
            document.getElementById('numero_ol').innerHTML = 'Tracciabilita dell \' OL ' + '<?php echo $id_prol[0]->Id_PrOL ?>'
        </script>

        <?php
    }

    public
    function get_bolla($id)
    {

        $utente = session('utente');

        $attivita = DB::select('SELECT * from PRBLAttivita Where Id_PrOLAttivita IN (
                SELECT Id_PrOLAttivita from PROLAttivita Where Cd_PrAttivita = \'IMBALLAGGIO\' and Id_PrOL IN (
                    SELECT Id_PrOL from PROL Where Id_PrOL IN (
                        SELECT Id_PrOL from xWPPD Where Nr_Pedana = \'' . trim($id) . '\'
                    )
                )
            )');

        if (sizeof($attivita) > 0) {
            echo $attivita[0]->Id_PrBLAttivita;
            exit;
        }


        $attivita = DB::select('SELECT * from PRBLAttivita Where Id_PrBLAttivita = ' . $id);
        if (sizeof($attivita) > 0) {
            echo $attivita[0]->Id_PrBLAttivita;
            exit;
        }
    }


    public
    function set_stampato($nome_file)
    {

        $stampe = DB::select('SELECT * from xStampeIndustry Where nome_file = \'' . $nome_file . '\'');
        if (sizeof($stampe) > 0) {
            $stampa = $stampe[0];
            DB::update('update xStampeIndustry set Stampato = 1 Where Id_xStampeIndustry =' . $stampa->Id_xStampeIndustry);

            if ($stampa->Collo != '') {
                DB::update('update xWPCollo set Stampato = 1 where  Nr_Collo = \'' . $stampa->Collo . '\'');
            }
        }

    }

    public
    function modifica_pedana_imballaggio($id)
    {


        $pedane = DB::select('
            SELECT p.*,c.Descrizione as cliente,a.Cd_AR,PROL.Id_PrOL,a.Descrizione as Descrizione_Articolo,PRBLAttivita.NotePrBLAttivita,a.xPesobobina,a.xBase,a.PesoNetto as peso_pedana,PRBLAttivita.Id_PrBLAttivita,PRRLAttivita.Id_PrRLAttivita  from xWPPD  p
            LEFT JOIN PROL ON PROL.Id_PrOL = p.Id_PrOL
            LEFT JOIN AR a ON a.Cd_AR = PROL.Cd_AR
            LEFT JOIN PROLDorig ON PROLDorig.Id_PrOL = PROL.Id_PrOL
            LEFT JOIN DOrig d ON d.Id_Dorig = PROLDorig.Id_Dorig
            LEFT JOIN CF c ON c.Cd_CF = d.Cd_CF
            Left JOIN PROLAttivita ON PROLAttivita.Id_PrOL = PROL.Id_PrOL and PROLAttivita.Cd_PrAttivita = \'IMBALLAGGIO\'
            LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
            LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and PRRLAttivita.TipoRilevazione = \'E\' and PRRLAttivita.NotePrRLAttivita = p.Nr_Pedana
            where p.Id_xWPPD = ' . $id . '
        ');

        if (sizeof($pedane) > 0) {
            $p = $pedane[0];
            $p->mandrini = DB::select('SELECT * from AR Where Cd_AR IN (SELECT xMandrino from AR Where Cd_AR = \'' . $p->Cd_AR . '\')');
            $p->colli = DB::select('SELECT * from xWPCollo Where IdOrdineLavoro = ' . $p->Id_PrOL . ' and Id_PRBLAttivita IN (SELECT TOP 1 Id_PRBLAttivita from xWPCollo Where IdOrdineLavoro = ' . $p->Id_PrOL . ' Order By Id_PRBLAttivita DESC) order by Id_xWPCollo DESC');
            $pallet = DB::select('SELECT * from AR Where Cd_AR LIKE \'05%\'');

            return View::make('backend.ajax.modifica_pedana_imballaggio', compact('p', 'pallet'));

        }

    }

    public
    function cerca_pedana($nr_pedana)
    {

        $nr_pedana = 'P.' . $nr_pedana;

        $pedane = DB::select('

            SELECT p.*,c.Descrizione as cliente,a.Cd_AR,a.Descrizione as Descrizione_Articolo,PRBLAttivita.NotePrBLAttivita,a.xPesobobina,a.xBase,a.PesoNetto as peso_pedana,PrBLAttivita.Id_PrBLAttivita,PRRLAttivita.Id_PrRLAttivita  from xWPPD  p
            LEFT JOIN PROL ON PROL.Id_PrOL = p.Id_PrOL
            LEFT JOIN AR a ON a.Cd_AR = PROL.Cd_AR
            LEFT JOIN PROLDorig ON PROLDorig.Id_PrOL = PROL.Id_PrOL
            LEFT JOIN DOrig d ON d.Id_Dorig = PROLDorig.Id_Dorig
            LEFT JOIN CF c ON c.Cd_CF = d.Cd_CF
            Left JOIN PROLAttivita ON PROLAttivita.Id_PrOL = PROL.Id_PrOL and PROLAttivita.Cd_PrAttivita = \'IMBALLAGGIO\'
            LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
            LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and PRRLAttivita.TipoRilevazione = \'E\' and PRRLAttivita.NotePrRLAttivita = p.Nr_Pedana
            where p.Nr_Pedana LIKE \'%' . $nr_pedana . '%\'

        ');

        return View::make('backend.ajax.cerca_pedana', compact('pedane'));

    }


    public
    function get_etichetta($id)
    {


        $etichette = DB::select('SELECT * from xSPReport where Id_xSPReport=' . $id);
        $clienti = DB::select('SELECT Cd_CF,Descrizione from CF where Cliente = 1');
        $articoli = DB::select('SELECT * from AR');
        $fasi = DB::select('SELECT Cd_PrAttivita from PRAttivita');

        return View::make('backend.ajax.get_etichetta', compact('etichette', 'clienti', 'articoli', 'fasi'));

    }
}
