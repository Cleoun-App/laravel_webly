@extends('layouts.app', [
'page_title' => $page_title,
'page_id' => \Str::random(12),
])

@section('content')

<!-- Start Content -->

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>Informasi pengguna</span>
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" title="Address">Alamat</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        @if ($msg = session()->get('error'))
                            <div class="alert alert-danger" role="alert">
                                <strong>Error!</strong> {{ $msg }}
                            </div>
                        @endif

                        @if ($msg = session()->get('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>Berhasil!</strong> {{ $msg }}
                            </div>
                        @endif
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form class="multisteps-form__form mb-8" action="{{ route('postUserData') }}" method="POST">
                            @method('POST')
                            @csrf
                            <!--single form panel-->

                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">Tentang Saya</h5>
                                <p class="mb-0 text-sm">Infomasi mandatory(<strong>wajib</strong>)</p>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama Lenkap</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Michael" name="nama_lenkap" value="{{ $faker->name }}"/>
                                            @error('nama_lenkap')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Nama Pengguna</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Prior" name="nama_pengguna"value="{{ $faker->firstName }}"/>
                                            @error('nama_pengguna')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nomor Telepon</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Creative Tim" name="nomor_telp" value="{{ $faker->numberBetween(1000000, 9999999) }}"/>
                                            @error('nomor_telp')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Alamat Email</label>
                                            <input class="multisteps-form__input form-control" type="email" placeholder="eg. soft@dashboard.com" name="email" value="{{ $faker->email }}"/>
                                            @error('email')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Katasandi</label>
                                            <input class="multisteps-form__input form-control" type="password" placeholder="******" name="password" value="password"/>
                                            @error('password')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Konfirmasi Katasandi</label>
                                            <input class="multisteps-form__input form-control" type="password" placeholder="******" name="password_confirm" value="password"/>
                                            @error('password_confirm')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">Selanjutnya</button>
                                    </div>
                                </div>
                            </div>
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                <h5 class="font-weight-bolder">Alamat</h5>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label>Alamat KTP</label>
                                            <input class="multisteps-form__input form-control" name="alamat_ktp" type="text" placeholder="eg. Street 111" value="{{ $faker->address }}"/>
                                            @error('alamat_ktp')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label>Alamat Sekarang</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Street 221" name="alamat_tinggal" value="{{ $faker->address }}"/>
                                            @error('alamat_tinggal')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Kota Sekarang</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Tokyo" name="kota" value="{{ $faker->city }}"/>
                                        </div>
                                        <div class="col-6 col-sm-3 mt-3 mt-sm-0">
                                            <label>Zip</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="7 letters" name="zip_code" value="{{ $faker->numberBetween(1000, 9999) }}"/>
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Sebelumnya</button>

                                        <button class="btn bg-gradient-success ms-auto mb-0" type="submit">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer pt-3">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        made with <i class="fa fa-heart"></i> by
                        <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                        for a better web.
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- End Content -->

@endsection
