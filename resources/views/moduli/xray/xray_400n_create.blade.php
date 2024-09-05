<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabella Bootstrap Laravel</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .form-label {
            font-weight: bold;
        }

        .custom-checkbox {
            transform: scale(1.5);
        }
    </style>
</head>

<body>
    <form action="{{ route('createPost400N', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST"
        class="container mt-5">
        <table class="table table-bordered" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Linea <b> X-RAY Serie EL - 400/N </b></th>
                    <th>FE 1,5 mm</th>
                    <th>NON FE 1,5 mm</th>
                    <th>STAINLESS 1,8mm</th>
                    <th>CRYSTAL GLASS 3,0 mm</th>
                    <th>CERAMIC 8,0 mm</th>
            </thead>
            <tbody>
                <tr id="referenceRow">
                    <td>
                        <span class="counter">1</span>° con. ore <input name="ore1" type="number" required
                            class="form-control">

                        <div class="mb-3">
                            <label for="lotto1" class="form-label">LOTTO</label>
                            <input name="lotto1" class="form-control" type="text" id="lotto1">
                        </div>

                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe1" value="false">
                            <input name="fe1" type="checkbox" id="fe1"
                                class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe1" value="false">
                            <input name="nofe1" type="checkbox" id="nofe1"
                                class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless1" value="false">
                            <input name="stainless1" type="checkbox" id="stainless1"
                                class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="crystal1" value="false">
                            <input name="crystal1" type="checkbox" id="crystal1"
                                class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="ceramic1" value="false">
                            <input name="ceramic1" type="checkbox" id="ceramic1"
                                class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>

                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary mt-3" id="aggiungiBtn" onclick="aggiungiRiga()">Aggiungi
            Riga</button>
        <input type="submit" class="btn btn-success mt-3" value="SALVA">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var counter = 1;

        let options = [];

        document.addEventListener('DOMContentLoaded', function() {

            axios.get('/XWPCollo/{{ $attivity->Id_PrBLAttivita }}')
                .then(function(response) {
                    var xwpCollo = document.getElementById('xwpCollo');
                    response.data.forEach(function(collo) {
                        var option = document.createElement('option');
                        option.text = `${collo.Cd_AR} - ${collo.xLotto}`;
                        option.value = `${collo.Cd_AR} - ${collo.xLotto}`;
                        xwpCollo.add(option);
                    });
                    options = response.data;
                    // $(xwpCollo).selectpicker('refresh');
                })
                .catch(function(error) {
                    console.error('Errore nella richiesta Axios', error);
                });
        });

        $(document).ready(function() {
            $('#xwpCollo').change(function() {
                var selectedValue = $(this).val();
                console.log('Valore selezionato:', selectedValue);

                if (selectedValue === 'NESSUN LOTTO') {
                    alert('Seleziona un lotto valido.');
                    $(this).val('');
                    $('#xwp').val('');
                } else {
                    $('#xwp').val(selectedValue);
                }
            });
        });

        function aggiungiRiga() {
            counter++;

            var newRowHTML = `
                <tr>
                    <td>
                        <span class="counter">${counter}</span>° con. ore <input name="ore${counter}" type="number"
                            required class="form-control">
                        <div class="mb-3">
                         <div class="mb-3">
                            <label for="lotto${counter}" class="form-label">LOTTO</label>
                            <input name="lotto${counter}" class="form-control" type="text" id="lotto${counter}">
                        </div>
                        </div>
                        @csrf
        <input required type="hidden" name="xwp${counter}" class="xwp form-control" id="xwp${counter}">
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe${counter}" value="false">
                            <input name="fe${counter}" type="checkbox" id="fe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe${counter}" value="false">
                            <input name="nofe${counter}" type="checkbox" id="nofe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless${counter}" value="false">
                            <input name="stainless${counter}" type="checkbox" id="stainless${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="crystal${counter}" value="false">
                            <input name="crystal${counter}" type="checkbox" id="crystal${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="ceramic${counter}" value="false">
                            <input name="ceramic${counter}" type="checkbox" id="ceramic${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                </tr>
            `;

            $('#myTable tbody').append(newRowHTML);
        }

        function getAjaxOptions() {
            // Funzione per ottenere le opzioni da Ajax e restituire una stringa HTML
            var ajaxOptions = '';

            options.forEach(function(collo) {
                ajaxOptions +=
                    `<option value="${collo.Cd_AR} - ${collo.xLotto}">${collo.Cd_AR} - ${collo.xLotto}</option>`;
            });

            return ajaxOptions;
        }

        function confermaEliminazione(button) {
            // Apri il modale di conferma
            $('#confermaEliminazioneModal').modal('show');

            // Salva la riga corrispondente
            rigaDaEliminare = button.closest('tr');
        }

        function eliminaRiga(button) {
            // var row = button.closest('tr');
            // row.remove();

            $('#confermaEliminazioneModal').modal('hide');

            // Rimuovi la riga salvata
            rigaDaEliminare.remove();
        }
    </script>
</body>

</html>
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
