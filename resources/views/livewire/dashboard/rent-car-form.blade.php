
<div class="col-12 col-sm-6 mt-3 mt-sm-0">
    <label class="mt-0">Daftar Mobil Tersedia</label>
    <select class="form-control" wire:model="item_id" id="choices-category">
        <option default value="7">Pilih Mobil</option>
        @foreach ($cars as $car)
        <option value="{{ $car->id }}">{{ $car->name }}</option>
        @endforeach
    </select>
    @error('item_id')
        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
    @enderror
</div>
