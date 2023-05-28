<div class="multisteps-form__form mb-8">

    <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">

        <h5 class="font-weight-bolder mb-0">Tambahkan Penyewa</h5>
        <p class="mb-0 text-sm">Silahkan masukkan data berikut untuk menambahkan data</p>
        <div class="multisteps-form__content">

            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label class="mt-0">Penyewa</label>
                    <select class="form-control" wire:model="renter_id" id="choices-category">
                        <option default value="1298">Pilih Penyewa</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('renter_id')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label class="mt-0">Daftar Gedung Tersedia</label>
                    <select class="form-control" wire:model="building_id" id="choices-category">
                        <option default value="7">Pilih Gedung</option>
                        @foreach ($buildings as $building)
                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                        @endforeach
                    </select>
                    @error('building_id')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 col-sm-6">
                    <label>Mulai Sewa</label>
                    <input class="multisteps-form__input form-control" wire:model="start_date" type="date" placeholder="Mulai Sewa" />
                    @error('start_date')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Ahkir Sewa</label>
                    <input class="multisteps-form__input form-control" wire:model="end_date" type="date" placeholder="Mulai Sewa" />
                    @error('end_date')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label>Harga Gedung</label>
                    <input class="multisteps-form__input form-control disabled" disabled type="text" value="{{ $building_price }}" placeholder="Harga tenan" />
                </div>

                <div class="col-12">
                    <label>Catatan</label>
                    <textarea wire:model="note" class="multisteps-form__input form-control" cols="20" rows="10" style="min-height: 15vh; height:15vh"></textarea>
                    @error('note')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="button-row d-flex mt-4">
                @if ($can_next)
                    <button class="btn bg-gradient-primary ms-auto mb-0 js-btn-next" type="button" title="Next">Selanjutnya</button>
                @else
                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" disabled type="button" title="Next">Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>
    <!--single form panel-->
    <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
        <h5 class="font-weight-bolder">Detail Pembayaran</h5>
        <div class="multisteps-form__content">
            <div class="row mt-2">
                <div class="col-12 col-sm-6">
                    <label>Durasi Penyewaan</label>
                    <input class="multisteps-form__input form-control disabled" value="{{ $duration }}" type="text" disabled/>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Total Pembayaran</label>
                    <input class="multisteps-form__input form-control disabled" value="{{ "RP " .  number_format($total_payment, 0, ',', '.') }}" type="text" disabled/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label>Nama Penyewa</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ $renter?->name }}" disabled/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label>Kota Sekarang</label>
                    <input class="multisteps-form__input form-control" type="text" placeholder="eg. Tokyo" name="kota" value="" />
                </div>
                <div class="col-6 col-sm-3 mt-3 mt-sm-0">
                    <label>Zip</label>
                    <input class="multisteps-form__input form-control" type="text" placeholder="7 letters" name="zip_code" value="" />
                </div>
            </div>
            <div class="button-row d-flex mt-4">
                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Sebelumnya</button>
                <button class="btn bg-gradient-success ms-auto mb-0" type="submit">Simpan</button>
            </div>
        </div>
    </div>

</div>
