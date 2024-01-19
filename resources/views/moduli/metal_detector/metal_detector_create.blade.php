<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabella Bootstrap Laravel</title>
</head>
<body>

<div class="container mt-5">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Linea X-RAY XBR-6000</th>
            <th scope="col">1° con. ore________</th>
            <th scope="col">Lotto __________</th>
        </tr>
        </thead>
        <tbody>
        @for($i = 1; $i <= 6; $i++)
            <tr>
                <td></td>
                <td><input type="text" class="form-control" placeholder="Inserisci valore"></td>
                <td><input type="text" class="form-control" placeholder="Inserisci valore"></td>
            </tr>
            <tr>
                <td>{{ $i }}° con. ore________</td>
                <td><input type="text" class="form-control" placeholder="Inserisci valore"></td>
                <td><input type="text" class="form-control" placeholder="Inserisci valore"></td>
            </tr>
            <tr>
                <td>FE 1,5 mm</td>
                <td><input type="checkbox"> Conforme</td>
                <td><input type="checkbox"> Non Conforme</td>
            </tr>
            <tr>
                <td>NON FE 1,5 mm</td>
                <td><input type="checkbox"> Conforme</td>
                <td><input type="checkbox"> Non Conforme</td>
            </tr>
            <tr>
                <td>STAINLESS 1,8mm</td>
                <td><input type="checkbox"> Conforme</td>
                <td><input type="checkbox"> Non Conforme</td>
            </tr>
            <tr>
                <td>CRYSTAL GLASS 3,0 mm</td>
                <td><input type="checkbox"> Conforme</td>
                <td><input type="checkbox"> Non Conforme</td>
            </tr>
            <tr>
                <td>CERAMIC 8,0 mm</td>
                <td><input type="checkbox"> Conforme</td>
                <td><input type="checkbox"> Non Conforme</td>
            </tr>
        @endfor
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
