@include('backend.common.header')
@include('backend.common.sidebar')

<div class="content-wrapper" style="display: flex;flex-direction: column">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Attivita Bolla
                        <?php echo $attivita_bolla->Id_PrBLAttivita ?>
                        (<small>Ordine N°
                            <?php echo $nr_dotes; ?>
                        </small>
                        |
                        <small>Bolla
                            <?php echo $bolla->Id_PrBL ?>
                        </small>
                        |
                        <small>OL
                            <?php echo $attivita_bolla->Id_PrOL ?>
                        </small>)
                    </h1>

                    <?php if (sizeof($attivita_bolla->CF) > 0) {
                        echo $attivita_bolla->CF[0]->Descrizione . ' - ';
                    } ?>

                    <?php echo $articolo->Cd_AR ?> -
                    <?php echo $attivita_bolla->Descrizione ?>
                    -
                    <?php echo $attivita_bolla->Articolo ?><br>


                    <?php if (trim($bolla->NotePrBL) != ''){ ?>
                    <h3 style="color:red;">
                            <?php echo nl2br($bolla->NotePrBL) ?>
                    </h3>
                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
    <section class="content" style="flex: 1;display: flex">
        <div style="display: flex;justify-content: space-between; align-items: center;flex: 1;">
            <button type="button" onclick="$('#modal_versamento').modal('show');"
                    style="flex: 1;margin:2%;height: 80%;background-color: blue" class="btn btn-secondary">
                <h4> VERSAMENTO </h4>
            </button>
            <button type="button" onclick="$('#modal_versamento_ultimo').modal('show');"
                    style="flex: 1;margin:2%;height: 80%;background-color: green;color: white;" class="btn btn-warning">
                <h4> VERSAMENTO E FINE LAVORAZIONE </h4>
            </button>
        </div>
    </section>
</div>


@include('backend.common.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>
<form method="post">
    <div class="modal fade" id="modal_versamento_ultimo">
        <div class="modal-dialog" style="height: 70%;margin-left: 10rem;">
            <div class="modal-content" style="width: 200%;height: 100%">
                <div class="modal-header">
                    <h4 class="modal-title">Inserire Quantità Prodotta</h4>
                </div>
                <div class="modal-body" style="display: flex;justify-content: space-between;flex-direction: column">
                    <div
                        style="flex:3;width: 100%;height: 100%;display: flex;justify-content: center;align-items:center; ">
                        <h2> Valore in KG </h2>
                    </div>
                    <div style="flex:4">
                        <input type="number" step="0.01" class="form-control" id="quantita_prodotta"
                               min="0"
                               max="99999"
                               name="quantita_prodotta" value=""
                               style="height: 100%; font-size: 7rem;text-align: center"
                               placeholder="Quantità Prodotta">
                    </div>
                    <div
                        style="flex:3;width: 100%;height: 100%;display: flex;justify-content: center;align-items:center; ">
                        <h2 style="color: red;"> ATTENZIONA STAI CHIUDENDO LA LAVORAZIONE ! </h2>
                    </div>
                    <input type="hidden" name="crea_versamento" value="crea_versamento">
                    <input type="hidden" name="UltimoVR" value="1">
                    <div class="clearfix"></div>
                </div>


                <div class="modal-footer" style="height:20%;display: flex;justify-content: space-between;">
                    <button type="submit" style="height: 100%;width: 40%;font-size:2.5rem;" data-dismiss="modal"
                            class="btn btn-primary">
                        Salva
                    </button>
                    <button type="button"
                            style="height: 100%;width: 40%;font-size:2.5rem;background-color: indianred;color: white;"
                            class="btn btn-default"
                            data-dismiss="modal"
                            onclick="$('#modal_versamento_ultimo').modal('hide')">Chiudi
                    </button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<form method="post">
    <div class="modal fade" id="modal_versamento">
        <div class="modal-dialog" style="height: 70%;margin-left: 10rem;">
            <div class="modal-content" style="width: 200%;height: 100%">
                <div class="modal-header">
                    <h4 class="modal-title">Inserire Quantità Prodotta</h4>
                </div>
                <div class="modal-body" style="display: flex;justify-content: space-between;flex-direction: column">
                    <div
                        style="flex:1;width: 100%;height: 100%;display: flex;justify-content: center;align-items:center; ">
                        <h2> Valore in KG </h2>
                    </div>
                    <div style="flex:2">
                        <input type="number" step="0.01" class="form-control" id="quantita_prodotta"
                               min="0"
                               max="99999"
                               name="quantita_prodotta" value=""
                               style="height: 100%; font-size: 7rem;text-align: center"
                               placeholder="Quantità Prodotta">
                    </div>
                    <input type="hidden" name="crea_versamento" value="crea_versamento">
                    <input type="hidden" name="UltimoVR" value="0">
                    <div class="clearfix"></div>
                </div>


                <div class="modal-footer" style="height:20%;display: flex;justify-content: space-between;">
                    <button type="submit" style="height: 100%;width: 40%;font-size:2.5rem;" data-dismiss="modal"
                            class="btn btn-primary">
                        Salva
                    </button>
                    <button type="button"
                            style="height: 100%;width: 40%;font-size:2.5rem;background-color: indianred;color: white;"
                            class="btn btn-default"
                            data-dismiss="modal"
                            onclick="$('#modal_versamento').modal('hide')">Chiudi
                    </button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
 </form>
<script type="text/javascript">
	
    $('form').submit(function () {
        $('#ajax_loader').fadeIn();
    });
</script>
<style>
    #search {
        width: 90%;
    }

    .searchicon {
        color: #5CB85C;
    }

    .items-collection {
        margin: 5px 0 0 0;
    }

    .items-collection label.btn-default.active {
        background-color: #007ba7;
        color: #FFF;
    }

    .items-collection label.btn-default {
        width: 100%;
        border: 1px solid #305891;
        border-radius: 17px;
        color: #305891;
    }

    .items-collection label .itemcontent {
        width: 100%;
    }

    .items-collection .btn-group {
        width: 100%
    }
</style>
