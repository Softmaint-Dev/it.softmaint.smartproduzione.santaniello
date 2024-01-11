<button type="button" class="btn btn-primary btn-lg">Large button</button>


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
        const regex = /<main\b[^>]*>([\s\S]*?)<\/main>/i;
 
        const match = domString.match(regex);
        var risultato = "<h1> vuoto </h1>"
        if (match) {
             risultato = match[1];
         } else {
            console.log('Tag <main> non trovato o vuoto.');
        }
       

         
        const url = '/generate-and-save-pdf';

        const fetchOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'text/html',
            },
            body: risultato,
        };

          fetch(url, fetchOptions)
             .then(response => {
                 if (!response.ok) {
                     throw new Error(`Errore nella risposta del server: ${response.statusText}`);
                 }
                 return response.text();
             })
             .then(data => {
                 console.log('Risposta dal server:', data);
             })
             .catch(error => {
                 console.error('Errore nella richiesta:', error.message);
             });

    }; 
</script>

</html>