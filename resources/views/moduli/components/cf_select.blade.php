<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="mb-3">
    <label for="cliente" class="form-label">CLIENTE</label>
    <select class="selectpicker" data-live-search="true" id="cliente" name="cliente">
        <option>NESSUN CF</option>
    </select>
</div>
@csrf
<input required type="hidden" name="cf" id="cf">

<script>
    document.addEventListener('DOMContentLoaded', function () {
    axios.get('/CF')
        .then(function (response) {
            console.log(response.data);
            var xwpCollo = document.getElementById('cliente'); 
            response.data.data.forEach(function (collo) {
                var option = document.createElement('option');
                option.text = `${collo.Descrizione}`;
                option.value = collo.Cd_CF;
                xwpCollo.add(option); 
            });
            console.log(response.data); 
            $(xwpCollo).selectpicker('refresh'); 
        })
        .catch(function (error) {
            console.error('Errore nella richiesta Axios', error);
        });
});

$(document).ready(function () {
            $('#cliente').change(function () {
                // Aggiorna il valore del campo di input required nascosto
                var selectedValue = $(this).val();
                console.log('Valore selezionato:', selectedValue);
                $('#cf').val(selectedValue);
            });
        });
</script>