<div class="mb-3">
    <label for="{{ $name }}" class="form-label">ANALYSIS TIME</label>
    <input required name="{{ $name }}" class="form-control" id="{{ $name }}"
        @if (isset($value)) {{ 'value=' . $value }} @endif>
</div>
