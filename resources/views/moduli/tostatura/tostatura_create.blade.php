 
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
  {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
  <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

  <!-- Include il plugin datepicker per la data -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.it.min.js"></script>

  <!-- Include il plugin clockpicker per l'ora -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css"> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script> --}}

  @include('moduli.components.header') 

<form class="mt-5" action="{{ route('createPostTostatura', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST"
    onsubmit="return validateForm()">
    <div class="container mt-5">
        <h2>Testata</h2>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="data">Seleziona Data*:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" id="data" name="data" required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="container mt-5">
        <h2>Tostatura</h2>
    
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 200px">Lotto</th>
                    <th>NCesta</th>
                    <th>Ricetta</th>  
                    <th>RHIN</th>
                    <th>RHOUT</th> 
                </tr>
            </thead>
            <tbody id="tabella-corpo">
                <!-- Le tue righe verranno aggiunte qui dinamicamente -->
            </tbody>
        </table>
    
        <button type="button" class="btn btn-primary" onclick="aggiungiRiga()">Aggiungi Riga</button>

        <div class="container mt-5">
            <input id="salva" required type="submit" class="btn btn-primary btn-block" value="SALVA" />
        </div>

 
    <script>

 

function rigaStyle(name) {
    var tbody = document.getElementById('tabella-corpo');

    // Crea un input di tipo text con nome dinamico (lotto1, lotto2, ...)
    var input = document.createElement("input");
    input.type = "text";
    input.name = name + (tbody.rows.length); 
    input.classList.add("form-control"); 
    input.setAttribute("required", "required"); 
    return input;
}

function aggiungiRiga() {
        var tbody = document.getElementById('tabella-corpo');
        var newRow = tbody.insertRow();
        


        newRow.insertCell(0).innerHTML = `
            <td>
                <select style="width:200px " data-live-search="true" id="lotto${(tbody.rows.length)}" name="lotto${(tbody.rows.length)}" required>
                    <option value="" disabled selected>Seleziona un lotto</option> </select> 
            </td>
        `;
        var xwpCollo = document.getElementById(`lotto${(tbody.rows.length)}`);
                lotti.forEach(function (collo) {
                    var option = document.createElement('option');
                    option.text = `${collo.Cd_AR} - ${collo.xLotto}`;
                    option.value = `${collo.Cd_AR} - ${collo.xLotto}`;
                    xwpCollo.add(option);
                });
                console.log(lotti);
                $(xwpCollo).selectpicker('refresh');

        newRow.insertCell(1).appendChild(rigaStyle("cesta")) 
        newRow.insertCell(2).appendChild(rigaStyle("ricetta")) 
        newRow.insertCell(3).appendChild(rigaStyle("rhin"))  
        newRow.insertCell(4).appendChild(rigaStyle("rhout")) 
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

    fetchLotti();
    lotti = [];

    function fetchLotti() {
        axios.get('/XWPCollo/{{$attivity->Id_PrBLAttivita}}')
            .then(function (response) {
                console.log(response.data);
                  lotti = response.data;
            })
            .catch(function (error) {
                console.error('Errore nella richiesta Axios', error);
            });
    }

    </script>
</form>

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