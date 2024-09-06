<div>
    <h1>Calibratura</h1>
    <br />
    <div class="mb-3">
        <label for="caliber" class="form-label">gr campione / sample</label>
        <input required oninput="updateCalculations()" type="text" name="analisys_calibratura" class="form-control"
            id="caliberSample">
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
                        <td><input type="text" name="value1" id="value1" class="form-control"
                                oninput="updateCalculations()"></td>
                        <td><input type="text" name="value1Percentage" id="value1Percentage" class="form-control"
                                readonly></td>
                    </tr>
                    <tr>
                        <td>>1</td>
                        <td><input type="text" name="value2" id="value2" class="form-control"
                                oninput="updateCalculations()"></td>
                        <td><input type="text" name="value2Percentage" id="value2Percentage" class="form-control"
                                readonly></td>
                    </tr>
                    <tr>
                        <td>&lt;1</td>
                        <td><input type="text" name="value3" id="value3" class="form-control"
                                oninput="updateCalculations()"></td>
                        <td><input type="text" name="value3Percentage" id="value3Percentage" class="form-control"
                                readonly></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input type="text" name="valueTot" id="valueTot" class="form-control" readonly></td>
                        <td><input type="text" name="valueTotPercentage" id="valueTotPercentage" class="form-control"
                                readonly></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function updateCalculations() {
        // Ottieni i valori di input
        var caliberSample = parseFloat(document.getElementById('caliberSample').value) || 0;
        var value1 = parseFloat(document.getElementById('value1').value) || 0;
        var value2 = parseFloat(document.getElementById('value2').value) || 0;
        var value3 = parseFloat(document.getElementById('value3').value) || 0;

        // Calcola il totale
        var valueTot = value1 + value2 + value3;

        // Controlla se caliberSample è maggiore di 0 per evitare divisioni per zero
        var value1Percentage = (caliberSample > 0) ? (value1 / caliberSample) * 100 : 0;
        var value2Percentage = (caliberSample > 0) ? (value2 / caliberSample) * 100 : 0;
        var value3Percentage = (caliberSample > 0) ? (value3 / caliberSample) * 100 : 0;

        // Calcola la percentuale totale (dovrebbe idealmente essere 100%)
        var valueTotPercentage = (caliberSample > 0) ? (valueTot / caliberSample) * 100 : 0;

        // Aggiorna i campi input
        document.getElementById('value1Percentage').value = value1Percentage.toFixed(2);
        document.getElementById('value2Percentage').value = value2Percentage.toFixed(2);
        document.getElementById('value3Percentage').value = value3Percentage.toFixed(2);
        document.getElementById('valueTot').value = valueTot.toFixed(2);
        document.getElementById('valueTotPercentage').value = valueTotPercentage.toFixed(2);
    }
</script>
