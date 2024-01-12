@include('backend.common.header')
@include('backend.common.sidebar')

 <style>

 </style>
<div class="table-scrollable">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id Calibratura</th>
                <th>Varietà / Variety</th>
                <th>Lotto / Batch</th>
                <th>Calibro / Caliber</th>
                <th>Cliente / Customer</th>
                <th>Kg Totale Ordine/ Total Kg Order</th>
                <th>Data / Date</th>
                <th>ANALYSIS TIME</th>
                <th>gr campione / sample</th>
                <th>Umidità/Moisture</th>
                <th>Skin</th> 
                <th>Taste and smell</th>
                <th>Colour</th>
                <th>gr campione / sample Calibratura</th>
                <th>over size</th>
                <th>>5/10></th>
                <th>under size</th>
                <th>total </th>
                <th>osservazioni / observations</th>
            </tr>
        </thead>
        <tbody>
            <!-- Itera attraverso i dati e mostra ciascuna riga della tabella -->
            @foreach($calibrature as $calibratura)
                <tr>
                    {{-- <td>{{ $utente-> }}</td>
                    <td>{{ $utente->Variety }}</td>
                    <td>{{ $utente->Cd_ARLotto }}</td> --}}
                    <td>
                        <!-- Aggiungi eventuali azioni come bottoni o collegamenti -->
                        <a href="{{ route('utenti.edit', $utente->id) }}" class="btn btn-primary btn-sm">Modifica</a>
                        <a href="{{ route('utenti.destroy', $utente->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo utente?')">Elimina</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
     </table>
    </div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@include('backend.common.footer')