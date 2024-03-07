<div class="mb-3">
    <label for="caliber" class="form-label">Calibro / Caliber</label>
    <input required type="text" class="form-control" id="calibre" name="caliber"
           @if(isset($value))
               {{'value='.$value}}
           @endif

           aria-describedby="emailHelp">
</div>
