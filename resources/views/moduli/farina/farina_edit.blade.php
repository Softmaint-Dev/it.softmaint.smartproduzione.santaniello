@include('backend.common.header')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Selectpicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Selectpicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="content-wrapper p-4 bg-light">

    <form action="{{ route('editPostFarina', ['idActivity' => $activity->Id_PrBLAttivita, 'id' => $id]) }}" method="POST"
        onsubmit="return validateForm()">
        @csrf
        <div class="container">
            <div class="row mb-4">
                <!-- Prima Colonna -->
                <div class="col-md-4">
                    <h2 class="mb-4">Dati</h2>

                    <div class="mb-3">
                        <label for="variety" class="form-label">Varietà / Variety</label>
                        <input required type="text" class="form-control" id="variety" name="variety"
                            value="{{ $json->variety }}">
                    </div>

                    <div class="mb-3">
                        <label for="caliber" class="form-label">Calibro / Caliber</label>
                        <input required type="text" class="form-control" id="caliber" name="caliber"
                            value="{{ $json->caliber }}">
                    </div>

                    <div class="mb-3">
                        <label for="wpCollo" class="form-label">Lotto Produzione / Production Batch</label>
                        <input required type="text" class="form-control" id="wpCollo" name="wpCollo"
                            value="{{ $json->wpCollo }}">
                    </div>

                    <div class="mb-3">
                        <label for="cf" class="form-label">Cliente / Customer</label>
                        <input required type="text" class="form-control" id="cf" name="cf"
                            value="{{ $json->cf }}">
                    </div>

                    <div class="mb-3">
                        <label for="kg" class="form-label">KG Totali / Total KG</label>
                        <input required type="text" class="form-control" id="kg" name="kg"
                            value="{{ $json->kg }}">
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Data / Date</label>
                        <input required type="date" class="form-control" id="date" name="date"
                            value="{{ $json->date }}">
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Orario / Time</label>
                        <input required type="time" class="form-control" id="time" name="time"
                            value="{{ $json->time }}">
                    </div>
                </div>

                <div class="col-md-8">
                    <h2 class="mb-4">Target</h2>

                    <div class="mb-3">
                        <label for="grCampione" class="form-label">gr Campione / gr Sample</label>
                        <input required type="text" class="form-control" id="grCampione" name="grCampione"
                            value="{{ $json->grCampione }}" oninput="calculatePercentages()">
                    </div>

                    <div class="mb-3">
                        <label for="moisture" class="form-label">Umidità / Moisture</label>
                        <input required type="text" class="form-control" id="moisture" name="moisture"
                            value="{{ $json->moisture }}">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Titolo</th>
                                    <th scope="col">Target Natural</th>
                                    <th scope="col">Target Roasted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>3,5% / 4,00%</td>
                                    <td>2,5% / 3,00%</td>
                                </tr>
                                <tr>
                                    <td>Colore / Color</td>
                                    <td><input type="text" id="colore" name="colore" class="form-control"
                                            value="{{ $json->colore }}" oninput="calculatePercentages()"></td>
                                    <td><input type="text" id="colorePercentage" name="colorePercentage"
                                            class="form-control" value="{{ $json->colorePercentage }}" readonly></td>
                                </tr>
                                <tr>
                                    <td>Sapore / Taste</td>
                                    <td><input type="text" id="sapore" name="sapore" class="form-control"
                                            value="{{ $json->sapore }}" oninput="calculatePercentages()"></td>
                                    <td><input type="text" id="saporePercentage" name="saporePercentage"
                                            class="form-control" value="{{ $json->saporePercentage }}" readonly></td>
                                </tr>
                                <tr>
                                    <td><strong>Totale Percentuale / Total Percentage</strong></td>
                                    <td></td>
                                    <td><input type="text" id="totalPercentage" name="totalPercentage"
                                            class="form-control" value="{{ $json->totalPercentage }}" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Titolo</th>
                                    <th scope="col">Valore / Value</th>
                                    <th scope="col">Percentuale / Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Farina / Meal</td>
                                    <td><input type="text" id="farina" name="farina" class="form-control"
                                            value="{{ $json->farina }}" oninput="calculateMealPercentages()"></td>
                                    <td><input type="text" id="farinaPercentage" name="farinaPercentage"
                                            class="form-control" value="{{ $json->farinaPercentage }}" readonly></td>
                                </tr>
                                <tr>
                                    <td>Granella / Chopped</td>
                                    <td><input type="text" id="granella" name="granella" class="form-control"
                                            value="{{ $json->granella }}" oninput="calculateMealPercentages()"></td>
                                    <td><input type="text" id="granellaPercentage" name="granellaPercentage"
                                            class="form-control" value="{{ $json->granellaPercentage }}" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Totale Percentuale / Total Percentage</strong></td>
                                    <td></td>
                                    <td><input type="text" id="totalMealPercentage" name="totalMealPercentage"
                                            class="form-control" value="{{ $json->totalMealPercentage }}" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function validateForm() {
        const elements = document.querySelectorAll('.form-control');
        for (let i = 0; i < elements.length; i++) {
            if (!elements[i].value.trim()) {
                alert('Mancano dei campi obbligatori per procedere.');
                return false;
            }
        }
        return true;
    }

    function calculatePercentages() {
        var grCampione = parseFloat(document.getElementById('grCampione').value);
        var colore = parseFloat(document.getElementById('colore').value);
        var sapore = parseFloat(document.getElementById('sapore').value);

        if (!isNaN(grCampione) && grCampione > 0) {
            var colorePercentage = (!isNaN(colore) ? (colore / grCampione) * 100 : 0).toFixed(2);
            var saporePercentage = (!isNaN(sapore) ? (sapore / grCampione) * 100 : 0).toFixed(2);
            var totalPercentage = (parseFloat(colorePercentage) + parseFloat(saporePercentage)).toFixed(2);

            document.getElementById('colorePercentage').value = colorePercentage + '%';
            document.getElementById('saporePercentage').value = saporePercentage + '%';
            document.getElementById('totalPercentage').value = totalPercentage + '%';
        } else {
            document.getElementById('colorePercentage').value = '0%';
            document.getElementById('saporePercentage').value = '0%';
            document.getElementById('totalPercentage').value = '0%';
        }
    }

    function calculateMealPercentages() {
        var farina = parseFloat(document.getElementById('farina').value);
        var granella = parseFloat(document.getElementById('granella').value);

        var total = farina + granella;

        if (!isNaN(total) && total > 0) {
            var farinaPercentage = (!isNaN(farina) ? (farina / total) * 100 : 0).toFixed(2);
            var granellaPercentage = (!isNaN(granella) ? (granella / total) * 100 : 0).toFixed(2);
            var totalMealPercentage = (parseFloat(farinaPercentage) + parseFloat(granellaPercentage)).toFixed(2);

            document.getElementById('farinaPercentage').value = farinaPercentage + '%';
            document.getElementById('granellaPercentage').value = granellaPercentage + '%';
            document.getElementById('totalMealPercentage').value = totalMealPercentage + '%';
        } else {
            document.getElementById('farinaPercentage').value = '0%';
            document.getElementById('granellaPercentage').value = '0%';
            document.getElementById('totalMealPercentage').value = '0%';
        }
    }

    // Esegui il calcolo delle percentuali al caricamento della pagina
    document.addEventListener('DOMContentLoaded', function() {
        calculatePercentages();
        calculateMealPercentages();
    });
