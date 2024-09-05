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
    <form action="{{ route('editPostMDPMO', ['idActivity' => $activity->Id_PrBLAttivita, 'id' => $id]) }}" method="POST"
        onsubmit="return validateForm()" class="container mt-5">
        <table class="table table-bordered" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Linea Pentec MD-PMO</th>
                    <th>FE 2,5 mm</th>
                    <th>NON FE 2,5 mm</th>
                    <th>STAINLESS 3.5mm</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Rows will be added here by JavaScript -->
            </tbody>
        </table>
        <button type="button" class="btn btn-primary mt-3" id="aggiungiBtn" onclick="aggiungiRiga()">Aggiungi
            Riga</button>
        <input type="submit" class="btn btn-success mt-3" value="SALVA">
    </form>

    @foreach ($json as $ciao => $j)
        <input type="hidden" id="{{ 'x' . $ciao }}" value="{{ $j }}">
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let jsonArray = @json($json);
        let counter = 0;

        function init() {
            console.log(jsonArray);
            Object.keys(jsonArray).forEach(key => {

                if (key.startsWith('ore')) {

                    const num = key.match(/\d+/)[0];
                    counter++;
                    const newRowHTML = `
                        <tr>
                            <td>
                                <span class="counter">${num}</span>° con. ore <input id="ore${num}" name="ore${num}" type="text" required class="form-control" value="${jsonArray['ore' + num]}">
                                <div class="mb-3">
                                    <label for="lotto${num}" class="form-label">LOTTO</label>
                                    <input type="text" name="lotto${num}" id="lotto${num}" class="form-control" value="${jsonArray['lotto' + num]}">
                                </div>
                            </td>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="hidden" name="fe${num}" value="false">
                                    <input name="fe${num}" type="checkbox" id="fe${num}" class="custom-checkbox form-check-input" value="true" ${jsonArray['fe' + num] === 'true' ? 'checked' : ''}>
                                </div>
                            </td>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="hidden" name="nofe${num}" value="false">
                                    <input name="nofe${num}" type="checkbox" id="nofe${num}" class="custom-checkbox form-check-input" value="true" ${jsonArray['nofe' + num] === 'true' ? 'checked' : ''}>
                                </div>
                            </td>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="hidden" name="stainless${num}" value="false">
                                    <input name="stainless${num}" type="checkbox" id="stainless${num}" class="custom-checkbox form-check-input" value="true" ${jsonArray['stainless' + num] === 'true' ? 'checked' : ''}>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#tableBody').append(newRowHTML);
                }
            });
        }

        function aggiungiRiga() {
            counter++;
            const newRowHTML = `
                <tr>
                    <td>
                          <div class="mb-3">
                                     <span class="counter">${counter}</span>° con. ore <input name="ore${counter}" id="ore${counter}" type="text" required class="form-control">
                                    <label for="lotto${counter}" class="form-label">LOTTO</label>
                                    <input type="text" name="lotto${counter}" id="lotto${counter}" class="form-control">
                                </div>
                        @csrf
                        <input required type="hidden" name="xwp${counter}" class="xwp form-control" id="xwp${counter}">
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="fe${counter}" value="false">
                            <input name="fe${counter}" type="checkbox" id="fe${counter}" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="nofe${counter}" value="false">
                            <input name="nofe${counter}" type="checkbox" id="nofe${counter}" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center">
                            <input type="hidden" name="stainless${counter}" value="false">
                            <input name="stainless${counter}" type="checkbox" id="stainless${counter}" class="custom-checkbox form-check-input" value="true">
                        </div>
                    </td>
                </tr>
            `;
            $('#tableBody').append(newRowHTML);
        }

        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>

</html>
