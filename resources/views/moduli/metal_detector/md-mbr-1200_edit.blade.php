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
            max-width: 800px;
            margin: 50px auto;
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
<form action="{{ route('editPostMBR1200', ['idActivity' => $activity->Id_PrBLAttivita, 'id'=>$id]) }}"
      method="POST" onsubmit="return validateForm()" class="container mt-5">
    <table class="table table-bordered" id="myTable">
        <thead class="table-dark">
        <tr>
            <th scope="col">Linea Pentec MBR-1200</th>
            <th>FE 2,5 mm</th>
            <th>NON FE 2,5 mm</th>
            <th>STAINLESS 3.5mm</th>
        </thead>
        <tbody>
        <tr id="referenceRow">
            <td>
                <span class="counter">1</span>° con. ore <input name="ore1" id="ore1" value="{{$json->ore1}}"
                                                                type="text"
                                                                required
                                                                class="form-control">

                <div class="mb-3">
                    <label for="xwpCollo" class="form-label">LOTTO</label>
                    <select class="form-select selectpicker" data-live-search="true" id="xwpCollo"

                            name="lotto1" required>
                        <option value="" disabled selected>Seleziona un lotto</option>
                    </select>
                </div>
                @csrf
                <input required type="hidden" name="xwp1" class="xwp form-control" id="xwp1">
            </td>
            <td>
                <div class="form-check d-flex justify-content-center">
                    <input type="hidden" name="fe1" value="false">
                    <input name="fe1" type="checkbox" id="fe1" class="custom-checkbox form-check-input">
                </div>
            </td>
            <td>
                <div class="form-check d-flex justify-content-center">
                    <input type="hidden" name="nofe1" value="false">
                    <input name="nofe1" type="checkbox" id="nofe1"
                           class="custom-checkbox form-check-input">
                </div>
            </td>
            <td>
                <div class="form-check d-flex justify-content-center">
                    <input type="hidden" name="stainless1" value="false">
                    <input name="stainless1" type="checkbox" id="stainless1"
                           class="custom-checkbox form-check-input">
                </div>
            </td>

        </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary mt-3" id="aggiungiBtn" onclick="aggiungiRiga()">Aggiungi Riga</button>
    <input type="submit" class="btn btn-success mt-3" value="SALVA">
</form>


@foreach($json as  $ciao => $j )
    <input type="hidden" id="{{'x'.$ciao}}" value="{{$j}}">
