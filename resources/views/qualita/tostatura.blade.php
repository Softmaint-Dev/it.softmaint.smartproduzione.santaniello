@include('qualita.components.header', [
"title" => "Sistema di Gestione Integrato",
"code" => "M_IO 17_1",
"review" => 0,
]);


<table class="table">
    <thead>
        <tr>
            <th style="font-size: 12px" scope="row">DATA</th>
            <th style="font-size: 12px" scope="row">
                <?php echo date('d-M-Y', (strtotime('today'))); ?>
            </th>
        </tr>
    </thead>
</table>

<table class="table">
    <thead>
        <tr>
            <th>LOTTO</th>
            <th>N CESTA</th>
            <th>RICETTA CARICATA</th>
            <th>RH IN</th>
            <th>RH OUT</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php for ($i = 0;
                       $i < 5;
                       $i++){ ?>
            <td><input class="form-control" type="text" name="lotto_<?php echo $i;?>" id="lotto_<?php echo $i;?>"></td>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($i = 0;
                       $i < 5;
                       $i++){ ?>
            <td><input class="form-control" type="number" step="1" min="1" max="19" name="nr_cesta_<?php echo $i;?>"
                    id="nr_cesta_<?php echo $i;?>"></td>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($i = 0;
                       $i < 5;
                       $i++){ ?>
            <td><input class="form-control" type="text" name="ricetta_<?php echo $i;?>" id="ricetta_<?php echo $i;?>">
            </td>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($i = 0;
                       $i < 5;
                       $i++){ ?>
            <td><input class="form-control" type="number" step="1" min="1" max="100" name="rh_in_<?php echo $i;?>"
                    id="rh_in_<?php echo $i;?>"></td>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($i = 0;
                       $i < 5;
                       $i++){ ?>
            <td><input class="form-control" type="number" step="1" min="1" max="100" name="rh_out_<?php echo $i;?>"
                    id="rh_out_<?php echo $i;?>"></td>
            <?php } ?>
        </tr>
    </tbody>
</table>


<table class="table">
    <tbody>
        <tr>
            <th style="font-size: 12px" scope="row">FIRMA CQ</th>
            <td> </td>
        </tr>
    </tbody>
</table>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

<script></script>

</html>