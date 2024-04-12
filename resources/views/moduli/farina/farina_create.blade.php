@include('backend.common.header')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Selectpicker CSS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap Selectpicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



<div class="content-wrapper p-3">

    <form action="{{ route('createPostFarina', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST" onsubmit="return validateForm()">
        <div class="container">
            <div class="row">
                <!-- Prima Colonna -->
                <div class="col-md-4">
                    <h1> Dati </h1>
                    @include('moduli.farina.components.variety')
                    @include('moduli.farina.components.caliber')
                    @include('moduli.components.xwpcollo_select',
                        ['attivita' => $attivity]
                    )
                    @include('moduli.farina.components.simple_date')

                    @include('moduli.farina.components.analysis_time', ['name' => 'analysisTime']),
                    @include('moduli.farina.components.sample', ['name' => 'sample'])
                    @include('moduli.farina.components.moisture')


                </div>

                <div class="col-md-4">
                    @include('moduli.farina.components.target')
                </div>
                 <div class="col-md-4">
                    @include('moduli.farina.components.caliber_op')
                    @include('moduli.farina.components.observations')
                    <div class="mb-3">
                        <a href="{{ url()->previous() }}" type="submit" class="btn btn-danger">ANNULLA</a>

                        <input required type="submit" class="btn btn-primary" value="SALVA" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function validateForm() {
                for (var i = 0; i < elementCalculated.length; i++) {
                var currentElement = elementCalculated[i];

                // Ottieni gli elementi associati
                var percentageTd = document.getElementById(currentElement.idFieldinput);
                var percentageInput = document.getElementById(currentElement.idPercentageinput);

                // Verifica se entrambi gli elementi sono vuoti
                if (!percentageTd.value.trim() || !percentageInput.value.trim()) {
                    // Messaggio di errore (puoi personalizzarlo)
                    alert('Mancano dei campi Obbligatori per procedere.');
                    return false; // Impe
                    // Puoi anche visualizzare un messaggio per l'utente, ad esempio con alert o mostrando un elemento HTML
                    // alert('Entrambi i campi percentuali sono vuoti. Inserisci i valori necessari.');
                }
            }
            return true;
        }
    </script>
</div>
