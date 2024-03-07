 
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


 --}}


 @include('moduli.components.header')


<form class="mt-5" action="{{ route('editPostEfficienza', ['idActivity' => $activity->Id_PrBLAttivita, 'id'=>$id]) }}" method="POST"
    onsubmit="return validateForm()">
    <div class="container mt-5">
        <h2>Testata</h2>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="data">Seleziona Data*:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" id="data" name="data" readonly required value="{{ $json->data }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                    </div>
                </div>
            </div>                   
           
           
            <div class="col-md-4 mb-3">
                <label for="oraInizio">Seleziona Ora Inizio*:</label>
                <div class="input-group">
                    <input type="text" class="form-control timepicker" id="oraInizio" name="oraInizio" readonly required value="{{ $json->oraInizio }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="oraFine">Seleziona Ora Fine*:</label>
                <div class="input-group">
                    <input type="text" class="form-control timepicker" id="oraFine" name="oraFine" readonly required value="{{ $json->oraFine }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @include('moduli.components.xwpcollo_select', ['attivita' => $activity, 'selected' => $json->xwpCollo])

    </div>

    <div class="container mt-5">
        <h2>AREA PRODUZIONE</h2>
    
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Funzionamento SEA HYPERSORT</th>
                    <th>Conforme</th>
                    <th>Descrizione</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Assenza di allarmi o visivi</td>
                    <td>
                        <div class="form-check form-switch">
                            <input name="allarme" class="form-check-input required" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" required @if ($json->allarme == "on") checked @endif>
                         </div>
                    </td>
                    <td><textarea name="allarme_nota"  class="form-control"  rows="3">{{$json->allarme_nota}}</textarea> </td>
                </tr>
                <tr>
                    <td>“Ricetta” corretta su display</td>
                    <td>
                        <div class="form-check form-switch">
                            <input   name="ricetta" class="form-check-input required" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" @if ($json->ricetta == "on") checked @endif>
                         </div>
                    </td>
                    <td><textarea name="ricetta_nota" class="form-control" rows="3">{{$json->ricetta_nota}}</textarea></td>
                </tr>
               <tr>
                    <td>Assenza acqua in filtro dell’aria</td>
                    <td>
                        <div class="form-check form-switch">
                            <input name="filtro_acqua" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" @if ($json->filtro_acqua == "on") checked @endif>

                         </div>
                    </td>
                    <td><textarea class="form-control" name="filtro_acqua_note" rows="3">{{$json->filtro_acqua_note}}</textarea></td>
                </tr>
                <tr>
                    <td>Controllo Elettrovalvole a buon fine</td>
                    <td>
                        <div class="form-check form-switch">
                            <input   name="elettrovalvole" class="form-check-input required" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" @if ($json->elettrovalvole == "on") checked @endif>
                         </div>
                    </td>
                    <td><textarea class="form-control" name="elettrovalvole_note" rows="3">{{$json->elettrovalvole_note}}</textarea></td>
                </tr>
                <tr>
                    <td>Controllo avvenuta calibrazione</td>
                    <td>
                        <div class="form-check form-switch">
                            <input   name="calibrazione" class="form-check-input required" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" @if ($json->calibrazione == "on") checked @endif>
                         </div>
                    </td>
                    <td><textarea class="form-control" name="calibrazione_note" rows="3">{{$json->calibrazione_note}}</textarea></td>
                </tr>
            </tbody>
        </table>
        
        <div class="container mt-5">
            <h2>NOTE E INDICAZIONI PER RISOLUZIONE EVENTUALI NC RILEVATE</h2>
            <textarea class="form-control" name="note" rows="3">{{$json->note}}</textarea>    
        </div>

        <div class="container mt-5">
            <input id="salv" required type="submit" class="btn btn-primary btn-block" value="MODIFICA" />
        </div>
    
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>  
     <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script> --}}



    <script>
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