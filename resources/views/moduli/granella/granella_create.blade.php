@include('backend.common.header')
@include('moduli.components.header') 



 

<div class="content-wrapper p-3">

    <form action="{{ route('createPostGranella', ['id' => $attivity->Id_PrBLAttivita]) }}" method="POST" onsubmit="return validateForm()">
        <div class="container">
            <div class="row">
                <!-- Prima Colonna -->
                <div class="col-md-6">
                    <h1> Dati </h1>
                    <div class="mb-3">
                        <label class="form-label">Varieta / Variery</label>
                        <input required type="text" class="form-control" id="variety" name="variety" required>
                    </div>
                    <div class="mb-3">
                        <label for="calibre" class="form-label">Calibro / Caliber</label>
                        <input required type="text" class="form-control" id="calibre" name="calibre"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="kg" class="form-label">Kg Totale Ordine / Total Kg Order</label>
                        <input required type="text" class="form-control" id="kg" name="kg" aria-describedby="emailHelp">
                    </div>
                    {{-- <div class="mb-3">
                        <label for="customer" class="form-label">Cliente</label>
                        <select class="selectpicker" data-live-search="true" id="customer">
                            <option>NESSUN CLIENTE</option>
                            <!-- Aggiungi altre opzioni qui -->
                        </select>
                    </div>
                    @csrf
                    <input required type="hidden" name="cf" id="cf"> --}}
                    @include('moduli.components.xwpcollo_select', ['attivita' => $attivity])
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Data / Date</label>
                        <input required type="date" name="date" class="form-control" id="date"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">ANALYSIS TIME</label>
                        <input required type="number" name="analysis" class="form-control" id="analysis"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">gr campione / sample</label>
                        <input required type="number" name="sample" class="form-control" id="sample"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="caliber" class="form-label">Umidità / Moisture</label>
                        <input required type="number" class="form-control" id="moisture" name="moisture"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="form-check form-switch">
                        <input required name="skin" class="form-check-input required" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">SKIN</label>
                    </div>
                    <br />
                    <div class="form-check form-switch">
                        <input required name="tastAndSmell" class="form-check-input required" type="checkbox"
                            role="switch" id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Taste and smell </label>
                    </div>
                    <br />
                    <div class="form-check form-switch">
                        <input required name="colour" class="form-check-input required" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Colour</label>
                    </div>
                </div>

                <!-- Seconda Colonna -->
                <div class="col-md-6">
                    <h1> Calibratura </h1>
                    <br />
                    <div class="mb-3">
                        <label for="caliber" class="form-label">gr campione / sample</label>
                        <input required type="text" name="sampleCalibratura" class="form-control" id="caliberSample"
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
                                        <td>over size</td>
                                        <td id="overSize" contenteditable="true"></td>
                                        <td id="overSizePercentage"></td>

                                        @csrf
                                        <input required type="hidden" name="overSize" id="overSizeI">

                                        @csrf
                                        <input required type="hidden" name="overSizePercentage"
                                            id="overSizePercentageI">
                                    </tr>
                                    <tr>
                                        <td>>5/10></td>
                                        <td id="calculation" contenteditable="true"></td>
                                        <td id="calculationPercentage"></td>

                                        @csrf
                                        <input required type="hidden" name="calculation" id="calculationSizeI">

                                        @csrf
                                        <input required type="hidden" name="calculationPercentage"
                                            id="calculationPercentageI">

                                        <!-- 
                                        @csrf
                                        <input required type="hidden" name="calculation"  id="cf">

                                        @csrf
                                        <input required type="hidden" name="calculationPercentage"  id="cf"> -->
                                    </tr>
                                    <tr>
                                        <td>under size</td>
                                        <td id="underSize" contenteditable="true"></td>
                                        <td id="underSizePercentage"></td>

                                        @csrf
                                        <input required type="hidden" name="underSize" id="underSizeI" value="">

                                        @csrf
                                        <input required type="hidden" name="underSizePercentage"
                                            id="underSizePercentageI">
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td id="total" contenteditable="true"></td>
                                        <td id="totalPercentage"></td>

                                        @csrf
                                        <input required type="hidden" name="total" id="totalI">

                                        @csrf
                                        <input required type="hidden" name="totalPercentage" id="totalPercentageI">
                                    </tr>
                                    <!-- Aggiungi altre righe secondo le tue esigenze -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea name="observations" class="form-control" placeholder="Leave a comment here"
                                id="floatingTextarea2" style="height: 300px"></textarea>
                            <label for="floatingTextarea2">osservazioni / observations</label>
                        </div>
                    </div>
                    <div class="mb-3">
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
        


        var caliberSample = document.getElementById("caliberSample");
        var elementCalculated = [
            {
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
            element.field.addEventListener('input',
                () => elementCalculated.forEach(element => percentageOp(element)
                ));
        });

        caliberSample.addEventListener('input', () =>
            elementCalculated.forEach(element => percentageOp(element)));

        function percentageOp(element) {
            const value = Number.parseInt(element.field.textContent) || 0;
            const caliber = Number.parseInt(caliberSample.value) || 0;
            const calculatedPercentage = (caliber / value).toFixed(2);
            element.percentage.textContent = isFinite(caliber / value) ? (caliber / value).toFixed(2) : 0;
            var tdContent = $('#' + element.idFieldTd).text();
            $('#' + element.idFieldinput).val(tdContent);
 
            var content = $('#' + element.idPercentageTd).text();
            $('#' + element.idPercentageinput).val(content);
        }

        // document.addEventListener('DOMContentLoaded', function () {
        //     axios.get('/CF')
        //         .then(function (response) {

        //             var selectCliente = document.getElementById('customer');

        //             response.data.data.forEach(function (cliente) {
        //                 var option = document.createElement('option');
        //                 option.text = cliente.Descrizione;
        //                 option.value = cliente.Cd_CF;
        //                 selectCliente.add(option);
        //             });

        //             console.log(response.data.data);
        //             $(selectCliente).selectpicker('refresh');
        //         })
        //         .catch(function (error) {
        //             console.error('Errore nella richiesta Axios', error);
        //         });
        // });

        $(document).ready(function () {
            $('.selectpicker').selectpicker();

        });

        $(document).ready(function () {
            $('#customer').change(function () {
                // Aggiorna il valore del campo di input required nascosto
                var selectedValue = $(this).val();
                console.log('Valore selezionato:', selectedValue);
                $('#cf').val(selectedValue);
            });
        });

        // document.getElementById('customer').addEventListener('input required', function () {
        //     // Ottieni il valore digitato nel campo di ricerca
        //     var searchTerm = this.value;

        //     // Effettua la chiamata HTTP con Axios
        //     axios.get('/CF', {
        //         params: {
        //             q: searchTerm
        //         }
        //     })
        //         .then(function (response) {
        //             // Aggiorna le opzioni del dropdown con i risultati della ricerca
        //             var customerSelect = document.getElementById('customer');
        //             customerSelect.innerHTML = '<option>NESSUN CLIENTE</option>'; // Opzione predefinita

        //             response.data.forEach(function (cliente) {
        //                 var option = document.createElement('option');
        //                 option.text = cliente.Descrizione;
        //                 option.value = cliente.Cd_CF;
        //                 customerSelect.add(option);
        //             });

        //             // Aggiorna il selettore Bootstrap Selectpicker dopo la modifica delle opzioni
        //             $('.selectpicker').selectpicker('refresh');
        //         })
        //         .catch(function (error) {
        //             console.error('Errore nella richiesta Axios', error);
        //         });
        // });
    </script>
</div>