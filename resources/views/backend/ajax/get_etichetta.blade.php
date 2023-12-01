<?php foreach($etichette as $e){ ?>

<form method="post" enctype="multipart/form-data">
    <div class="modal fade" id="modal_modifica_<?php echo $e->Id_xSPReport ?>">
        <div class="modal-dialog modal-lg" style="min-width:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modifica Etichetta</h4>
                </div>
                <div class="modal-body row">

                    <div class="col-md-8">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Codice</label>
                                    <input type="text" class="form-control" value="<?php echo $e->codice ?>" name="codice" placeholder="Codice">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrizione</label>
                                    <input type="text" class="form-control" name="descrizione" value="<?php echo $e->descrizione ?>" placeholder="Descrizione">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Formato</label>
                                    <input type="text" class="form-control" name="grandezza" value="<?php echo $e->grandezza ?>" placeholder="Grandezza">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipologia</label>
                                    <select name="tipologia" class="form-control">
                                        <option value="0" <?php echo ($e->tipologia == 0)?'selected':'' ?>>Collo Grande</option>
                                        <option value="1" <?php echo ($e->tipologia == 1)?'selected':'' ?>>Qualita Grande</option>
                                        <option value="2" <?php echo ($e->tipologia == 2)?'selected':'' ?>>Collo Piccolo</option>
                                        <option value="3" <?php echo ($e->tipologia == 3)?'selected':'' ?>>Qualita Piccolo</option>
                                        <option value="4" <?php echo ($e->tipologia == 4)?'selected':'' ?>>Collo Anonimo</option>
                                        <option value="5" <?php echo ($e->tipologia == 5)?'selected':'' ?>>Pedana</option>
                                        <option value="6" <?php echo ($e->tipologia == 6)?'selected':'' ?>>Etichetta Pedana</option>
                                        <option value="7" <?php echo ($e->tipologia == 7)?'selected':'' ?>>Stampa Libera</option>
                                        <option value="8" <?php echo ($e->tipologia == 8)?'selected':'' ?>>Etichetta Piccola</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Abilitato</label>
                                    <select name="Abilitato" class="form-control">
                                        <option value="0" <?php echo ($e->abilitato == 0)?'selected':'' ?>>NO</option>
                                        <option value="1" <?php echo ($e->abilitato == 1)?'selected':'' ?>>SI</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Query</label>
                                    <textarea style="height:200px;" class="form-control" name="query" placeholder="Query"><?php echo $e->query ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <h2>Filtri</h2>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select name="cd_cf" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti i Clienti</option>
                                    <?php foreach($clienti as $c){ ?>
                                    <option value="<?php echo $c->Cd_CF ?>" <?php echo ($c->Cd_CF == $e->cd_cf)?'selected':''  ?>><?php echo $c->Cd_CF.' - '.$c->Descrizione ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fase</label>
                                <select name="cd_prattivita" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutte le Fasi</option>
                                    <?php foreach($fasi as $f){ ?>
                                    <option value="<?php echo $f->Cd_PrAttivita ?>" <?php echo ($f->Cd_PrAttivita == $e->cd_prattivita)?'selected':''  ?>><?php echo $f->Cd_PrAttivita  ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Articolo 1</label>
                                <select name="cd_ar1" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti gli Articoli</option>
                                    <option value="000000000">Nessun Articolo</option>
                                    <?php foreach($articoli as $a){ ?>
                                    <option value="<?php echo $a->Cd_AR ?>" <?php echo ($a->Cd_AR == $e->cd_ar1)?'selected':''  ?>><?php echo $a->Cd_AR.' '.$a->Descrizione  ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Articolo 2</label>
                                <select name="cd_ar2" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti gli Articoli</option>
                                    <option value="000000000">Nessun Articolo</option>
                                    <?php foreach($articoli as $a){ ?>
                                    <option value="<?php echo $a->Cd_AR ?>" <?php echo ($a->Cd_AR == $e->cd_ar2)?'selected':''  ?>><?php echo $a->Cd_AR.' '.$a->Descrizione  ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Articolo 3</label>
                                <select name="cd_ar3" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti gli Articoli</option>
                                    <option value="000000000">Nessun Articolo</option>
                                    <?php foreach($articoli as $a){ ?>
                                    <option value="<?php echo $a->Cd_AR ?>" <?php echo ($a->Cd_AR == $e->cd_ar3)?'selected':''  ?>><?php echo $a->Cd_AR.' '.$a->Descrizione  ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Articolo 4</label>
                                <select name="cd_ar4" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti gli Articoli</option>
                                    <option value="000000000">Nessun Articolo</option>
                                    <?php foreach($articoli as $a){ ?>
                                    <option value="<?php echo $a->Cd_AR ?>" <?php echo ($a->Cd_AR == $e->cd_ar4)?'selected':''  ?>><?php echo $a->Cd_AR.' '.$a->Descrizione  ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Articolo 5</label>
                                <select name="cd_ar5" class="form-control select2" style="width:100%;height:30px;">
                                    <option value="">Tutti gli Articoli</option>
                                    <option value="000000000">Nessun Articolo</option>
                                    <?php foreach($articoli as $a){ ?>
                                    <option value="<?php echo $a->Cd_AR ?>" <?php echo ($a->Cd_AR == $e->cd_ar5)?'selected':''  ?>><?php echo $a->Cd_AR.' '.$a->Descrizione  ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Chiudi</button>
                    <input type="hidden" name="Id_xSPReport" value="<?php echo $e->Id_xSPReport ?>">
                    <input type="submit" class="btn btn-primary pull-right" name="modifica" value="Modifica" style="margin-right:5px;">
                </div>
            </div>
        </div>
    </div>
</form>

<?php } ?>
