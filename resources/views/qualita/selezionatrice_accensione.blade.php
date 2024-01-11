 
@include('qualita.components.header', [
            "title" => "Monitoraggio efficienza Sea Hypersort",
            "code" => "M_IO 17_1",
            "review" => 0,
        ]);  


        <table class="table">
            <thead>
                <tr>
                    <th style="font-size: 12px" scope="row">VERIFICA EFFETTUATA DA</th>
                    <th style="font-size: 12px" scope="row">GIORNO</th>
                    <th style="font-size: 12px" scope="row">MESE</th>
                    <th style="font-size: 12px" scope="row">ANNO</th>
                    <th style="font-size: 12px" scope="row">ORA INIZIO</th>
                    <th style="font-size: 12px" scope="row">ORA FINE</th>
                    <th style="font-size: 12px" scope="row">LOTTO</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row">[V_EFF]</td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                    <td scope="row">[ORA INIZIO]</td>
                    <td scope="row">[ORA FINE]</td>
                    <td scope="row">[LOTTO]</td>
                </tr>
            </tbody>
        </table>

        <table class="table">

            <thead>
                <tr>
                    <th style="font-size: 12px" scope="row">AREA PRODUZIONE</th>


                </tr>
                <tr>
                    <th style="font-size: 12px" scope="row">Funzionamento SEA HYPERSORT: </th>
                    <th style="font-size: 12px" scope="row">C</th>
                    <th style="font-size: 12px" scope="row">NC</th>
                    <th style="font-size: 12px" scope="row">DESCRIZIONE NC / NOTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row">Accensione della macchina</td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
                <tr>
                    <td scope="row"> Assenza di allarmi o visivi </td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
                <tr>
                    <td scope="row"> “Ricetta” corretta su display
                    </td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
                <tr>
                    <td scope="row"> Assenza acqua in filtro dell’aria
                    </td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
                <tr>
                    <td scope="row"> Controllo Elettrovalvole a buon fine
                    </td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
                <tr>
                    <td scope="row"> Controllo avvenuta calibrazione

                    </td>
                    <td scope="row">[GIORNO]</td>
                    <td scope="row">[MESE]</td>
                    <td scope="row">[ANNO]</td>
                </tr>
            </tbody>
        </table>



        <table class="table">
            <thead>
                <tr>
                    <td scope="row"> NOTE E INDICAZIONI PER RISOLUZIONE EVENTUALI NC RILEVATE </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row"></td>
                </tr>
            </tbody>
        </table>


        <table class="table">
            <tbody>
                <tr>
                    <th style="font-size: 12px" scope="row">FIRMA CQ</th>
                    <td>[SKIN_C_1]</td>
                </tr>
            </tbody>
        </table>

        <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <td>Redatto da</td>
                    <td>Verificato</td>
                    <td>Approvato da</td>
                  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Redatto da</td>
                    <td>Verificato</td>
                    <td>Approvato da</td>
                </tr>
            </tbody>
        </table>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</body>

<script></script>

</html>