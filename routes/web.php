<?php

use App\Http\Controllers\arca\XWPColloController;
use App\Http\Controllers\moduli\FarinaController;
use App\Http\Controllers\arca\CFController;
use App\Http\Controllers\moduli\EfficienzaController;
use App\Http\Controllers\moduli\GranellaController;
use App\Http\Controllers\moduli\MetalDetector;
use App\Http\Controllers\moduli\MetalDetectorController;
use App\Http\Controllers\moduli\ModuloController;
use App\Http\Controllers\moduli\RaffinatriceController;
use App\Http\Controllers\moduli\SortexController;
use App\Http\Controllers\moduli\TostaturaController;
use App\Http\Controllers\moduli\ConfezionamentoController;
use App\Http\Controllers\moduli\XRayController;
use Illuminate\Support\Facades\Route;

Route::any('api/invia_mail/{id-prrlattivita}', 'AjaxController@invia_mail');
Route::any('api/invia_mail_2/{id-prrlattivita}', 'AjaxController@invia_mail_2');

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
Route::any('tracciabilita', 'HomeController@tracciabilita');
Route::any('view_ol/{id_prol}', 'HomeController@view_ol');

Route::any('stampa_pers/{stampante}/{pdf}', 'AjaxController@stampa_pers');

Route::any('ajax/lista_versamenti/{Id_PrBLAttivita}', 'AjaxController@lista_versamenti');
Route::any('ajax/check_bolla/{Id_PrBLAttivita}', 'AjaxController@check_bolla');
Route::any('ajax/modifica_pedana_imballaggio/{Id_xWPPD}', 'AjaxController@modifica_pedana_imballaggio');
Route::any('ajax/dettagli_versdamento/{Id_PrVRAttivita}', 'AjaxController@dettagli_versamento');
Route::any('ajax/controlla_lotto/{lotto}', 'AjaxController@controlla_lotto');
Route::any('ajax/controlla_lotto_scar/{lotto}', 'AjaxController@controlla_lotto_scar');
Route::any('ajax/controlla_lotto_mod/{lotto}/{Id_PrBLMateriale}', 'AjaxController@controlla_lotto_mod');
Route::any('ajax/visualizza_file/{id_dms}', 'AjaxController@visualizza_file');
Route::any('ajax/cambia_armisura/{Id_PrBLAttivita}/{cd_armisura}', 'AjaxController@cambia_armisura');
Route::any('ajax/get_bolla/{numero_bolla}', 'AjaxController@get_bolla');
Route::any('ajax/set_stampato/{nome_file}', 'AjaxController@set_stampato');
Route::any('ajax/load_colli/{attivita_bolla}/{cd_ar}', 'AjaxController@load_colli');
Route::any('ajax/load_tutti_colli/{attivita_bolla}/{cd_ar}', 'AjaxController@load_tutti_colli');
Route::any('ajax/get_etichetta/{id}', 'AjaxController@get_etichetta');
Route::any('ajax/load_tracciabilita/{lotto}', 'AjaxController@load_tracciabilita');

Route::any('ajax/crea_collo/{Id_PrBLAttivita}/{qta}/{esemplari}/{cd_armisura}/{nr_pedana}/{rif1}/{rif2}', 'AjaxController@crea_collo');
Route::any('ajax/chiudi_collo/{Id_PrBLAttivita}/{qta}/{esemplari}/{cd_armisura}/{nr_pedana}/{rif1}/{rif2}', 'AjaxController@chiudi_collo');
Route::any('ajax/cerca_pedana/{Nr_Pedana}', 'AjaxController@cerca_pedana');
Route::any('ajax/scarica_excel_lotto/{lotto}', 'AjaxController@scarica_excel_lotto');
Route::any('ajax/cerca_semilavorato/{lotto}/{cd_ar}', 'AjaxController@cerca_semilavorato');
Route::any('ajax/cerca_script_semilavorato/{lotto}/{cd_ar}', 'AjaxController@cerca_script_semilavorato');

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

