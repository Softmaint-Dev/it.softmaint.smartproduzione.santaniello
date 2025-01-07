@include('backend.common.header')
@include('moduli.components.header')

<div class="content-wrapper p-3">
    <form action="{{ route('createPostGranella', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST"
        onsubmit="return validateForm()">
        <div class="container">
            <div class="row">
                <!-- Prima Colonna -->
                <div class="col-md-6">
                    <h1>Dati</h1>
                    <div class="mb-3">
                        <label class="form-label">Varietà / Variety</label>
                        <input required type="text" class="form-control" id="variety" name="variety">
                    </div>
                    <div class="mb-3">
                        <label for="calibre" class="form-label">Calibro / Caliber</label>
                        <input required type="text" class="form-control" id="calibre" name="calibre">
                    </div>
                    <div class="mb-3">
                        <label for="kg" class="form-label">Kg Totale Ordine / Total Kg Order</label>
                        <input required type="text" class="form-control" id="kg" name="kg">
                    </div>
                    {{-- @include('moduli.components.xwpcollo_select', ['attivita' => $attivity]) --}}
                    <div class="mb-3">
                        <label for="xwpCollo" class="form-label">Lotto</label>
                        <input required type="text" class="form-control" id="xwpCollo" name="xwpCollo">
                    </div>

                    <div class="mb-3">
                        <label for="cf" class="form-label">Cliente / Customer</label>
                        <input required type="text" class="form-control" id="cf" name="cf">
                    </div>

                    <div class="mb-3">
                        <label for="caliber" class="form-label">Data / Date</label>
                        <input type="datetime-local" class="form-control" id="data" name="date"
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Analysis Time</label>
                        <input required type="number" name="analysis" class="form-control" id="analysis">
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Gr Campione / Gr Sample</label>
                        <input required type="number" name="sample" class="form-control" id="sample">
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Umidità / Moisture</label>
                        <input required type="number" class="form-control" id="moisture" name="moisture">
                    </div>
                    <div class="form-check form-switch">
                        <input name="skin" class="form-check-input" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Pellicine / Skin</label>
                    </div>
                    <br />
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="tastAndSmell" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Sapore E Odore / Taste And
                            Smell</label>
                    </div>
                    <br />
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="colour" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Colore / Colour</label>
                    </div>
                </div>

                <!-- Seconda Colonna -->
                <div class="col-md-6">
                    <h1>Calibratura</h1>
                    <br />
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Gr Campione / Sample</label>
                        <input required type="text" name="sampleCalibratura" class="form-control"
                            id="caliberSample">
                    </div>
                    <div class="mb-3">
                        <div class="container mt-5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Titolo</th>
                                        <th scope="col">Gr</th>
                                        <th scope="col">Percentuale / Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Over Size</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="overSize"
                                                    name="overSize" required>
                                            </div>
                                        </td>
                                        <td id="overSizePercentage"></td>
                                    </tr>
                                    <tr>
                                        <td>>5 / 10></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="calculation"
                                                    name="calculation" required>
                                            </div>
                                        </td>
                                        <td id="calculationPercentage"></td>
                                    </tr>
                                    <tr>
                                        <td>Under Size</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="underSize"
                                                    name="underSize" required>
                                            </div>
                                        </td>
                                        <td id="underSizePercentage"></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="total"
                                                    name="total" required>
                                            </div>
                                        </td>
                                        <td id="totalPercentage"></td>
                                    </tr>
                                    @csrf <input required type="hidden" name="calculationPercentage"
                                        id="calculationPercentageI">
                                    @csrf <input required type="hidden" name="overSizePercentage"
                                        id="overSizePercentageI">
                                    @csrf <input required type="hidden" name="underSizePercentage"
                                        id="underSizePercentageI">
                                    @csrf <input required type="hidden" name="totalPercentage"
                                        id="totalPercentageI">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea name="observations" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                                style="height: 300px"></textarea>
                            <label for="floatingTextarea2">Osservazioni / Observations</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input required type="submit" class="btn btn-primary" value="Salva" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function validateForm() {
            for (var i = 0; i < elementCalculated.length; i++) {
                var currentElement = elementCalculated[i];

                var percentageTd = document.getElementById(currentElement.idFieldinput);
                var percentageInput = document.getElementById(currentElement.idPercentageinput);

                if (!percentageTd.value.trim() || !percentageInput.value.trim()) {
                    alert('Mancano dei campi Obbligatori per procedere.');
                    return false;
                }
            }
            return true;
        }

        var caliberSample = document.getElementById("caliberSample");
        var elementCalculated = [{
                field: document.getElementById("overSize"),
                percentage: document.getElementById("overSizePercentage"),
                idFieldTd: "overSize",
                idFieldinput: "overSizeI",
                idPercentageTd: "overSizePercentage",
                idPercentageinput: "overSizePercentageI",
            },
            {
                field: document.getElementById("underSize"),
                percentage: document.getElementById("underSizePercentage"),
                idFieldTd: "underSize",
                idFieldinput: "underSizeI",
                idPercentageTd: "underSizePercentage",
                idPercentageinput: "underSizePercentageI",
            },
            {
                field: document.getElementById("calculation"),
                percentage: document.getElementById("calculationPercentage"),
                idFieldTd: "calculation",
                idFieldinput: "calculationI",
                idPercentageTd: "calculationPercentage",
                idPercentageinput: "calculationPercentageI",
            },
            {
                field: document.getElementById("total"),
                percentage: document.getElementById("totalPercentage"),
                idFieldTd: "total",
                idFieldinput: "totalI",
                idPercentageTd: "totalPercentage",
                idPercentageinput: "totalPercentageI",
            }
        ];

        elementCalculated.forEach(element => {
            element.field.addEventListener('input', () => elementCalculated.forEach(element => percentageOp(
                element)));
        });

        caliberSample.addEventListener('input', () => elementCalculated.forEach(element => percentageOp(element)));

        function percentageOp(element) {
            const value = Number.parseInt(element.field.value) || 0;
            const caliber = Number.parseInt(caliberSample.value) || 0;
            const calculatedPercentage = (caliber / value).toFixed(2);
            element.percentage.textContent = isFinite(caliber / value) ? calculatedPercentage : 0;
            var tdContent = $('#' + element.idFieldTd).text();
            $('#' + element.idFieldinput).val(tdContent);

            var content = $('#' + element.idPercentageTd).text();
            $('#' + element.idPercentageinput).val(content);
        }

        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });

        $(document).ready(function() {
            $('#customer').change(function() {
                var selectedValue = $(this).val();
                $('#cf').val(selectedValue);
            });
        });
    </script>
</div>
