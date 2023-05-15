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
                            <button class="multisteps-form__progress-btn" type="button" title="Socials">Sosmed</button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form class="multisteps-form__form mb-8">
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">Tentang Saya</h5>
                                <p class="mb-0 text-sm">Infomasi mandatory(<strong>wajib</strong>)</p>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama Lenkap</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Michael" />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Nama Pengguna</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Prior" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nomor Telepon</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Creative Tim" />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Alamat Email</label>
                                            <input class="multisteps-form__input form-control" type="email" placeholder="eg. soft@dashboard.com" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Katasandi</label>
                                            <input class="multisteps-form__input form-control" type="password" placeholder="******" />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Konfirmasi Katasandi</label>
                                            <input class="multisteps-form__input form-control" type="password" placeholder="******" />
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
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Street 111" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label>Alamat Sekarang</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Street 221" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Kota Sekarang</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="eg. Tokyo" />
                                        </div>
                                        <div class="col-6 col-sm-3 mt-3 mt-sm-0">
                                            <label>Zip</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="7 letters" />
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Sebelumnya</button>
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">Selanjutnya</button>
                                    </div>
                                </div>
                            </div>
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                <h5 class="font-weight-bolder">Sosial Media</h5>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label>Twitter Handle</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="@soft" />
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label>Akun Facebook</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="https://..." />
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label>Akun Instagram</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="https://..." />
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label>Akun Tiktok</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="https://..." />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">Sebelumnya</button>
                                            <button class="btn bg-gradient-success ms-auto mb-0" type="button">Simpan</button>
                                        </div>
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
