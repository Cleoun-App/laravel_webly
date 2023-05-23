@extends('layouts.app', [
'page_title' => $page_title,
'page_id' => \Str::random(12),
])

@section('content')

<!-- Start Content -->


<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-5">
                <!--progress bar-->
                <div class="row d-none">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>Update Data Mobil</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-8 m-auto">
                        @if ($msg = session()->get('error'))
                            <div class="alert alert-danger text-light" role="alert">
                                <strong>Error!</strong> {{ $msg }}
                            </div>
                        @endif

                        @if ($msg = session()->get('success'))
                            <div class="alert alert-success text-light" role="alert">
                                <strong>Berhasil!</strong> {{ $msg }}
                            </div>
                        @endif
                    </div>
                </div>

                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form class="multisteps-form__form mb-8"  method="POST" >
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">Update Data Pengendaran Mobil Sewa</h5>
                                <div class="multisteps-form__content">
                                    @csrf
                                    @method('POST')
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama</label>
                                            <input class="multisteps-form__input form-control" type="text" name="name" value="{{ $driver->name }}"/>
                                            @error('name')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="mt-0">Jenis Sim</label>
                                            <select class="form-control" name="license_type" id="choices-category">
                                                <option value="{{ $driver->license }}">{{ $driver->license }}</option>
                                                <option value="SIM A">SIM A</option>
                                                <option value="SIM B1">SIM B1</option>
                                                <option value="SIM B2">SIM B2</option>
                                                <option value="SIM C">SIM C</option>
                                                <option value="SIM D">SIM D</option>
                                            </select>
                                            @error('license_type')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>No KTP</label>
                                            <input class="multisteps-form__input form-control" type="number" name="no_ktp" value="{{ $driver->ktp }}"/>
                                            @error('no_ktp')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label>Nomor Ponsel</label>
                                            <input class="multisteps-form__input form-control" type="number" name="nomor_ponsel" value="{{ $driver->phone_number }}"/>
                                            @error('nomor_ponsel')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label>Alamat Tinggal</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="Alamat Driver" name="address" value="{{ $driver->address }}"/>
                                            @error('address')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-success ms-auto mb-0" type="submit">Update</button>
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
