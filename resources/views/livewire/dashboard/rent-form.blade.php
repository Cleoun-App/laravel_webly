<div class="multisteps-form__form mb-8">

    <div class="card multisteps-form__panel p-3 border-radius-xl bg-white @if($form_page == 'penyewaan') js-active @endif" data-animation="FadeIn">

        <h5 class="font-weight-bolder mb-0">Tambahkan Penyewa</h5>
        <p class="mb-0 text-sm">Silahkan masukkan data berikut untuk menambahkan data</p>
        <div class="multisteps-form__content">

            <div class="row mt-4">
                <div class="col-lg-12 m-auto">
                    @if ($msg = session()->get('error'))
                        <div class="alert alert-danger text-light" role="alert">
                            <strong>Error!</strong> {{ $msg }}
                        </div>
                    @endif

                    @if ($msg = session()->get('success'))
                        <div class="alert alert-success text-light" role="alert">
                            <strong>Success!</strong> {{ $msg }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label class="mt-0">Penyewa</label>
                    <select class="form-control" wire:model="renter_id" id="choices-category">
                        <option default value="1298">Pilih Penyewa</option>
                        @foreach ($users as $user)
                            @if ($renter_id === $user->id)
                                <option value="{{ $user->id }}" checked>{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
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
                        @foreach ($buildings as $building_)
                        <option value="{{ $building_->id }}">{{ $building_->name }}</option>
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
                {{-- <div class="col-12">
                    <label>Kode Promo</label>
                    <input class="multisteps-form__input form-control" wire:model="promo_code" type="text" placeholder="Kode Promo" />
                    @error('promo_code')
                        <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                    @enderror
                </div> --}}
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
                    <button class="btn bg-gradient-primary ms-auto mb-0 js-btn-next" wire:click="next" type="button" title="Next">Selanjutnya</button>
                @else
                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" disabled type="button" title="Next">Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card multisteps-form__panel p-3 border-radius-xl bg-white @if($form_page == 'pembayaran') js-active @endif" data-animation="FadeIn">
        <h5 class="font-weight-bolder">Data Penyewaan</h5>
        <div class="multisteps-form__content">

            <div class="row mt-4">
                <div class="col-lg-12 m-auto">
                    @if ($msg = session()->get('error'))
                        <div class="alert alert-danger text-light" role="alert">
                            <strong>Error!</strong> {{ $msg }}
                        </div>
                    @endif

                    @if ($msg = session()->get('success'))
                        <div class="alert alert-success text-light" role="alert">
                            <strong>Success!</strong> {{ $msg }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label>Nama Penyewa</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ $renter?->name }}" disabled/>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Nomor Telpon</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ $renter?->nomor_telp }}" disabled/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label>Nama Gedung</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ $building?->name }}" disabled/>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Harga Gedung</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ "RP " .  number_format($building?->price ?? 0, 0, ',', '.') }}" disabled/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-sm-6">
                    <label>Biaya Administrasi</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ "RP " .  number_format($adm_fee ?? 0, 0, ',', '.') }}" disabled/>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Pajak</label>
                    <input class="multisteps-form__input form-control" type="text" value="{{ "RP " .  number_format($tax_fee ?? 0, 0, ',', '.') }}" disabled/>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 col-sm-6">
                    <label>Durasi Penyewaan</label>
                    <input class="multisteps-form__input form-control disabled" value="{{ $duration }}" type="text" disabled/>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                    <label>Total Pembayaran</label>
                    <input class="multisteps-form__input form-control disabled" value="{{ "RP " .  number_format($total_payment, 0, ',', '.') }}" type="text" disabled/>
                </div>

                <div class="col-12">
                    <label>Catatan</label>
                    <textarea disabled class="multisteps-form__input form-control" cols="20" rows="10" style="min-height: 15vh; height:15vh">{{ $note ?? '-' }}</textarea>
                </div>
            </div>
            <div class="button-row d-flex mt-4">
                @if ($have_booking)
                    <button class="btn bg-gradient-danger mb-0" type="button" wire:click="unBooking" wire:loading.attr="disabled" wire:loading.class="bg-gradient-dark">UnBooking</button>
                    <button class="btn bg-gradient-success ms-auto mb-0" type="button" wire:loading.attr="disabled" wire:loading.class="bg-gradient-dark">Bayar</button>
                @else
                    <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev" wire:click="prev">Sebelumnya</button>
                    <button class="btn bg-gradient-warning mb-0 ms-auto" type="button" wire:click="booking" wire:loading.attr="disabled" wire:loading.class="bg-gradient-dark">Booking</button>
                @endif
            </div>
        </div>
    </div>

</div>
