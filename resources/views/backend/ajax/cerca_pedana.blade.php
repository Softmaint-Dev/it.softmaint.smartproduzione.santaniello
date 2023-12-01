<?php foreach($pedane as $p){ ?>
    <div class="col-lg-3 col-6 pedana ol_<?php echo $p->Id_PrOL ?> <?php echo $p->Nr_Pedana ?>" style="cursor:pointer;color:white;" id="<?php echo str_replace('.','',$p->Nr_Pedana) ?>" onclick="<?php echo ($p->Id_PrRLAttivita != '')?'azioni_pedana':'rileva_pedana' ?>(<?php echo $p->Id_xWPPD ?>)" >
        <!-- small box -->
        <div class="small-box bg-<?php echo ($p->Id_PrRLAttivita != '')?'green':'yellow' ?>">
            <div class="inner">
                <b style="font-size:20px;margin:0;display:block;"><?php echo $p->cliente ?></b>
                <small><?php echo $p->Nr_Pedana ?> (<?php echo $p->NumeroColli ?> Colli) OL <?php echo $p->Id_PrOL ?></small><br>
                <small style="font-size:20px;">
                    Peso Lordo: <?php echo number_format($p->PesoLordo,2,'.','') ?> Kg<br>
                    Peso Netto: <?php echo number_format($p->PesoNetto,2,'.','') ?> Kg<br>
                    Peso Nettissimo: <?php echo number_format($p->PesoNettissimo,2,'.','') ?> Kg<br>
                </small>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a class="small-box-footer">Azioni Pedana<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
<?php } ?>
