@include('backend.common.header')
@include('backend.common.sidebar')
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->

    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="input-icons">
                            <button onclick="cerca()" style="width:10%;height: 80%;border: none"><i class="fa fa-check"></i></button>
                            <input type="text" autocomplete="off" style="width:100%;border: none"  id="modal_cd_arlotto" class="form-control input-field" placeholder="Cerca Lotto...">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" id="lotto">Tracciabilita</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="ajax_tracciabilita">
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('backend.common.footer')
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- Page specific script -->
<script type="text/javascript">

    $('#modal_cd_arlotto').val(localStorage.getItem("modal_cd_arlotto"));

    function cerca(){
        lotto = document.getElementById('modal_cd_arlotto').value;
        localStorage.setItem("modal_cd_arlotto",lotto);

        $.ajax({
            url: "<?php echo URL::asset('ajax/load_tracciabilita') ?>/"+lotto
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

   /* function cerca1(ProlAttivita){
        lotto = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_tracciabilita1') ?>/"+lotto+"/"+ProlAttivita
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

    function cercaimballo(ProlAttivita){
        id_prol = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_imballo') ?>/"+id_prol+"/"+ProlAttivita
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

    function cercaimballo2(ProlAttivita,Nr_Pedana){
        id_prol = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_imballo2') ?>/"+id_prol+"/"+ProlAttivita+"/"+Nr_Pedana
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

    function cerca2(ProlAttivita,Nr_Collo){
        id_prol = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_tracciabilita2') ?>/"+id_prol+"/"+ProlAttivita+"/"+Nr_Collo
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

    function cerca_dietro(ProlAttivita,Rif_Nr_Collo,Rif_Nr_Collo2){
        id_prol = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_tracciabilita_dietro') ?>/"+id_prol+"/"+ProlAttivita+"/"+Rif_Nr_Collo+"/"+Rif_Nr_Collo2
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }

    function cerca_collo(Nr_Collo,ProlAttivita){
        id_prol = document.getElementById('modal_cd_arlotto').value;
        $.ajax({
            url: "<?php echo URL::asset('ajax/load_cerca_collo') ?>/"+id_prol+"/"+ProlAttivita+"/"+Nr_Collo
        }).done(function (result) {
            $('#ajax_tracciabilita').html(result);
        });
    }*/
</script>
</body>
</html>
