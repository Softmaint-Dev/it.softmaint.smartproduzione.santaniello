<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelExport;


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

    public
    function load_tracciabilita($lotto)
    {

        $carico = DB::SELECT('	SELECT MGMov.Cd_AR,MGMov.Cd_ARLotto,MGMov.Quantita,ARARMisura.Cd_ARMisura,Mgmov.DataMov,DORig.NumeroDoc as NumeroOVC,DDT.NumeroDoc as NumeroDDT,PROL.Numero as NumeroOL,PRVRAttivita.Note as Note
                    FROM
                        MGMov
                        Left  Join MGMovInt 	On MGMov.Id_MGMovInt 			= MGMovInt.Id_MGMovInt
						LEFT  JOIN PRVRMateriale ON MGMov.Id_PrVRMateriale = PRVRMateriale.Id_PRVRMateriale
						LEFT  JOIN PRVRAttivita ON PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
						Left  Join PROLDoRig    On PROLDoRig.Id_PrOL= PrOL.Id_PrOL
						Left  Join DORig 		On DORig.Id_DORig 			= PROLDoRig.Id_DORig
						Left  Join DORig DDT    On DDT.Id_DORig_Evade = DORig.Id_DoRig
						Inner Join AR	 		On AR.Cd_AR 					= MGMov.Cd_AR
                        Left  Join ARARMisura	On AR.Cd_AR 					= ARARMisura.Cd_AR And ARARMisura.DefaultMisura = 1
						--Left  Join DORig DDT    On DDT.Id_DORig = MGMov.Id_DoRig
                        --Left  Join DORig 		On DORig.Id_DORig 			= DDT.Id_DORig_Evade
                        --Left  Join PROLDoRig    On PROLDoRig.Id_DoRig           = DoRig.Id_DoRig
                        --Left  Join PRVRMateriale on PRVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale
                        --Left  Join PRVRAttivita  on PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        --Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        --Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        --Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
                       --Left  Join PrVRMateriale PrVRMaterialeT on PrVRMaterialeT.Id_PRVRAttivita IN
                       --(SELECT Id_PRVRAttivita from PRVRAttivita  where Id_PRBLAttivita in (SELECT Id_PRBLAttivita  FROM PRBLAttivita  WHERE Id_PROLAttivita in (SELECT Id_PROLAttivita from PROLAttivita  where Id_PROL = PrOL.Id_PROL)))
                        --Inner Join MG			On MG.Cd_MG						= PrVRMaterialeT.Cd_MG
                    Where
                        MGMov.Ini = 0
						AND MGMov.PartenzaArrivo = \'A\'
						and mgmov.Cd_ARLotto = \'' . $lotto . '\'
						and MGMov.Id_PrVRMateriale is not null');

        $scarico = DB::SELECT('	SELECT MGMov.Cd_AR,MGMov.Cd_ARLotto,MGMov.Quantita,ARARMisura.Cd_ARMisura,Mgmov.DataMov,DORig.NumeroDoc as NumeroOVC,DDT.NumeroDoc as NumeroDDT,PROL.Numero as NumeroOL,PRVRAttivita.Note as Note
                    FROM
                        MGMov
                        Left  Join MGMovInt 	On MGMov.Id_MGMovInt 			= MGMovInt.Id_MGMovInt
						LEFT  JOIN PRVRMateriale ON MGMov.Id_PrVRMateriale = PRVRMateriale.Id_PRVRMateriale
						LEFT  JOIN PRVRAttivita ON PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
						Left  Join PROLDoRig    On PROLDoRig.Id_PrOL= PrOL.Id_PrOL
						Left  Join DORig 		On DORig.Id_DORig 			= PROLDoRig.Id_DORig
						Left  Join DORig DDT    On DDT.Id_DORig_Evade = DORig.Id_DoRig
						Inner Join AR	 		On AR.Cd_AR 					= MGMov.Cd_AR
                        Left  Join ARARMisura	On AR.Cd_AR 					= ARARMisura.Cd_AR And ARARMisura.DefaultMisura = 1
						--Left  Join DORig DDT    On DDT.Id_DORig = MGMov.Id_DoRig
                        --Left  Join DORig 		On DORig.Id_DORig 			= DDT.Id_DORig_Evade
                        --Left  Join PROLDoRig    On PROLDoRig.Id_DoRig           = DoRig.Id_DoRig
                        --Left  Join PRVRMateriale on PRVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale
                        --Left  Join PRVRAttivita  on PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        --Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        --Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        --Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
                       --Left  Join PrVRMateriale PrVRMaterialeT on PrVRMaterialeT.Id_PRVRAttivita IN
                       --(SELECT Id_PRVRAttivita from PRVRAttivita  where Id_PRBLAttivita in (SELECT Id_PRBLAttivita  FROM PRBLAttivita  WHERE Id_PROLAttivita in (SELECT Id_PROLAttivita from PROLAttivita  where Id_PROL = PrOL.Id_PROL)))
                        --Inner Join MG			On MG.Cd_MG						= PrVRMaterialeT.Cd_MG
                    Where
                        MGMov.Ini = 0
						AND MGMov.PartenzaArrivo = \'P\'
						and mgmov.Cd_ARLotto = \'' . $lotto . '\'
						and MGMov.Id_PrVRMateriale is not null ');

        $documenti = DB::SELECT('SELECT DORig.Cd_AR,DORig.Cd_ARLotto,Dorig.Qta as Quantita,DORig.Cd_ARMisura,DOTes.DataDoc,DOTes.NumeroDoc,DOTes.Cd_Do,,\'Ciao\' as Note
                                       CASE
                                       WHEN (DOTes.Cd_Do = \'OVC\')
                                       THEN
                                       (SELECT Numero from PRol where Id_PROl in (SELECT TOP 1 Id_PROL FROM PROLDoRig where Id_DoRig = DORig.Id_DORig))
                                       WHEN (DOTes.Cd_Do = \'DDT\')
                                       THEN
                                       (SELECT Numero from PRol where Id_PROl in (SELECT TOP 1 Id_PROL FROM PROLDoRig where Id_DoRig = DORig.Id_DORig_Evade))
                                       ELSE NULL
                                       END as NumeroOL
                                       FROM DORig
                                       LEFT JOIN DOTes ON DOTes.Id_DoTes = DORig.Id_DOTes
                                       WHERE
                                       DORig.Cd_ARLotto = \'' . $lotto . '\'
                                       AND DOTes.Cd_Do IN (\'DDT\',\'OVC\')');
        ?>
        <?php /*<h3 class="card-title" id="info_ol" style="width: 100%;text-align: center"><strong>Articolo</strong>
            : <?php echo $base->Cd_AR; ?> <strong
                style="margin-left: 40px;">Quantita </strong>: <?php echo number_format($base1->QuantitaUM1_PR, 2, ',', '') ?>
            <strong style="margin-left: 40px;">Cliente</strong> : <?php echo $base1->Descrizione ?> <strong
                style="margin-left: 40px;"><?php echo ($base1->Cd_DO == 'OVC') ? 'OVC' : 'OCL' ?>  </strong>: <?php echo $base1->NumeroDoc ?>
        </h3><br><br>
       <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a onclick="cerca()">Attività</a></li>
            </ol>
        </nav> */ ?>
        <div class="row">
            <div class="col-xl-4 col-xs-12 col-md-4">
                <ul class="list-group">
                    <li class="list-group-item" style="text-align: center;font-weight: bolder;font-size: 18px;">
                        DOCUMENTO
                    </li>
                    <?php foreach ($documenti as $i) { ?>
                        <li class="list-group-item">
                            <?php echo $i->Cd_ARLotto . '(' . $i->Cd_AR . ') - ' . number_format($i->Quantita, 2, ',', ' ') . ' ' . $i->Cd_ARMisura . ' ' . $i->Cd_Do . '(<strong>' . $i->NumeroDoc . '</strong>)  Numero OL ( <strong>' . $i->NumeroOL . '</strong> ) Note ( <strong>' . $i->Note . '</strong> )'; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xl-4 col-xs-12 col-md-4">
                <ul class="list-group">
                    <li class="list-group-item" style="text-align: center;font-weight: bolder;font-size: 18px;">
                        PRODOTTO
                    </li>

                    <?php foreach ($carico as $i) { ?>
                        <li class="list-group-item">
                            <?php echo $i->Cd_ARLotto . '(' . $i->Cd_AR . ') - ' . number_format($i->Quantita, 2, ',', ' ') . ' ' . $i->Cd_ARMisura . ' OL( <strong>' . $i->NumeroOL . '</strong> ) DDT( <strong>' . $i->NumeroDDT . '</strong> ) OVC( <strong>' . $i->NumeroOVC . '</strong> ) Note ( <strong>' . $i->Note . '</strong> )'; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xl-4 col-xs-12 col-md-4">
                <ul class="list-group">
                    <li class="list-group-item" style="text-align: center;font-weight: bolder;font-size: 18px;">
                        COMPONENTE
                    </li>

                    <?php foreach ($scarico as $i) { ?>
                        <li class="list-group-item">
                            <?php echo $i->Cd_ARLotto . '(' . $i->Cd_AR . ') - ' . number_format($i->Quantita, 2, ',', ' ') . ' ' . $i->Cd_ARMisura . ' OL( <strong>' . $i->NumeroOL . '</strong> ) DDT( <strong>' . $i->NumeroDDT . '</strong> ) OVC( <strong>' . $i->NumeroOVC . '</strong> ) Note ( <strong>' . $i->Note . '</strong> )'; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>


        <script type="text/javascript">
            document.getElementById('lotto').innerHTML = 'Tracciabilita del Lotto ' + '<?php echo $lotto; ?>'
        </script>

        <?php
    }

    public
    function scarica_excel_lotto($lotto)
    {

        $carico = DB::SELECT('	SELECT MGMov.Cd_AR,MGMov.Cd_ARLotto,MGMov.Quantita,ARARMisura.Cd_ARMisura,Mgmov.DataMov,DORig.NumeroDoc as NumeroOVC,DDT.NumeroDoc as NumeroDDT,PROL.Numero as NumeroOL
                    FROM
                        MGMov
                        Left  Join MGMovInt 	On MGMov.Id_MGMovInt 			= MGMovInt.Id_MGMovInt
						LEFT  JOIN PRVRMateriale ON MGMov.Id_PrVRMateriale = PRVRMateriale.Id_PRVRMateriale
						LEFT  JOIN PRVRAttivita ON PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
						Left  Join PROLDoRig    On PROLDoRig.Id_PrOL= PrOL.Id_PrOL
						Left  Join DORig 		On DORig.Id_DORig 			= PROLDoRig.Id_DORig
						Left  Join DORig DDT    On DDT.Id_DORig_Evade = DORig.Id_DoRig
						Inner Join AR	 		On AR.Cd_AR 					= MGMov.Cd_AR
                        Left  Join ARARMisura	On AR.Cd_AR 					= ARARMisura.Cd_AR And ARARMisura.DefaultMisura = 1
						--Left  Join DORig DDT    On DDT.Id_DORig = MGMov.Id_DoRig
                        --Left  Join DORig 		On DORig.Id_DORig 			= DDT.Id_DORig_Evade
                        --Left  Join PROLDoRig    On PROLDoRig.Id_DoRig           = DoRig.Id_DoRig
                        --Left  Join PRVRMateriale on PRVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale
                        --Left  Join PRVRAttivita  on PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        --Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        --Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        --Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
                       --Left  Join PrVRMateriale PrVRMaterialeT on PrVRMaterialeT.Id_PRVRAttivita IN
                       --(SELECT Id_PRVRAttivita from PRVRAttivita  where Id_PRBLAttivita in (SELECT Id_PRBLAttivita  FROM PRBLAttivita  WHERE Id_PROLAttivita in (SELECT Id_PROLAttivita from PROLAttivita  where Id_PROL = PrOL.Id_PROL)))
                        --Inner Join MG			On MG.Cd_MG						= PrVRMaterialeT.Cd_MG
                    Where
                        MGMov.Ini = 0
						AND MGMov.PartenzaArrivo = \'A\'
						and mgmov.Cd_ARLotto = \'' . $lotto . '\'
						and MGMov.Id_PrVRMateriale is not null');

        $scarico = DB::SELECT('	SELECT MGMov.Cd_AR,MGMov.Cd_ARLotto,MGMov.Quantita,ARARMisura.Cd_ARMisura,Mgmov.DataMov,DORig.NumeroDoc as NumeroOVC,DDT.NumeroDoc as NumeroDDT,PROL.Numero as NumeroOL
                    FROM
                        MGMov
                        Left  Join MGMovInt 	On MGMov.Id_MGMovInt 			= MGMovInt.Id_MGMovInt
						LEFT  JOIN PRVRMateriale ON MGMov.Id_PrVRMateriale = PRVRMateriale.Id_PRVRMateriale
						LEFT  JOIN PRVRAttivita ON PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
						Left  Join PROLDoRig    On PROLDoRig.Id_PrOL= PrOL.Id_PrOL
						Left  Join DORig 		On DORig.Id_DORig 			= PROLDoRig.Id_DORig
						Left  Join DORig DDT    On DDT.Id_DORig_Evade = DORig.Id_DoRig
						Inner Join AR	 		On AR.Cd_AR 					= MGMov.Cd_AR
                        Left  Join ARARMisura	On AR.Cd_AR 					= ARARMisura.Cd_AR And ARARMisura.DefaultMisura = 1
						--Left  Join DORig DDT    On DDT.Id_DORig = MGMov.Id_DoRig
                        --Left  Join DORig 		On DORig.Id_DORig 			= DDT.Id_DORig_Evade
                        --Left  Join PROLDoRig    On PROLDoRig.Id_DoRig           = DoRig.Id_DoRig
                        --Left  Join PRVRMateriale on PRVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale
                        --Left  Join PRVRAttivita  on PRVRAttivita.Id_PRVRAttivita = PRVRMateriale.Id_PRVRAttivita
                        --Left  Join PrBLAttivita  on PrBLAttivita.Id_PrBLAttivita = PRVRAttivita.Id_PrBLAttivita
                        --Left  Join PrOLAttivita  on PrBLAttivita.Id_PrOLAttivita = PrOLAttivita.Id_PrOLAttivita
                        --Left  Join PrOL  on PrOL.Id_PrOL = PrOLAttivita.Id_PrOL
                       --Left  Join PrVRMateriale PrVRMaterialeT on PrVRMaterialeT.Id_PRVRAttivita IN
                       --(SELECT Id_PRVRAttivita from PRVRAttivita  where Id_PRBLAttivita in (SELECT Id_PRBLAttivita  FROM PRBLAttivita  WHERE Id_PROLAttivita in (SELECT Id_PROLAttivita from PROLAttivita  where Id_PROL = PrOL.Id_PROL)))
                        --Inner Join MG			On MG.Cd_MG						= PrVRMaterialeT.Cd_MG
                    Where
                        MGMov.Ini = 0
						AND MGMov.PartenzaArrivo = \'P\'
						and mgmov.Cd_ARLotto = \'' . $lotto . '\'
						and MGMov.Id_PrVRMateriale is not null ');

        $documenti = DB::SELECT('SELECT DORig.Cd_AR,DORig.Cd_ARLotto,Dorig.Qta as Quantita,DORig.Cd_ARMisura,DOTes.DataDoc as DataMov,
                                       CASE
                                       WHEN (DOTes.Cd_Do = \'OVC\')
                                       THEN
                                       DOTes.NumeroDoc
                                       ELSE NULL
                                       END as NumeroOVC,
                                       CASE
                                       WHEN (DOTes.Cd_Do = \'DDT\')
                                       THEN
                                       DOTes.NumeroDoc
                                       ELSE NULL
                                       END as NumeroDDT,
                                       CASE
                                       WHEN (DOTes.Cd_Do = \'OVC\')
                                       THEN
                                       (SELECT Numero from PRol where Id_PROl in (SELECT TOP 1 Id_PROL FROM PROLDoRig where Id_DoRig = DORig.Id_DORig))
                                       WHEN (DOTes.Cd_Do = \'DDT\')
                                       THEN
                                       (SELECT Numero from PRol where Id_PROl in (SELECT TOP 1 Id_PROL FROM PROLDoRig where Id_DoRig = DORig.Id_DORig_Evade))
                                       ELSE NULL
                                       END as NumeroOL
                                       FROM DORig
                                       LEFT JOIN DOTes ON DOTes.Id_DoTes = DORig.Id_DOTes
                                       WHERE
                                       DORig.Cd_ARLotto = \'' . $lotto . '\'
                                       AND DOTes.Cd_Do IN (\'DDT\',\'OVC\')');

        $localFilePath = storage_path('/' . $lotto . '.xls');

        $file = fopen($localFilePath, 'w+');

        if ($file === false) {
            $error = error_get_last();
            return 'Errore nell\'apertura del file: ' . $error['message'];
        }

        $directory = dirname($localFilePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $data = array_merge($documenti, $carico, $scarico);

        //dd($data);

        foreach ($data as $row) {

            $row2[] = array(strval($row->Cd_AR), strval($row->Cd_ARLotto), strval($row->Quantita), strval($row->Cd_ARMisura), strval($row->DataMov), strval($row->NumeroOVC), strval($row->NumeroDDT), strval($row->NumeroOL));
        }

        return Excel::download(new ExcelExport($row2), $lotto . '.xls');


    }
}