Route::group(['prefix' => 'moduli'], function () {
    Route::get('{id}/dms', [ModuloController::class, 'getDMS']);
    Route::get('show/{id}', [ModuloController::class, 'showDMS']);
    Route::get('edit/{activity}/{id}', [ModuloController::class, 'editDMS']);
    Route::get('delete/{activity}/{id}', [ModuloController::class, 'deleteDMS']);

    Route::group(['prefix' => 'granella'], function () {
        Route::get('/', [GranellaController::class, 'showAll']);
        Route::get("/create/{id}", [GranellaController::class, 'createView'])->name("createGranella");
        Route::post("/create/{id}", [GranellaController::class, 'create'])->name("createPostGranella");
        Route::get("/edit/{activity}/{id}", [GranellaController::class, 'editView'])->name("editGranella");
        Route::post("/edit/{idActivity}/{id}", [GranellaController::class, 'edit'])->name("editPostGranella");
    });
    Route::group(['prefix' => 'efficienza'], function () {
        Route::get('/', [EfficienzaController::class, 'showAll']);
        Route::get("/create/{id}", [EfficienzaController::class, 'createView'])->name("createEfficienza");
        Route::post("/create/{id}", [EfficienzaController::class, 'create'])->name("createPostEfficienza");
        Route::get("/edit/{activity}/{id}", [EfficienzaController::class, 'editView'])->name("editEfficienza");
        Route::post("/edit/{idActivity}/{id}", [EfficienzaController::class, 'edit'])->name("editPostEfficienza");
    });
    Route::group(['prefix' => 'tostatura'], function () {
        Route::get('/', [TostaturaController::class, 'showAll']);
        Route::get("/create/{id}", [TostaturaController::class, 'createView'])->name("createTostatura");
        Route::post("/create/{id}", [TostaturaController::class, 'create'])->name("createPostTostatura");
        Route::get("/edit/{activity}/{id}", [TostaturaController::class, 'editView'])->name("editTostatura");
        Route::post("/edit/{idActivity}/{id}", [TostaturaController::class, 'edit'])->name("editPostTostatura");
    });
    Route::group(['prefix' => 'raffinatrice'], function () {
        Route::get('/', [RaffinatriceController::class, 'showAll']);
        Route::get("/create/{id}", [RaffinatriceController::class, 'createView'])->name("createRaffinatrice");
        Route::post("/create/{id}", [RaffinatriceController::class, 'create'])->name("createPostRaffinatrice");
        Route::get("/edit/{activity}/{id}", [RaffinatriceController::class, 'editView'])->name("editRaffinatrice");
        Route::post("/edit/{idActivity}/{id}", [RaffinatriceController::class, 'edit'])->name("editPostRaffinatrice");
    });
    Route::group(['prefix' => 'sortex'], function () {
        Route::get('/', [SortexController::class, 'showAll']);
        Route::get("/create/{id}", [SortexController::class, 'createView'])->name("createSortex");
        Route::post("/create/{id}", [SortexController::class, 'create'])->name("createPostSortex");
        Route::get("/edit/{activity}/{id}", [SortexController::class, 'editView'])->name("editSortex");
        Route::post("/edit/{idActivity}/{id}", [SortexController::class, 'edit'])->name("editPostSortex");
    });
    Route::group(['prefix' => 'confezionamento'], function () {
        Route::get('/', [ConfezionamentoController::class, 'showAll']);
        Route::get("/create/{id}", [ConfezionamentoController::class, 'createView'])->name("createConfezionamento");
        Route::post("/create/{id}", [ConfezionamentoController::class, 'create'])->name("createPostConfezionamento");
        Route::get("/edit/{activity}/{id}", [ConfezionamentoController::class, 'editView'])->name("editConfezionamento");
        Route::post("/edit/{idActivity}/{id}", [ConfezionamentoController::class, 'edit'])->name("editPostConfezionamento");
    });
    Route::group(['prefix' => 'farina'], function () {
        Route::get('/', [FarinaController::class, 'showAll']);
        Route::get("/create/{id}", [FarinaController::class, 'createView'])->name("createFarina");
        Route::post("/create/{id}", [FarinaController::class, 'create'])->name("createPostFarina");
        Route::get("/edit/{activity}/{id}", [FarinaController::class, 'editView'])->name("editFarina");
        Route::post("/edit/{idActivity}/{id}", [FarinaController::class, 'edit'])->name("editPostFarina");
    });
    Route::group(['prefix' => 'xray'], function () {
        Route::group(['prefix' => 'BR6000'], function () {
            Route::get('/', [XRayController::class, 'showAll']);
            Route::get("/create/{id}", [XRayController::class, 'createViewBR6000'])->name("createXrayBR6000");
            Route::post("/create/{id}", [XRayController::class, 'createPostXBR6000'])->name("createPostXBR6000");
            Route::get("/edit/{activity}/{id}", [XRayController::class, 'editviewBR6000'])->name("editBR6000");
            Route::post("/edit/{idActivity}/{id}", [XRayController::class, 'editBR6000'])->name("editPostBR6000");
        });
        Route::group(['prefix' => '400N'], function () {
            Route::get('/', [XRayController::class, 'showAll']);
            Route::get("/create/{id}", [XRayController::class, 'createView400N'])->name("createXray400N");
            Route::post("/create/{id}", [XRayController::class, 'createPost400N'])->name("createPost400N");
            Route::get("/edit/{activity}/{id}", [XRayController::class, 'editView400N'])->name("edit400N");
            Route::post("/edit/{idActivity}/{id}", [XRayController::class, 'edit400N'])->name("editPost400N");
        });
    });
    Route::group(['prefix' => 'metal-detector'], function () {
        Route::group(['prefix' => 'MBR1200'], function () {
            Route::get('/', [MetalDetectorController::class, 'showAll']);
            Route::get("/create/{id}", [MetalDetectorController::class, 'createViewMBR1200'])->name("createMDMBR1200");
            Route::post("/create/{id}", [MetalDetectorController::class, 'createPostMBR1200'])->name("createPostMDMBR1200");
            Route::get("/edit/{activity}/{id}", [MetalDetectorController::class, 'editViewMBR1200'])->name("editMBR1200");
            Route::post("/edit/{idActivity}/{id}", [MetalDetectorController::class, 'editMBR1200'])->name("editPostMBR1200");
        });
        Route::group(['prefix' => 'PMO'], function () {
            Route::get('/', [MetalDetectorController::class, 'showAll']);
            Route::get("/create/{id}", [MetalDetectorController::class, 'createViewPMO'])->name("createMDPMO");
            Route::post("/create/{id}", [MetalDetectorController::class, 'createPostPMO'])->name("createMDPMO");
            Route::get("/edit/{activity}/{id}", [MetalDetectorController::class, 'editViewPMO'])->name("editMDPMO");
            Route::post("/edit/{idActivity}/{id}", [MetalDetectorController::class, 'editPMO'])->name("editPostMDPMO");
        });
    });
});

Route::group(['prefix' => 'CF'], function () {
    Route::get("/", [CFController::class, 'findAll']);
});

Route::group(['prefix' => 'XWPCollo'], function () {
    Route::get("/{id}", [XWPColloController::class, 'xWPColloByAttivita']);
});
