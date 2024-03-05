<div class="mb-3">
    <label for="moisture" class="form-label">Umidità / Moisture</label>
    <input required type="number" class="form-control" id="moisture" name="moisture" @if(isset($value))
        {{'value='.$value}}
        @endif>
</div>
