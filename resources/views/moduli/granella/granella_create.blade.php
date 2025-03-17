@include('backend.common.header')
@include('moduli.components.header')

<div class="content-wrapper p-3">
    <form action="{{ route('createPostGranella', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST" onsubmit="return validateForm()">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Dati</h1>
                    <div class="mb-3">
                        <label class="form-label">Varietà / Variety</label>
                        <input required type="text" class="form-control" id="variety" name="variety">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Calibro / Caliber</label>
                        <input required type="text" class="form-control" id="calibre" name="calibre">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kg Totale Ordine / Total Kg Order</label>
                        <input required type="text" class="form-control" id="kg" name="kg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">LOTTO</label>
                        <input required type="text" class="form-control" id="xwpCollo" name="xwpCollo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data / Date</label>
                        <input type="datetime-local" class="form-control" id="data" name="date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ANALYSIS TIME</label>
                        <input required type="number" class="form-control" id="analysis" name="analysis">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">gr Campione / gr Sample</label>
                        <input required type="number" class="form-control" id="sample" name="sample">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Umidità / Moisture</label>
                        <input required type="number" class="form-control" id="moisture" name="moisture">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input name="skin" class="form-check-input" type="checkbox" id="skin" checked>
                        <label class="form-check-label" for="skin">Pellicine / Skin</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input name="tastAndSmell" class="form-check-input" type="checkbox" id="tastAndSmell" checked>
                        <label class="form-check-label" for="tastAndSmell">Sapore e odore / Taste and smell</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input name="colour" class="form-check-input" type="checkbox" id="colour" checked>
                        <label class="form-check-label" for="colour">Colore / Colour</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h1>Calibratura</h1>
                    <div class="mb-3">
                        <label class="form-label">gr campione / sample</label>
                        <input required type="text" name="sampleCalibratura" class="form-control" id="caliberSample">
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">gr</th>
                                <th scope="col">Percentuale / Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="calibraturaTable">
                            <tr>
                                <td>over size</td>
                                <td><input type="text" class="form-control" id="overSize" name="overSize" required></td>
                                <td id="overSizePercentage"></td>
                            </tr>
                            <tr>
                                <td><input type='text' class='form-control' id='calculation_title' name='calculation_title'></td>
                                <td><input type="text" class="form-control" id="calculation" name="calculation" required></td>
                                <td id="calculationPercentage"></td>
                            </tr>
                            <tr>
                                <td>under size</td>
                                <td><input type="text" class="form-control" id="underSize" name="underSize" required></td>
                                <td id="underSizePercentage"></td>
                            </tr>
                            <tr>
                                <td>total</td>
                                <td><input type="text" class="form-control" id="total" name="total" required></td>
                                <td id="totalPercentage"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mb-3">
                        <textarea name="observations" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 300px"></textarea>
                        <label for="floatingTextarea2">osservazioni / observations</label>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary" value="SALVA">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="overSizePercentageInput" name="overSizePercentage">
        <input type="hidden" id="calculationPercentageInput" name="calculationPercentage">
        <input type="hidden" id="underSizePercentageInput" name="underSizePercentage">
        <input type="hidden" id="totalPercentageInput" name="totalPercentage">
        <input type="hidden" id="calculation_2" name="calculation_2" value="0">
        <input type="hidden" id="cf" name="cf" value="0">
    </form>
    <script>
        function validateForm() {
            let valid = true;
            document.querySelectorAll('input[required]').forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                }
            });
            if (!valid) {
                alert('Mancano dei campi obbligatori per procedere.');
            }
            return valid;
        }

        function calculatePercentages() {
            const sampleValue = parseFloat(document.getElementById("caliberSample").value) || 0;
            document.querySelectorAll('#calibraturaTable tr').forEach(row => {
                const input = row.querySelector('td:nth-child(2) input');
                const percentageCell = row.querySelector('td:nth-child(3)');
                if (input && percentageCell) {
                    const value = parseFloat(input.value) || 0;
                    const percentage = sampleValue ? ((value / sampleValue) * 100).toFixed(2) : 0;
                    percentageCell.textContent = percentage + '%';
                }
            });
        }

        document.getElementById("caliberSample").addEventListener('input', calculatePercentages);
        document.querySelectorAll('#calibraturaTable input').forEach(input => input.addEventListener('input', calculatePercentages));
    </script>
</div>