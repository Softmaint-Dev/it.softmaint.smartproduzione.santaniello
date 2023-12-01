@include('backend.common.header')
@include('backend.common.sidebar')

<div class="content-wrapper">
    @include('qualita.Controllo selezione')
</div>

<div id="ajax_loader"></div>

@include('backend.common.footer')

<script type="text/javascript">

    function gotobolla(){

        numero_bolla = $('#numero_bolla').val();
        if(numero_bolla != '') {

            $.get("<?php echo URL::asset('ajax/get_bolla') ?>/"+numero_bolla, function( data ) {
                if(data != ''){
                    top.location.href = '<?php echo URL::asset('dettaglio_bolla') ?>/' + data
                }
            });

        }

    }


    $('#numero_bolla').keyup(function(event) {
        if (event.which === 13) {
            gotobolla()
        }
    });

</script>


<style>
    td{
        font-size:11px;
    }
    th{
        font-size:11px;
    }
</style>
