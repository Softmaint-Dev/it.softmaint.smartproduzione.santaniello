<div>
    <h1>Calibratura</h1>
    <br />
    <div class="mb-3">
        <label for="caliber" class="form-label">gr campione / sample</label>
        <input required oninput="updateCalculations()"  type="text"  name="sampleCalibratura" class="form-control" id="caliberSample"
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
                        <td><input type="text" name="value1" id="value1" class="form-control" oninput="updateCalculations()"></td>
                        <td><input type="text" name="value1Percentage" id="value1Percentage" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td>>1</td>
                        <td><input type="text" name="value2" id="value2" class="form-control" oninput="updateCalculations()"></td>
                        <td><input type="text" name="value2Percentage" id="value2Percentage" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td>&lt;1</td>
                        <td><input type="text" name="value3" id="value3" class="form-control" oninput="updateCalculations()"></td>
                        <td><input type="text" name="value3Percentage" id="value3Percentage" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td>total</td>
                        <td><input type="text" name="valueTot" id="valueTot" class="form-control" readonly></td>
                        <td><input type="text" name="valueTotPercentage" id="valueTotPercentage" class="form-control" readonly></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

 
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
        document.getElementById('valueTotPercentage').value = (!isNaN(valueTotPercentage) && valueTotPercentage !== 0) ? valueTotPercentage : 0;
    }
</script>


 
