

<div class="mb-3">
    <label for="xwpCollo" class="form-label">LOTTO</label>
    <select class="selectpicker" data-live-search="true" id="xwpCollo" name="xwpCollo" required>
        <option value="" disabled selected>Seleziona un lotto</option>

    </select>
</div>
@csrf
<input required type="hidden" name="xwp" id="xwp">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.get('/XWPCollo/{{$attivita->Id_PrBLAttivita}}')
            .then(function (response) {
                console.log(response.data);
                var xwpCollo = document.getElementById('xwpCollo');
                response.data.forEach(function (collo) {
                    var option = document.createElement('option');
                    option.text = `${collo.Cd_AR} - ${collo.xLotto}`;
                    option.value = `${collo.Cd_AR} - ${collo.xLotto}`;
                    if(option.value === '@if(isset($selected)){{$selected}}@else{{''}}@endif') option.selected = true;
                    xwpCollo.add(option);
                });
                $(xwpCollo).selectpicker('refresh');
            })
            .catch(function (error) {
                console.error('Errore nella richiesta Axios', error);
            });
    });

    $(document).ready(function () {
        $('#xwpCollo').change(function () {
            // Aggiorna il valore del campo di input required nascosto
            var selectedValue = $(this).val();
            console.log('Valore selezionato:', selectedValue);

            if (selectedValue === 'NESSUN LOTTO') {
                // L'utente deve selezionare un'opzione diversa da "NESSUN LOTTO"
                alert('Seleziona un lotto valido.');
                $(this).val(''); // Imposta il valore a vuoto
                $('#xwp').val(''); // Assicurati che il campo nascosto sia vuoto
            } else {
                $('#xwp').val(selectedValue);
            }
        });
    });
</script>
