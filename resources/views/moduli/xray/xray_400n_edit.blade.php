<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabella Dinamica Bootstrap con Laravel</title>
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

    <form action="{{ route('editPost400N', ['idActivity' => $activity->Id_PrBLAttivita, 'id' => $id]) }}" method="POST"
        onsubmit="return validateForm()" class="container mt-5">
        <div class="mb-3">
            <label for="lotto" class="form-label">DATA</label>
            <input value={{ $json->data }} type="text" name="data" name="sampleCalibratura" class="form-control"
                id="data" aria-describedby="emailHelp">
        </div>
        <table class="table table-bordered" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Linea X-RAY XBR-6000</th>
                    <th>FE 1,5 mm</th>
                    <th>NON FE 1,5 mm</th>
                    <th>STAINLESS 1,8mm</th>
                    <th>CRYSTAL GLASS 3,0 mm</th>
                    <th>CERAMIC 8,0 mm</th>
                    <th>Elimina</th>
                </tr>
            </thead>
            <tbody>
                <!-- La riga di esempio per i dati iniziali -->
                <tr id="referenceRow">

                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary mt-3" id="aggiungiBtn" onclick="aggiungiRiga()">Aggiungi
            Riga</button>
        <input type="submit" class="btn btn-success mt-3" value="SALVA">
    </form>

    <!-- Modal per conferma eliminazione -->
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var counter = 1;
        var rigaDaEliminare = null;

        // Recupera il JSON passato da Laravel
        var data = @json($json);

        // Funzione per confermare l'eliminazione
        function confermaEliminazione(button) {
            rigaDaEliminare = button.closest('tr');
            var modal = new bootstrap.Modal(document.getElementById('confermaEliminazioneModal'));
            modal.show();
        }

        // Funzione per eliminare una riga
        function eliminaRiga() {
            if (rigaDaEliminare) {
                rigaDaEliminare.remove();
            }
            var modal = bootstrap.Modal.getInstance(document.getElementById('confermaEliminazioneModal'));
            modal.hide();
        }

        // Funzione per aggiungere una nuova riga alla tabella
        function aggiungiRiga() {
            counter++;
            let newRow = `
                <tr>
                    <td>
                        <span class="counter">${counter}</span>° con. ore
                        <input name="ore${counter}" type="text" required class="form-control">
                        <div class="mb-3">
                            <label for="lotto${counter}" class="form-label">LOTTO</label>
                            <input required type="text" class="form-control" id="lotto${counter}" name="lotto${counter}">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe${counter}" value="false">
                            <input name="fe${counter}" type="checkbox" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe${counter}" value="false">
                            <input name="nofe${counter}" type="checkbox" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless${counter}" value="false">
                            <input name="stainless${counter}" type="checkbox" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="crystal${counter}" value="false">
                            <input name="crystal${counter}" type="checkbox" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="ceramic${counter}" value="false">
                            <input name="ceramic${counter}" type="checkbox" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                            <i class="fas fa-trash"></i> Elimina
                        </button>
                    </td>
                </tr>
            `;
            document.querySelector('#myTable tbody').insertAdjacentHTML('beforeend', newRow);
        }

        // Funzione per aggiungere righe dal JSON passato
        function aggiungiRigheDaJson(jsonData) {
            let maxCounter = 0;

            // Trova il numero massimo di set di dati numerati (es. ore1, ore2, ore3, ecc.)
            for (let key in jsonData) {
                let match = key.match(/\d+$/); // Cerca i numeri alla fine delle chiavi
                if (match) {
                    maxCounter = Math.max(maxCounter, parseInt(match[0]));
                }
            }

            // Itera e crea le righe
            for (let i = 1; i <= maxCounter; i++) {
                let ore = jsonData[`ore${i}`] || '';
                let lotto = jsonData[`lotto${i}`] || '';
                let fe = jsonData[`fe${i}`] === "true" ? "checked" : "";
                let nofe = jsonData[`nofe${i}`] === "true" ? "checked" : "";
                let stainless = jsonData[`stainless${i}`] === "true" ? "checked" : "";
                let crystal = jsonData[`crystal${i}`] === "true" ? "checked" : "";
                let ceramic = jsonData[`ceramic${i}`] === "true" ? "checked" : "";

                let newRow = `
                    <tr>
                        <td>
                            <span class="counter">${i}</span>° con. ore
                            <input name="ore${i}" type="text" value="${ore}" required class="form-control">
                            <div class="mb-3">
                                <label for="lotto${i}" class="form-label">LOTTO</label>
                                <input required type="text" class="form-control" id="lotto${i}" name="lotto${i}" value="${lotto}">
                            </div>
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input type="hidden" name="fe${i}" value="false">
                                <input name="fe${i}" type="checkbox" class="custom-checkbox form-check-input" value="true" ${fe}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input type="hidden" name="nofe${i}" value="false">
                                <input name="nofe${i}" type="checkbox" class="custom-checkbox form-check-input" value="true" ${nofe}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input type="hidden" name="stainless${i}" value="false">
                                <input name="stainless${i}" type="checkbox" class="custom-checkbox form-check-input" value="true" ${stainless}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input type="hidden" name="crystal${i}" value="false">
                                <input name="crystal${i}" type="checkbox" class="custom-checkbox form-check-input" value="true" ${crystal}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input type="hidden" name="ceramic${i}" value="false">
                                <input name="ceramic${i}" type="checkbox" class="custom-checkbox form-check-input" value="true" ${ceramic}>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confermaEliminazione(this)">
                                <i class="fas fa-trash"></i> Elimina
                            </button>
                        </td>
                    </tr>
                `;
                document.querySelector('#myTable tbody').insertAdjacentHTML('beforeend', newRow);
            }
            counter = maxCounter;
        }

        // Carica le righe dal JSON al caricamento della pagina
        window.onload = function() {
            aggiungiRigheDaJson(data);
        };
    </script>
</body>

</html>
