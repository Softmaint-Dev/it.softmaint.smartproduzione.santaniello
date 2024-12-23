@include('backend.common.header')

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


</div>