</script>















{{-- @include('backend.common.header')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Selectpicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap Selectpicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<div class="content-wrapper p-3">

    <form action="{{ route('editPostFarina', ['idActivity' => $activity->Id_PrBLAttivita, 'id' => $id]) }}" method="POST"
        onsubmit="return validateForm()">
        <div class="container">
            <div class="row">
                <!-- Prima Colonna -->
                <div class="col-md-4">
                    <h1> Dati </h1>
                    @include('moduli.farina.components.variety', ['value' => $json->variety])
                    @include('moduli.farina.components.caliber', ['value' => $json->caliber])
                    {{-- @include('moduli.components.xwpcollo_select',['attivita' => $activity,'selected'=>$json->xwpCollo]) --}}
                    <div class="mb-3">
                        <label for="xwpCollo">LOTTO</label>
                        <input type="text" name="xwpCollo" id="xwpCollo" value={{ $json->xwpCollo }} required
                            class="form-control">
                    </div>
                    @include('moduli.farina.components.simple_date', ['value' => $json->simpleDate])

                    @include('moduli.farina.components.analysis_time', [
                        'name' => 'analysisTime',
                        'value' => $json->analysisTime,
                    ])

                    @include('moduli.farina.components.sample', [
                        'name' => 'sample',
                        'value' => $json->sample,
                    ])
                    @include('moduli.farina.components.moisture', ['value' => $json->moisture])


                </div>

                <div class="col-md-4">
                    <div>
                        <h1>TARGET</h1>
                        <br />
                        <div class="mb-3">
                            <label for="caliber" class="form-label">Analysis Time</label>
                            <input required type="text" name="sampleCalibratura" class="form-control"
                                id="caliberSample" value="{{ $json->sampleCalibratura }}" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <div class="container mt-5">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Descrizione</th>
                                            <th scope="col">% target natural</th>
                                            <th scope="col">% target roasted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">3,5%/4,00%</th>
                                            <th scope="col">2,5%/3,00%</th>
                                        </tr>
                                        <tr>
                                            <td>color / colore</td>
                                            <td>
                                                <input type="text" name="colorNatural" id="colorNatural"
                                                    value="{{ isset($json->colorNatural) ? 'X' : '' }}"
                                                    class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="colorRoasted" id="colorRoasted"
                                                    value="{{ isset($json->colorRoasted) ? 'X' : '' }}"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>taste and smell</td>
                                            <td><input type="text" name="tasNatural" id="tasNatural"
                                                    value="{{ isset($json->tasNatural) ? 'X' : '' }}"
                                                    class="form-control"></td>
                                            <td><input type="text" name="tasRoasted" id="tasRoasted"
                                                    value="{{ isset($json->tasRoasted) ? 'X' : '' }}"
                                                    class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>total</td>
                                            <td><input type="text" name="totalNatural" id="totalNatural"
                                                    value="{{ isset($json->totalNatural) ? 'X' : '' }}"
                                                    class="form-control"></td>
                                            <td><input type="text" name="totalRoasted" id="totalRoasted"
                                                    value="{{ isset($json->totalRoasted) ? 'X' : '' }}"
                                                    class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <h1>Calibratura</h1>
                        <br />
                        <div class="mb-3">
                            <label for="caliber" class="form-label">gr campione / sample</label>
                            <input required oninput="updateCalculations()" type="text" name="analisys_calibratura"
                                value="{{ $json->analisys_calibratura }}" class="form-control" id="caliberSample"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <div class="container mt-5">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Titolo</th>
                                            <th scope="col">gr</th>
                                            <th scope="col">Percentuale / Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>>2</td>
                                            <td><input type="text" name="value1" value="{{ $json->value1 }}"
                                                    id="value1" class="form-control" oninput="updateCalculations()">
                                            </td>
                                            <td><input type="text" name="value1Percentage" id="value1Percentage"
                                                    value="{{ $json->value1Percentage }}" class="form-control"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td>>1</td>
                                            <td><input type="text" name="value2" value="{{ $json->value2 }}"
                                                    id="value2" class="form-control"
                                                    oninput="updateCalculations()"></td>
                                            <td><input type="text" name="value2Percentage" id="value2Percentage"
                                                    value="{{ $json->value2Percentage }}" class="form-control"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td>&lt;1</td>
                                            <td><input type="text" name="value3" value="{{ $json->value3 }}"
                                                    id="value3" class="form-control"
                                                    oninput="updateCalculations()"></td>
                                            <td><input type="text" name="value3Percentage" id="value3Percentage"
                                                    value="{{ $json->value3Percentage }}" class="form-control"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td>total</td>
                                            <td><input type="text" name="valueTot" id="valueTot"
                                                    class="form-control" value="{{ $json->valueTot }}" readonly></td>
                                            <td><input type="text" name="valueTotPercentage"
                                                    id="valueTotPercentage" value="{{ $json->valueTotPercentage }}"
                                                    class="form-control" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @include('moduli.farina.components.observations', ['value' => $json->observations])
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

    <script>
        function updateCalculations() {
            var caliberSample = parseFloat(document.getElementById('caliberSample').value);
            var value1 = parseFloat(document.getElementById('value1').value);
            var value2 = parseFloat(document.getElementById('value2').value);
            var value3 = parseFloat(document.getElementById('value3').value);

            var value1Percentage = (!isNaN(value1) && value1 !== 0) ? (caliberSample / value1) * 100 : 0;
            var value2Percentage = (!isNaN(value2) && value2 !== 0) ? (caliberSample / value2) * 100 : 0;
            var value3Percentage = (!isNaN(value3) && value3 !== 0) ? (caliberSample / value3) * 100 : 0;

            var valueTot = (!isNaN(value1) ? value1 : 0) + (!isNaN(value2) ? value2 : 0) + (!isNaN(value3) ? value3 : 0);
            var valueTotPercentage = value1Percentage + value2Percentage + value3Percentage;

            document.getElementById('value1Percentage').value = value1Percentage;
            document.getElementById('value2Percentage').value = value2Percentage;
            document.getElementById('value3Percentage').value = value3Percentage;
            document.getElementById('valueTot').value = valueTot;
            document.getElementById('valueTotPercentage').value = (!isNaN(valueTotPercentage) && valueTotPercentage !== 0) ?
                valueTotPercentage : 0;
        }
    </script>


</div> --}}
