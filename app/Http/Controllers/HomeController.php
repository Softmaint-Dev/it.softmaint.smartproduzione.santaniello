<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use PHPHtmlParser\Dom;
use Spatie\GoogleCalendar\Event;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use function Symfony\Component\String\s;
use Predis;

/**
 * Controller principale del webticket
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{


    public function login(Request $request)
    {

        if (session()->has('utente')) {
            return Redirect::to('');
        }

        $dati = $request->all();

        if (isset($dati['login'])) {

            $utenti = DB::select('SELECT * from Operatore where Id_Operatore = ' . $dati['Id_Operatore']);
            if (sizeof($utenti) > 0) {
                $utente = $utenti[0];
                $utente->Cd_Terminale = $dati['risorsa'];
                if (isset($dati['reparto'])) $utente->Cd_PRRiparto = $dati['reparto'];
                if (isset($dati['risorsa'])) $utente->Cd_PRRisorsa = $dati['risorsa'];
                $utente->Cd_Operatore2 = '';

                if (isset($dati['Id_Operatore2']) && $dati['Id_Operatore2'] != 0) {
                    $utenti2 = DB::select('SELECT * from Operatore where Id_Operatore = ' . $dati['Id_Operatore2']);
                    if (sizeof($utenti) > 0) {
                        $utente2 = $utenti2[0];
                        $utente->Cd_Operatore2 = $utente2->Cd_Operatore;
                    }
                }

                session(['utente' => $utente]);
                session(['risorsa' => $dati['risorsa']]);
                session()->save();

                DB::update("update DBFASE set Cd_ARMisura = 'mt'  WHere (Cd_PrAttivita = 'STAMPA' or Cd_PrAttivita = 'RISTAMPA') and Cd_ARMisura = 'kg'  and YEAR(TimeIns) = YEAR(GETDATE())");
                DB::update("update PROLAttivita set Cd_ARMisura = 'mt' where (Cd_PrAttivita = 'STAMPA' or Cd_PrAttivita = 'RISTAMPA') and Cd_ARMisura = 'kg' and YEAR(TimeIns) = YEAR(GETDATE())");

                if ($dati['reparto'] == 'IMBALLAGGO') {
                    return Redirect::to('imballaggio');
                } else {
                    return Redirect::to('');
                }
            }

        }

        $operatori = array();
        $risorse = array();

        if (isset($dati['reparto'])) {
            $risorse = DB::select('
                SELECT * from PRRisorsa Where Cd_PRReparto = \'' . $dati['reparto'] . '\'
            ');
        }

        if (isset($dati['risorsa'])) {
            //$operatori = DB::select('SELECT * from Operatore Where CD_Operatore IN (SELECT CD_Operatore from PRRisorsa_Operatore Where Cd_PRRIsorsa = \'' . $dati['risorsa'] . '\')');
            $operatori = DB::select('SELECT * from Operatore ');
        }

        $reparti = DB::select('SELECT * from PRReparto');
        $terminali = DB::select('SELECT distinct Cd_Terminale from Terminali_PRRisorsa Where Cd_PRRisorsa IN (Select Cd_PRRisorsa_C From PRRisorsaLink) order by Cd_Terminale ASC');
        return View::make('backend.login', compact('operatori', 'terminali', 'risorse', 'reparti'));

    }

    public function index()
    {

        if (!session()->has('utente')) {
            return Redirect::to('login');
        }

        $utente = session('utente');

        if ($utente->Cd_PRRiparto == 'LAB') {
            $bolle = DB::select('
            SELECT top 50 Id_PrOL,Id_PrBLAttivita,Articolo,Quantita,QuantitaProdotta,PercProdotta,
    STUFF((SELECT \',\' + NumeroDoc FROM dotes WHERE Id_DOTes IN
        (SELECT Id_DOTes FROM dorig WHERE Id_DORig IN
            (SELECT Id_DORig FROM PROLDorig WHERE Id_PrOL = PrBLAttivitaEx.Id_PrOL))
    FOR XML PATH(\'\')), 1, 1, \'\')
    AS NumeroDoc from PrBLAttivitaEx order by TimeIns DESC
            ');
            return View::make('backend.index', compact('utente', 'bolle'));
        }

        $bolle = DB::select('
            SELECT top 50 Id_PrOL,Id_PrBLAttivita,Articolo,Quantita,QuantitaProdotta,PercProdotta,
    STUFF((SELECT \',\' + NumeroDoc FROM dotes WHERE Id_DOTes IN
        (SELECT Id_DOTes FROM dorig WHERE Id_DORig IN
            (SELECT Id_DORig FROM PROLDorig WHERE Id_PrOL = PrBLAttivitaEx.Id_PrOL))
    FOR XML PATH(\'\')), 1, 1, \'\')
    AS NumeroDoc from PrBLAttivitaEx where Prodotta = 0 and Cd_PrRisorsa = \'' . $utente->Cd_PRRisorsa . '\' order by TimeIns DESC
            ');


        //$bolle = DB::select('SELECT * from PrBLAttivitaEx Where InCorso = 1 and Cd_PrRisorsa = \''.$utente->Cd_PRRisorsa.'\' order by TimeIns desc');
        return View::make('backend.index', compact('utente', 'bolle'));

    }

    public function statistiche()
    {

        if (!session()->has('utente')) {
            return Redirect::to('login');
        }

        return View::make('backend.statistiche');

    }

    public function operatori()
    {

        $operatori = DB::select('SELECT * from Operatore');
        return View::make('backend.operatori', compact('operatori'));
    }

    public function gruppi_risorse()
    {
        $risorse = DB::select('SELECT * from PRRisorsa Where Gruppo = 1');
        return View::make('backend.gruppi_risorse', compact('risorse'));
    }

    public function risorse($Cd_PrRisorsa)
    {
        $risorse = DB::select('SELECT * from PRRisorsa Where  Cd_PrRisorsa IN (SELECT Cd_PrRisorsa_C  from PRRisorsaLink Where Cd_PrRisorsa_P = \'' . $Cd_PrRisorsa . '\')');
        return View::make('backend.risorse', compact('risorse'));
    }

    public function imballaggio_bolle_da_chiudere(Request $request)
    {

        $utente = session('utente');
        $dati = $request->all();

        if (isset($dati['chiudi_tutte_le_bolle'])) {

            $bolle_da_chiudere = DB::select('
                SELECT PRBLAttivitaEX.* from PRBLAttivitaEX
                Where Id_PrBLAttivita IN (Select Id_PrBLAttivita from PRVRAttivita Where Id_PrBLAttivita = PrBLAttivitaEx.Id_PrBLAttivita) and Prodotta = 0 and Arrestata = 0 and FaseFinale = 1 and PRBLAttivitaEX.PercProdotta >= 100
                order by PRBLAttivitaEX.Data DESC
            ');

            foreach ($bolle_da_chiudere as $bd) {

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $bd->Id_PrBLAttivita);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    DB::update('update PRBLAttivita set Attrezzaggio = (select SUM(Attrezzaggio) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRBLAttivita set Esecuzione = (select SUM(Esecuzione) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRBLAttivita set Attesa = (select SUM(Fermo) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRRLAttivita Set UltimoRL = 1 Where Id_PrRLAttivita IN (Select max(Id_PrRLAttivita) From PrRLAttivita r Where r.Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' And r.InizioFine = \'F\' and r.TipoRilevazione = \'E\')');

                    $PrVRAttivita = DB::select('SELECT top 1 Id_PrVRAttivita from PRVRAttivita Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' and Esecuzione > 0 order by Data DESC');
                    if (sizeof($PrVRAttivita) > 0) {
                        DB::update('UPDATE PRVRAttivita set UltimoVR = 1 Where Id_PrVRAttivita = ' . $PrVRAttivita[0]->Id_PrVRAttivita);
                    }
                }

            }

            return Redirect::to('imballaggio');
        }

        if (isset($dati['chiudi_bolla'])) {

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $dati['Id_PrBLAttivita']);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];

                DB::update('update PRBLAttivita set Attrezzaggio = (select SUM(Attrezzaggio) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRBLAttivita set Esecuzione = (select SUM(Esecuzione) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRBLAttivita set Attesa = (select SUM(Fermo) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRRLAttivita Set UltimoRL = 1 Where Id_PrRLAttivita IN (Select max(Id_PrRLAttivita) From PrRLAttivita r Where r.Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' And r.InizioFine = \'F\' and r.TipoRilevazione = \'E\')');

                $PrVRAttivita = DB::select('SELECT top 1 Id_PrVRAttivita from PRVRAttivita Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' and Esecuzione > 0 order by Data DESC');
                if (sizeof($PrVRAttivita) > 0) {
                    DB::update('UPDATE PRVRAttivita set UltimoVR = 1 Where Id_PrVRAttivita = ' . $PrVRAttivita[0]->Id_PrVRAttivita);
                }
            }

            return Redirect::to('imballaggio_bolle_da_chiudere');
        }


        $bolle_da_chiudere = DB::select('
		    SELECT PRBLAttivitaEX.* from PRBLAttivitaEX
            Where Id_PrBLAttivita IN (Select Id_PrBLAttivita from PRVRAttivita Where Id_PrBLAttivita = PrBLAttivitaEx.Id_PrBLAttivita) and Prodotta = 0 and Arrestata = 0 and FaseFinale = 1 and PRBLAttivitaEX.PercProdotta >= 100
            order by PRBLAttivitaEX.Data DESC
        ');

        return View::make('backend.imballaggio_bolle_da_chiudere', compact('bolle_da_chiudere'));
    }


    public function imballaggio(Request $request)
    {

        $utente = session('utente');
        $dati = $request->all();

        if (isset($dati['modifica_pedana'])) {
            unset($dati['modifica_pedana']);
            $id_pedana = $dati['Id_xWPPD'];
            unset($dati['Id_xWPPD']);
            $id = $dati['Id_PrBLAttivita'];
            unset($dati['Id_PrBLAttivita']);
            unset($dati['Id_PrRLAttivita']);
            unset($dati['Id_PrOL']);

            if (isset($dati['colli_associati'])) {
                $colli_associati = $dati['colli_associati'];
                unset($dati['colli_associati']);
            }

            DB::update('Update xWPCollo set Nr_Pedana = null where Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\'');

            if (isset($colli_associati)) {
                $dati['NumeroColli'] = sizeof($colli_associati);
                foreach ($colli_associati as $ca) {
                    DB::update('Update xWPCollo set Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\' where Id_xWPCollo = ' . $ca);
                }
            }


            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                foreach ($pedane as $p) {
                    DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                }
            }


            HomeController::ripulisci_pedana($id_pedana);
        }
        if (isset($dati['pedana'])) {
            echo "<script>localStorage.setItem('filtro_ricerca', '" . $dati['pedana'] . "');</script>";
        }
        if (isset($dati['errore'])) {
            echo "<script>alert(\"Bolla inserita non esistente!\");</script>";
        }

        if (isset($dati['crea_pedana'])) {

            $attivita_bolle = DB::select('SELECT * FROM PRBLAttivitaEx WHERE Id_PrOLAttivita IN (select Id_PrOLAttivita from PROLAttivita where Cd_PrAttivita = \'IMBALLAGGIO\' and Id_PrOL = ' . $dati['Id_PRBLAttivita'] . ')');
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                if (sizeof($pedane) == 0) {
                    $insert_pedana['Nr_Pedana'] = 'P.' . $attivita_bolla->Id_PrOL . '.1';
                    $insert_pedana['Descrizione'] = 'Pedana 1 di OL ' . $attivita_bolla->Id_PrOL;
                } else {
                    $numero = sizeof($pedane) + 1;
                    $insert_pedana['Nr_Pedana'] = 'P.' . $attivita_bolla->Id_PrOL . '.' . $numero;
                    $insert_pedana['Descrizione'] = 'Pedana ' . $numero . '. di OL ' . $attivita_bolla->Id_PrOL;
                    DB::update('update xWPPD set Confermato = 1 Where Id_PrVRAttivita IS NULL and Id_PrOL = ' . $attivita_bolla->Id_PrOL);

                }


                $insert_pedana['Cd_xPD'] = $dati['Cd_xPD'];
                $insert_pedana['Id_PrOL'] = $attivita_bolla->Id_PrOL;
                $insert_pedana['Cd_ARMisura'] = $attivita_bolla->Cd_ARMisura;
                $insert_pedana['IdCodiceAttivita'] = $attivita_bolla->Id_PrOLAttivita;

                $insert_pedana['PesoTara'] = 0;
                $ar = DB::SELECT('SELECT * from AR Where Cd_AR = \'' . $dati['Cd_xPD'] . '\'');
                if (sizeof($ar) > 0) {
                    $insert_pedana['PesoTara'] = $ar[0]->PesoLordo;
                }
                $insert_pedana['QuantitaProdotta'] = 0;

                $id_pedana = DB::table('xWPPD')->insertGetId($insert_pedana);

                return Redirect::to('imballaggio?pedana=' . $attivita_bolla->Id_PrOL);
            }

            return Redirect::to('imballaggio?errore=1');

        }

        if (isset($dati['inizio_rilevazione'])) {
            $insert['NotePrRLAttivita'] = $dati['Nr_Pedana'];
            $insert['Terminale'] = $utente->Cd_Terminale;
            $insert['Cd_operatore'] = $utente->Cd_Operatore;
            $insert['InizioFine'] = 'I';
            $insert['TipoRilevazione'] = 'E';
            $insert['Id_PrBlAttivita'] = $dati['Id_PrBLAttivita'];
            $insert['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
            DB::table('PRRLAttivita')->insert($insert);

            return Redirect::to('imballaggio');
        }

        if (isset($dati['versa_pedana'])) {
            unset($dati['versa_pedana']);
            $id_pedana = $dati['Id_xWPPD'];

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $dati['Id_PrBLAttivita']);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $ols = DB::select('SELECT * from PrOL Where Id_PrOL = ' . $dati['Id_PrOL']);
                if (sizeof($ols) > 0) {
                    $ol = $ols[0];

                    $quantita = DB::select('SELECT PesoNetto as QtaProdotta from xWPPD Where Id_xWPPD = ' . $id_pedana)[0]->QtaProdotta;

                    $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                    $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                    $insert['Quantita'] = $quantita;
                    $insert['Quantita_Scar'] = 0;
                    $insert['Data'] = date('Ymd');
                    $insert['Cd_MG'] = '00001';
                    $insert['Cd_Operatore'] = $utente->Cd_Operatore;
                    $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione';

                    $insert['CostoLavorazione'] = 0;
                    $insert['Esecuzione'] = 0;
                    $insert['Attrezzaggio'] = 0;
                    $insert['Fermo'] = 0;
                    $id_attivita = DB::table('PRVRAttivita')->insertGetId($insert);

                    DB::update('update xWPPD Set Id_PRVRAttivita = ' . $id_attivita . ' where Id_xWPPD=' . $id_pedana);

                    $materiale = DB::SELECT('SELECT * from PRBLMateriale Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    foreach ($materiale as $m) {
                        if ($m->Tipo != 0) {
                            $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                            $insert_pr_materiale['Tipo'] = $m->Tipo;
                            $insert_pr_materiale['Id_PrOLAttivita'] = $m->Id_PrOLAttivita;
                            $insert_pr_materiale['Cd_AR'] = $m->Cd_AR;
                            $insert_pr_materiale['Consumo'] = $m->Consumo;
                            $insert_pr_materiale['Cd_ARMisura'] = $m->Cd_ARMisura;
                            $insert_pr_materiale['FattoreToUM1'] = $m->FattoreToUM1;
                            $insert_pr_materiale['Sfrido'] = 0;
                            $insert_pr_materiale['Cd_MG'] = $m->Cd_MG;
                            $insert_pr_materiale['Cd_MGUbicazione'] = $m->Cd_MGUbicazione;
                            $insert_pr_materiale['Cd_ARLotto'] = $m->Cd_ARLotto;
                            $insert_pr_materiale['NotePrVRMateriale'] = $m->NotePrBLMateriale;
                            if ($m->Tipo == 2) {
                                $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                                if (sizeof($costo) > 0) {
                                    $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                                }
                                if (isset($insert_pr_materiale['ValoreUnitario'])) {
                                    if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                        $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                } else {
                                    $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                }
                            } else {
                                if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                            }
                            DB::table('PrVrMateriale')->insert($insert_pr_materiale);

                        }
                    }

                    $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                    $insert_pr_materiale['Tipo'] = 0;
                    $insert_pr_materiale['Id_PrOLAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                    $insert_pr_materiale['Cd_AR'] = $ol->Cd_AR;
                    $insert_pr_materiale['Consumo'] = -$quantita;
                    $insert_pr_materiale['Cd_ARMisura'] = 'Kg';
                    $insert_pr_materiale['FattoreToUM1'] = 1;
                    $insert_pr_materiale['Sfrido'] = 0;
                    $insert_pr_materiale['Cd_MG'] = '00001';
                    $insert_pr_materiale['NotePrVRMateriale'] = 'Versamento Pedana ' . $dati['Nr_Pedana'];
                    $insert_pr_materiale['ValoreUnitario'] = 0;
                    $costo = DB::select('SELECT top 1 Costo from ARCostoItem Where Cd_AR = \'' . $ol->Cd_AR . '\' and TipoCosto = \'U\' Order By Cd_MGEsercizio DESC');
                    if (sizeof($costo) > 0) {
                        $insert_pr_materiale['ValoreUnitario'] = $costo[0]->Costo;
                    }

                    if ($m->Tipo == 2) {
                        $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                        if (sizeof($costo) > 0) {
                            $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                        }
                        if (isset($insert_pr_materiale['ValoreUnitario'])) {
                            if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                $insert_pr_materiale['ValoreUnitario'] = 0.01;
                        } else {
                            $insert_pr_materiale['ValoreUnitario'] = 0.01;
                        }
                    } else {
                        if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                    }
                    DB::table('PrVrMateriale')->insert($insert_pr_materiale);
                    DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update(array('Imballato' => 1));


                    $esiste = DB::select('SELECT * from PRRLAttivita where Id_PrRLAttivita_Sibling = ' . $dati['Id_PrRLAttivita']);
                    if (sizeof($esiste) == 0) {
                        $insert_rl['NotePrRLAttivita'] = $dati['Nr_Pedana'];
                        $insert_rl['Id_PrVRAttivita'] = $id_attivita;
                        $insert_rl['Id_PrRLAttivita_Sibling'] = $dati['Id_PrRLAttivita'];
                        $insert_rl['Terminale'] = $utente->Cd_Terminale;
                        $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                        $insert_rl['InizioFine'] = 'F';
                        $insert_rl['TipoRilevazione'] = 'E';
                        $insert_rl['Id_PrBlAttivita'] = $dati['Id_PrBLAttivita'];
                        $insert_rl['Quantita'] = $quantita;
                        $insert_rl['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                        DB::table('PRRLAttivita')->insert($insert_rl);
                    }

                    DB::update('
                        update rf
                        set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                        from PRRLAttivita rf
                        JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrVRAttivita = ' . $id_attivita);

                    DB::update('
                        update vr
                        set vr.Esecuzione = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                        from PRVRAttivita vr
                        JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                        JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrVRAttivita = ' . $id_attivita);


                    return Redirect::to('imballaggio');
                }

            }

        }

        if (isset($dati['elimina_versamento'])) {
            unset($dati['versa_pedana']);
            $id_pedana = $dati['Id_xWPPD'];

            if ($dati['Id_PrRLAttivita'] != '') {
                DB::delete('DELETE from PRRLAttivita Where Id_PrRLAttivita_Sibling = ' . $dati['Id_PrRLAttivita']);
                DB::delete('DELETE from PRRLAttivita Where Id_PrRLAttivita = ' . $dati['Id_PrRLAttivita']);
            }

            if ($dati['Id_PrVRAttivita'] != '') {
                DB::delete('DELETE from PrVrMateriale Where Id_PrVRAttivita = ' . $dati['Id_PrVRAttivita']);
                DB::delete('DELETE from PRVRAttivita where Id_PrVRAttivita =' . $dati['Id_PrVRAttivita']);
            }

            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update(array('Imballato' => 0));
            return Redirect::to('imballaggio');

        }

        if (isset($dati['stampa_etichetta_pedana'])) {

            $id = $dati['Id_PrBLAttivita'];

            $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $dati['Id_xWPPD'], 6);
            if ($nome_file != '') {
                $nomi_colli = array();
                while ($dati['Copie'] > 0) {
                    array_push($nomi_colli, $nome_file);
                    $dati['Copie'] -= 1;
                }


                return Redirect::to('imballaggio?stampa=' . implode(',', $nomi_colli));

            }

        }

        if (isset($dati['modifica_pedana_e_stampa'])) {
            unset($dati['modifica_pedana_e_stampa']);
            $id_pedana = $dati['Id_xWPPD'];
            unset($dati['Id_xWPPD']);
            $id_bolla = $dati['Id_PrBLAttivita'];
            unset($dati['Id_PrBLAttivita']);
            $id_ol = $dati['Id_PrOL'];
            unset($dati['Id_PrOL']);
            $id_prrl = $dati['Id_PrRLAttivita'];
            unset($dati['Id_PrRLAttivita']);
            unset($dati['Id_PrBLAttivita']);


            if (isset($dati['colli_associati'])) {
                $colli_associati = $dati['colli_associati'];
                unset($dati['colli_associati']);
            }

            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

            DB::update('Update xWPCollo set Nr_Pedana = null where Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\'');

            if (isset($colli_associati)) {
                $dati['NumeroColli'] = sizeof($colli_associati);
                foreach ($colli_associati as $ca) {
                    DB::update('Update xWPCollo set Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\' where Id_xWPCollo = ' . $ca);
                }
            }


            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_bolla);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                foreach ($pedane as $p) {
                    DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                }
            }

            HomeController::ripulisci_pedana($id_pedana);


            $dati['Id_PrBLAttivita'] = $id_bolla;
            $dati['Id_xWPPD'] = $id_pedana;
            $dati['stampa_foglio_pedana'] = 1;
        }

        if (isset($dati['modifica_pedana_stampa_versa'])) {

            unset($dati['modifica_pedana_stampa_versa']);
            $id_pedana = $dati['Id_xWPPD'];
            unset($dati['Id_xWPPD']);
            $id_bolla = $dati['Id_PrBLAttivita'];
            unset($dati['Id_PrBLAttivita']);
            $id_ol = $dati['Id_PrOL'];
            unset($dati['Id_PrOL']);
            $id_prrl = $dati['Id_PrRLAttivita'];
            unset($dati['Id_PrRLAttivita']);
            HomeController::ripulisci_pedana($id_pedana);
            unset($dati['Id_PrBLAttivita']);


            if (isset($dati['colli_associati'])) {
                $colli_associati = $dati['colli_associati'];
                unset($dati['colli_associati']);
            }

            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

            DB::update('Update xWPCollo set Nr_Pedana = null where Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\'');

            if (isset($colli_associati)) {
                $dati['NumeroColli'] = sizeof($colli_associati);
                foreach ($colli_associati as $ca) {
                    DB::update('Update xWPCollo set Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\' where Id_xWPCollo = ' . $ca);
                }
            }


            DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id_bolla);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                foreach ($pedane as $p) {
                    DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                }
            }

            HomeController::ripulisci_pedana($id_pedana);


            $dati['Id_PrBLAttivita'] = $id_bolla;
            $dati['Id_xWPPD'] = $id_pedana;
            $dati['Id_PrOL'] = $id_ol;
            $dati['Id_PrRLAttivita'] = $id_prrl;

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $dati['Id_PrBLAttivita']);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];
                $ols = DB::select('SELECT * from PrOL Where Id_PrOL = ' . $dati['Id_PrOL']);
                if (sizeof($ols) > 0) {
                    $ol = $ols[0];

                    $quantita = DB::select('SELECT PesoNetto as QtaProdotta from xWPPD Where Id_xWPPD = ' . $id_pedana)[0]->QtaProdotta;

                    $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                    $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                    $insert['Quantita'] = $quantita;
                    $insert['Quantita_Scar'] = 0;
                    $insert['Data'] = date('Ymd');
                    $insert['Cd_MG'] = '00001';
                    $insert['Cd_Operatore'] = $utente->Cd_Operatore;
                    $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione';

                    $insert['CostoLavorazione'] = 0;
                    $insert['Esecuzione'] = 0;
                    $insert['Attrezzaggio'] = 0;
                    $insert['Fermo'] = 0;
                    $id_attivita = DB::table('PRVRAttivita')->insertGetId($insert);

                    DB::update('update xWPPD Set Id_PRVRAttivita = ' . $id_attivita . ' where Id_xWPPD=' . $id_pedana);

                    $materiale = DB::SELECT('SELECT * from PRBLMateriale Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    foreach ($materiale as $m) {
                        if ($m->Tipo != 0) {
                            $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                            $insert_pr_materiale['Tipo'] = $m->Tipo;
                            $insert_pr_materiale['Id_PrOLAttivita'] = $m->Id_PrOLAttivita;
                            $insert_pr_materiale['Cd_AR'] = $m->Cd_AR;
                            $insert_pr_materiale['Consumo'] = $m->Consumo;
                            $insert_pr_materiale['Cd_ARMisura'] = $m->Cd_ARMisura;
                            $insert_pr_materiale['FattoreToUM1'] = $m->FattoreToUM1;
                            $insert_pr_materiale['Sfrido'] = 0;
                            $insert_pr_materiale['Cd_MG'] = $m->Cd_MG;
                            $insert_pr_materiale['Cd_MGUbicazione'] = $m->Cd_MGUbicazione;
                            $insert_pr_materiale['Cd_ARLotto'] = $m->Cd_ARLotto;
                            $insert_pr_materiale['NotePrVRMateriale'] = $m->NotePrBLMateriale;
                            if ($m->Tipo == 2) {
                                $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                                if (sizeof($costo) > 0) {
                                    $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                                }
                                if (isset($insert_pr_materiale['ValoreUnitario'])) {
                                    if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                        $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                } else {
                                    $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                }
                            } else {
                                if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                            }
                            DB::table('PrVrMateriale')->insert($insert_pr_materiale);

                        }
                    }

                    $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                    $insert_pr_materiale['Tipo'] = 0;
                    $insert_pr_materiale['Id_PrOLAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                    $insert_pr_materiale['Cd_AR'] = $ol->Cd_AR;
                    $insert_pr_materiale['Consumo'] = -$quantita;
                    $insert_pr_materiale['Cd_ARMisura'] = 'Kg';
                    $insert_pr_materiale['FattoreToUM1'] = 1;
                    $insert_pr_materiale['Sfrido'] = 0;
                    $insert_pr_materiale['Cd_MG'] = '00001';
                    $insert_pr_materiale['NotePrVRMateriale'] = 'Versamento Pedana ' . $dati['Nr_Pedana'];
                    $insert_pr_materiale['ValoreUnitario'] = 0;
                    $costo = DB::select('SELECT top 1 Costo from ARCostoItem Where Cd_AR = \'' . $ol->Cd_AR . '\' and TipoCosto = \'U\' Order By Cd_MGEsercizio DESC');
                    if (sizeof($costo) > 0) {
                        $insert_pr_materiale['ValoreUnitario'] = $costo[0]->Costo;
                    }

                    if ($m->Tipo == 2) {
                        $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                        if (sizeof($costo) > 0) {
                            $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                        }
                        if (isset($insert_pr_materiale['ValoreUnitario'])) {
                            if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                $insert_pr_materiale['ValoreUnitario'] = 0.01;
                        } else {
                            $insert_pr_materiale['ValoreUnitario'] = 0.01;
                        }
                    } else {
                        if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                    }

                    DB::table('PrVrMateriale')->insert($insert_pr_materiale);
                    DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update(array('Imballato' => 1));


                    $esiste = DB::select('SELECT * from PRRLAttivita where Id_PrRLAttivita_Sibling = ' . $dati['Id_PrRLAttivita']);
                    if (sizeof($esiste) == 0) {
                        $insert_rl['NotePrRLAttivita'] = $dati['Nr_Pedana'];
                        $insert_rl['Id_PrVRAttivita'] = $id_attivita;
                        $insert_rl['Id_PrRLAttivita_Sibling'] = $dati['Id_PrRLAttivita'];
                        $insert_rl['Terminale'] = $utente->Cd_Terminale;
                        $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                        $insert_rl['InizioFine'] = 'F';
                        $insert_rl['TipoRilevazione'] = 'E';
                        $insert_rl['Id_PrBlAttivita'] = $dati['Id_PrBLAttivita'];
                        $insert_rl['Quantita'] = $quantita;
                        $insert_rl['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                        DB::table('PRRLAttivita')->insert($insert_rl);
                    }

                    DB::update('
                        update rf
                        set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                        from PRRLAttivita rf
                        JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrVRAttivita = ' . $id_attivita);

                    DB::update('
                        update vr
                        set vr.Esecuzione = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                        from PRVRAttivita vr
                        JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                        JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrVRAttivita = ' . $id_attivita);

                }

            }

            $dati['stampa_foglio_pedana'] = 1;


        }

        if (isset($dati['stampa_foglio_pedana'])) {

            $id = $dati['Id_PrBLAttivita'];

            $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $dati['Id_xWPPD'], 5);
            if ($nome_file != '') {
                $nomi_colli = array();
                while ($dati['Copie'] > 0) {
                    array_push($nomi_colli, $nome_file);
                    $dati['Copie'] -= 1;
                }


                return Redirect::to('imballaggio?stampa=' . implode(',', $nomi_colli));

            }

        }

        if (isset($dati['chiudi_bolla'])) {

            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $dati['Id_PrBLAttivita']);
            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];

                DB::update('update PRBLAttivita set Attrezzaggio = (select SUM(Attrezzaggio) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRBLAttivita set Esecuzione = (select SUM(Esecuzione) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRBLAttivita set Attesa = (select SUM(Fermo) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                DB::update('update PRRLAttivita Set UltimoRL = 1 Where Id_PrRLAttivita IN (Select max(Id_PrRLAttivita) From PrRLAttivita r Where r.Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' And r.InizioFine = \'F\' and r.TipoRilevazione = \'E\')');

                $PrVRAttivita = DB::select('SELECT top 1 Id_PrVRAttivita from PRVRAttivita Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' and Esecuzione > 0 order by Data DESC');
                if (sizeof($PrVRAttivita) > 0) {
                    DB::update('UPDATE PRVRAttivita set UltimoVR = 1 Where Id_PrVRAttivita = ' . $PrVRAttivita[0]->Id_PrVRAttivita);
                }
            }

            return Redirect::to('imballaggio');
        }

        $pedane = DB::select('

           SELECT * from (

                SELECT p.*,c.Descrizione as cliente,a.Cd_AR,a.Descrizione as Descrizione_Articolo,PRBLAttivita.NotePrBLAttivita,a.xPesobobina,a.xBase,a.PesoNetto as peso_pedana,PrBLAttivita.Id_PrBLAttivita,PRRLAttivita.Id_PrRLAttivita  from xWPPD  p
                LEFT JOIN PROL ON PROL.Id_PrOL = p.Id_PrOL
                LEFT JOIN AR a ON a.Cd_AR = PROL.Cd_AR
                LEFT JOIN PROLDorig ON PROLDorig.Id_PrOL = PROL.Id_PrOL
                LEFT JOIN DOrig d ON d.Id_Dorig = PROLDorig.Id_Dorig
                LEFT JOIN CF c ON c.Cd_CF = d.Cd_CF
                Left JOIN PROLAttivita ON PROLAttivita.Id_PrOL = PROL.Id_PrOL and PROLAttivita.Cd_PrAttivita = \'IMBALLAGGIO\'
                LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
                LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and PRRLAttivita.TipoRilevazione = \'E\' and PRRLAttivita.NotePrRLAttivita = p.Nr_Pedana
                where p.Confermato = 1 and p.Imballato = 0 and PRBLAttivita.Id_PrBLAttivita IS NOT NULL

			) x where x.Id_PrBLAttivita IS NOT NULL and DATEDIFF(HOUR, x.TimeUpd,GETDATE()) <= 168
        ');

        $pedane_imballate = DB::select('
            SELECT distinct * from (

                SELECT p.*,c.Descrizione as cliente,a.Cd_AR,a.Descrizione as Descrizione_Articolo,PRBLAttivita.NotePrBLAttivita,a.xPesobobina,a.xBase,a.PesoNetto as peso_pedana  from xWPPD  p
                LEFT JOIN PROL ON PROL.Id_PrOL = p.Id_PrOL
                LEFT JOIN PROLDorig ON PROLDorig.Id_PrOL = PROL.Id_PrOL
                LEFT JOIN DOrig d ON d.Id_Dorig = PROLDorig.Id_Dorig
                LEFT JOIN CF c ON c.Cd_CF = d.Cd_CF
                LEFT JOIN AR a ON a.Cd_AR = PROL.Cd_AR
                Left JOIN PROLAttivita ON PROLAttivita.Id_PrOL = PROL.Id_PrOL and PROLAttivita.Cd_PrAttivita = \'IMBALLAGGIO\'
                LEFT JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = PROLAttivita.Id_PrOLAttivita
                LEFT JOIN PRRLAttivita ON PRRLAttivita.Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita and PRRLAttivita.InizioFine = \'I\' and PRRLAttivita.TipoRilevazione = \'E\' and PRRLAttivita.NotePrRLAttivita = p.Nr_Pedana
                where p.Confermato = 1 and p.Imballato = 1

				) as x where DATEDIFF(HOUR, x.TimeUpd,GETDATE()) <= 24

        ');

        return View::make('backend.imballaggio', compact('pedane', 'pedane_imballate'));
    }

    public function lista_attivita($Cd_Attivita = null)
    {

        if ($Cd_Attivita != null) {
            $attivita = DB::select(' SELECT * from PROLAttivitaEX where Cd_PrAttivita = \'' . $Cd_Attivita . '\' and PercRilasciata < 100 order by Id_PrOLAttivita DESC ');
        } else {
            $attivita = DB::select(' SELECT TOP 100 * from PROLAttivitaEX where PercRilasciata < 100 order by Id_PrOLAttivita DESC');
        }

        return View::make('backend.lista_attivita', compact('attivita', 'Cd_Attivita'));
    }

    public function login2(Request $request)
    {

        $dati = $request->all();
        $dati['Id_Operatore'] = '150';
        $utenti = DB::select('SELECT * from Operatore where Id_Operatore = ' . $dati['Id_Operatore']);
        if (sizeof($utenti) > 0) {
            $utente = $utenti[0];
            $utente->Cd_Terminale = $dati['risorsa'];
            if (isset($dati['reparto'])) $utente->Cd_PRRiparto = $dati['reparto'];
            if (isset($dati['risorsa'])) $utente->Cd_PRRisorsa = $dati['risorsa'];
            $utente->Cd_Operatore2 = '';

            if (isset($dati['Id_Operatore2']) && $dati['Id_Operatore2'] != 0) {
                $utenti2 = DB::select('SELECT * from Operatore where Id_Operatore = ' . $dati['Id_Operatore2']);
                if (sizeof($utenti) > 0) {
                    $utente2 = $utenti2[0];
                    $utente->Cd_Operatore2 = $utente2->Cd_Operatore;
                }
            }

            session(['utente' => $utente]);
            session(['risorsa' => $dati['risorsa']]);
            session()->save();

            DB::update("update DBFASE set Cd_ARMisura = 'mt'  WHere (Cd_PrAttivita = 'STAMPA' or Cd_PrAttivita = 'RISTAMPA') and Cd_ARMisura = 'kg'  and YEAR(TimeIns) = YEAR(GETDATE())");
            DB::update("update PROLAttivita set Cd_ARMisura = 'mt' where (Cd_PrAttivita = 'STAMPA' or Cd_PrAttivita = 'RISTAMPA') and Cd_ARMisura = 'kg' and YEAR(TimeIns) = YEAR(GETDATE())");
            return View::make('backend.tracciabilita'/*,compact('attivita','Cd_Attivita')*/);
        }

        // lei inserisce l'id ordine lavoro ovver il prol e da li vuole vedere tutte le attività svolte su quel ol
        // inserisce nella pagina l'ol e poi dopo fai questa query
        // DB::SELECT('SELECT * FROM PROLAttivita WHERE Id_PRol = \''.$id_prol.'\'');
        // suddiviso per attività , ti richiami tutti i colli sottostanti
        // in xWpCollo dove IdOrdineLavoro = Id_prol e IdCodiceAttivita = id_prolattivita
        // cosi hai tutti i colli divisi dall'ol

    }

    public function QualitaBolla($idBolla, $idQualita, Request $request)
    {
        switch ($idQualita) {
            case '1':
                return view("");
            default:
                return Redirect::to('dettaglio_bolla/' . $idBolla);
        }
    }

    public function dettaglio_bolla($id, Request $request)
    {
        $mese_lettera = '';
        switch (date('m', strtotime('now'))) {
            case 1:
                $mese_lettera = 'A';
                break;
            case 2:
                $mese_lettera = 'B';
                break;
            case 3:
                $mese_lettera = 'C';
                break;
            case 4:
                $mese_lettera = 'D';
                break;
            case 5:
                $mese_lettera = 'E';
                break;
            case 6:
                $mese_lettera = 'F';
                break;
            case 7:
                $mese_lettera = 'G';
                break;
            case 8:
                $mese_lettera = 'H';
                break;
            case 9:
                $mese_lettera = 'I';
                break;
            case 10:
                $mese_lettera = 'L';
                break;
            case 11:
                $mese_lettera = 'M';
                break;
            case 12:
                $mese_lettera = 'N';
                break;
            default:
                $mese_lettera = 'error';
                break;
        }

        if (!session()->has('utente')) {
            return Redirect::to('login');
        }

        if (session()->has('utente')) {

            $utente = session('utente');
            $risorsa = session('risorsa');
            $dati = $request->all();
            if (isset($dati['stampa'])) unset($dati['stampa']);
            $id_ultima_rilevazione = 0;

            $stato_attuale = DB::select('SELECT top 1 *, CONCAT(InizioFine,\'\',TipoRilevazione) as stato from PRRLAttivita Where Id_PrBLAttivita = ' . $id . ' order by DataOra desc,TipoRilevazione DESC');
            if (sizeof($stato_attuale) > 0) {
                $id_ultima_rilevazione = $stato_attuale[0]->Id_PrRLAttivita;
                $stato_attuale = $stato_attuale[0]->stato;

                if ($stato_attuale == 'FF') {
                    $stato_attuale = DB::select('SELECT top 1 *, CONCAT(InizioFine,\'\',TipoRilevazione) as stato from PRRLAttivita Where (TipoRilevazione = \'E\' or TipoRilevazione = \'A\') and Id_PrBLAttivita = ' . $id . ' order by DataOra desc,TipoRilevazione DESC');
                    if (sizeof($stato_attuale) > 0) {
                        $id_ultima_rilevazione = $stato_attuale[0]->Id_PrRLAttivita;
                        $stato_attuale = $stato_attuale[0]->stato;
                    } else $stato_attuale = 'FE';
                }
            } else $stato_attuale = 'FE';


            if (isset($dati['aggiungi_qualita'])) {
                unset($dati['aggiungi_qualita']);
                $dati['json_dati'] = json_encode($dati['json_dati']);
                $dati['Id_PrBlAttivita'] = $id;
                DB::table('xFormQualita')->insert($dati);
                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['elimina_qualita'])) {
                unset($dati['elimina_qualita']);
                DB::table('xFormQualita')->where('Id_xFormQualita', $dati['Id_xFormQualita'])->delete();
                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['invia_segnalazione'])) {

                $insert['Id_PrBlAttivita'] = $id;
                $insert['Cd_PRRisorsa'] = $dati['Cd_PrRisorsa'];
                $insert['Messaggio'] = $dati['Messaggio'];
                $insert['Cd_operatore'] = $utente->Cd_Operatore;
                DB::table('xWPSegnalazione')->insert($insert);

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $numero = $attivita_bolla->Id_PrOL;
                    $cf = DB::SELECT('SELECT Cf.Descrizione FROM CF
                                            LEFT JOIN DORig ON CF.Cd_CF = Dorig.Cd_CF
                                            LEFT JOIN PROLDoRig ON PROLDoRig.Id_DoRig = DORig.Id_DORig
                                            WHERE PROLDoRig.ID_PROL = \'' . $numero . '\'');
                    if (sizeof($cf) > 0)
                        $cf = $cf[0]->Descrizione;
                    else
                        $cf = 'Non Riscontrato';
                    $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                    if (sizeof($OLAttivita) > 0) {
                        $OLAttivita = $OLAttivita[0];

                        /*$mail = new  PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host = 'out.postassl.it';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'produzione@allpackaging.it';
                        $mail->Password = '3UtKQVz@!mz6';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                        $mail->setFrom('produzione@allpackaging.it', 'Produzione All Packaging');
                        $mail->addAddress('laboratorio@allpackaging.it');
                        $mail->IsHTML(true);

                        $mail->Subject = 'Arca Industry - All Packaging - Nuova Segnalazione Bolla ' . $id;

                        $mail->Body = '
                                Id OL: ' . $OLAttivita->Id_PrOL . '<br>
                                Risorsa: ' . $risorsa . '<br>
                                Operatore: ' . $utente->Cd_Operatore . '<br>
                                Messaggio: ' . nl2br($dati['Messaggio']);

                        $mail->send();*/
                    }
                }


                return Redirect::to('dettaglio_bolla/' . $id);

            }

            if (isset($dati['inizio_attrezzaggio'])) {
                $insert['NotePrRLAttivita'] = '';
                $insert['Terminale'] = $utente->Cd_Terminale;
                $insert['Cd_operatore'] = $utente->Cd_Operatore;
                $insert['InizioFine'] = 'I';
                $insert['TipoRilevazione'] = 'A';
                $insert['Id_PrBlAttivita'] = $id;
                $insert['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                DB::table('PRRLAttivita')->insert($insert);

                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['inizio_esecuzione'])) {
                $insert_rl2['NotePrRLAttivita'] = '';
                $insert_rl2['Terminale'] = $utente->Cd_Terminale;
                $insert_rl2['Cd_operatore'] = $utente->Cd_Operatore;
                $insert_rl2['InizioFine'] = 'I';
                $insert_rl2['TipoRilevazione'] = 'E';
                $insert_rl2['Id_PrBlAttivita'] = $id;
                $insert_rl2['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                DB::table('PRRLAttivita')->insert($insert_rl2);

                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['fine_attrezzaggio'])) {

                $quantita_scarto = 0;
                $quantita = 0;

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    if (isset($dati['Cd_Operatore2'])) {
                        $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                        $insert['Quantita'] = $quantita;
                        $insert['Quantita_Scar'] = $quantita_scarto;
                        $insert['FattoreMks'] = $attivita_bolla->FattoreMks;
                        $insert['Data'] = date('Ymd');
                        //$insert['Cd_MG'] = $attivita_bolla->Cd_MG;
                        if ($quantita > 0) {
                            $insert['Cd_MG'] = '00009';
                        }
                        $insert['Cd_Operatore'] = $dati['Cd_Operatore2'];

                        $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione - Secondo Operatore di Attrezzaggio';
                        $insert['CostoLavorazione'] = 0;
                        $insert['Attrezzaggio'] = 0;
                        $insert['Esecuzione'] = 0;
                        $insert['Fermo'] = 0;
                        DB::table('PRVRAttivita')->insert($insert);
                    }

                    $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                    $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                    $insert['Quantita'] = $quantita;
                    $insert['Quantita_Scar'] = $quantita_scarto;
                    $insert['FattoreMks'] = $attivita_bolla->FattoreMks;
                    $insert['Data'] = date('Ymd');
                    //$insert['Cd_MG'] = $attivita_bolla->Cd_MG;
                    if ($quantita > 0) {
                        $insert['Cd_MG'] = '00001';
                    }
                    $insert['Cd_Operatore'] = $utente->Cd_Operatore;

                    $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione';
                    $insert['CostoLavorazione'] = 0;
                    $insert['Attrezzaggio'] = 0;
                    $insert['Esecuzione'] = 0;
                    $insert['Fermo'] = 0;
                    $id_attivita = DB::table('PRVRAttivita')->insertGetId($insert);

                    $insert_rl['Id_PrVRAttivita'] = $id_attivita;
                    $insert_rl['Id_PrRLAttivita_Sibling'] = $id_ultima_rilevazione;
                    $insert_rl['Terminale'] = $utente->Cd_Terminale;
                    $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                    $insert_rl['InizioFine'] = 'F';
                    $insert_rl['TipoRilevazione'] = 'A';
                    $insert_rl['Id_PrBlAttivita'] = $id;
                    $insert_rl['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                    DB::table('PRRLAttivita')->insert($insert_rl);

                    DB::update('
                        update rf
                        set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                        from PRRLAttivita rf
                        JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrVRAttivita = ' . $id_attivita);


                    DB::update('
                        update vr
                        set vr.Attrezzaggio = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                        from PRVRAttivita vr
                        JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                        JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrVRAttivita = ' . $id_attivita);


                    /* $insert_rl2['NotePrRLAttivita'] = '';
                     $insert_rl2['Terminale'] = $utente->Cd_Terminale;
                     $insert_rl2['Cd_operatore'] = $utente->Cd_Operatore;
                     $insert_rl2['InizioFine'] = 'I';
                     $insert_rl2['TipoRilevazione'] = 'E';
                     $insert_rl2['Id_PrBlAttivita'] = $id;
                     $insert_rl2['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                     DB::table('PRRLAttivita')->insert($insert_rl2);*/

                    return Redirect::to('dettaglio_bolla/' . $id);
                }

            }

            if (isset($dati['crea_pedana'])) {

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                    if (sizeof($pedane) == 0) {
                        $insert_pedana['Nr_Pedana'] = 'P.' . $attivita_bolla->Id_PrOL . '.1';
                        $insert_pedana['Descrizione'] = 'Pedana 1 di OL ' . $attivita_bolla->Id_PrOL;
                    } else {
                        $numero = sizeof($pedane) + 1;
                        $insert_pedana['Nr_Pedana'] = 'P.' . $attivita_bolla->Id_PrOL . '.' . $numero;
                        $insert_pedana['Descrizione'] = 'Pedana ' . $numero . '. di OL ' . $attivita_bolla->Id_PrOL;
                        DB::update('update xWPPD set Confermato = 1 Where Id_PrVRAttivita IS NULL and Id_PrOL = ' . $attivita_bolla->Id_PrOL);

                    }


                    $insert_pedana['Cd_xPD'] = $dati['Cd_xPD'];
                    $insert_pedana['Id_PrOL'] = $attivita_bolla->Id_PrOL;
                    $insert_pedana['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $insert_pedana['IdCodiceAttivita'] = $attivita_bolla->Id_PrOLAttivita;

                    $insert_pedana['PesoTara'] = 0;
                    $ar = DB::SELECT('SELECT * from AR Where Cd_AR = \'' . $dati['Cd_xPD'] . '\'');
                    if (sizeof($ar) > 0) {
                        $insert_pedana['PesoTara'] = $ar[0]->PesoLordo;
                    }
                    $insert_pedana['QuantitaProdotta'] = 0;

                    $id_pedana = DB::table('xWPPD')->insertGetId($insert_pedana);


                    $nomi_colli = array();
                    $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $id_pedana, 6);
                    if ($nome_file != '') {

                        return Redirect::to('dettaglio_bolla/' . $id . '?stampa=' . $nome_file);

                    }

                }

                return Redirect::to('dettaglio_bolla/' . $id . '#tab3');

            }

            if (isset($dati['modifica_pedana'])) {
                unset($dati['modifica_pedana']);
                $id_pedana = $dati['Id_xWPPD'];
                unset($dati['Id_xWPPD']);
                if (isset($dati['colli_associati'])) {
                    $colli_associati = $dati['colli_associati'];
                    unset($dati['colli_associati']);
                }


                DB::update('Update xWPCollo set Nr_Pedana = null where Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\'');


                if (isset($colli_associati)) {
                    $dati['NumeroColli'] = sizeof($colli_associati);
                    foreach ($colli_associati as $ca) {
                        DB::update('Update xWPCollo set Nr_Pedana = \'' . $dati['Nr_Pedana'] . '\' where Id_xWPCollo = ' . $ca);
                    }
                }


                DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update($dati);

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                    foreach ($pedane as $p) {
                        DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                    }
                }


                HomeController::ripulisci_pedana($id_pedana);


                return Redirect::to('dettaglio_bolla/' . $id . '#tab3');
            }

            if (isset($dati['conferma_pedana'])) {
                unset($dati['conferma_pedana']);
                $id_pedana = $dati['Id_xWPPD'];

                DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->update(array('Confermato' => 1));

                return Redirect::to('dettaglio_bolla/' . $id . '#tab3');
            }

            if (isset($dati['elimina_pedana'])) {
                unset($dati['elimina_pedana']);
                $id_pedana = $dati['Id_xWPPD'];
                unset($dati['Id_xWPPD']);

                HomeController::ripulisci_pedana($id_pedana);

                DB::table('xWPPD')->where('Id_xWPPD', $id_pedana)->delete();

                return Redirect::to('dettaglio_bolla/' . $id . '#tab3');
            }

            if (isset($dati['modifica_collo'])) {
                unset($dati['modifica_collo']);
                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);
                unset($dati['Quantita']);
                unset($dati['esemplari']);
                unset($dati['Descrizione']);
                unset($dati['copie']);
                if (isset($dati['Rif_Nr_Collo_Ultimo'])) unset($dati['Rif_Nr_Collo_Ultimo']);

                if (isset($dati['xLotto'])) {
                    $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                    if (sizeof($attivita_bolle) > 0) {
                        $attivita_bolla = $attivita_bolle[0];
                        $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $articolo = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'')[0];
                            $check = DB::SELECT('SELECT * FROM ARLotto where Cd_ARLotto = \'' . $dati['xLotto'] . '\' and Cd_AR = \'' . $articolo->Cd_AR . '\'');
                            if (sizeof($check) <= 0) {
                                $data_scadenza = date('Y-m-d', strtotime('+2 year'));
                                DB::insert('INSERT INTO ARLotto (Cd_AR,Cd_ARLotto,Descrizione,DataScadenza) VALUES (\'' . $articolo->Cd_AR . '\',\'' . $dati['xLotto'] . '\',\'Lotto ' . $dati['xLotto'] . ' Articolo ' . $articolo->Cd_AR . '\',\'' . $data_scadenza . '\')');
                            }
                        }
                    }
                }

                $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'];


                if (isset($dati['Nr_Pedana_Collo']) || $dati['Nr_Pedana_Collo'] == '') {
                    $dati['Nr_Pedana'] = $dati['Nr_Pedana_Collo'];
                    unset($dati['Nr_Pedana_Collo']);
                }


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    $bolle = DB::select('SELECT * from PrBLEx Where Id_PrBL = ' . $attivita_bolla->Id_PrBL);
                    if (sizeof($bolle) > 0) {
                        $bolla = $bolle[0];
                        $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $articoli = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'');
                            if (sizeof($articoli) > 0) {
                                $articolo = $articoli[0];
                                $dati['Cd_AR'] = $articolo->Cd_AR;


                                $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                if (sizeof($umfatt) > 0) {
                                    $umfatt = $umfatt[0]->UMFatt;
                                    $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'] * $umfatt;
                                }
                            }
                        }
                    }
                }

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($dati);

                HomeController::ripulisci_collo($id_collo);

                return Redirect::to('dettaglio_bolla/' . $id);

            }

            if (isset($dati['collo_non_conforme'])) {
                unset($dati['collo_non_conforme']);
                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $colli = DB::select('SELECT TOP 1 * from xWPCollo Where NC = 1 and Id_PRBLAttivita = ' . $id . ' order by CAST(Descrizione as int) ASC');

                    if (sizeof($colli) == 0) {
                        $update['Nr_Collo'] = '-' . $id . '.1';
                        $update['Descrizione'] = '-1';
                    } else {

                        $numero = strval(intval($colli[0]->Descrizione) - 1);
                        $update['Nr_Collo'] = '-' . $id . '.' . abs($numero);
                        $update['Descrizione'] = $numero;
                    }
                }

                $update['NC'] = 1;
                $update['Nr_Pedana'] = '';
                $update['Cd_PRCausaleScarto'] = $dati['Cd_PRCausaleScarto'];


                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($update);

                if (isset($dati['Nr_Pedana_Collo'])) {
                    DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $dati['Nr_Pedana_Collo'] . '\'');
                }

                $tipologia = 2;
                $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                if (sizeof($OLAttivita) > 0) {
                    $OLAttivita = $OLAttivita[0];

                    if ($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO') {
                        $tipologia = 0;
                    }
                }

                $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $update['Nr_Collo'], $tipologia);
                if ($nome_file != '') {
                    $nomi_colli = array();
                    $dati['Copie'] = 1;
                    while ($dati['Copie'] > 0) {
                        array_push($nomi_colli, $nome_file);
                        $dati['Copie'] -= 1;
                    }
                }

                return Redirect::to('dettaglio_bolla/' . $id);

            }

            if (isset($dati['collo_conforme'])) {
                unset($dati['collo_conforme']);
                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);
                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update(array('NC' => 0));


                if ($dati['Nr_Pedana_Collo'] != '') {
                    DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $dati['Nr_Pedana_Collo'] . '\'');
                }

                return Redirect::to('dettaglio_bolla/' . $id . '#tab2');
            }

            if (isset($dati['elimina_collo'])) {
                unset($dati['elimina_collo']);
                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);

                HomeController::ripulisci_collo($id_collo);

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->delete();


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                    foreach ($pedane as $p) {
                        DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                    }
                }

                return Redirect::to('dettaglio_bolla/' . $id . '#tab2');
            }

            if (isset($dati['stampa_etichetta_pedana'])) {

                $cd_CF = DB::select('SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                        SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                            SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                                SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id . '
                            )
                        )
                    )
                ');

                if (sizeof($cd_CF) > 0) {

                    $cd_CF = $cd_CF[0]->CD_CF;
                    $report = DB::select('SELECT * from xWPReport where Cd_CF = \'' . $cd_CF . '\'');
                    if (sizeof($report) == 0) {
                        $report = DB::select('SELECT * from xWPReport where WPDefault_Report = 1');
                    }

                    if (sizeof($report) > 0) {

                        $report = DB::select('SELECT Ud_Report,NoteReport from ReportAll where Tipo = \'ARCA_INDUSTRY\' and Nome = \'' . $report[0]->RI_ETICHETTA_PEDANA . '\' Order by TimeIns desc');

                        if (sizeof($report) > 0) {


                            if (!file_exists('upload/etichetta_pedana_' . $dati['Id_xWPPD'] . '.pdf')) {

                                $insert_stampa['Modulo'] = $report[0]->Ud_Report;
                                $insert_stampa['Collo'] = '';
                                $insert_stampa['Pedana'] = $dati['Nr_Pedana'];
                                $insert_stampa['stampato'] = 0;
                                $insert_stampa['nome_file'] = 'etichetta_pedana_' . $dati['Id_xWPPD'] . '.pdf';
                                DB::table('xStampeIndustry')->insert($insert_stampa);
                                $kill_process = DB::Select('SELECT top 1 kill_process from xArcaIndustryConf')[0]->kill_process;
                                if ($kill_process == 1) {
                                    exec('taskkill /f /im splwow64.exe');
                                    exec('taskkill /f /im arcasql.exe');
                                }
                                exec('"C:\Program Files (x86)\Artel\ArcaEvolution\ArcaSql.exe" *Server=' . env('DB_HOST', 'forge') . '  *Ditta=' . env('DB_DATABASE', 'forge') . ' *LoginPrompt=3 *UserName=' . env('DB_USERNAME', 'forge') . ' *Password=' . env('DB_PASSWORD', 'forge') . ' *execute=C:\Program Files (x86)\Artel\ArcaEvolution\ArcaIndustry_all_packaging.prg');
                                while (!file_exists('upload/etichetta_pedana_' . $dati['Id_xWPPD'] . '.pdf')) sleep(1);

                            }


                            if ($report[0]->NoteReport != '') {
                                list($base, $altezza) = explode(';', $report[0]->NoteReport);
                                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization

                                $pagecount = $mpdf->setSourceFile('upload/etichetta_pedana_' . $dati['Id_xWPPD'] . '.pdf');
                                $tplId = $mpdf->importPage($pagecount);

                                $mpdf->useTemplate($tplId);
                                $mpdf->Output('upload/etichetta_pedana_' . $dati['Id_xWPPD'] . '.pdf', 'F');
                            }


                            return Redirect::to('dettaglio_bolla/' . $id . '?stampa=etichetta_pedana_' . $dati['Id_xWPPD']);

                        } else {
                            return Redirect::to('dettaglio_bolla/' . $id);
                        }

                    }

                }

            }

            if (isset($dati['stampa_foglio_pedana'])) {

                $cd_CF = DB::select('SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                        SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                            SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                                SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id . '
                            )
                        )
                    )
                ');

                if (sizeof($cd_CF) > 0) {

                    $cd_CF = $cd_CF[0]->CD_CF;
                    $report = DB::select('SELECT * from xWPReport where Cd_CF = \'' . $cd_CF . '\'');
                    if (sizeof($report) == 0) {
                        $report = DB::select('SELECT * from xWPReport where WPDefault_Report = 1');
                    }

                    if (sizeof($report) > 0) {

                        $report = DB::select('SELECT Ud_Report,NoteReport from ReportAll where Tipo = \'ARCA_INDUSTRY\' and Nome = \'' . $report[0]->RI_PEDANA . '\' Order by TimeIns desc');

                        if (sizeof($report) > 0) {

                            if (!file_exists('upload/foglio_pedana_' . $dati['Id_xWPPD'] . '.pdf')) {

                                $insert_stampa['Modulo'] = $report[0]->Ud_Report;
                                $insert_stampa['Collo'] = '';
                                $insert_stampa['Pedana'] = $dati['Nr_Pedana'];
                                $insert_stampa['stampato'] = 0;
                                $insert_stampa['nome_file'] = 'foglio_pedana_' . $dati['Id_xWPPD'] . '.pdf';
                                DB::table('xStampeIndustry')->insert($insert_stampa);
                                $kill_process = DB::Select('SELECT top 1 kill_process from xArcaIndustryConf')[0]->kill_process;
                                if ($kill_process == 1) {
                                    exec('taskkill /f /im splwow64.exe');
                                    exec('taskkill /f /im arcasql.exe');
                                }
                                exec('"C:\Program Files (x86)\Artel\ArcaEvolution\ArcaSql.exe" *Server=' . env('DB_HOST', 'forge') . '  *Ditta=' . env('DB_DATABASE', 'forge') . ' *LoginPrompt=3 *UserName=' . env('DB_USERNAME', 'forge') . ' *Password=' . env('DB_PASSWORD', 'forge') . ' *execute=C:\Program Files (x86)\Artel\ArcaEvolution\ArcaIndustry_all_packaging.prg');

                                while (!file_exists('upload/foglio_pedana_' . $dati['Id_xWPPD'] . '.pdf')) sleep(1);
                            }

                            return Redirect::to('dettaglio_bolla/' . $id . '?stampa=foglio_pedana_' . $dati['Id_xWPPD']);

                        } else {
                            return Redirect::to('dettaglio_bolla/' . $id);
                        }

                    }
                }

            }

            if (isset($dati['stampa_tutte_etichette'])) {

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $nomi_colli = array();
                    $colli = DB::select('SELECT * from xWPCollo where IdCodiceAttivita = ' . $attivita_bolla->Id_PrOLAttivita . ' order by Nr_Collo ASC');
                    /*                    foreach ($colli as $c) {

                                            $insert_stampa['Modulo'] = $report[0]->Ud_Report;
                                            $insert_stampa['Collo'] = $c->Nr_Collo;
                                            $insert_stampa['Pedana'] = '';
                                            $insert_stampa['stampato'] = 0;
                                            $insert_stampa['nome_file'] = 'etichetta_interna_' . $c->Nr_Collo . '.pdf';
                                            DB::table('xStampeIndustry')->insert($insert_stampa);
                                        }

                                        foreach ($colli as $c) {
                                            if (!file_exists('upload/etichetta_interna_' . $c->Nr_Collo . '.pdf')) {

                                                $kill_process = DB::Select('SELECT top 1 kill_process from xArcaIndustryConf')[0]->kill_process;
                                                if ($kill_process == 1) {
                                                    exec('taskkill /f /im splwow64.exe');
                                                    exec('taskkill /f /im arcasql.exe');
                                                }
                                                exec('"C:\Program Files (x86)\Artel\ArcaEvolution\ArcaSql.exe" *Server=' . env('DB_HOST', 'forge') . '  *Ditta=' . env('DB_DATABASE', 'forge') . ' *LoginPrompt=3 *UserName=' . env('DB_USERNAME', 'forge') . ' *Password=' . env('DB_PASSWORD', 'forge') . ' *execute=C:\Program Files (x86)\Artel\ArcaEvolution\ArcaIndustry_all_packaging.prg');
                                                while (!file_exists('upload/etichetta_interna_' . $c->Nr_Collo . '.pdf')) sleep(1);

                                            }

                                            if ($report[0]->NoteReport != '') {
                                                list($base, $altezza) = explode(';', $report[0]->NoteReport);
                                                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                                                $pagecount = $mpdf->setSourceFile('upload/etichetta_interna_' . $c->Nr_Collo . '.pdf');
                                                $tplId = $mpdf->importPage($pagecount);
                                                $mpdf->useTemplate($tplId);
                                                $mpdf->Output('upload/etichetta_interna_' . $c->Nr_Collo . '.pdf', 'F');
                                            }

                                            while ($c->Copie > 0) {
                                                array_push($nomi_colli, 'etichetta_interna_' . $c->Nr_Collo);
                                                $c->Copie -= 1;
                                            }

                                        }*/

                    /**
                     * Forzatura All Packaging
                     * Di Default prova a stampare collo grande tipologia 0
                     * se la fase è la saldatura stampa collo piccolo tipologia 2
                     * se la fase è quella prima dell'imballaggio stampa il collo anonimo tipologia 4
                     **/

                    $tipologia = 2;
                    $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                    if (sizeof($OLAttivita) > 0) {
                        $OLAttivita = $OLAttivita[0];

                        if ($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO') {
                            $tipologia = 0;
                        }
                    }
                    foreach ($colli as $c) {
                        $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $c->Nr_Collo, $tipologia);
                        if ($nome_file != '') {
                            $dati['Copie'] = $c->Copie;
                            while ($dati['Copie'] > 0) {
                                array_push($nomi_colli, $nome_file);
                                $dati['Copie'] -= 1;
                            }
                        }
                    }

                    return Redirect::to('dettaglio_bolla/' . $id);

                }

            }

            if (isset($dati['stampa_etichetta_interna'])) {

                $report = DB::select('SELECT * from xWPReport where WPDefault_Report = 1');

                if (sizeof($report) > 0) {

                    $report = DB::select('SELECT Ud_Report,NoteReport from ReportAll where Tipo = \'ARCA_INDUSTRY\' and Nome = \'' . $report[0]->RI_ETICHETTA_INTERNA . '\' Order by TimeIns desc');

                    if (sizeof($report) > 0) {

                        if (!file_exists('upload/etichetta_interna_' . $dati['Nr_Collo'] . '.pdf')) {

                            $insert_stampa['Modulo'] = $report[0]->Ud_Report;
                            $insert_stampa['Collo'] = $dati['Nr_Collo'];
                            $insert_stampa['Pedana'] = '';
                            $insert_stampa['stampato'] = 0;
                            $insert_stampa['nome_file'] = 'etichetta_interna_' . $dati['Nr_Collo'] . '.pdf';
                            DB::table('xStampeIndustry')->insert($insert_stampa);
                            $kill_process = DB::Select('SELECT top 1 kill_process from xArcaIndustryConf')[0]->kill_process;
                            if ($kill_process == 1) {
                                exec('taskkill /f /im splwow64.exe');
                                exec('taskkill /f /im arcasql.exe');
                            }
                            exec('"C:\Program Files (x86)\Artel\ArcaEvolution\ArcaSql.exe" *Server=' . env('DB_HOST', 'forge') . '  *Ditta=' . env('DB_DATABASE', 'forge') . ' *LoginPrompt=3 *UserName=' . env('DB_USERNAME', 'forge') . ' *Password=' . env('DB_PASSWORD', 'forge') . ' *execute=C:\Program Files (x86)\Artel\ArcaEvolution\ArcaIndustry_all_packaging.prg');
                            while (!file_exists('upload/etichetta_interna_' . $dati['Nr_Collo'] . '.pdf')) sleep(1);

                        }


                        if ($report[0]->NoteReport != '') {
                            list($base, $altezza) = explode(';', $report[0]->NoteReport);
                            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization

                            $pagecount = $mpdf->setSourceFile('upload/etichetta_interna_' . $dati['Nr_Collo'] . '.pdf');
                            $tplId = $mpdf->importPage($pagecount);

                            $mpdf->useTemplate($tplId);
                            $mpdf->Output('upload/etichetta_interna_' . $dati['Nr_Collo'] . '.pdf', 'F');
                        }


                        $nomi_colli = array();
                        $dati['Copie'] = 1;
                        while ($dati['Copie'] > 0) {
                            array_push($nomi_colli, 'etichetta_interna_' . $dati['Nr_Collo']);
                            $dati['Copie'] -= 1;
                        }

                        return Redirect::to('dettaglio_bolla/' . $id);

                    } else {
                        return Redirect::to('dettaglio_bolla/' . $id);
                    }

                }

            }

            if (isset($dati['stampa_collo'])) {
                unset($dati['stampa_collo']);

                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);
                unset($dati['Quantita']);
                unset($dati['esemplari']);
                unset($dati['Descrizione']);
                unset($dati['copie']);
                unset($dati['Rif_Nr_Collo_Ultimo']);

                $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'];

                if (isset($dati['Nr_Pedana_Collo']) || $dati['Nr_Pedana_Collo'] == '') {
                    $dati['Nr_Pedana'] = $dati['Nr_Pedana_Collo'];
                    unset($dati['Nr_Pedana_Collo']);
                }

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    $bolle = DB::select('SELECT * from PrBLEx Where Id_PrBL = ' . $attivita_bolla->Id_PrBL);
                    if (sizeof($bolle) > 0) {
                        $bolla = $bolle[0];
                        $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $articoli = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'');
                            if (sizeof($articoli) > 0) {
                                $articolo = $articoli[0];
                                $dati['Cd_AR'] = $articolo->Cd_AR;


                                $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                if (sizeof($umfatt) > 0) {
                                    $umfatt = $umfatt[0]->UMFatt;
                                    $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'] * $umfatt;
                                }
                            }
                        }
                    }
                }

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($dati);

                HomeController::ripulisci_collo($id_collo);

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                    foreach ($pedane as $p) {
                        DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                    }
                }


                /**
                 * Forzatura All Packaging
                 * Di Default prova a stampare collo grande tipologia 0
                 * se la fase è la saldatura stampa collo piccolo tipologia 2
                 * se la fase è quella prima dell'imballaggio stampa il collo anonimo tipologia 4
                 **/

                $tipologia = 2;
                $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                if (sizeof($OLAttivita) > 0) {
                    $OLAttivita = $OLAttivita[0];
                    if ($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO')
                        $tipologia = 0;
                }

                $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $dati['Nr_Collo'], $tipologia);
                if ($nome_file != '') {
                    $nomi_colli = array();
                    $dati['Copie'] = 1;
                    while ($dati['Copie'] > 0) {
                        array_push($nomi_colli, $nome_file);
                        $dati['Copie'] -= 1;
                    }
                }

                if (isset($dati['Id_xWPCollo'])) $id_collo = $dati['Id_xWPCollo'];
                if (isset($dati['Id_xWPCollo'])) unset($dati['Id_xWPCollo']);
                if (isset($dati['Quantita'])) unset($dati['Quantita']);
                if (isset($dati['esemplari'])) unset($dati['esemplari']);
                if (isset($dati['Descrizione'])) unset($dati['Descrizione']);
                if (isset($dati['Copie'])) unset($dati['copie']);
                if (isset($dati['Rif_Nr_Collo_Ultimo'])) unset($dati['Rif_Nr_Collo_Ultimo']);


                if (isset($dati['Nr_Pedana_Collo'])) {
                    $dati['Nr_Pedana'] = $dati['Nr_Pedana_Collo'];
                    unset($dati['Nr_Pedana_Collo']);
                }

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($dati);

                return Redirect::to('dettaglio_bolla/' . $id);


            }

            if (isset($dati['stampa_materia_prima'])) {
                unset($dati['stampa_materia_prima']);

                unset($dati['Id_xWPCollo']);
                unset($dati['Quantita']);
                unset($dati['esemplari']);
                unset($dati['Descrizione']);
                unset($dati['copie']);
                unset($dati['Rif_Nr_Collo_Ultimo']);


                /**
                 * Forzatura All Packaging
                 * Di Default prova a stampare collo grande tipologia 0
                 * se la fase è la saldatura stampa collo piccolo tipologia 2
                 * se la fase è quella prima dell'imballaggio stampa il collo anonimo tipologia 4
                 **/

                $tipologia = 2;
                /* $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                if (sizeof($OLAttivita) > 0) {
                    $OLAttivita = $OLAttivita[0];
                    if ($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO')
                        $tipologia = 0;
                } */
                $param1 = 'SELECT \'' . $dati['Id_PrBLAttivita'] . '\' as IdOrdineLavoro,\'' . $dati['Cd_AR'] . '\' as Cd_AR,\'' . $dati['Consumo'] . '\' as QtaProdotta,\'' . $dati['Cd_ARLotto'] . '\' as xLotto,\'' . $dati['Id_PrBLMateriale'] . '\' as Nr_Collo,\'' . $dati['Cd_ARMisura'] . '\' as Cd_ARMisura';
                $nome_file = StampaController::motore_industry_materiale($utente->Cd_PRRisorsa, $id, $param1, $tipologia, $dati['Id_PrBLMateriale']);
                if ($nome_file != '') {
                    $nomi_colli = array();
                    $dati['Copie'] = 1;
                    while ($dati['Copie'] > 0) {
                        array_push($nomi_colli, $nome_file);
                        $dati['Copie'] -= 1;
                    }
                }

                return Redirect::to('dettaglio_bolla/' . $id);


            }

            if (isset($dati['stampa_collo_qualita'])) {
                unset($dati['stampa_collo_qualita']);

                $nomi_colli = array();
                $id_collo = $dati['Id_xWPCollo'];
                unset($dati['Id_xWPCollo']);
                unset($dati['Quantita']);
                unset($dati['esemplari']);
                unset($dati['Descrizione']);
                unset($dati['copie']);
                unset($dati['Rif_Nr_Collo_Ultimo']);

                $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'];

                if (isset($dati['Nr_Pedana_Collo']) || $dati['Nr_Pedana_Collo'] == '') {
                    $dati['Nr_Pedana'] = $dati['Nr_Pedana_Collo'];
                    unset($dati['Nr_Pedana_Collo']);
                }

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    $bolle = DB::select('SELECT * from PrBLEx Where Id_PrBL = ' . $attivita_bolla->Id_PrBL);
                    if (sizeof($bolle) > 0) {
                        $bolla = $bolle[0];
                        $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $articoli = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'');
                            if (sizeof($articoli) > 0) {
                                $articolo = $articoli[0];
                                $dati['Cd_AR'] = $articolo->Cd_AR;


                                $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                if (sizeof($umfatt) > 0) {
                                    $umfatt = $umfatt[0]->UMFatt;
                                    $dati['QtaProdottaUmFase'] = $dati['QtaProdotta'] * $umfatt;
                                }
                            }
                        }
                    }
                }

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($dati);

                HomeController::ripulisci_collo($id_collo);

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $pedane = DB::select('SELECT * from xWPPD Where Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by Id_xWPPD DESC');
                    foreach ($pedane as $p) {
                        DB::update('
                        update xWPPD
                        Set QuantitaProdotta = (Select SUM(QtaProdottaUmFase) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        ,NumeroColli = (Select COUNT(*) from xWPCollo Where Nr_Pedana = xWPPD.Nr_Pedana and NC = 0)
                        where Nr_Pedana = \'' . $p->Nr_Pedana . '\'');
                    }
                }

                $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                if (sizeof($OLAttivita) > 0) {
                    $OLAttivita = $OLAttivita[0];

                    if ($OLAttivita->Cd_PrAttivita == 'SALDATURA') {

                        $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $dati['Nr_Collo'], 3);
                        if ($nome_file != '') {
                            array_push($nomi_colli, $nome_file);
                        }

                    } else {

                        $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $dati['Nr_Collo'], 1);
                        if ($nome_file != '') {
                            array_push($nomi_colli, $nome_file);
                        }
                    }

                }

                if (isset($dati['Id_xWPCollo'])) $id_collo = $dati['Id_xWPCollo'];
                if (isset($dati['Id_xWPCollo'])) unset($dati['Id_xWPCollo']);
                if (isset($dati['Quantita'])) unset($dati['Quantita']);
                if (isset($dati['esemplari'])) unset($dati['esemplari']);
                if (isset($dati['Descrizione'])) unset($dati['Descrizione']);
                if (isset($dati['Copie'])) unset($dati['copie']);
                if (isset($dati['Rif_Nr_Collo_Ultimo'])) unset($dati['Rif_Nr_Collo_Ultimo']);


                if (isset($dati['Nr_Pedana_Collo'])) {
                    $dati['Nr_Pedana'] = $dati['Nr_Pedana_Collo'];
                    unset($dati['Nr_Pedana_Collo']);
                }

                DB::table('xWPCollo')->where('Id_xWPCollo', $id_collo)->update($dati);

                if (sizeof($nomi_colli) > 0) {
                    return Redirect::to('dettaglio_bolla/' . $id);
                } else {
                    return Redirect::to('dettaglio_bolla/' . $id);

                }


            }

            if (isset($dati['start'])) {

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $esemplari = $dati['esemplari'];
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
                        $insert_collo['Rif_Nr_Collo'] = isset($dati['Rif_Nr_Collo_Ultimo']) ? $dati['Rif_Nr_Collo_Ultimo'] : '';
                        $insert_collo['Rif_Nr_Collo2'] = isset($dati['Rif_Nr_Collo2_Ultimo']) ? $dati['Rif_Nr_Collo2_Ultimo'] : '';
                        $insert_collo['IdOrdineLavoro'] = $attivita_bolla->Id_PrOL;
                        $insert_collo['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert_collo['IdCodiceAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                        $insert_collo['QtaProdotta'] = $dati['Quantita'];
                        $insert_collo['QtaProdottaUmFase'] = $dati['Quantita'];
                        $insert_collo['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert_collo['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                        $insert_collo['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                        $insert_collo['Copie'] = 1;
                        $insert_collo['NC'] = 0;
                        if (isset($dati['Nr_Pedana'])) {
                            $insert_collo['Nr_Pedana'] = $dati['Nr_Pedana'];
                        }

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

                                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                    if (sizeof($umfatt) > 0) {
                                        $umfatt = $umfatt[0]->UMFatt;
                                        $insert_collo['QtaProdottaUmFase'] = $dati['Quantita'] * $umfatt;
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

            if (isset($dati['stop'])) {


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $esemplari = $dati['esemplari'];
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

                        if (isset($dati['xLotto'])) {
                            $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                            if (sizeof($ordini) > 0) {
                                $ordine = $ordini[0];
                                if ($utente->Cd_PRRiparto != 'IMB') {
                                    if (!strpos($dati['xLotto'], '-')) {
                                        $dati['xLotto'] = $dati['xLotto'] . '-' . $id;
                                    }
                                }
                                $articolo = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'')[0];
                                $check = DB::SELECT('SELECT * FROM ARLotto where Cd_ARLotto = \'' . $dati['xLotto'] . '\' and Cd_AR = \'' . $articolo->Cd_AR . '\'');
                                if (sizeof($check) <= 0) {
                                    $data_scadenza = date('Y-m-d', strtotime('+2 year'));
                                    DB::insert('INSERT INTO ARLotto (Cd_AR,Cd_ARLotto,Descrizione,DataScadenza) VALUES (\'' . $articolo->Cd_AR . '\',\'' . $dati['xLotto'] . '\',\'Lotto ' . $dati['xLotto'] . ' Articolo ' . $articolo->Cd_AR . '\',\'' . $data_scadenza . '\')');
                                }
                            }
                        }

                        $insert_collo['Rif_Nr_Collo'] = isset($dati['Rif_Nr_Collo_Ultimo']) ? $dati['Rif_Nr_Collo_Ultimo'] : '';
                        $insert_collo['Rif_Nr_Collo2'] = isset($dati['Rif_Nr_Collo2_Ultimo']) ? $dati['Rif_Nr_Collo2_Ultimo'] : '';
                        $insert_collo['IdOrdineLavoro'] = $attivita_bolla->Id_PrOL;
                        $insert_collo['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert_collo['IdCodiceAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                        $insert_collo['QtaProdotta'] = $dati['Quantita'];
                        $insert_collo['QtaProdottaUmFase'] = $dati['Quantita'];
                        $insert_collo['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert_collo['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                        $insert_collo['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                        $insert_collo['xLotto'] = $dati['xLotto'];
                        $insert_collo['Copie'] = 1;
                        $insert_collo['NC'] = 0;
                        if (isset($dati['Nr_Pedana'])) {
                            $insert_collo['Nr_Pedana'] = $dati['Nr_Pedana'];
                        }

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

                                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR LIKE \'' . $articolo->Cd_AR . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                    if (sizeof($umfatt) > 0) {
                                        $umfatt = $umfatt[0]->UMFatt;
                                        $insert_collo['QtaProdottaUmFase'] = $dati['Quantita'] * $umfatt;
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

                    $nomi_colli = array();

                    $colli = DB::select('SELECT * from xWPCollo where QtaVersata < QtaProdotta and Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' order by Nr_Collo ASC');


                    $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                    if (sizeof($OLAttivita) > 0) {
                        $OLAttivita = $OLAttivita[0];

                        if (sizeof($colli) == $dati['esemplari']) {

                            if ($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO') {

                                $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $colli[0]->Nr_Collo, 0);
                                if ($nome_file != '') {
                                    array_push($nomi_colli, $nome_file);
                                }

                            } else {

                                $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, $colli[0]->Nr_Collo, 1);
                                if ($nome_file != '') {
                                    array_push($nomi_colli, $nome_file);
                                }
                            }
                        }
                    }

                    $tipologia = 2;
                    $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                    if (sizeof($OLAttivita) > 0) {
                        $OLAttivita = $OLAttivita[0];
                        if (($OLAttivita->Cd_PrAttivita == 'IMBALLAGGIO')) {
                            $tipologia = 0;
                        }
                    }


                    foreach ($colli as $c) {

                        if ($c->Stampato == 0) {

                            $nome_file = StampaController::motore_industry($utente->Cd_PRRisorsa, $id, str_replace(' ', '', $c->Nr_Collo), $tipologia);
                            if ($nome_file != '') {

                                DB::update('update xWPCollo set Stampato = 1 where Nr_Collo = \'' . str_replace(' ', '', $c->Nr_Collo) . '\'');

                                $dati['Copie'] = 1;
                                while ($dati['Copie'] > 0) {
                                    array_push($nomi_colli, $nome_file);
                                    $dati['Copie'] -= 1;
                                }
                            }

                        }

                    }

                    if (sizeof($nomi_colli) > 0) {

                        return Redirect::to('dettaglio_bolla/' . $id);

                    } else {
                        return Redirect::to('dettaglio_bolla/' . $id);
                    }

                }


            }

            if (isset($dati['fine_lavorazione_no'])) {
                //todo inserire valore unitario su MATERIALE

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    $quantita = $dati['quantita_totale'];

                    // todo quando fanno attivita di saldatura e stampa possono cambiare unita di misura ma dobbiamo dichiararli sempre a chili


//                    $quantita = DB::select('SELECT isnull(sum(QtaProdottaUmFase),0) as QtaProdotta from xWPCollo Where NC = 0 and Id_PrVRAttivita IS NULL and IdCodiceAttivita = ' . $attivita_bolla->Id_PrOLAttivita)[0]->QtaProdotta;


                    if ($dati['quantita_totale'] == 0) {

                        $insert_contatore['Id_PRBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert_contatore['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert_contatore['contatore'] = $dati['quantita_contatore'];
                        $insert_contatore['xWPCollo'] = '';
                        DB::table('xContatore')->insertGetId($insert_contatore);
                        return Redirect::to('');
                    }


                    if ($quantita > 0) {
                        $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                        $insert['Quantita'] = $quantita;
                        $insert['Quantita_Scar'] = (abs($dati['quantita_scarto_vr'])) ? abs($dati['quantita_scarto_vr']) : 0;
                        // $insert['xContatoreRisorsa'] = $dati['quantita_contatore'];
                        $insert['Data'] = date('Ymd');
                        //$insert['Cd_MG'] = $attivita_bolla->Cd_MG;
                        $insert['Cd_MG'] = $dati['cd_mg'];
                        $insert['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione';

                        $insert['CostoLavorazione'] = 0;
                        $insert['Esecuzione'] = 0;
                        $insert['Attrezzaggio'] = 0;
                        $insert['Fermo'] = 0;
                        $id_attivita = DB::table('PRVRAttivita')->insertGetId($insert);

                        $ordini = DB::select('SELECT PROL.*,PrOLAttivita.Cd_ARMisura,PrOLAttivita.Id_PrOLAttivita,PrOLAttivita.Id_PrOLAttivita_Next,PrOLAttivita.FattoreToUM1 from PrOL left join PrOLAttivita on PrOLAttivita.Id_PrOL = PrOL.Id_PrOL Where PrOLAttivita.Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $xlotto = (isset($dati['xLotto'])) ? $dati['xLotto'] : null;

                            $week = date('W', strtotime('today'));
                            $year = date('Y', strtotime('today'));
                            //$xlotto = $week . $ordine[0]->Cd_AR . $year;
                            /* if ($xlotto != '') {
                                 $check = DB::SELECT('SELECT * FROM ARLotto where Cd_ARLotto = \'' . $xlotto . '\' and Cd_AR = \'' . $ordine->Cd_AR . '\'');
                                 if (sizeof($check) <= 0) {
                                     $data_scadenza = date('Y-m-d', strtotime('+2 year'));
                                     DB::insert('INSERT INTO ARLotto (Cd_AR,Cd_ARLotto,Descrizione,DataScadenza) VALUES (\'' . $ordine->Cd_AR . '\',\'' . $xlotto . '\',\'Lotto ' . $xlotto . ' Articolo ' . $ordine->Cd_AR . '\',\'' . $data_scadenza . '\')');
                                 }
                                 //$attivita = DB::SELECT('SELECT * FROM PrOLAttivita WHERE Id_PrOLAttivita in ( SELECT Id_PrOLAttivita FROM PRBLAttivita WHERE Id_PrBLAttivita =  ' . $attivita_bolla->Id_PrBLAttivita . ')')[0];
                                 //if ($attivita->Id_PrOLAttivita_Next == null) {
                                 $consumo_tot = $dati['quantita_contatore'];

                                 if ($ordine->Id_PrOLAttivita_Next == null) {
                                     DB::table('PrVrMateriale')->insert(['Id_PrOLAttivita' => $attivita_bolla->Id_PrOLAttivita, 'Cd_AR' => $ordine->Cd_AR, 'Cd_ARLotto' => $xlotto, 'Cd_ARMisura' => $ordine->Cd_ARMisura, 'FattoreToUM1' => $ordine->FattoreToUM1, 'Cd_MG' => '00001', 'Tipo' => 0, 'Id_PRVRAttivita' => $id_attivita, 'Consumo' => -$consumo_tot]);
                                 }
                             } */
                        }
                    }

                    $insert_contatore['Id_PRBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                    $insert_contatore['Cd_Operatore'] = $utente->Cd_Operatore;
                    $insert_contatore['contatore'] = $dati['quantita_contatore'];
                    $consumo_tot = $dati['quantita_contatore'];

                    $key = '';

                    foreach ($dati['QtaVersata'] as $key => $value) {
                        DB::update('update xWPCollo set QtaVersata = QtaVersata + ' . $value . ' Where Id_xWPCollo = ' . $key);
                        // DB::update('update xWPCollo set Id_PrVRAttivita2 = ' . $id_attivita . ' Where Id_PrVrAttivita IS NOT NUll and Id_PrVrAttivita2 IS NUll and Id_xWPCollo = ' . $key);
                        DB::update('update xWPCollo set Id_PrVRAttivita = ' . $id_attivita . ' Where Id_PrVrAttivita IS NUll and Id_xWPCollo = ' . $key);
                    }

                    DB::update('update xWPPD set Confermato = 1 Where Id_PrVRAttivita IS NULL and Id_PrOL = ' . $attivita_bolla->Id_PrOL);

                    if ($key != '') {
                        $collo = DB::SELECT('SELECT Nr_Collo FROM xWPCollo where Id_xWPCollo = ' . $key)[0]->Nr_Collo;
                        $insert_contatore['xWPCollo'] = $collo;
                        $controllo = DB::SELECT('SELECT * FROM xContatore WHERE Id_PRBLattivita = \'' . $insert_contatore['Id_PRBLAttivita'] . '\' and xWPCollo = \'\'');
                        if (sizeof($controllo) > 0) {
                            foreach ($controllo as $c) {
                                DB::update('update xContatore set xWPCollo = ' . $collo . ' Where Id_xContatore = \'' . $c->Id_xContatore . '\' ');
                            }
                        }
                    }


                    DB::table('xContatore')->insertGetId($insert_contatore);

                    $qta_colli = DB::select('SELECT isnull(SUM(QtaProdotta),0) as qta from xWPCollo Where Id_PrVrAttivita = ' . $id_attivita)[0]->qta;
                    $proporzione = $qta_colli / $attivita_bolla->Quantita;

                    $materiale = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                    foreach ($materiale as $m) {
                        if ($m->NotePrBLMateriale == 'CALO PESO')
                            $m->Tipo = 2;

                        if (($m->Tipo == 2) ? $m->Consumo : $dati['quantita_totale'] > 0)
                            if ($m->Tipo != 0) {
                                $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                                $insert_pr_materiale['Tipo'] = $m->Tipo;
                                $insert_pr_materiale['Id_PrOLAttivita'] = $m->Id_PrOLAttivita;
                                $insert_pr_materiale['Cd_AR'] = $m->Cd_AR;
                                $insert_pr_materiale['Consumo'] = ($m->Tipo == 2) ? $m->Consumo : $dati['quantita_totale'];
                                $insert_pr_materiale['Cd_ARMisura'] = $m->Cd_ARMisura;
                                $insert_pr_materiale['FattoreToUM1'] = $m->FattoreToUM1;
                                $insert_pr_materiale['Sfrido'] = 0;


                                if ($m->Tipo == 2) {
                                    $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                                    if (sizeof($costo) > 0) {
                                        $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                                    }

                                    if (isset($m->Cd_ARLotto)) {
                                        $semilavorato = DB::select('SELECT TOP 1 pm.ValoreUnitario
                                    FROM PrVRAttivita pa
                                    LEFT JOIN PrVRMateriale pm on pm.Id_PrVRAttivita = pa.Id_PrVRAttivita and pm.Tipo = 0
                                    Where pm.Cd_AR = \'' . $m->Cd_AR . '\' and pm.Cd_ARLotto = \'' . $m->Cd_ARLotto . '\' order by pa.TimeIns Desc');
                                        if (sizeof($semilavorato) > 0) {
                                            $insert_pr_materiale['ValoreUnitario'] = number_format($semilavorato[0]->ValoreUnitario, 4, '.', '');
                                        }
                                    }
                                    if (isset($insert_pr_materiale['ValoreUnitario'])) {
                                        if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                            $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                    } else {
                                        $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                    }
                                } else {
                                    if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                                }

                                $insert_pr_materiale['Cd_MG'] = $m->Cd_MG;
                                $insert_pr_materiale['Cd_MGUbicazione'] = $m->Cd_MGUbicazione;
                                $insert_pr_materiale['Cd_ARLotto'] = $m->Cd_ARLotto;
                                $insert_pr_materiale['NotePrVRMateriale'] = $m->NotePrBLMateriale;

                                DB::table('PrVrMateriale')->insert($insert_pr_materiale);
                            }
                    }
                    session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');

                    $insert_rl['Id_PrVRAttivita'] = $id_attivita;
                    $insert_rl['Id_PrRLAttivita_Sibling'] = $id_ultima_rilevazione;
                    $insert_rl['Terminale'] = $utente->Cd_Terminale;
                    $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                    $insert_rl['InizioFine'] = 'F';
                    $insert_rl['TipoRilevazione'] = 'E';
                    $insert_rl['Id_PrBlAttivita'] = $id;
                    $insert_rl['Quantita'] = $qta_colli;
                    $insert_rl['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                    DB::table('PRRLAttivita')->insert($insert_rl);

                    DB::update('
                        update rf
                        set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                        from PRRLAttivita rf
                        JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrVRAttivita = ' . $id_attivita);
                    DB::update('
                        update vr
                        set vr.Esecuzione = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                        from PRVRAttivita vr
                        JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                        JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrVRAttivita = ' . $id_attivita);

                    DB::update('UPDATE  rl SET rl.Quantita = vr.Quantita FROM PRRLAttivita rl JOIN PRVRAttivita vr ON rl.Id_PrVRAttivita = vr.Id_PrVRAttivita and vr.Quantita != rl.Quantita and Rl.InizioFine = \'F\' and rl.TipoRilevazione = \'E\' and YEAR(DataOra) = YEAR(GETDATE()) and MONTH(DataOra) = MONTH(GETDATE())');
                    DB::update('UPDATE m set m.Consumo = -v.Quantita from PRVRMateriale m JOIN PRVRAttivita v ON v.Id_PrVRAttivita = m.Id_PrVRAttivita  Where m.Tipo = 0 and YEAR(v.Data) = YEAR(GETDATE()) and MONTH(v.Data) = MONTH(GETDATE()) and v.Quantita != -m.Consumo');
                    $ordini = DB::select('SELECT PROL.*,PrOLAttivita.Id_PrOLAttivita_Next,PrOLAttivita.Cd_ARMisura,PrOLAttivita.FattoreToUM1 from PrOL left join PrOLAttivita on PrOLAttivita.Id_PrOL = PrOL.Id_PrOL Where PrOLAttivita.Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                    if (sizeof($ordini) > 0) {
                        $ordine = $ordini[0];
                        $xlotto = (isset($dati['xLotto'])) ? $dati['xLotto'] : null;

                        $week = date('W', strtotime('today'));
                        $year = date('Y', strtotime('today'));
                        // $xlotto = $week . $ordine[0]->Cd_AR . $year;
                        if ($xlotto != '') {
                            $check = DB::SELECT('SELECT * FROM ARLotto where Cd_ARLotto = \'' . $xlotto . '\' and Cd_AR = \'' . $ordine->Cd_AR . '\'');
                            if (sizeof($check) <= 0) {
                                $data_scadenza = date('Y-m-d', strtotime('+2 year'));
                                DB::insert('INSERT INTO ARLotto (Cd_AR,Cd_ARLotto,Descrizione,DataScadenza) VALUES (\'' . $ordine->Cd_AR . '\',\'' . $xlotto . '\',\'Lotto ' . $xlotto . ' Articolo ' . $ordine->Cd_AR . '\',\'' . $data_scadenza . '\')');
                            }
                            //$attivita = DB::SELECT('SELECT * FROM PrOLAttivita WHERE Id_PrOLAttivita in ( SELECT Id_PrOLAttivita FROM PRBLAttivita WHERE Id_PrBLAttivita =  ' . $attivita_bolla->Id_PrBLAttivita . ')')[0];
                            //if ($attivita->Id_PrOLAttivita_Next == null) {

                            if ($ordine->Id_PrOLAttivita_Next == null) {
                                DB::table('PrVrMateriale')->insert(['Id_PrOLAttivita' => $attivita_bolla->Id_PrOLAttivita, 'Cd_AR' => $ordine->Cd_AR, 'Cd_ARLotto' => $xlotto, 'Cd_ARMisura' => $ordine->Cd_ARMisura, 'FattoreToUM1' => $ordine->FattoreToUM1, 'Cd_MG' => (isset($dati['cd_mg'])) ? $dati['cd_mg'] : '00009', 'Cd_MGUbicazione' => ((isset($dati['cd_mg_ubicazione']) && ($dati['cd_mg'] != '00009'))) ? $dati['cd_mg_ubicazione'] : null, 'Tipo' => 0, 'Id_PRVRAttivita' => $id_attivita, 'Consumo' => -$consumo_tot]);
                            }

                        }
                        //}

                        $durata = DB::SELECT('SELECT * FROM PrRLAttivita where Id_PrVRAttivita = ' . $id_attivita);
                        if (sizeof($durata) > 0)
                            $durata = $durata[0]->DurataMks;
                        else
                            $durata = 1;

                        $OLAttivita = DB::select('SELECT * FROM PrOLAttivita WHERE Id_PrOLAttivita = (SELECT Id_PrOLAttivita from PrBLAttivita WHERE Id_PrBLAttivita = ' . $id . ')');

                        DB::update('DECLARE @RESULT Numeric(18, 6);
                                      EXEC @RESULT = [dbo].[afn_PRVRCostoLavorazione] \'' . $OLAttivita[0]->Cd_PrRisorsa . '\', ' . $durata . ', null,\'' . $ordine->Cd_AR . '\', \'' . $OLAttivita[0]->Quantita . '\', NULL
                                      UPDATE PrVRAttivita set CostoLavorazione = @RESULT where Id_PrVRAttivita = ' . $id_attivita);

                        DB::update('DECLARE @result  Numeric(18, 6)
                                      EXEC @result = [xafn_PRVRCostoUnitario] ' . $id_attivita . ', \'' . $ordine->Cd_AR . '\', Null, \'U\', Null
                                      UPDATE PrVRMateriale set ValoreUnitario = @result where Tipo = 0 and Id_PrVRAttivita = ' . $id_attivita);

                        DB::update('
                        update MGMov
                        set MGMov.Valore = (PrVRMateriale.ValoreUnitario * PrVRMateriale.Consumo)
                        from MGMov
                        JOIN PrVRMateriale ON PrVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale and PrVRMateriale.Consumo > 0 and PrVRMateriale.Tipo != 0 and PrVRMateriale.Id_PrVRAttivita = ' . $id_attivita);

                        //DB::SELECT('EXEC xCalcolaLavoroLorenzo ' . $id_attivita);
                    }

                    return Redirect::to('');
                } else {

                    DB::delete('DELETE from PRRLAttivita Where Id_PRRLAttivita=' . $id_ultima_rilevazione . ' and Id_PrRLAttivita_Sibling IS NOT NULL');
                    return Redirect::to('');
                }
            }


            if (isset($dati['xcontatore'])) {
                if ($dati['xcontatore'] == 'SI') {
                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['contatore'] = $dati['contatore'];
                    $insert['Cd_Operatore'] = $utente->Cd_Operatore;
                    DB::table('xContatore')->insertGetId($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);
                }
            }

            if (isset($dati['fine_lavorazione_si'])) {


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    if ($dati['quantita_totale'] == 0) {
                        $insert_contatore['Id_PRBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert_contatore['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert_contatore['contatore'] = $dati['quantita_contatore'];
                        $insert_contatore['xWPCollo'] = '';
                        DB::table('xContatore')->insertGetId($insert_contatore);
                        return Redirect::to('');
                    }

                    $quantita = DB::select('SELECT isnull(sum(QtaProdottaUmFase),0) as QtaProdotta from xWPCollo Where NC = 0 and Id_PrVRAttivita IS NULL and IdCodiceAttivita = ' . $attivita_bolla->Id_PrOLAttivita)[0]->QtaProdotta;


                    $quantita_scarto_nc = DB::select('SELECT isnull(sum(QtaProdottaUmFase),0) as QtaProdotta from xWPCollo Where NC = 1 and Id_PRBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita)[0]->QtaProdotta;

                    if ($quantita > 0) {
                        $insert['Id_PrBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert['Cd_PrRisorsa'] = $utente->Cd_PRRisorsa;
                        $insert['Quantita'] = $quantita;
                        if ($dati['quantita_scarto_vr'] > 0) {
                            $insert['Quantita_Scar'] = abs($dati['quantita_scarto_vr']);
                        }
                        $consumo_tot = $dati['quantita_contatore'];

                        $insert['Data'] = date('Ymd');
                        //$insert['Cd_MG'] = $attivita_bolla->Cd_MG;
                        $insert['Cd_MG'] = $dati['cd_mg'];
                        $insert['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert['NotePrVRAttivita'] = 'Creato con SmartProduzione';

                        $insert['CostoLavorazione'] = 0;
                        $insert['Esecuzione'] = 0;
                        $insert['Attrezzaggio'] = 0;
                        $insert['Fermo'] = 0;
                        $insert['UltimoVR'] = 1;
                        $id_attivita = DB::table('PRVRAttivita')->insertGetId($insert);
                        $insert_contatore['Id_PRBLAttivita'] = $attivita_bolla->Id_PrBLAttivita;
                        $insert_contatore['Cd_Operatore'] = $utente->Cd_Operatore;
                        $insert_contatore['contatore'] = $dati['quantita_contatore'];


                        DB::update('update xWPCollo set Id_PrVRAttivita = ' . $id_attivita . ' Where Id_PrVRAttivita IS NULL and IdCodiceAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                        DB::update('update xWPPD set Confermato = 1 Where Id_PrVRAttivita IS NULL and Id_PrOL = ' . $attivita_bolla->Id_PrOL);

                        $qta_colli = DB::select('SELECT isnull(SUM(QtaProdotta),0) as qta from xWPCollo Where Id_PrVrAttivita = ' . $id_attivita)[0]->qta;
                        $proporzione = $qta_colli / $attivita_bolla->Quantita;

                        $materiale = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');


                        foreach ($materiale as $m) {
                            if ($m->NotePrBLMateriale == 'CALO PESO')
                                $m->Tipo = 2;

                            if (($m->Tipo == 2) ? $m->Consumo : $dati['quantita_totale'] > 0)
                                if ($m->Tipo != 0) {
                                    $insert_pr_materiale['Id_PRVRAttivita'] = $id_attivita;
                                    $insert_pr_materiale['Tipo'] = $m->Tipo;
                                    $insert_pr_materiale['Id_PrOLAttivita'] = $m->Id_PrOLAttivita;
                                    $insert_pr_materiale['Cd_AR'] = $m->Cd_AR;
                                    $insert_pr_materiale['Consumo'] = ($m->Tipo == 2) ? $m->Consumo : $dati['quantita_totale'];
                                    $insert_pr_materiale['Cd_ARMisura'] = $m->Cd_ARMisura;
                                    $insert_pr_materiale['FattoreToUM1'] = $m->FattoreToUM1;
                                    $insert_pr_materiale['Sfrido'] = 0;


                                    if ($m->Tipo == 2) {
                                        $costo = DB::select('SELECT * from ARCostoItem Where Cd_AR = \'' . $m->Cd_AR . '\'and Cd_MGEsercizio = YEAR(GETDATE()) and TipoCosto = \'U\'');
                                        if (sizeof($costo) > 0) {
                                            $insert_pr_materiale['ValoreUnitario'] = number_format($costo[0]->Costo, 4, '.', '');
                                        }
                                        if (isset($m->Cd_ARLotto)) {
                                            $semilavorato = DB::select('SELECT TOP 1 pm.ValoreUnitario
                                    FROM PrVRAttivita pa
                                    LEFT JOIN PrVRMateriale pm on pm.Id_PrVRAttivita = pa.Id_PrVRAttivita and pm.Tipo = 0
                                    Where pm.Cd_AR = \'' . $m->Cd_AR . '\' and pm.Cd_ARLotto = \'' . $m->Cd_ARLotto . '\'  order by pa.TimeIns Desc');
                                            if (sizeof($semilavorato) > 0) {
                                                $insert_pr_materiale['ValoreUnitario'] = number_format($semilavorato[0]->ValoreUnitario, 4, '.', '');
                                            }
                                        }
                                        if (isset($insert_pr_materiale['ValoreUnitario'])) {
                                            if ($insert_pr_materiale['ValoreUnitario'] == null || $insert_pr_materiale['ValoreUnitario'] <= 0)
                                                $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                        } else {
                                            $insert_pr_materiale['ValoreUnitario'] = 0.01;
                                        }
                                    } else {
                                        if (isset($insert_pr_materiale['ValoreUnitario'])) unset($insert_pr_materiale['ValoreUnitario']);
                                    }

                                    $insert_pr_materiale['Cd_MG'] = $m->Cd_MG;
                                    $insert_pr_materiale['Cd_MGUbicazione'] = $m->Cd_MGUbicazione;
                                    $insert_pr_materiale['Cd_ARLotto'] = $m->Cd_ARLotto;
                                    $insert_pr_materiale['NotePrVRMateriale'] = $m->NotePrBLMateriale;

                                    DB::table('PrVrMateriale')->insert($insert_pr_materiale);
                                }
                        }
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');

                        $insert_rl['Id_PrVRAttivita'] = $id_attivita;
                        $insert_rl['Id_PrRLAttivita_Sibling'] = $id_ultima_rilevazione;
                        $insert_rl['Terminale'] = $utente->Cd_Terminale;
                        $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                        $insert_rl['InizioFine'] = 'F';
                        $insert_rl['TipoRilevazione'] = 'E';
                        $insert_rl['Id_PrBlAttivita'] = $id;
                        $insert_rl['Quantita'] = $qta_colli;
                        $insert_rl['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                        DB::table('PRRLAttivita')->insert($insert_rl);

                        DB::update('
                            update rf
                            set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                            from PRRLAttivita rf
                            JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrVRAttivita = ' . $id_attivita);

                        DB::update('
                        update MGMov
                        set MGMov.Valore = (PrVRMateriale.ValoreUnitario * PrVRMateriale.Consumo)
                        from MGMov
                        JOIN PrVRMateriale ON PrVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale and PrVRMateriale.Tipo != 0 and PrVRMateriale.Tipo != 3 and PrVRMateriale.Id_PrVRAttivita = ' . $id_attivita);
                        /*
                                                DB::update('
                                                update MGMov
                                                set MGMov.Valore = (SELECT SUM(Valore) from MGMov where Id_PrVRMateriale IN (SELECT Id_PrVRMateriale FROM PRVRMateriale where Id_PrVRAttivita = ' . $id_attivita . '))
                                                from MGMov
                                                JOIN PrVRMateriale ON
                                                PrVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale and PrVRMateriale.Tipo = 0
                                                and PrVRMateriale.Id_PrVRAttivita = ' . $id_attivita
                                                );


                                                DB::update(' update PRVRMateriale
                                                set PRVRMateriale.ValoreUnitario =
                                                (SELECT
                                                ((SELECT SUM(Valore) from MGMov where Id_PrVRMateriale IN (SELECT Id_PrVRMateriale FROM PRVRMateriale where Id_PrVRAttivita = ' . $id_attivita . '))
                                                / (SELECT Quantita FROM PRVRAttivita where Id_PrVRAttivita = ' . $id_attivita . ')))
                                                from PRVRMateriale
                                                JOIN MGMov ON
                                                PrVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale and PrVRMateriale.Tipo = 0
                                                and PrVRMateriale.Id_PrVRAttivita = ' . $id_attivita);
                        */

                        DB::update('
                            update vr
                            set vr.Esecuzione = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                            from PRVRAttivita vr
                            JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                            JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrVRAttivita = ' . $id_attivita);
                        DB::update('UPDATE  rl SET rl.Quantita = vr.Quantita FROM PRRLAttivita rl JOIN PRVRAttivita vr ON rl.Id_PrVRAttivita = vr.Id_PrVRAttivita and vr.Quantita != rl.Quantita and Rl.InizioFine = \'F\' and rl.TipoRilevazione = \'E\' and YEAR(DataOra) = YEAR(GETDATE()) and MONTH(DataOra) = MONTH(GETDATE())');
                        DB::update('UPDATE m set m.Consumo = -v.Quantita from PRVRMateriale m JOIN PRVRAttivita v ON v.Id_PrVRAttivita = m.Id_PrVRAttivita  Where m.Tipo = 0 and YEAR(v.Data) = YEAR(GETDATE()) and MONTH(v.Data) = MONTH(GETDATE()) and v.Quantita != -m.Consumo');

                        $ordini = DB::select('SELECT PROL.*,PrOLAttivita.Cd_ARMisura,PrOLAttivita.Id_PrOLAttivita_Next,PrOLAttivita.FattoreToUM1 from PrOL left join PrOLAttivita on PrOLAttivita.Id_PrOL = PrOL.Id_PrOL Where PrOLAttivita.Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $xlotto = (isset($dati['xLotto'])) ? $dati['xLotto'] : null;
                            if ($xlotto != '') {
                                $check = DB::SELECT('SELECT * FROM ARLotto where Cd_ARLotto = \'' . $xlotto . '\' and Cd_AR = \'' . $ordine->Cd_AR . '\'');
                                if (sizeof($check) <= 0) {
                                    $data_scadenza = date('Y-m-d', strtotime('+2 year'));
                                    DB::insert('INSERT INTO ARLotto (Cd_AR,Cd_ARLotto,Descrizione,DataScadenza) VALUES (\'' . $ordine->Cd_AR . '\',\'' . $xlotto . '\',\'Lotto ' . $xlotto . ' Articolo ' . $ordine->Cd_AR . '\',\'' . $data_scadenza . '\')');
                                }
                                if ($ordine->Id_PrOLAttivita_Next == null) {
                                    DB::table('PrVrMateriale')->insert(['Id_PrOLAttivita' => $attivita_bolla->Id_PrOLAttivita, 'Cd_AR' => $ordine->Cd_AR, 'Cd_ARLotto' => $xlotto, 'Cd_ARMisura' => $ordine->Cd_ARMisura, 'FattoreToUM1' => $ordine->FattoreToUM1, 'Cd_MG' => ($dati['cd_mg']) ? $dati['cd_mg'] :
                                        '00001', 'Tipo' => 0, 'Id_PRVRAttivita' => $id_attivita, 'Consumo' => -$consumo_tot]);
                                }

                            }

                            $durata = DB::SELECT('SELECT * FROM PrRLAttivita where Id_PrVRAttivita = ' . $id_attivita);
                            if (sizeof($durata) > 0)
                                $durata = $durata[0]->DurataMks;
                            else
                                $durata = 1;

                            $OLAttivita = DB::select('SELECT * FROM PrOLAttivita WHERE Id_PrOLAttivita = (SELECT Id_PrOLAttivita from PrBLAttivita WHERE Id_PrBLAttivita = ' . $id . ')');

                            DB::update('DECLARE @RESULT Numeric(18, 6);
                                      EXEC @RESULT = [dbo].[afn_PRVRCostoLavorazione] \'' . $OLAttivita[0]->Cd_PrRisorsa . '\', ' . $durata . ', null,\'' . $ordine->Cd_AR . '\', \'' . $OLAttivita[0]->Quantita . '\', NULL
                                      UPDATE PrVRAttivita set CostoLavorazione = @RESULT where Id_PrVRAttivita = ' . $id_attivita);

                            DB::update('DECLARE @result  Numeric(18, 6)
                                      EXEC @result = [xafn_PRVRCostoUnitario] ' . $id_attivita . ', \'' . $ordine->Cd_AR . '\', Null, \'U\', Null
                                      UPDATE PrVRMateriale set ValoreUnitario = @result where Tipo = 0 and Id_PrVRAttivita = ' . $id_attivita);

                            DB::update('
                        update MGMov
                        set MGMov.Valore = (PrVRMateriale.ValoreUnitario * PrVRMateriale.Consumo)
                        from MGMov
                        JOIN PrVRMateriale ON PrVRMateriale.Id_PrVRMateriale = MGMov.Id_PrVRMateriale and PrVRMateriale.Consumo > 0 and PrVRMateriale.Tipo != 0 and PrVRMateriale.Id_PrVRAttivita = ' . $id_attivita);

                            //DB::SELECT('EXEC xCalcolaLavoroLorenzo ' . $id_attivita);
                        }
                        return Redirect::to('');

                    } else {

                        DB::delete('DELETE from PRRLAttivita Where Id_PRRLAttivita=' . $id_ultima_rilevazione . ' and Id_PrRLAttivita_Sibling IS NOT NULL');
                        return Redirect::to('');
                    }

                }

            }

            if (isset($dati['aggiungi_utente_gruppo'])) {
                unset($dati['aggiungi_utente_gruppo']);
                $dati['Id_PrBlAttivita'] = $id;
                DB::table('xwpGruppiLavoro')->insert($dati);
                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['elimina_utente_gruppo'])) {
                unset($dati['elimina_utente_gruppo']);
                DB::table('xwpGruppiLavoro')->where('Id_xwpGruppiLavoro', $dati['Id_xwpGruppiLavoro'])->delete();
                return Redirect::to('dettaglio_bolla/' . $id);
            }

            if (isset($dati['inizio_fermo'])) {

                $insert['NotePrRLAttivita'] = '';
                $insert['Terminale'] = $utente->Cd_Terminale;
                $insert['Cd_operatore'] = $utente->Cd_Operatore;
                $insert['InizioFine'] = 'I';
                $insert['TipoRilevazione'] = 'F';
                $insert['Id_PrBlAttivita'] = $id;
                $insert['Cd_PRRisorsa'] = $dati['Cd_PrRisorsa'];
                DB::table('PRRLAttivita')->insert($insert);

                return Redirect::to('dettaglio_bolla/' . $id);

            }

            if (isset($dati['fine_fermo'])) {
                $causale = DB::SELECT('SELECT * FROM PRCausaleFermo WHERE Cd_PRCausaleFermo = \'' . $dati['Cd_PRCausaleFermo'] . '\' ')[0]->Descrizione;
                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $numero = $attivita_bolla->Id_PrOL;
                    $cf = DB::SELECT('SELECT Cf.Descrizione FROM CF
                                            LEFT JOIN DORig ON CF.Cd_CF = Dorig.Cd_CF
                                            LEFT JOIN PROLDoRig ON PROLDoRig.Id_DoRig = DORig.Id_DORig
                                            WHERE PROLDoRig.ID_PROL = \'' . $numero . '\'');


                    if (sizeof($cf) > 0)
                        $cf = $cf[0]->Descrizione;
                    else
                        $cf = 'Non Riscontrato';

                    $insert_rl['Id_PrRLAttivita_Sibling'] = $id_ultima_rilevazione;
                    $insert_rl['Terminale'] = $utente->Cd_Terminale;
                    $insert_rl['Cd_operatore'] = $utente->Cd_Operatore;
                    $insert_rl['InizioFine'] = 'F';
                    $insert_rl['TipoRilevazione'] = 'F';
                    $insert_rl['Id_PrBlAttivita'] = $id;
                    $insert_rl['Cd_PRRisorsa'] = $dati['Cd_PrRisorsa'];
                    $insert_rl['NotePrRLAttivita'] = $dati['NotePrRLAttivita'];

                    DB::table('PRRLAttivita')->insert($insert_rl);


                    DB::update('
                        update rf
                        set rf.DurataMks = DATEDIFF(SECOND,ri.DataOra,rf.DataOra)
                        from PRRLAttivita rf
                        JOIN PRRLAttivita ri ON ri.Id_PrRLAttivita = rf.Id_PrRLAttivita_Sibling and rf.Id_PrRLAttivita_Sibling = ' . $id_ultima_rilevazione);


                    DB::update('
                        update vr
                        set vr.Fermo = CONVERT(numeric(18,8),rf.DurataMks) / vr.FattoreMKS, vr.CostoLavorazione = (pr.CostoOrario / vr.FattoreMks) * (rf.DurataMks / vr.FattoreMKS)
                        from PRVRAttivita vr
                        JOIN PRRisorsa pr ON pr.Cd_PrRisorsa = vr.Cd_PrRisorsa
                        JOIN PRRLAttivita rf ON rf.Id_PrVRAttivita = vr.Id_PrVRAttivita and rf.Id_PrRLAttivita_Sibling = ' . $id_ultima_rilevazione);

                    /*
                                        $mail = new  PHPMailer(true);
                                        $mail->isSMTP();
                                        $mail->Host = 'out.postassl.it';
                                        $mail->SMTPAuth = true;
                                        $mail->Username = 'produzione@allpackaging.it';
                                        $mail->Password = '#4fxJ934Mk76';
                                        $mail->SMTPSecure = 'ssl';
                                        $mail->Port = 465;
                                        $mail->setFrom('produzione@allpackaging.it', 'Produzione All Packaging');
                                        $mail->addAddress('laboratorio@allpackaging.it');

                                        $mail->IsHTML(true);

                                        $mail->Subject = 'Arca Industry - All Packaging - Fine fermo Bolla ' . $numero . '  -  ' . $cf;

                                        $mail->Body = '
                                                    Causale Fermo : ' . $dati['Cd_PRCausaleFermo'] . ' - ' . $causale . '<br>
                                                    Risorsa: ' . $risorsa . '<br>
                                                    Operatore: ' . $utente->Cd_Operatore . '<br>
                                                    Messaggio: ' . nl2br($dati['NotePrRLAttivita']);
                                        $mail->send();*/


                    return Redirect::to('dettaglio_bolla/' . $id);
                }

            }

            if (isset($dati['chiudi_bolla'])) {

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    DB::update('update PRBLAttivita set Attrezzaggio = (select SUM(Attrezzaggio) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRBLAttivita set Esecuzione = (select SUM(Esecuzione) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRBLAttivita set Attesa = (select SUM(Fermo) from PRVRAttivita Where Id_PrBLAttivita = PRBLAttivita.Id_PrBLAttivita) Where Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita);
                    DB::update('update PRRLAttivita Set UltimoRL = 1 Where Id_PrRLAttivita IN (Select max(Id_PrRLAttivita) From PrRLAttivita r Where r.Id_PrBLAttivita = ' . $attivita_bolla->Id_PrBLAttivita . ' And r.InizioFine = \'F\' and r.TipoRilevazione = \'E\')');

                    $PrVRAttivita = DB::select('SELECT top 1 Id_PrVRAttivita from PRVRAttivita Where Id_PrBLAttivita = ' . $id . ' and Esecuzione > 0 order by Data DESC');
                    if (sizeof($PrVRAttivita) > 0) {
                        DB::update('UPDATE PRVRAttivita set UltimoVR = 1 Where Id_PrVRAttivita = ' . $PrVRAttivita[0]->Id_PrVRAttivita);
                    }
                }

                return Redirect::to('');
            }
            if (isset($dati['aggiungi_scarto'])) {
                unset($dati['aggiungi_scarto']);

                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);
                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];
                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['Id_PrOLAttivita'] = null;
                    $insert['Id_PrBLMateriale'] = rand(0, 999999);
                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = -$dati['Quantita'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                    if (sizeof($umfatt) > 0) {
                        $umfatt = $umfatt[0]->UMFatt;
                    } else $umfatt = 1;
                    $insert['FattoreToUM1'] = $umfatt;
                    $insert['Cd_AR'] = $dati['Cd_AR'];
                    $insert['Descrizione'] = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                    $insert['Obbligatorio'] = DB::select('SELECT COALESCE(MG_LottoObbligatorio,0) as ciao FROM AR WHERE AR.Cd_AR = \'' . $dati['Cd_AR'] . '\' ')[0]->ciao;
                    $insert['NotePrBLMateriale'] = '';
                    $insert['Cd_ARLotto'] = $dati['Cd_ARLotto'];
                    $insert['Cd_MG'] = $dati['Cd_MG'];
                    $insert['Cd_MGUbicazione'] = NULL;


                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update1 = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        $materiale_update1[sizeof($materiale_update1)] = json_decode(json_encode($insert));
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update1]);
                        session()->save();
                    }

                    $insert['Id_PrBLMateriale'] = rand(0, 999999);
                    $insert['Cd_AR'] = $dati['articolo_scarto'];
                    $insert['Consumo'] = -$dati['quantita_scarto_sclav'];
                    $insert['Cd_ARLotto'] = NULL;
                    $insert['Obbligatorio'] = DB::select('SELECT COALESCE(MG_LottoObbligatorio,0) as ciao FROM AR WHERE AR.Cd_AR = \'' . $dati['articolo_scarto'] . '\' ')[0]->ciao;

                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update1 = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        $materiale_update1[sizeof($materiale_update1)] = json_decode(json_encode($insert));
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update1]);
                        session()->save();
                    }
                    return Redirect::to('dettaglio_bolla/' . $id);
                }
            }
            if (isset($dati['elimina_materiale'])) {
                unset($dati['elimina_materiale']);
                /*                DB::table('PRBLMateriale')->where('Id_PrBLMateriale', $dati['Id_PrBLMateriale'])->delete();
                                return Redirect::to('dettaglio_bolla/' . $id);*/


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        foreach ($materiale_update as $KEY => $m) {
                            if ($m->Id_PrBLMateriale == $dati['Id_PrBLMateriale']) {
                                unset($materiale_update[$KEY]);
                            }
                        }
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update]);
                        session()->save();
                    }
                }
                //DB::table('PRBLMateriale')->where('Id_PrBLMateriale', $dati['Id_PrBLMateriale'])->delete();
                return Redirect::to('dettaglio_bolla/' . $id);
            }
            if (isset($dati['aggiungi_calo_peso'])) {
                unset($dati['aggiungi_calo_peso']);


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        foreach ($materiale_update as $m) {
                            if ($m->Id_PrBLMateriale == $dati['Id_PrBLMateriale']) {
                                $m->Tipo = $dati['Tipo'];
                                $m->Consumo = $dati['Quantita_Madre']; //- $dati['calo_peso'];
                                $m->Cd_ARMisura = $dati['Cd_ARMisura'];
                                $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                if (sizeof($umfatt) > 0) {
                                    $umfatt = $umfatt[0]->UMFatt;
                                } else $umfatt = 1;
                                $m->FattoreToUM1 = $umfatt;
                                $m->Cd_AR = $dati['Cd_AR'];
                                $m->Descrizione = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                                $m->Cd_ARLotto = $dati['Cd_ARLotto'];
                                $m->Cd_MG = $dati['Cd_MG'];
                                $m->Cd_MGUbicazione = $dati['Cd_MGUbicazione'];
                            }
                        }
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update]);
                        session()->save();
                    }

                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['Id_PrOLAttivita'] = null;
                    $insert['Id_PrBLMateriale'] = rand(0, 999999);
                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = -$dati['calo_peso'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                    if (sizeof($umfatt) > 0) {
                        $umfatt = $umfatt[0]->UMFatt;
                    } else $umfatt = 1;
                    $insert['FattoreToUM1'] = $umfatt;
                    $insert['Cd_AR'] = $dati['cp'];
                    $insert['Cd_ARLotto'] =null;
                    $insert['Descrizione'] = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                    $insert['Obbligatorio'] = $dati['Obbligatorio'];
                    $insert['NotePrBLMateriale'] = 'CALO PESO';
                    //$insert['Cd_ARLotto'] = $dati['Cd_ARLotto'];
                    $insert['Cd_MG'] = 'CP';//$dati['Cd_MG'];
                    $insert['Cd_MGUbicazione'] = $dati['Cd_MGUbicazione'];


                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update1 = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        $materiale_update1[sizeof($materiale_update1)] = json_decode(json_encode($insert));
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update1]);
                        session()->save();
                    }


                    return Redirect::to('dettaglio_bolla/' . $id);

                }

            }
            if (isset($dati['aggiungi_materiale_senza_lotto'])) {
                unset($dati['aggiungi_materiale_senza_lotto']);


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


                if (sizeof($attivita_bolle) > 0) {
                    $attivita_bolla = $attivita_bolle[0];

                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['Id_PrOLAttivita'] = null;
                    $insert['Id_PrBLMateriale'] = rand(0, 999999);
                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = $dati['Quantita'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                    if (sizeof($umfatt) > 0) {
                        $umfatt = $umfatt[0]->UMFatt;
                    } else $umfatt = 1;
                    $insert['FattoreToUM1'] = $umfatt;
                    $insert['Cd_AR'] = $dati['Cd_AR'];
                    $insert['Descrizione'] = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                    $insert['Obbligatorio'] = DB::select('SELECT COALESCE(MG_LottoObbligatorio,0) as ciao FROM AR WHERE AR.Cd_AR = \'' . $dati['Cd_AR'] . '\' ')[0]->ciao;
                    $insert['NotePrBLMateriale'] = '';
                    $insert['Cd_MG'] = $dati['Cd_MG'];
                    $insert['Cd_ARLotto'] = null;
                    $insert['Cd_MGUbicazione'] = $dati['Cd_MGUbicazione'];


                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        $materiale_update[sizeof($materiale_update)] = json_decode(json_encode($insert));
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update]);
                        session()->save();
                    }

                    // DB::table('PRBLMateriale')->insert($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);

                }

            }

            if (isset($dati['aggiungi_materiale'])) {
                unset($dati['aggiungi_materiale']);


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


                if (sizeof($attivita_bolle) > 0) {/*
                    $attivita_bolla = $attivita_bolle[0];

                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['Id_PrOLAttivita'] = null;
                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = $dati['Quantita'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    if ($dati['Tipo'] == 2) {
                        $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                        if (sizeof($umfatt) > 0) {
                            $umfatt = $umfatt[0]->UMFatt;
                        } else $umfatt = 1;
                        $insert['FattoreToUM1'] = $umfatt;
                        $insert['Cd_AR'] = $dati['Cd_AR'];
                        $insert['Cd_ARLotto'] = $dati['Cd_ARLotto'];
                        $insert['Cd_MG'] = $dati['Cd_MG'];
                        $insert['Cd_MGUbicazione'] = $dati['Cd_MGUbicazione'];
                    } else {

                        $colli = DB::select('SELECT * from xWPCollo Where Nr_Collo = \'' . $dati['Cd_ARLotto'] . '\'');
                        if (sizeof($colli) > 0) {
                            $insert['NotePrBLMateriale'] = $dati['Cd_ARLotto'];
                            $insert['Id_PrOLAttivita'] = $attivita_bolla->Id_PrOLAttivita;
                            $insert['Cd_MG'] = '00001';
                        }
                    }

                    DB::table('PRBLMateriale')->insert($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);*/
                    $attivita_bolla = $attivita_bolle[0];

                    $insert['Id_PrBLAttivita'] = $id;
                    $insert['Id_PrOLAttivita'] = null;
                    $insert['Id_PrBLMateriale'] = rand(0, 999999);
                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = $dati['Quantita'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                    if (sizeof($umfatt) > 0) {
                        $umfatt = $umfatt[0]->UMFatt;
                    } else $umfatt = 1;
                    $insert['FattoreToUM1'] = $umfatt;
                    $insert['Cd_AR'] = $dati['Cd_AR'];
                    $insert['Descrizione'] = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                    $insert['Obbligatorio'] = DB::select('SELECT COALESCE(MG_LottoObbligatorio,0) as ciao FROM AR WHERE AR.Cd_AR = \'' . $dati['Cd_AR'] . '\' ')[0]->ciao;
                    $insert['NotePrBLMateriale'] = '';
                    $insert['Cd_ARLotto'] = $dati['Cd_ARLotto'];
                    $insert['Cd_MG'] = $dati['Cd_MG'];
                    $insert['Cd_MGUbicazione'] = $dati['Cd_MGUbicazione'];


                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        $materiale_update[sizeof($materiale_update)] = json_decode(json_encode($insert));
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update]);
                        session()->save();
                    }

                    // DB::table('PRBLMateriale')->insert($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);

                }

            }

            if (isset($dati['modifica_materiale'])) {
                unset($dati['modifica_materiale']);


                $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


                if (sizeof($attivita_bolle) > 0) {/*
                    $attivita_bolla = $attivita_bolle[0];

                    $insert['Tipo'] = $dati['Tipo'];
                    $insert['Consumo'] = $dati['Quantita'];
                    $insert['Cd_ARMisura'] = $dati['Cd_ARMisura'];
                    $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                    if (sizeof($umfatt) > 0) {
                        $umfatt = $umfatt[0]->UMFatt;
                    } else $umfatt = 1;
                    $insert['FattoreToUM1'] = $umfatt;
                    $insert['Cd_AR'] = $dati['Cd_AR'];
                    $insert['Cd_ARLotto'] = $dati['Cd_ARLotto'];
                    $insert['Cd_MG'] = $dati['Cd_MG'];
                    $insert['Cd_MGUbicazione'] = $dati['Cd_MGUbicazione'];


                    DB::table('PRBLMateriale')->where('Id_PrBLMateriale', $dati['Id_PrBLMateriale'])->update($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);*/
                    $attivita_bolla = $attivita_bolle[0];
                    if (session()->has('\'' . $attivita_bolla->Id_PrBLAttivita . '\'')) {
                        $materiale_update = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        foreach ($materiale_update as $m) {
                            if ($m->Id_PrBLMateriale == $dati['Id_PrBLMateriale']) {
                                $m->Tipo = $dati['Tipo'];
                                $m->Consumo = $dati['Quantita'];
                                $m->Cd_ARMisura = $dati['Cd_ARMisura'];
                                $umfatt = DB::select('SELECT UMFatt from ARARMisura Where Cd_AR = \'' . $dati['Cd_AR'] . '\' and Cd_ARMisura = \'' . $dati['Cd_ARMisura'] . '\'');
                                if (sizeof($umfatt) > 0) {
                                    $umfatt = $umfatt[0]->UMFatt;
                                } else $umfatt = 1;
                                $m->FattoreToUM1 = $umfatt;
                                $m->Cd_AR = $dati['Cd_AR'];
                                $m->Descrizione = DB::SELECT('SELECT Descrizione from AR where Cd_AR = \'' . $dati['Cd_AR'] . '\'')[0]->Descrizione;
                                $m->Cd_ARLotto = $dati['Cd_ARLotto'];
                                $m->Cd_MG = $dati['Cd_MG'];
                                $m->Cd_MGUbicazione = $dati['Cd_MGUbicazione'];
                            }
                        }
                        session()->remove('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                        session(['\'' . $attivita_bolla->Id_PrBLAttivita . '\'' => $materiale_update]);
                        session()->save();
                    }


                    //DB::table('PRBLMateriale')->where('Id_PrBLMateriale', $dati['Id_PrBLMateriale'])->update($insert);
                    return Redirect::to('dettaglio_bolla/' . $id);

                }

            }

            $risorse = DB::select('SELECT * from PRRisorsa Where Cd_PrRisorsa = \'' . $utente->Cd_PRRisorsa . '\'');
            $utente = session('utente');

            $ultima_rilevazione = DB::select('SELECT TOP 1 * from PRRLAttivita Where Id_PrBLAttivita = ' . $id . ' order by DataOra DESC');
            if (sizeof($ultima_rilevazione) > 0) {
                if ($ultima_rilevazione[0]->TipoRilevazione == 'F' and $ultima_rilevazione[0]->InizioFine == 'F') {
                    $ultima_rilevazione = DB::select('SELECT TOP 1 * from PRRLAttivita Where TipoRilevazione=\'E\' and Id_PrBLAttivita = ' . $id . ' order by DataOra DESC');
                }
            }
            $attivita_bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrBLAttivita = ' . $id);


            if (sizeof($attivita_bolle) > 0) {
                $attivita_bolla = $attivita_bolle[0];

                $OLAttivita = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $attivita_bolla->Id_PrOLAttivita);
                if (sizeof($OLAttivita) > 0) {
                    $OLAttivita = $OLAttivita[0];
                    $crea_pedana = 0;
                    if ($OLAttivita->Id_PrOLAttivita_Next != '') {
                        $OLAttivitaNext = DB::select('SELECT * from PrOLAttivita Where Id_PrOLAttivita = ' . $OLAttivita->Id_PrOLAttivita_Next);
                        if ((sizeof($OLAttivitaNext) > 0 && $OLAttivitaNext[0]->Cd_PrAttivita == 'IMBALLAGGIO')) {
                            $crea_pedana = 1;
                        }
                    } else {
                        $crea_pedana = 1;
                    }

                    $causali_fermo = DB::select('SELECT * from PRCausaleFermo');
                    //$causali_fermo = DB::select('SELECT * from PRCausaleFermo Where xCd_PrAttivita IS NULL or xCd_PrAttivita = \'' . $OLAttivita->Cd_PrAttivita . '\'');
                    $anomalie_fermo = DB::select('SELECT * from xWPAnomalia');
                    $attivita = DB::select('SELECT * from PRAttivita Where Cd_PrAttivita = \'' . $OLAttivita->Cd_PrAttivita . '\'');
                    if (sizeof($attivita) > 0) {

                        $bolla_chiusa = DB::select('SELECT * from PrVRAttivita Where UltimoVR = 1 and Id_PrBLAttivita=' . $id);
                        if (sizeof($bolla_chiusa) == 0) {

                            if ($stato_attuale == 'FE') {
                                $insert_rl2['NotePrRLAttivita'] = '';
                                $insert_rl2['Terminale'] = $utente->Cd_Terminale;
                                $insert_rl2['Cd_operatore'] = $utente->Cd_Operatore;
                                $insert_rl2['InizioFine'] = 'I';
                                $insert_rl2['TipoRilevazione'] = 'A';
                                $insert_rl2['Id_PrBlAttivita'] = $id;
                                $insert_rl2['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                                DB::table('PRRLAttivita')->insert($insert_rl2);

                                return Redirect::to('dettaglio_bolla/' . $id);

                            } /*else if ($stato_attuale == 'FA') {

                                $insert_rl2['NotePrRLAttivita'] = '';
                                $insert_rl2['Terminale'] = $utente->Cd_Terminale;
                                $insert_rl2['Cd_operatore'] = $utente->Cd_Operatore;
                                $insert_rl2['InizioFine'] = 'I';
                                $insert_rl2['TipoRilevazione'] = 'E';
                                $insert_rl2['Id_PrBlAttivita'] = $id;
                                $insert_rl2['Cd_PRRisorsa'] = $utente->Cd_PRRisorsa;
                                DB::table('PRRLAttivita')->insert($insert_rl2);
                                return Redirect::to('dettaglio_bolla/' . $id);
                            }*/
                        }
                    }

                    $causali_scarto = DB::select('SELECT * from PRCausaleScarto');


                    $attivita_bolla->CF = DB::select('
                        SELECT * from CF Where CD_CF IN(SELECT top 1 CD_CF from DORig Where Id_DORig IN (
                                SELECT Id_DoRig from PROLDoRig Where Id_PrOL IN (
                                    SELECT Id_PrOL From PROLAttivita Where Id_PrOLAttivita IN (
                                        SELECT Id_PrOLAttivita from PRBLAttivita Where Id_PrBLAttivita = ' . $id . '
                                    )
                                )
                            )
                        )
                    ');


                    $attivita_bolla->versamenti = DB::select('SELECT * from PrVRAttivitaEx Where Id_PrBLAttivita=' . $id);
                    $attivita_bolla->materiali = DB::select('SELECT *,(SELECT MG_LottoObbligatorio FROM AR WHERE AR.Cd_AR = PRBLMateriale.Cd_AR) as Obbligatorio from PRBLMateriale Where (Cd_AR not in (\'SCLAV\',\'BPN\',\'BPM\',\'BPP\') or Cd_AR is NULL)  and Id_PrBLAttivita = ' . $id);
                    if (!session()->has('\'' . $id . '\'')) {
                        session(['\'' . $id . '\'' => $attivita_bolla->materiali]);
                        session()->save();
                    }
                    $materiali = session('\'' . $attivita_bolla->Id_PrBLAttivita . '\'');
                    $LottiObbligatorio = 0;
                    foreach ($materiali as $m) {
                        if ($m->Obbligatorio == 1 && $m->NotePrBLMateriale != 'CALO PESO')
                            if ($m->Cd_ARLotto == null || $m->Cd_ARLotto == '' || $m->Cd_ARLotto == 'null')
                                $LottiObbligatorio = 1;
                    }
                    $attivita_bolla->colli = DB::select('SELECT * from xWPCollo Where Id_PrBLAttivita =  ' . $attivita_bolla->Id_PrBLAttivita . ' order by Id_xWPCollo DESC');
                    $attivita_bolla->pedane = DB::select('SELECT p.*,AR.PesoNetto as peso_pedana from xWPPD p LEFT JOIN AR ON AR.Cd_AR = p.Cd_xPD Where p.Id_PrOL = ' . $attivita_bolla->Id_PrOL . ' order by p.Id_xWPPD DESC');
                    $attivita_bolla->segnalazioni = DB::select('SELECT * from xWPSegnalazione Where Id_PrBLAttivita = ' . $id);
                    $attivita_bolla->moduli_qualita = DB::select('SELECT * from xFormQualita Where Id_PrBLAttivita = ' . $id);
                    $pallet = DB::select('SELECT * from AR Where Cd_AR LIKE \'05%\'');
                    $attivita_bolla->gruppo_lavoro = DB::select('SELECT * from xwpGruppiLavoro where Id_PrBLAttivita = ' . $id);
                    $attivita_bolla->allegati = DB::select('SELECT * from DmsDocument Where EntityTable = \'AR\' and EntityId IN (
                            SELECT Cd_AR from PrOLEx Where Id_PrOL IN (
                                SELECT Id_PrOL from PROLAttivitaEX Where Id_PrOLAttivita IN (
                                    select Id_PrOLAttivita FROM PrBLAttivitaEx Where Id_PrBLAttivita = \'' . $id . '\'
                                )
                            )
                        )
                    ');
                    $scarto_attuale = DB::SELECT('SELECT SUM(QtaProdotta) AS QtaProdotta FROM xWPCollo where Id_PrBLAttivita = \'' . $id . '\' and QtaVersata < QtaProdotta')[0]->QtaProdotta;
                    foreach ($materiali as $m) {
                        if ($m->NotePrBLMateriale == null || $m->NotePrBLMateriale == '' || $m->NotePrBLMateriale == 'null') {
                            $check_gruppo = DB::select('SELECT isnull(Cd_ARGruppo1,\'\') as Cd_ARGruppo1 FROM AR where Cd_AR = \'' . $m->Cd_AR . '\' ')[0]->Cd_ARGruppo1;
                            if ($check_gruppo != '010' && $check_gruppo != '011') {
                                if ($m->Consumo > 0)
                                    $scarto_attuale = $m->Consumo - $scarto_attuale;
                                else
                                    $scarto_attuale = $m->Consumo + $scarto_attuale;
                            }
                        }
                    }
                    if ($scarto_attuale == NULL)
                        $scarto_attuale = 0;

                    $attivita_bolla->scarto = db::select('SELECT
                        COALESCE(
                        (
                            (SELECT SUM(PRVRMateriale.Consumo) FROM PRVRMateriale WHERE PRVRMateriale.Id_PRVRAttivita in (SELECT Id_PRvrattivita from prvrattivita where id_prblattivita = ' . $id . ') and prvrmateriale.Consumo > 0 and ((SELECT Cd_ARGruppo1 from ar where ar.cd_AR = prvrmateriale.cd_ar) != \'010\') and ((SELECT Cd_ARGruppo1 from ar where ar.cd_AR = prvrmateriale.cd_ar) != \'011\') and PRVRMateriale.Tipo != 0 and  PRVRMateriale.Tipo != 3 AND PRVRMateriale.NotePrVRMateriale not like \'%CALO%PESO%\')
                        -
                        (SELECT SUM(PRVRMateriale.Consumo) FROM PRVRMateriale WHERE PRVRMateriale.Id_PRVRAttivita in (SELECT Id_PRvrattivita from prvrattivita where id_prblattivita = ' . $id . ') and prvrmateriale.Consumo < 0 and ((SELECT Cd_ARGruppo1 from ar where ar.cd_AR = prvrmateriale.cd_ar) != \'010\') and ((SELECT Cd_ARGruppo1 from ar where ar.cd_AR = prvrmateriale.cd_ar) != \'011\') and PRVRMateriale.Tipo != 0 and  PRVRMateriale.Tipo != 3 AND PRVRMateriale.NotePrVRMateriale not like \'%CALO%PESO%\')
                        -SUM(PRVRAttivita.Quantita))
                        ,0) + ' . $scarto_attuale . ' as Scarto
                        FROM PRVRAttivita
                        WHERE PRVRAttivita.Id_PRBLAttivita = ' . $id . ' AND PRVRAttivita.Quantita > 0 AND PRVRAttivita.Attrezzaggio = 0
                        ');

                    $bolle = DB::select('SELECT * from PrBLEx Where Id_PrBL = ' . $attivita_bolla->Id_PrBL);
                    if (sizeof($bolle) > 0) {
                        $bolla = $bolle[0];
                        $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita_bolla->Id_PrOL);
                        if (sizeof($ordini) > 0) {
                            $ordine = $ordini[0];
                            $nr_dotes = DB::SELECT('SELECT NumeroDoc from dotes where Id_DOTes in (SELECT Id_DOTes from dorig where Id_DORig in (SELECT Id_DORig FROM PROLDorig where Id_PrOL = \'' . $attivita_bolla->Id_PrOL . '\'))');
                            if (sizeof($nr_dotes) > 0)
                                $nr_dotes = $nr_dotes[0]->NumeroDoc;
                            else
                                $nr_dotes = 0;
                            $articoli = DB::select('SELECT * from AR where CD_AR = \'' . $ordine->Cd_AR . '\'');
                            if (sizeof($articoli) > 0) {
                                $articolo = $articoli[0];
                                $articolo->UM = DB::select('SELECT * from ARARMisura Where Cd_AR = \'' . $articolo->Cd_AR . '\'');
                                $stampe_libere = DB::select('SELECT * from xWPReport where Libero = 1 and (Cd_AR = \'' . $articolo->Cd_AR . '\' or Cd_AR2 = \'' . $articolo->Cd_AR . '\' or Cd_AR3 = \'' . $articolo->Cd_AR . '\' or Cd_AR4= \'' . $articolo->Cd_AR . '\' or Cd_AR5 = \'' . $articolo->Cd_AR . '\')');


                                $mandrini = DB::select('SELECT * from AR ');


                                $contatori = DB::select('SELECT * from xContatore where Id_PrBlAttivita = ' . $id . ' order by Ts DESC');


                                $colli_da_versare = DB::select('SELECT * from xWPCollo Where QtaVersata < QtaProdotta and Id_PrBLAttivita =  ' . $id . ' order by Id_xWPCollo DESC');


                                $operatori = DB::select('SELECT * from Operatore Where CD_Operatore IN (SELECT CD_Operatore from PRRisorsa_Operatore Where Cd_PRRIsorsa = \'' . $utente->Cd_PRRisorsa . '\')');

                                $articoli_mat = DB::SELECT('SELECT Cd_AR,Descrizione from AR WHERE Obsoleto = 0 and Fittizio = 0');

                                $cp = DB::SELECT('SELECT Cd_AR,Descrizione from AR WHERE Descrizione like \'%Calo%peso%\'');

                                return View::make('backend.dettaglio_bolla', compact('articoli_mat', 'cp', 'mese_lettera', 'materiali', 'attivita_bolla', 'LottiObbligatorio', 'bolla', 'nr_dotes', 'utente', 'risorse', 'ultima_rilevazione', 'stato_attuale', 'causali_scarto', 'causali_fermo', 'anomalie_fermo', 'operatori', 'articolo', 'ordine', 'attivita', 'mandrini', 'crea_pedana', 'OLAttivita', 'pallet', 'stampe_libere', 'contatori', 'colli_da_versare'));

                            }
                        }
                    }
                }
            }
        } else return Redirect::to('login');

    }


    public
    function stampa_libera($id, $nome_report)
    {


        $report = DB::select('SELECT Ud_Report,NoteReport from ReportAll where Tipo = \'ARCA_INDUSTRY\' and Nome = \'' . $nome_report . '\' Order by TimeIns desc');

        if (sizeof($report) > 0) {

            if (file_exists('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf')) unlink('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf');

            if (!file_exists('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf')) {

                $insert_stampa['Modulo'] = $report[0]->Ud_Report;
                $insert_stampa['Collo'] = '';
                $insert_stampa['Pedana'] = '';
                $insert_stampa['Qualita'] = '';
                $insert_stampa['stampato'] = 0;
                $insert_stampa['nome_file'] = str_replace(' ', '_', strtolower($nome_report)) . '.pdf';
                DB::table('xStampeIndustry')->insert($insert_stampa);
                $kill_process = DB::Select('SELECT top 1 kill_process from xArcaIndustryConf')[0]->kill_process;
                if ($kill_process == 1) {
                    exec('taskkill /f /im splwow64.exe');
                    exec('taskkill /f /im arcasql.exe');
                }

                exec('"C:\Program Files (x86)\Artel\ArcaEvolution\ArcaSql.exe" *Server=' . env('DB_HOST', 'forge') . '  *Ditta=' . env('DB_DATABASE', 'forge') . ' *LoginPrompt=3 *UserName=' . env('DB_USERNAME', 'forge') . ' *Password=' . env('DB_PASSWORD', 'forge') . ' *execute=C:\Program Files (x86)\Artel\ArcaEvolution\ArcaIndustry_all_packaging.prg');
                while (!file_exists('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf')) sleep(1);


            }


            if ($report[0]->NoteReport != '') {
                list($base, $altezza) = explode(';', $report[0]->NoteReport);
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$base, $altezza], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization
                $pagecount = $mpdf->setSourceFile('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf');
                $tplId = $mpdf->importPage($pagecount);
                $mpdf->useTemplate($tplId);
                $mpdf->Output('upload/' . str_replace(' ', '_', strtolower($nome_report)) . '.pdf', 'F');
            }

            return Redirect::to('dettaglio_bolla/' . $id . '?stampa=' . str_replace(' ', '_', strtolower($nome_report)));


        } else {
            return Redirect::to('dettaglio_bolla/' . $id);
        }

    }

    public
    function odl()
    {
        return View::make('backend.odl');
    }

    public
    function dettaglio_odl($id_attivita)
    {

        $attivita = DB::select('SELECT * from PrOLAttivitaEx Where Id_PrOLAttivita = ' . $id_attivita);
        if (sizeof($attivita) > 0) {
            $attivita = $attivita[0];
            $ordini = DB::select('SELECT * from PrOLEx Where Id_PrOL = ' . $attivita->Id_PrOL);
            if (sizeof($ordini) > 0) {
                $ordine = $ordini[0];
                $bolle = DB::select('SELECT * from PrBLAttivitaEx Where Id_PrOL = ' . $attivita->Id_PrOL);
                return View::make('backend.dettaglio_odl', compact('ordine', 'attivita', 'bolle', 'id_attivita'));

            }
        }
    }

    public
    function carico_merce()
    {
        return View::make('backend.carico_merce');
    }

    public
    function trasferimento_merce()
    {
        return View::make('backend.trasferimento_merce');
    }

    public
    function calendario()
    {
        return View::make('backend.calendario');
    }

    public
    function logistic_offline()
    {
        return View::make('backend.logistic_offline');
    }

    public
    function logistic_schermata_carico()
    {
        return View::make('backend.logistic_schermata_carico');
    }

    public
    function logistic_crea_documento()
    {
        return View::make('backend.logistic_crea_documento');
    }

    public
    function logistic_evadi_documento()
    {
        return View::make('backend.logistic_evadi_documento');
    }

    public
    function logout(Request $request)
    {

        session()->flush();

        return Redirect::to('login');

    }

    public
    static function ripulisci_collo($id_collo)
    {

        $colli = DB::select('SELECT * from xWPCollo Where Id_xWPCollo =' . $id_collo);

        if (sizeof($colli) > 0) {
            $collo = $colli[0];

            $stampe = DB::select('SELECT * from xStampeIndustry Where Collo = \'' . $collo->Nr_Collo . '\'');
            foreach ($stampe as $s) {
                if (file_exists('upload/' . $s->nome_file)) {
                    unlink('upload/' . $s->nome_file);
                }
            }
        }
    }


    public
    function etichette(Request $request)
    {

        $dati = $request->all();

        if (isset($dati['aggiungi'])) {
            unset($dati['aggiungi']);
            DB::table('xSPReport')->insert($dati);
            return Redirect::to('etichette');
        }

        if (isset($dati['importa_etichetta'])) {
            unset($dati['importa_etichetta']);

            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

                $json = file_get_contents($_FILES['file']['tmp_name']);

                $json = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($json));

                $report = json_decode($json);
                $report = (array)$report;

                unset($report['Id_xSPReport']);
                unset($report['Ts']);
                unset($report['TimeUpd']);
                unset($report['TimeIns']);

                DB::table('xSPReport')->insert($report);

                return Redirect::to('etichette');

            }

        }

        if (isset($dati['esporta_etichette'])) {
            unset($dati['esporta_etichette']);

            $report = DB::select('SELECT * from xSPReport where Id_xSPReport = ' . $dati['Id_xSPReport']);
            if (sizeof($report) > 0) {
                $report = $report[0];
                $data = json_encode($report, JSON_INVALID_UTF8_IGNORE);


                $file = $dati['codice'] . '.json';
                $fp = fopen($file, "w") or die("Couldn't open $file for writing!");
                fwrite($fp, $data) or die("Couldn't write values to file!");
                fclose($fp);

                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");

                readfile($file);

                exit;

            }
        }


        if (isset($dati['duplica'])) {
            unset($dati['duplica']);


            $report = DB::select('SELECT * from xSPReport where Id_xSPReport= ' . $dati['Id_xSPReport']);
            if (sizeof($report) > 0) {
                $report = $report[0];
                $report = (array)$report;

                unset($report['Id_xSPReport']);
                unset($report['Ts']);
                unset($report['TimeUpd']);
                unset($report['TimeIns']);

                DB::table('xSPReport')->insert($report);

                return Redirect::to('etichette');
            }


        }

        if (isset($dati['modifica'])) {
            unset($dati['modifica']);
            $id = $dati['Id_xSPReport'];
            unset($dati['Id_xSPReport']);
            DB::table('xSPReport')->where('Id_xSPReport', $id)->update($dati);
            return Redirect::to('etichette');
        }
        if (isset($dati['anno'])) {
            DB::table('xRaccolta')->update($dati);
            return Redirect::to('etichette');
        }
        if (isset($dati['origine'])) {
            DB::table('xRaccolta')->update($dati);
            return Redirect::to('etichette');
        }

        if (isset($dati['elimina'])) {
            unset($dati['elimina']);
            DB::table('xSPReport')->where('Id_xSPReport', $dati['Id_xSPReport'])->delete();
            return Redirect::to('etichette');
        }

        $etichette = DB::select('SELECT * from xSPReport order by codice desc');
        $clienti = DB::select('SELECT Cd_CF,Descrizione from CF where Cliente = 1');
        $articoli = DB::select('SELECT * from AR');
        $fasi = DB::select('SELECT Cd_PrAttivita from PRAttivita');
        $raccolto = DB::select('SELECT * from xRaccolta')[0];
        return View::make('backend.etichette', compact('etichette', 'clienti', 'articoli', 'fasi', 'raccolto'));
    }


    public
    function editor_etichetta($id, Request $request)
    {

        $etichette = DB::select('SELECT * from xSPReport where Id_xSPReport = ' . $id);
        if (sizeof($etichette) > 0) {
            $etichetta = $etichette[0];
            $clienti = DB::SELECT('SELECT * FROM CF');
            return View::make('backend.editor_etichetta', compact('etichetta', 'clienti'));
        }
    }

    public
    function test_etichetta($param1, $tipologia, $codice_stampa, Request $request)
    {

        $id = 0;

        $utente = session('utente');

        $colli = DB::select('SELECT * from xWPCollo Where Nr_Collo = \'' . $param1 . '\'');
        if (sizeof($colli) > 0) {
            $id = $colli[0]->Id_PRBLAttivita;
        }

        if ($id == 0) {
            $colli = DB::select('SELECT PRBLAttivita.Id_PrBLAttivita,xWPPD.Id_xWPPD from xWPPD
                JOIN PRBLAttivita ON PRBLAttivita.Id_PrOLAttivita = xWPPD.IdCodiceAttivita
                Where Nr_Pedana = \'' . $param1 . '\' and IdCodiceAttivita IS NOT NULL');

            if (sizeof($colli) > 0) {
                $id = $colli[0]->Id_PrBLAttivita;
                $param1 = $colli[0]->Id_xWPPD;
            }
        }


        $nome_file = StampaController::motore_industry_test($utente->Cd_PRRisorsa, $id, $param1, $tipologia, $codice_stampa);

        return Redirect::to('upload/test/' . $nome_file . '.pdf');


    }


    public
    function salva_etichetta($id, Request $request)
    {

        $dati = $request->all();

        if (is_numeric($id)) {

            DB::table('xSPReport')->where('Id_xSPReport', $id)->update($dati);
            echo 'campi salvati';
        }
    }


    public
    static function ripulisci_pedana($id_pedana)
    {

        $pedane = DB::select('SELECT * from xWPPD Where Id_xWPPD =' . $id_pedana);

        if (sizeof($pedane) > 0) {
            $pedana = $pedane[0];

            $stampe = DB::select('SELECT * from xStampeIndustry Where Pedana = \'' . $pedana->Nr_Pedana . '\'');
            foreach ($stampe as $s) {
                if (file_exists('upload/' . $s->nome_file)) {
                    unlink('upload/' . $s->nome_file);
                }
            }
        }

    }

    public static function tracciabilita(Request $request)
    {
        if (!session()->has('utente')) {
            return Redirect::to('login');
        }

        if (session()->has('utente')) {
            $utente = session('utente');
            $risorsa = session('risorsa');
            $dati = $request->all();
            return View::make('backend.tracciabilita');
        }
    }

    public static function view_ol(Request $request, $id_prol)
    {
        if (!session()->has('utente')) {
            return Redirect::to('login');
        }

        if (session()->has('utente')) {
            $utente = session('utente');
            $risorsa = session('risorsa');
            $dati = $request->all();
            $numero_ol = DB::SELECT('SELECT * FROM PROL where id_prol = ' . $id_prol);
            if (sizeof($numero_ol) > 0) {
                $numero_ol = $numero_ol[0]->Numero;
                $consumi = DB::select('SELECT
                                            Id_PrVRAttivita,
                                            (SELECT Cd_PrAttivita FROM PROLAttivita WHERE Id_PrOLAttivita = PRVRMateriale.Id_PrOLAttivita) as Attivita,Tipo,Cd_AR,Cd_ARLotto,Cd_ARMisura,ABS(Consumo) as Consumo,NotePRVRMateriale
                                            FROM PRVRMateriale
                                            WHERE Id_PRVRAttivita IN (SELECT Id_PRVRAttivita FROM PRVRAttivita
                                            where Id_PRBLAttivita in (select Id_PrBLAttivita from PRBLAttivita
                                            where Id_PrOLAttivita in (SELECT Id_PrOLAttivita FROM PROLAttivita WHERE Id_PrOL = ' . $id_prol . ')
                                                )
                                            )');
                if (sizeof($consumi) > 0) {
                    return View::make('backend.view_ol', compact('numero_ol', 'consumi'));
                }
            }
        }
    }

    function convertPropNamesLower($obj)
    {
        return (object)array_change_key_case((array)$obj, CASE_LOWER);
    }

    function qualita()
    {
        return View::make('qualita.selezionatrice_controllo_prodotto');
    }

}
