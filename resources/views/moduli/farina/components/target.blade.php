<div>
    <h1>TARGET</h1>
    <br />
    <div class="mb-3">
        <label for="caliber" class="form-label">Analysis Time</label>
        <input required type="text" name="sampleCalibratura" class="form-control" id="caliberSample"
            aria-describedby="emailHelp">
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
                            {{-- <input type="text" name="colorNatural" id="colorNatural" class="form-control"> --}}
                            <input name="colorNatural" class="form-check-input required" type="checkbox" role="switch"
                                id="colorNatural">
                        </td>
                        <td>
                            <input name="colorRoasted" class="form-check-input required" type="checkbox" role="switch"
                                id="colorRoasted">
                            {{-- <input type="text" name="colorRoasted" id="colorRoasted" class="form-control"> --}}
                        </td>
                    </tr>
                    <tr>
                        <td>taste and smell</td>
                        <td>
                            <input name="tasNatural" class="form-check-input required" type="checkbox" role="switch"
                                id="tasNatural">
                            {{-- <input type="text" name="tasNatural" id="tasNatural" class="form-control"> --}}
                        </td>
                        <td>
                            <input name="tasRoasted" class="form-check-input required" type="checkbox" role="switch"
                                id="tasRoasted">
                            {{-- <input type="text" name="tasRoasted" id="tasRoasted" class="form-control"> --}}
                        </td>
                    </tr>
                    <tr>
                        <td>total</td>
                        <td>

                            <input type="text" name="totalNatural" id="totalNatural" class="form-control">
                        </td>
                        <td>
                            <input name="totalRoasted" class="form-check-input required" type="checkbox" role="switch"
                                id="totalRoasted">
                            <input type="text" name="totalRoasted" id="totalRoasted" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
