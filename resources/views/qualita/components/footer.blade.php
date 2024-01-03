<!-- <button type="button" class="btn btn-primary btn-lg">Large button</button> -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

<script>
    window.onbeforeprint = function convertiInParagrafo() {

        var inputTesto = document.querySelectorAll('input[type="text"]');
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        // Itera attraverso gli input e li sostituisce con paragrafi
        inputTesto.forEach(function(input) {
            // Crea un nuovo elemento paragrafo
            var paragrafo = document.createElement('p');
            paragrafo.className = "m-2";
            // Copia il valore dell'input nel testo del paragrafo
            paragrafo.textContent = input.value;
            
            // Sostituisci l'input con il paragrafo
            input.parentNode.replaceChild(paragrafo, input);

        });

        checkboxes.forEach(function(input) {
            var paragrafo = document.createElement('p');

            // Aggiungi testo al paragrafo in base allo stato della checkbox
            paragrafo.textContent = input.checked ? 'X' : '';
            input.parentNode.replaceChild(paragrafo, input);

        });

        const domString = new XMLSerializer().serializeToString(document);

        const url = '/generate-and-save-pdf';


        const axiosConfig = {
            method: 'POST',
            url: url,
            headers: {
                'Content-Type': 'text/html', // Tipo di contenuto del corpo della richiesta
            },
            data: domString, // Stringa rappresentante l'intero DOM
        };

        // Effettua la richiesta Axios
        axios(axiosConfig)
            .then(response => {
                console.log('Risposta dal server:', response.data);
            })
            .catch(error => {
                console.error('Errore nella richiesta:', error);
            });

    }; 
</script>

</html>