

<?php
foreach ($attivita_bolla->colli as $c){
    if ($c->NC == 0) {
        $background = ($c->QtaVersata == $c->QtaProdotta) ? 'green' : 'yellow';
    } else {
        $background = ($c->Id_PrVrAttivita != null && $c->NC == 1) ? 'cyan' : 'red';
    }
    ?>


<div class="col-lg-2 col-6 collo" style="cursor:pointer;color:white!important;"
     onclick="azioni_collo(<?php echo $c->Id_xWPCollo ?>)" id="collo_<?php echo $c->Id_xWPCollo ?>">
    <!-- small box -->
    <div class="small-box bg-<?php echo $background ?>">
        <div class="inner">
            <b style="font-size:19px;">Collo <?php echo ($c->NC == 1) ? 'NC' : '' ?>
                : <?php echo $c->Descrizione ?></b><br>
            <small style="font-size:18px;">
                    <?php if ($c->Nr_Pedana != ''){ ?>Pedana: <?php echo $c->Nr_Pedana ?><br><?php } ?>
                                                                                                 <?php if ($c->NC == 1){ ?>
                Causale: <?php echo $c->Cd_PRCausaleScarto ?><br><?php } ?>
                                                                     <?php if ($c->Id_PrVrAttivita != ''){ ?>
                Ver: <?php echo $c->Id_PrVrAttivita ?><br><?php } ?>
                Qta: <?php echo number_format($c->QtaProdotta, 2, '.', '') ?><?php echo $c->Cd_ARMisura ?>
            </small>
        </div>
    </div>
</div>


<form method="post">
    <div class="row form_colli" id="form_collo_<?php echo $c->Id_xWPCollo ?>"
         style="z-index:1000;display:none;border:1px solid #dee2e6;padding:10px;background:#f4f6f9;">

        <div class="col-md-12">
            <h4 style="float:left;">Azioni Collo <?php echo $c->Nr_Collo ?></h4>

            <button style="float:right;font-size: 40px;margin-top: -5px;" type="button" class="close"
                    data-dismiss="modal" aria-label="Close" onclick="$('.form_colli').hide();">
                <span aria-hidden="true">×</span></button>
        </div>

        <div class="col-md-6">
            <label>Qta Prodotta (<?php echo $c->Cd_ARMisura ?>)</label>
            <input class="form-control keyboard_num" type="text" step="0.1" name="QtaProdotta"
                   value="<?php echo number_format($c->QtaProdotta,1,'.','') ?>">
                <?php if ($c->QtaProdottaUmFase != $c->QtaProdotta){ ?>
            <small>Corrisponde
                a <?php echo number_format($c->QtaProdottaUmFase, 3, '.', '') ?><?php echo $attivita_bolla->Cd_ARMisura ?></small>
            <?php } ?>
        </div>

        <div class="col-md-6">
            <label>Copie</label>
            <input class="form-control keyboard_num" type="text" step="1" min="1" max="10" name="Copie"
                   value="<?php echo $c->Copie ?>">
        </div>

        <div class="col-md-12">
            <label>Nr_Pedana</label>
            <select name="Nr_Pedana_Collo" class="form-control">
                <option value="">Nessuna Pedana</option>
                    <?php foreach ($attivita_bolla->pedane as $p) { ?>
                <option
                        value="<?php echo $p->Nr_Pedana ?>" <?php echo ($p->Nr_Pedana == $c->Nr_Pedana) ? 'selected' : '' ?>><?php echo $p->Nr_Pedana ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-12">
            <label>xLotto</label>
            <input class="form-control keyboard" type="text" name="xLotto" value="<?php echo $c->xLotto ?>">
        </div>


        <div class="col-md-12" style="margin-top:10px;">
            <input type="hidden" name="Id_xWPCollo" value="<?php echo $c->Id_xWPCollo ?>">
            <input type="hidden" name="Nr_Collo" value="<?php echo $c->Nr_Collo ?>">
            <input type="hidden" name="Cd_ARMisura" value="<?php echo $c->Cd_ARMisura ?>">

            <div class="row">

                <div class="col-md-6">
                    <input style="width:100%;" type="submit" name="modifica_collo" value="Salva"
                           class="btn btn-primary">
                </div>

                <div class="col-md-6">
                    <input style="width:100%;" type="submit" name="elimina_collo" value="Elimina"
                           class="btn btn-danger pull-left">
                </div>

                <div class="col-md-6" style="margin-top:5px;">
                    <input style="width:100%;" type="submit" name="stampa_collo" value="Stampa Collo"
                           class="btn btn-success">
                </div>

                <div class="col-md-6" style="margin-top:5px;">
                    <input style="width:100%;" type="submit" name="stampa_collo_qualita" value="Stampa Qualità"
                           class="btn btn-success">
                </div>

                    <?php if ($c->NC == 0){ ?>
                <div class="col-md-12" style="margin-top:5px;">
                    <a style="width:100%;color:white;" class="btn btn-warning"
                       onclick="non_conforme(<?php echo $c->Id_xWPCollo ?>,'<?php echo $c->Nr_Collo ?>')">Collo non
                        Conforme</a>
                </div>
                <?php } ?>

            </div>
        </div>

    </div>
</form>


<?php } ?>


<form method="post" id="fine_lavorazione">

    <div class="modal fade" id="modal_fine_lavorazione">
        <div class="modal-dialog modal-lg" style="min-width:70%">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Fine Lavorazione</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="row">

                                <?php $qta_colli = 0;$qta_colli_nc = 0; ?>
                                <?php foreach ($attivita_bolla->colli as $colli) if ($colli->NC == 0 && $colli->Id_PrVrAttivita == null) $qta_colli += $colli->QtaProdotta; ?>
                                <?php foreach ($attivita_bolla->colli as $colli_nc) if ($colli_nc->NC == 1) $qta_colli_nc += $colli_nc->QtaProdotta; ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Qta Dei Colli
                                            (<?php echo (sizeof($attivita_bolla->colli) > 0) ? $attivita_bolla->colli[0]->Cd_ARMisura : '' ?>
                                            )</label>
                                        <input id="quantita_totale" name="quantita_totale" type="number" step="1"
                                               min="0" value="<?php echo $qta_colli ?>" class="form-control" readonly
                                               onkeyup="calcola_scarto()">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantità Prodotta
                                            (<?php echo (sizeof($attivita_bolla->colli) > 0) ? $attivita_bolla->colli[0]->Cd_ARMisura : '' ?>
                                            )</label>
                                        <input id="quantita_rilevata" name="quantita_contatore" type="text" step="1"
                                               min="0"
                                               value="<?php echo ($OLAttivita->Cd_PrAttivita == 'SALDATURA')?$qta_colli:'' ?>"
                                               onkeyup="calcola_scarto()" onchange="calcola_scarto()"
                                               class="form-control keyboard_num" required>
                                    </div>
                                </div>

                                {{--
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Scarto Contatore
                                                                            (<?php echo (sizeof($attivita_bolla->colli) > 0) ? $attivita_bolla->colli[0]->Cd_ARMisura : '' ?>
                                                                            )</label>--}}
                                <input id="quantita_scarto" type="hidden" step="1" min="0" value="0"
                                       class="form-control" name="quantita_scarto" readonly
                                       onkeyup="calcola_scarto()">
                                {{--           </div>
                                       </div>
       --}}
                                {{--  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Scarto N.C.
                                              (<?php echo (sizeof($attivita_bolla->colli) > 0) ? $attivita_bolla->colli[0]->Cd_ARMisura : '' ?>
                                              )</label>--}}
                                <input id="quantita_scarto_nc" type="hidden" step="1" min="0"
                                       class="form-control" name="quantita_scarto_nc"
                                       value="0" readonly>
                                {{--        </div>
                                    </div>--}}

                                <div class="col-md-6">
                                    <label>U.M.</label>
                                    <?php if ($OLAttivita->Cd_PrAttivita == 'SALDATURA'){ ?>
                                    <select name="xCd_ARMisura" class="form-control select2" readonly
                                            style="width:100%">
                                            <?php foreach ($articolo->UM as $um){ ?>
                                        <option
                                                value="<?php echo $um->Cd_ARMisura ?>" <?php echo ($um->TipoARMisura == 'V') ? 'selected' : '' ?>><?php echo $um->Cd_ARMisura ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                    <select name="xCd_ARMisura" class="form-control select2" readonly
                                            style="width:100%">
                                        <option
                                                value="<?php echo $attivita_bolla->Cd_ARMisura ?>"><?php echo $attivita_bolla->Cd_ARMisura ?></option>
                                    </select>
                                    <?php } ?>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Magazzino</label>
                                        <select id="cd_mg" name="cd_mg" class="form-control">
                                            <option seelcted value="00001">00001 - Magazzino Centrale</option>
                                            <option value="00009">00009 - Magazzino Produzione</option>
                                        </select>
                                        <input id="quantita_scarto_vr" type="hidden" step="1" min="0"
                                               class="form-control" name="quantita_scarto_vr"
                                               value="0" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top:10px;">
                                    <input type="text" class="form-control" name="xLotto" id="xLotto">
                                </div>
                                <div class="col-md-12" style="margin-top:10px;">
                                    <h2>Vuoi Chiudere la Bolla ?</h2>
                                </div>

                                <div class="col-md-4">
                                    <input
                                            style="width: 100%;display: block;font-size: 70px;margin: 20px auto auto auto;"
                                            type="submit" name="fine_lavorazione_si" value="SI" class="btn btn-success">
                                </div>
                                <div class="col-md-4">
                                    <input
                                            style="width: 100%;display: block;font-size: 70px;margin: 20px auto auto auto;"
                                            type="submit" name="fine_lavorazione_no" value="NO" class="btn btn-primary">
                                </div>
                                <div class="col-md-4">
                                    <button
                                            style="width: 100%;display: block;line-height:105px;font-size: 30px;margin: 20px auto auto auto;"
                                            type="button" class="btn btn-default" data-dismiss="modal">Annulla
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">

                            <h3>Riepilogo Colli</h3>
                            <table id="lista_materiali_bolla" class="table table-bordered table-striped"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Collo</th>
                                    <th style="width:250px;">Qta Collo</th>
                                    <th>Qta Versata</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($attivita_bolla->colli as $cdv){ ?>
                                    <?php if ($cdv->QtaVersata < $cdv->QtaProdotta){ ?>
                                <tr>
                                    <td><?php echo $cdv->Nr_Collo ?></td>
                                    <td style="width:250px;"><?php echo $cdv->QtaProdotta ?></td>
                                    <td><input class="form-control input_versamento_collo" onchange="calcola_totale()"
                                               type="text" name="QtaVersata[<?php echo $cdv->Id_xWPCollo ?>]"
                                               value="<?php echo $cdv->QtaProdotta - $cdv->QtaVersata ?>" readonly></td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="clearfix"></div>
                </div>


                <div class="modal-footer">
                    <input type="hidden" name="Cd_PrRisorsa" value="<?php echo $attivita_bolla->Cd_PrRisorsa ?>">

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</form>


<script type="text/javascript">

    $('.keyboard_num:not(readonly)').keyboard({
        layout: 'num', visible: function (e, keyboard, el) {
            keyboard.$preview[0].select();
        }
    });
    $('.keyboard:not(readonly)').keyboard({layout: 'qwerty'});

    $('form').submit(function () {
        $('#ajax_loader').fadeIn();
    });
    $('#fine_lavorazione').submit(function (e) {
        check_lotti(e);
    });
</script>
