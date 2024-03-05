<div class="mb-3">
    <label for="simpleDate" class="form-label">Data / Date</label>
    <input required type="simpleDate" name="simpleDate" class="form-control" id="simpleDate"
    @if(isset($value))
        {{'value='.$value}}
        @endif
    >
</div>
