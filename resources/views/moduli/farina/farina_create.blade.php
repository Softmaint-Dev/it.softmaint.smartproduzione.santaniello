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

    <form action="{{ route('createPostFarina', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST"
        onsubmit="return validateForm()">
        <div class="container">
            <div class="row mb-4">
                <!-- Prima Colonna -->
                <div class="col-md-4">
                    <h2 class="mb-4">Dati</h2>

                    <div class="mb-3">
                        <label for="variety" class="form-label">Varietà / Variety</label>
                        <input required type="text" class="form-control" id="variety" name="variety">
                    </div>

                    <div class="mb-3">
                        <label for="caliber" class="form-label">Calibro / Caliber</label>
                        <input required type="text" class="form-control" id="caliber" name="caliber">
                    </div>

                    <div class="mb-3">
                        <label for="wpCollo" class="form-label">Lotto Produzione / Production Batch</label>
                        <input required type="text" class="form-control" id="wpCollo" name="wpCollo">
                    </div>

                    <div class="mb-3">
                        <label for="cf" class="form-label">Cliente / Customer</label>
                        <input required type="text" class="form-control" id="cf" name="cf">
                    </div>

                    <div class="mb-3">
                        <label for="kg" class="form-label">KG Totali / Total KG</label>
                        <input required type="text" class="form-control" id="kg" name="kg">
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Data / Date</label>
                        <input required type="date" class="form-control" id="date" name="date">
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Orario / Time</label>
                        <input required type="time" class="form-control" id="time" name="time">
                    </div>
                </div>

                <div class="col-md-8">
                    <h2 class="mb-4">Target</h2>

                    <div class="mb-3">
                        <label for="grCampione" class="form-label">gr Campione / gr Sample</label>
                        <input required type="text" class="form-control" id="grCampione" name="grCampione"
                            oninput="calculatePercentages()">
                    </div>

                    <div class="mb-3">
                        <label for="moisture" class="form-label">Umidità / Moisture</label>
                        <input required type="text" class="form-control" id="moisture" name="moisture">
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
                                            oninput="calculatePercentages()"></td>
                                    <td><input type="text" id="colorePercentage" name="colorePercentage"
                                            class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <td>Sapore / Taste</td>
                                    <td><input type="text" id="sapore" name="sapore" class="form-control"
                                            oninput="calculatePercentages()"></td>
                                    <td><input type="text" id="saporePercentage" name="saporePercentage"
                                            class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <td><strong>Totale Percentuale / Total Percentage</strong></td>
                                    <td></td>
                                    <td><input type="text" id="totalPercentage" name="totalPercentage"
                                            class="form-control" readonly></td>
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
                                            oninput="calculateMealPercentages()"></td>
                                    <td><input type="text" id="farinaPercentage" name="farinaPercentage"
                                            class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <td>Granella / Chopped</td>
                                    <td><input type="text" id="granella" name="granella" class="form-control"
                                            oninput="calculateMealPercentages()"></td>
                                    <td><input type="text" id="granellaPercentage" name="granellaPercentage"
                                            class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <td><strong>Totale Percentuale / Total Percentage</strong></td>
                                    <td></td>
                                    <td><input type="text" id="totalMealPercentage" name="totalMealPercentage"
                                            class="form-control" readonly></td>
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
</script>
