@include('backend.common.header')
{{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/clockpicker/dist/bootstrap-clockpicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/clockpicker/dist/bootstrap-clockpicker.min.js"></script> --}}

<!-- Include le librerie di Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<!-- Include il plugin datepicker per la data -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.it.min.js"></script>

<!-- Include il plugin clockpicker per l'ora -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>


<form action="{{ route('editPostSortex', ['idActivity' => $activity->Id_PrBLAttivita, 'id'=>$id]) }}"
      method="POST" onsubmit="return validateForm()">
    <div class="container mt-5">
        <h2>Testata</h2>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="data">Seleziona Data*:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" id="data" value="{{$json->data}}" name="data"
                           required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="mb-3">
                    <label for="caliber" class="form-label">LOTTO MP</label>
                    <input required type="text" name="lotto_mp" name="sampleCalibratura" value="{{$json->lotto_mp}}"
                           class="form-control" id="caliberSample"
                           aria-describedby="emailHelp">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="mb-3">
                    <label for="caliber" class="form-label">VARIETA</label>
                    <input required type="text" name="varieta" name="sampleCalibratura" value="{{$json->varieta}}"
                           class="form-control" id="caliberSample"
                           aria-describedby="emailHelp">
                </div>
            </div>
        </div>

    </div>

    <div class="container mt-5">
        <h2>SORTEX</h2>

        <table class="table">
            <thead>
            <tr>
                <th>DATA</th>
                <th>ORA</th>
                <th>CORPI ESTRANEI</th>
                <th>RAGGRINZITO/SEMI NERI
                    - SCURI
                </th>
                <th>AMMUFFITO /
                    CIMICIATO
                </th>
            </tr>
            </thead>
            <tbody id="tabella-corpo">
            <!-- Le tue righe verranno aggiunte qui dinamicamente -->
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" onclick="aggiungiRiga()">Aggiungi Riga</button>

        <div class="container mt-5">
            <input id="salv" required type="submit" class="btn btn-primary btn-block" value="SALVA"/>
        </div>

        <div class="modal fade" id="confermaEliminazioneModal" tabindex="-1" role="dialog"
             aria-labelledby="confermaEliminazioneModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confermaEliminazioneModalLabel">Conferma Eliminazione</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Sei sicuro di voler eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-danger" onclick="eliminaRiga()">Elimina</button>
                    </div>
                </div>
            </div>
        </div>
</form>


@foreach($json as  $ciao => $j )
    <input type="hidden" id="{{'x'.$ciao}}" value="{{$j}}">
@endforeach

<script>

    <?php
    $array = $json;
    $risultati = array();
    foreach ($array as $valore => $val) {
        if (strpos($valore, 'data') !== false) {
            $risultati[] = $valore;
        }
    }
    $size = sizeof($risultati);
    ?>
    $(document).ready(() => {
        init({{$size}});
    });

    function eliminaRiga(button) {
        // var row = button.closest('tr');
        // row.remove();

        $('#confermaEliminazioneModal').modal('hide');

        // Rimuovi la riga salvata
        rigaDaEliminare.remove();
    }


    function rigaStyle(name, index) {
        var tbody = document.getElementById('tabella-corpo');


        var input = document.createElement("input");
        input.type = "text";
        input.name = name + (tbody.rows.length);
        input.classList.add("form-control");
        input.value = "";
        input.setAttribute("required", "required");

        return input;
    }

    function rigaEditStyle(name, index) {
        if (name === 'data') {
            var input = document.getElementById(name + index);
            input.value = document.getElementById('x' + name + index).value;
            console.log(document.getElementById('x' + name + index).value);
            return input;
        }
        var tbody = document.getElementById('tabella-corpo');
        var input = document.createElement("input");

        input.type = "text";
        input.name = name + index;
        input.classList.add("form-control");
        input.value = document.getElementById('x' + name + index).value;
        input.setAttribute("required", "required");

        return input;
    }

    function confermaEliminazione(button) {
        // Apri il modale di conferma
        $('#confermaEliminazioneModal').modal('show');

        // Salva la riga corrispondente
        rigaDaEliminare = button.closest('tr');
    }

    function init(size) {
        currentValue = 1;
        var tbody = document.getElementById('tabella-corpo');
        while (currentValue < size) {
            var newRow = tbody.insertRow();
            newRow.insertCell(0).innerHTML = `
                    <input type="date" class="form-control datepicker"  id="data${currentValue}" name="data${currentValue}" required>`;
            newRow.insertCell(1).appendChild(rigaEditStyle("ora", currentValue))
            newRow.insertCell(2).appendChild(rigaEditStyle("corpi", currentValue))
            newRow.insertCell(3).appendChild(rigaEditStyle("raggrizito", currentValue))
            newRow.insertCell(4).appendChild(rigaEditStyle("ammuffito", currentValue))
            newRow.insertCell(5).innerHTML = `
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>`;
            rigaEditStyle("data", currentValue)
            currentValue++;
        }
    }

    function aggiungiRiga() {

        var tbody = document.getElementById('tabella-corpo');
        var newRow = tbody.insertRow();
        newRow.insertCell(0).innerHTML = `
                    <input type="date" class="form-control datepicker"  id="data${(tbody.rows.length)}" name="data${(tbody.rows.length)}" required>
               `;
        newRow.insertCell(1).appendChild(rigaStyle("ora", 2))
        newRow.insertCell(2).appendChild(rigaStyle("corpi", 2))
        newRow.insertCell(3).appendChild(rigaStyle("raggrizito", 2))
        newRow.insertCell(4).appendChild(rigaStyle("ammuffito", 2))
        newRow.insertCell(5).innerHTML = `
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>`;
        // for (var i = 0; i < 5; i++) {

        //     var cell = newRow.insertCell(i);

        //     // Crea un input di tipo text con nome dinamico (lotto1, lotto2, ...)
        //     var input = document.createElement("input");
        //     input.type = "text";
        //     input.name = "lotto" + (tbody.rows.length + 1); // Nome con numero della riga
        //     input.classList.add("form-control"); // Aggiungi classe Bootstrap

        //     input.value = "Nuovo Valore"; // Puoi inizializzare il valore se necessario

        //     // Aggiungi l'input alla cella
        //     cell.appendChild(input);
        //             }
    }

    function validateForm() {

        var data = document.getElementById('data').value;
        var oraInizio = document.getElementById('oraInizio').value;
        var oraFine = document.getElementById('oraFine').value;

        if (data === '' || oraInizio === '' || oraFine === '') {
            $('#myModal').modal('show');
            return false;
        }

        return true;


    }

    $('.datepicker').datepicker({
        language: 'it',
        format: 'dd/mm/yyyy',
        autoclose: true
    });

    $('.timepicker').timepicker({
        showMeridian: false,
        minuteStep: 1,
        defaultTime: false
    });
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Attenzione!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Compila i campi obbligatori per proseguire.</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
