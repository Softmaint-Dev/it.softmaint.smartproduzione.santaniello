<div class="mb-3">
    <label class="form-label">Varieta / Variery</label>
    <input required type="text" class="form-control" id="variety" name="variety"
           @if(isset($value))
               {{'value='.$value}}
           @endif
    required>
</div>
