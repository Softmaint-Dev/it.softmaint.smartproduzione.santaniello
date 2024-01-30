@include('backend.common.header')
@include('backend.common.sidebar')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Etichette</h1>
                </div>

            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <!-- /.card -->

                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="post" id="form1">
                            <div style="display: flex">

                                <label for="anno" style="margin-left:2%;">
                                    Anno
                                </label>
                                <input id="anno" onblur="sumbitform()" name="anno" type="text" class="form-control"
                                       style="margin-left:2%;width: 25%;border-width: 0 0 2px 0;"
                                       value="{{$raccolto->Anno}}">
                                <label for="origine" style="margin-left:2%;">
                                    Origine
                                </label>
                                <input id="origine" onblur="sumbitform()" name="origine" type="text"
                                       class="form-control"
                                       style="margin-left:2%;width: 25%;border-width: 0 0 2px 0;"
                                       value="{{$raccolto->Origine}}">

                            </div>
                        </form>

                        <a style="float:right;margin-bottom:20px;" class="btn btn-success" onclick="aggiungi()">Crea
                            Etichetta</a>
                        <a style="float:right;margin-bottom:20px;margin-right:10px;" class="btn btn-primary"
                           onclick="importa()">Importa Etichetta</a>


                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                               aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th>Codice</th>
                                <th>Descrizione</th>
                                <th>Filtro</th>
                                <th>Tipologia</th>
                                <th>Abilitato</th>
                                <th style="width:400px;">Azioni</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($etichette as $e){ ?>
                            <tr>
                                <td><?php echo $e->codice ?></td>
                                <td><?php echo $e->descrizione ?><br>Formato:<?php echo $e->grandezza ?></td>
                                <td>
                                        <?php echo ($e->cd_cf != '') ? $e->cd_cf : 'Tutti i Clienti' ?><br>
                                        <?php echo ($e->cd_prattivita != '') ? $e->cd_prattivita : 'Tutti le Fasi' ?>
                                    <br>
                                        <?php echo ($e->cd_ar1 != '') ? $e->cd_ar1 . '<br>' : 'Tutti gli Articoli' ?>
                                        <?php echo ($e->cd_ar2 != '') ? $e->cd_ar2 . '<br>' : '' ?>
                                        <?php echo ($e->cd_ar3 != '') ? $e->cd_ar3 . '<br>' : '' ?>
                                        <?php echo ($e->cd_ar4 != '') ? $e->cd_ar4 . '<br>' : '' ?>
                                        <?php echo ($e->cd_ar5 != '') ? $e->cd_ar5 . '<br>' : '' ?>
                                </td>

                                <td>
                                        <?php echo ($e->tipologia == 0) ? 'Collo Grande' : '' ?>
                                        <?php echo ($e->tipologia == 1) ? 'Qualita Grande' : '' ?>
                                        <?php echo ($e->tipologia == 2) ? 'Collo Piccolo' : '' ?>
                                        <?php echo ($e->tipologia == 3) ? 'Qualità Piccolo' : '' ?>
                                        <?php echo ($e->tipologia == 4) ? 'Collo Anonimo' : '' ?>
                                        <?php echo ($e->tipologia == 5) ? 'Pedana' : '' ?>
                                        <?php echo ($e->tipologia == 6) ? 'Etichetta Pedana' : '' ?>
                                        <?php echo ($e->tipologia == 7) ? 'Stampa Libera' : '' ?>
                                        <?php echo ($e->tipologia == 8) ? 'Etichetta Piccola' : '' ?>
                                </td>

                                <td><?php echo ($e->abilitato == 1) ? 'SI' : 'NO' ?></td>
                                <td>

                                    <a style="float:left;margin-left:5px;" class="btn btn-success"
                                       onclick="modifica(<?php echo $e->Id_xSPReport ?>)">Modifica</a>

                                    <a style="float:left;margin-left:5px;" target="_blank" class="btn btn-primary"
                                       href="<?php echo URL::asset('editor_etichetta/'.$e->Id_xSPReport) ?>">Editor</a>

                                    <a style="float:left;margin-left:5px;" target="_blank" class="btn btn-primary"
                                       href="<?php echo URL::asset('etichette?esporta_etichette=Esporta&codice='.$e->codice.'&Id_xSPReport='.$e->Id_xSPReport) ?>">Esporta</a>

                                    <form method="post" onsubmit="return confirm('Vuoi Eliminare questa Etichetta ?')"
                                          style="float:left;margin-left:5px;">
                                        <input type="hidden" name="Id_xSPReport" value="<?php echo $e->Id_xSPReport ?>">
                                        <input type="submit" name="elimina" class="btn btn-danger" value="Elimina"
                                               style="float:left;margin-left:5px;">
                                    </form>

                                    <form method="post" onsubmit="return confirm('Vuoi duplicare questa Etichetta ?')"
                                          style="float:left;margin-left:5px;">
                                        <input type="hidden" name="Id_xSPReport" value="<?php echo $e->Id_xSPReport ?>">
                                        <input type="submit" name="duplica" class="btn btn-primary" value="Duplica"
                                               style="float:left;margin-left:5px;">
                                    </form>

                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>

@include('backend.common.footer')


<form method="post" enctype="multipart/form-data">
    <div class="modal fade" id="modal_aggiungi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crea una Nuova Etichetta</h4>
                </div>
                <div class="modal-body row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Codice</label>
                            <input type="text" class="form-control" name="codice" placeholder="Codice">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrizione</label>
                            <input type="text" class="form-control" name="descrizione" placeholder="Descrizione">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Formato</label>
                            <input type="text" class="form-control" name="grandezza" placeholder="Grandezza">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipologia</label>
                            <select name="tipologia" class="form-control select2">
                                <option value="0">Collo Grande</option>
                                <option value="1">Qualita Grande</option>
                                <option value="2">Collo Piccolo</option>
                                <option value="3">Qualita Piccolo</option>
                                <option value="4">Collo Anonimo</option>
                                <option value="5">Pedana</option>
                                <option value="6">Etichetta Pedana</option>
                                <option value="7">Stampa Libera</option>
                                <option value="8">Etichetta Piccola</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Query</label>
                            <textarea style="height:200px;" class="form-control" name="query"
                                      placeholder="Query"></textarea>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Chiudi</button>
                    <input type="submit" class="btn btn-primary pull-right" name="aggiungi" value="Aggiungi"
                           style="margin-right:5px;">
                </div>
            </div>
        </div>
    </div>
</form>


<form method="post" enctype="multipart/form-data">
    <div class="modal fade" id="modal_importa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Importa Etichetta</h4>
                </div>
                <div class="modal-body row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Chiudi</button>
                    <input type="submit" class="btn btn-primary pull-right" name="importa_etichetta" value="Importa"
                           style="margin-right:5px;">
                </div>
            </div>
        </div>
    </div>
</form>

<div id="ajax_loader2"></div>


<script type="text/javascript">

    function sumbitform() {
        $('#form1').submit();
    }

    function aggiungi() {
        $('#modal_aggiungi').modal('show');
    }

    function importa() {
        $('#modal_importa').modal('show');
    }

    function modifica(id) {

        $.get('<?php echo URL::asset('ajax/get_etichetta') ?>/' + id, function (data) {
            $('#ajax_loader2').html(data);

            $('.select2').select2();
            $('#modal_modifica_' + id).modal('show');
        });


    }


</script>


