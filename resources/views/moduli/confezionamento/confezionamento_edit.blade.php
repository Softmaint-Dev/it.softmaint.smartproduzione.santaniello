<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabella Bootstrap Laravel</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
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
    <form action="{{ route('editPostConfezionamento', ['idActivity' => $attivity->Id_PrBLAttivita, 'id' => $id]) }}"
        method="POST" class="container mt-3">
        <table class="table table-bordered" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th style="width: 150px;">DATA</th>
                    <th>PRODOTTO FINITO</th>
                    <th>KG PRODOTTO FINITO</th>
                    <th>LOTTO DI PRODUZIONE</th>
                    <th>LOTTO PACKAGING</th>
                    <th>VERIFICHE AVVIO CONFEZIONAMENTO</th>
                    <th>VERIFICHE DURANTE CONFEZIONAMENTO</th>
                    <th>VERIFICHE FINE CONFEZIONAMENTO</th>
                    <th>VERIFICA IDONEITA’ PALLET ****</th>
            </thead>
            <tbody>
                <tr id="referenceRow">
                    {{-- <td>
            <input required type="text" class="form-control" id="data1" name="data1" required>
        </td>
        <td>
            <input required type="text" class="form-control" id="finito1" name="finito1" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="kgfinito1" name="kgfinito1" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="produzione1" name="produzione1" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="packaging1" name="packaging1" required>

        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="avvio1" value="false">
              <input name="avvio1" type="checkbox" id="avvio1" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="durante1" value="false">
              <input name="durante1" type="checkbox" id="durante1" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="fine1" value="false">
              <input name="fine1" type="checkbox" id="fine1" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="pallet1" value="false">
              <input name="pallet1" type="checkbox" id="pallet1" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td> --}}

                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary mt-3" id="aggiungiBtn" onclick="aggiungiRiga()">Aggiungi
            Riga</button>
        <input type="submit" class="btn btn-success mt-3" value="SALVA">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var counter = 1;

        let options = [];

        //     dd($json);

        //     $array = $json;
        //     $risultati = array();
        //     foreach ($array as $valore => $val) {
        //         if (strpos($valore, 'lotto') !== false) {
        //             $risultati[] = $valore;
        //         }
        //     }
        //     $size = sizeof($risultati);
        // ?>
        document.addEventListener('DOMContentLoaded', function() {



        });
        init();
        $(document).ready(function() {
            $('#xwpCollo').change(function() {
                var selectedValue = $(this).val();
                console.log('Valore selezionato:', selectedValue);

                if (selectedValue === 'NESSUN LOTTO') {
                    alert('Seleziona un lotto valido.');
                    $(this).val('');
                    $('#xwp').val('');
                } else {
                    $('#xwp').val(selectedValue);
                }
            });
        });

        function aggiungiRiga() {
            counter++;
            var newRowHTML = `
        <tr id="referenceRow">
        <td>
            <input required type="datetime-local" class="form-control" id="data${counter}" name="data${counter}" required>
 
        </td>
        <td>
            <input required type="text" class="form-control" id="finito${counter}" name="finito${counter}" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="kgfinito${counter}" name="kgfinito${counter}" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="produzione${counter}" name="produzione${counter}" required>

        </td>
        <td>
            <input required type="text" class="form-control" id="packaging${counter}" name="packaging${counter}" required>

        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="avvio${counter}" value="false" />
              <input name="avvio${counter}" type="checkbox" id="avvio${counter}" class="custom-checkbox form-check-input"
                   value="true" />
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="durante${counter}" value="false">
              <input name="durante${counter}" type="checkbox" id="durante${counter}" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="fine${counter}" value="false">
              <input name="fine${counter}" type="checkbox" id="fine${counter}" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
        <td>
            <div class="form-check d-flex justify-content-center">
              <input type="hidden" name="pallet${counter}" value="false">
              <input name="pallet${counter}" type="checkbox" id="pallet${counter}" class="custom-checkbox form-check-input"
                   value="true">
            </div>
        </td>
      </tr>
        `;
            // var newRowHTML = `
        //         <tr>
        //             <td>
        //                 <span class="counter">${counter}</span>° con. ore <input name="ore${counter}" id="ore${counter}" type="text"
        //                     required class="form-control">
        //                 <div class="mb-3">
        //                     <label for="xwpCollo" class="form-label">LOTTO</label>
        //                     <select class="form-select selectpicker" data-live-search="true"
        //                         name="lotto${counter}" id="lotto${counter}"  required>
        //                         <option value="" disabled selected>Seleziona un lotto</option>
        //                         ${getAjaxOptions()}
        //                     </select>
        //                 </div>
        //                 @csrf
        // <input required type="hidden" name="xwp${counter}" class="xwp form-control" id="xwp${counter}">
        //             </td>
        //             <td>
        //                 <div class="form-check d-flex justify-content-center">
        //                     <input type="hidden" name="fe${counter}" value="false">
        //                     <input name="fe${counter}" type="checkbox" id="fe${counter}"
        //                         class="custom-checkbox form-check-input" value="true">
        //                  </div>
        //             </td>
        //             <td>
        //                 <div class="form-check d-flex justify-content-center">
        //                     <input type="hidden" name="nofe${counter}" value="false">
        //                     <input name="nofe${counter}" type="checkbox" id="nofe${counter}"
        //                         class="custom-checkbox form-check-input" value="true">
        //                  </div>
        //             </td>
        //             <td>
        //                 <div class="form-check d-flex justify-content-center">
        //                     <input type="hidden" name="stainless${counter}" value="false">
        //                     <input name="stainless${counter}" type="checkbox" id="stainless${counter}"
        //                         class="custom-checkbox form-check-input" value="true">
        //                  </div>
        //             </td>


        //         </tr>
        //     `;

            $('#myTable tbody').append(newRowHTML);
        }

        function getAjaxOptions() {
            // Funzione per ottenere le opzioni da Ajax e restituire una stringa HTML
            var ajaxOptions = '';

            options.forEach(function(collo) {
                ajaxOptions +=
                    `<option value="${collo.Cd_AR} - ${collo.xLotto}">${collo.Cd_AR} - ${collo.xLotto}</option>`;
            });

            return ajaxOptions;
        }

        function init() {


            var result = <?php echo json_encode($json); ?>;
            console.log(result);
            for (var key in result) {
                if (result.hasOwnProperty(key)) {
                    var item = result[key];
                    var counter = key;
                    var avvio = item.avvio == 'true';
                    var durante = item.durante == 'true';
                    var fine = item.fine == 'true';
                    var pallet = item.pallet == 'true';
                    var newRowHTML = `
<tr id="referenceRow">
<td>
<input required type="datetime-local" class="form-control" value=${item.data} id="data${counter}" name="data${counter}" required>
</td>
<td>
<input required type="text" class="form-control" value=${item.finito} id="finito${counter}" name="finito${counter}" required>
</td>
<td>
<input required type="text" class="form-control" value=${item.kgfinito} id="kgfinito${counter}" name="kgfinito${counter}" required>
</td>
<td>
<input required type="text" class="form-control" value=${ item.produzione} id="produzione${counter}" name="produzione${counter}" required>
</td>
<td>
<input required type="text" class="form-control" value=${item.packaging} id="packaging${counter}" name="packaging${counter}" required>
</td>
<td>
<div class="form-check d-flex justify-content-center">
<input type="hidden" id="avvio${counter}"  name="avvio${counter}"  />
<input name="avvio${counter}"  value="${avvio}"  type="checkbox" id="avvio${counter}" class="custom-checkbox form-check-input" ${avvio ? 'checked' : ''} />
</div>
</td>
<td>
<div class="form-check d-flex justify-content-center">
<input type="hidden"  name="durante${counter}">
<input name="durante${counter}" type="checkbox" id="durante${counter}" class="custom-checkbox form-check-input" ${durante ? 'checked' : ''} 
 >
</div>
</td>
<td>
<div class="form-check d-flex justify-content-center">
<input type="hidden"   name="fine${counter}" >
<input name="fine${counter}"  type="checkbox" id="fine${counter}" class="custom-checkbox form-check-input"
${fine ? 'checked' : ''}   >
</div>
</td>
<td>
<div class="form-check d-flex justify-content-center">
<input type="hidden"  name="pallet${counter}"/>
<input name="pallet${counter}"   type="checkbox" id="pallet${counter}" class="custom-checkbox form-check-input"
${pallet ? 'checked' : ''} />
</div>
</td>
</tr>
`;
                    $('#myTable tbody').append(newRowHTML);
                }
            }

        }
    </script>
</body>

</html>
