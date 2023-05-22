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

                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form class="multisteps-form__form mb-8"  method="POST" >
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">Update Data Mobil</h5>
                                <div class="multisteps-form__content">
                                    @csrf
                                    @method('POST')
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama</label>
                                            <input class="multisteps-form__input form-control" type="text" name="nama" value="{{ $car->name }}"/>
                                            @error('nama')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="mt-0">Tipe</label>
                                            <select class="form-control" name="tipe" id="choices-category" style="text-transform: capitalize">
                                                <option value="{{ $car->type }}">{{ $car->type }}</option>
                                                <option value="Sedan">Sedan</option>
                                                <option value="L300">L300</option>
                                                <option value="Double">Double</option>
                                                <option value="Engkel">Engkel</option>
                                                <option value="Fuso">Fuso</option>
                                            </select>
                                            @error('tipe')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-4 mt-3 mt-sm-0">
                                            <label>Kilometer</label>
                                            <input class="multisteps-form__input form-control" type="text" name="km" value="{{ $car->km }}"/>
                                            @error('km')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <label>Nomor Polisi</label>
                                            <input class="multisteps-form__input form-control" type="text" name="plat_nomor" value="{{ $car->license_plate }}"/>
                                            @error('plat_nomor')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-4 mt-3 mt-sm-0">
                                            <label>Nomor STNK</label>
                                            <input class="multisteps-form__input form-control" type="text" name="no_stnk" value="{{ $car->stnk }}"/>
                                            @error('no_stnk')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label>Harga Sewa</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="Masukan Harga Sewa" name="harga" value="{{ $car->price }}"/>
                                            @error('harga_sewa')
                                                <span style="color:#ff3131; font-size: 13px;">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="multisteps-form__input form-control" cols="30" rows="10">{{ $car->description }}</textarea>
                                            @error('deskripsi')
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
