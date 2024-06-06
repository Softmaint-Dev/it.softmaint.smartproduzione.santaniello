@include('backend.common.header')
@include('backend.common.sidebar')
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->

    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        {{--<section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="input-icons">
                            <button onclick="cerca()" style="width:10%;height: 80%;border: none"><i
                                    class="fa fa-check"></i></button>
                            <input type="text" autocomplete="off" style="width:100%;border: none" id="modal_cd_arlotto"
                                   class="form-control input-field" placeholder="Cerca Lotto...">
                        </div>
                    </div>
                </div>
            </div>
        </section>--}}
        <!-- Main content -->
        <section class="content" id="stampabile">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" id="lotto">Numero OL (<strong> <?php echo $numero_ol; ?> </strong>)</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="treeview">
                    <ul id="treeview">
                        <?php foreach ($consumi as $c){ ?>
                            <?php if ($c->Attivita != NUll){ ?>
                        <li class="tree-node">
                            {{ $c->Attivita }}
                            <ul>
                                    <?php foreach ($consumi as $c1){ ?>
                                    <?php if ($c1->Id_PrVRAttivita == $c->Id_PrVRAttivita){ ?>
                                    <?php if ($c1->Tipo != 3){ ?>
                                    <?php if ($c1->Tipo == 0){ ?>
                                <li class="tree-node">
                                    <p style="color: green">{{number_format($c1->Consumo,2,'.',' ').' '.$c1->Cd_ARMisura.' | '.$c1->Cd_AR}}
                                        <strong>@if($c1->Cd_ARLotto != NULL)
                                                {{' (  '.$c1->Cd_ARLotto.' )'}}
                                            @endif </strong> |</p>
                                </li>
                                <?php } ?>
                                    <?php if ($c1->Tipo != 0){ ?>
                                <li class="tree-node">
                                    <p>- {{number_format($c1->Consumo,2,'.',' ').' '.$c1->Cd_ARMisura.' | '.$c1->Cd_AR}}
                                        <strong>@if($c1->Cd_ARLotto != NULL)
                                                {{' (  '.$c1->Cd_ARLotto.' )'}}
                                            @endif </strong> |</p>

                                    <div id="ajax_tracciabilita_mod_{{$c1->Cd_ARLotto.'_'.$c1->Cd_AR}}"></div>
                                    {{--<?php foreach ($semilavorati as $s){ ?>
                                    <?php if ($s->Cd_ARLotto == $c1->Cd_ARLotto && $s->Cd_AR == $c1->Cd_AR){ ?>
                                <ul style="color: blue">
                                        <?php foreach ($semilavorati as $s2) { ?>
                                        <?php if ($s2->Tipo == 2){ ?>
                                    <li class="tree-node">
                                        <p>
                                            - {{number_format($s2->Consumo,2,'.',' ').' '.$s2->Cd_ARMisura.' | '.$s2->Cd_AR}}
                                            <strong>@if($s2->Cd_ARLotto != NULL)
                                                    {{' (  '.$s2->Cd_ARLotto.' )'}}
                                                @endif </strong> |</p>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                                <?php } ?>
                                <?php } ?>--}}
                                </li>
                                <?php } ?>
                                <?php } ?>
                                <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>

        <div class="btn btn-primary" style="margin-top: 1%;width: 100%;text-align:center" onclick="stampa();"
             id="button_stampa">STAMPA
        </div>
        {{--<div class="btn btn-secondary" style="margin-top: 1%;width: 100%;text-align:center" onclick="excel();"
             id="button_excel">EXCEL
        </div>--}}
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('backend.common.footer')
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- Page specific script -->
<script type="text/javascript">
    function cerca_semilavorato(cd_ar, lotto) {
        $.ajax({
            url: "<?php echo URL::asset('ajax/cerca_semilavorato') ?>/" + lotto + "/" + cd_ar
        }).done(function (result) {
            document.getElementById('ajax_tracciabilita_mod_' + lotto + '_' + cd_ar).innerHTML = result;
        });
    }

    function stampa() {
        document.getElementById('button_stampa').style.display = 'none';
        //document.getElementById('button_excel').style.display = 'none';
        document.getElementById('footer').style.display = 'none';
        window.print();
        document.getElementById('button_stampa').style.display = 'block';
        //document.getElementById('button_excel').style.display = 'block';
        document.getElementById('footer').style.display = 'block';
    }

    /* function excel() {
         lotto = document.getElementById('modal_cd_arlotto').value;
         window.open("<?php echo URL::asset('ajax/scarica_excel_lotto') ?>/" + lotto);
    }*/


</script>
<script type="text/javascript">
    <?php foreach ($consumi as $c){ ?>
        <?php if ($c->Cd_ARLotto != ''){ ?>
        <?php if ($c->Tipo != 0){ ?>
    cerca_semilavorato('{{$c->Cd_AR}}', '{{$c->Cd_ARLotto}}');
    <?php } ?>
    <?php } ?>
    <?php } ?>
</script>
</body>
</html>
