<?php

use App\Http\Controllers\moduli\CalibraturaController;
use App\Http\Controllers\arca\CFController;
use App\Http\Controllers\moduli\ModuloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('', 'HomeController@index');
Route::any('login', 'HomeController@login');
Route::any('statistiche', 'HomeController@statistiche');
Route::any('operatori', 'HomeController@operatori');
Route::any('etichette', 'HomeController@etichette');
Route::any('editor_etichetta/{id}', 'HomeController@editor_etichetta');
Route::any('salva_etichetta/{id}', 'HomeController@salva_etichetta');
Route::any('test_etichetta/{param1}/{tipologia}/{codice}', 'HomeController@test_etichetta');
Route::any('imballaggio', 'HomeController@imballaggio');
Route::any('imballaggio_bolle_da_chiudere', 'HomeController@imballaggio_bolle_da_chiudere');
Route::any('gruppi_risorse', 'HomeController@gruppi_risorse');
Route::any('risorse/{Cd_PrRisorsa}', 'HomeController@risorse');
Route::any('lista_attivita', 'HomeController@lista_attivita');
Route::any('valeria/tracciabilita', 'HomeController@login2');
Route::any('lista_attivita/{cd_attivita}', 'HomeController@lista_attivita');
Route::any('lista_bolle_attive/{cd_attivita}', 'HomeController@lista_bolle_attive');
Route::any('dettaglio_bolla/{id}', 'HomeController@dettaglio_bolla')->name('dettaglio_bolla');
Route::any('odl', 'HomeController@odl');
Route::any('dettaglio_odl', 'HomeController@dettaglio_odl');
Route::any('dettaglio_odl/{id_attivita}', 'HomeController@dettaglio_odl');
Route::any('carico_merce', 'HomeController@carico_merce');
Route::any('trasferimento_merce', 'HomeController@trasferimento_merce');
Route::any('calendario', 'HomeController@calendario');
Route::any('logistic_offline', 'HomeController@logistic_offline');
Route::any('logistic_schermata_carico', 'HomeController@logistic_schermata_carico');
Route::any('logistic_crea_documento', 'HomeController@logistic_crea_documento');
Route::any('logistic_evadi_documento', 'HomeController@logistic_evadi_documento');
Route::any('stampa_libera/{id}/{codice_stampa}', 'HomeController@stampa_libera');

Route::any('ajax/lista_versamenti/{Id_PrBLAttivita}', 'AjaxController@lista_versamenti');
Route::any('ajax/check_bolla/{Id_PrBLAttivita}', 'AjaxController@check_bolla');
Route::any('ajax/modifica_pedana_imballaggio/{Id_xWPPD}', 'AjaxController@modifica_pedana_imballaggio');
Route::any('ajax/dettagli_versdamento/{Id_PrVRAttivita}', 'AjaxController@dettagli_versamento');
Route::any('ajax/controlla_lotto/{lotto}', 'AjaxController@controlla_lotto');
Route::any('ajax/controlla_lotto_mod/{lotto}/{Id_PrBLMateriale}', 'AjaxController@controlla_lotto_mod');
Route::any('ajax/visualizza_file/{id_dms}', 'AjaxController@visualizza_file');
Route::any('ajax/cambia_armisura/{Id_PrBLAttivita}/{cd_armisura}', 'AjaxController@cambia_armisura');
Route::any('ajax/get_bolla/{numero_bolla}', 'AjaxController@get_bolla');
Route::any('ajax/set_stampato/{nome_file}', 'AjaxController@set_stampato');
Route::any('ajax/load_colli/{attivita_bolla}/{cd_ar}', 'AjaxController@load_colli');
Route::any('ajax/load_tutti_colli/{attivita_bolla}/{cd_ar}', 'AjaxController@load_tutti_colli');
Route::any('ajax/load_tracciabilita/{id_prol}', 'AjaxController@load_tracciabilita');
Route::any('ajax/load_tracciabilita1/{id_prol}/{ProlAttivita}', 'AjaxController@load_tracciabilita1');
Route::any('ajax/load_imballo/{id_prol}/{ProlAttivita}', 'AjaxController@load_imballo');
Route::any('ajax/load_imballo2/{id_prol}/{ProlAttivita}/{Nr_Pedana}', 'AjaxController@load_imballo2');
Route::any('ajax/load_tracciabilita2/{id_prol}/{ProlAttivita}/{Nr_Collo}', 'AjaxController@load_tracciabilita2');
Route::any('ajax/load_tracciabilita_dietro/{id_prol}/{ProlAttivita}/{Rif_Nr_Collo}/{Rif_Nr_Collo2}', 'AjaxController@load_tracciabilita_dietro');
Route::any('ajax/load_cerca_collo/{id_prol}/{ProlAttivita}/{Nr_Collo}', 'AjaxController@load_cerca_collo');
Route::any('ajax/get_etichetta/{id}', 'AjaxController@get_etichetta');

Route::any('ajax/crea_collo/{Id_PrBLAttivita}/{qta}/{esemplari}/{cd_armisura}/{nr_pedana}/{rif1}/{rif2}', 'AjaxController@crea_collo');
Route::any('ajax/chiudi_collo/{Id_PrBLAttivita}/{qta}/{esemplari}/{cd_armisura}/{nr_pedana}/{rif1}/{rif2}', 'AjaxController@chiudi_collo');
Route::any('ajax/cerca_pedana/{Nr_Pedana}', 'AjaxController@cerca_pedana');

Route::any('stampa/qualita/{Id_xFormQualita}', 'StampaController@qualita');
Route::any('qualita', 'HomeController@qualita');
Route::get('qualita/{Id_Bolla}/{Id_qualita}', 'HomeController@QualitaBolla')->name('foglio_qualita');;

Route::group(['prefix' => 'qualita'], function () {
    Route::get('/confezionamento', 'QualitaController@confezionamento')->name('confezionamento');
    Route::get('/confezionamento2', 'QualitaController@confezionamento2')->name('confezionamento2');
    Route::get('/selezionatrice_accensione', 'QualitaController@selezionatriceAccensione')->name('selezionatrice_accensione');
    Route::get('/selezionatrice_controllo', 'QualitaController@selezionatriceControlloProdotto')->name('selezionatrice_controllo');
    Route::get('/tostatura', 'QualitaController@tostatura')->name('tostatura');
    Route::get('/tostatura2', 'QualitaController@tostatura2')->name('tostatura2');
    Route::get('/tritatura', 'QualitaController@tritatura')->name('tritatura');
});
Route::post('/generate-and-save-pdf', 'QualitaController@generateAndSavePdf');

Route::any('logout', 'HomeController@logout');


Route::group(['prefix' => 'moduli'], function() {
    Route::get('{id}/dms', [ModuloController::class, 'getDMS']);
    Route::get('show/{id}', [ModuloController::class, 'showDMS']);
    Route::group(['prefix' => 'calibratura'], function() {
        Route::get('/', [CalibraturaController::class, 'showAll']);
        Route::get("/create/{id}", [CalibraturaController::class, 'createView'])->name("createCalibratura");
        Route::post("/create/{id}", [CalibraturaController::class, 'create'])->name("createPostCalibratura");
     });
});

Route::group(['prefix' => 'CF'], function() {
    Route::get("/", [CFController::class, 'findAll']);
    
});