@endforeach

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var counter = 1;

    let options = [];

    <?php
    $array = $json;
    $risultati = array();
    foreach ($array as $valore => $val) {
        if (strpos($valore, 'lotto') !== false) {
            $risultati[] = $valore;
        }
    }
    $size = sizeof($risultati);
    ?>

    document.addEventListener('DOMContentLoaded', function () {

        axios.get('/XWPCollo/{{$activity->Id_PrBLAttivita}}')
            .then(function (response) {
                var xwpCollo = document.getElementById('xwpCollo');
                response.data.forEach(function (collo) {
                    var option = document.createElement('option');
                    option.text = `${collo.Cd_AR} - ${collo.xLotto}`;
                    option.value = `${collo.Cd_AR} - ${collo.xLotto}`;
                    xwpCollo.add(option);
                });
                options = response.data;
                // $(xwpCollo).selectpicker('refresh');

                document.getElementById(`stainless1`).checked = (document.getElementById(`xstainless1`).value === "true") ? true : false;
                document.getElementById(`nofe1`).checked = (document.getElementById(`xnofe1`).value === "on") ? true : false;
                document.getElementById(`fe1`).checked = (document.getElementById(`xfe1`).value === "on") ? true : false;


                element = document.getElementById(`xwpCollo`);
                for (val in element) {
                    if (element[val])
                        if (element[val].value === document.getElementById(`xlotto1`).value)
                            element[val].selected = true;
                }

                init({{$size}});
            })
            .catch(function (error) {
                console.error('Errore nella richiesta Axios', error);
            });
    });

    $(document).ready(function () {
        $('#xwpCollo').change(function () {
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

    function init(size) {
        counter = 2;
        while (counter <= size) {
            var newRowHTML = `
                <tr>
                    <td>
                        <span class="counter">${counter}</span>° con. ore <input id="ore${counter}" name="ore${counter}" type="text"
                            required class="form-control">
                        <div class="mb-3">
                            <label for="xwpCollo" class="form-label">LOTTO</label>
                            <select class="form-select selectpicker" data-live-search="true"
                                 name="lotto${counter}" id="lotto${counter}" required>
                                <option value="" disabled selected>Seleziona un lotto</option>
                                ${getAjaxOptions()}
                            </select>
                        </div>
                        @csrf
            <input required type="hidden" name="xwp${counter}" class="xwp form-control" id="xwp${counter}">
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe${counter}" value="false">
                            <input name="fe${counter}" type="checkbox" id="fe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe${counter}" value="false">
                            <input name="nofe${counter}" type="checkbox" id="nofe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless${counter}" value="false">
                            <input name="stainless${counter}" type="checkbox" id="stainless${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>


                </tr>
            `;

            $('#myTable tbody').append(newRowHTML);

            if (document.getElementById(`xstainless${counter}`))
                document.getElementById(`stainless${counter}`).checked = (document.getElementById(`xstainless${counter}`).value === "true") ? true : false;
            else
                document.getElementById(`stainless${counter}`).checked = false;

            if (document.getElementById(`xnofe${counter}`))
                document.getElementById(`nofe${counter}`).checked = (document.getElementById(`xnofe${counter}`).value === "true") ? true : false;
            else
                document.getElementById(`nofe${counter}`).checked = false;

            if (document.getElementById(`xfe${counter}`))
                document.getElementById(`fe${counter}`).checked = (document.getElementById(`xfe${counter}`).value === "true") ? true : false;
            else
                document.getElementById(`fe${counter}`).checked = false;

            document.getElementById(`ore${counter}`).value = document.getElementById(`xore${counter}`).value;

            element = document.getElementById(`lotto${counter}`);
            for (val in element) {
                if (element[val])
                    if (element[val].value === document.getElementById(`xlotto${counter}`).value)
                        element[val].selected = true;
            }

            counter++;
        }
    }

    function aggiungiRiga() {
        counter++;

        var newRowHTML = `
                <tr>
                    <td>
                        <span class="counter">${counter}</span>° con. ore <input name="ore${counter}" id="ore${counter}" type="text"
                            required class="form-control">
                        <div class="mb-3">
                            <label for="xwpCollo" class="form-label">LOTTO</label>
                            <select class="form-select selectpicker" data-live-search="true"
                                name="lotto${counter}" id="lotto${counter}" required>
                                <option value="" disabled selected>Seleziona un lotto</option>
                                ${getAjaxOptions()}
                            </select>
                        </div>
                        @csrf
        <input required type="hidden" name="xwp${counter}" class="xwp form-control" id="xwp${counter}">
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe${counter}" value="false">
                            <input name="fe${counter}" type="checkbox" id="fe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe${counter}" value="false">
                            <input name="nofe${counter}" type="checkbox" id="nofe${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless${counter}" value="false">
                            <input name="stainless${counter}" type="checkbox" id="stainless${counter}"
                                class="custom-checkbox form-check-input" value="true">
                         </div>
                    </td>


                </tr>
            `;

        $('#myTable tbody').append(newRowHTML);
    }

    function getAjaxOptions() {
        // Funzione per ottenere le opzioni da Ajax e restituire una stringa HTML
        var ajaxOptions = '';

        options.forEach(function (collo) {
            ajaxOptions += `<option value="${collo.Cd_AR} - ${collo.xLotto}">${collo.Cd_AR} - ${collo.xLotto}</option>`;
        });

        return ajaxOptions;
    }
</script>
</body>

</html>
