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
                            <button class="multisteps-form__progress-btn" type="button" title="User Info">
                                <span>Pendaftaran Gedung</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 m-auto">
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

                        @if ($msg = session()->get('info'))
                        <div class="alert alert-warning text-light" role="alert">
                            <strong>Info!</strong> {{ $msg }}
                        </div>
                        @endif
                    </div>
                </div>

                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form class="multisteps-form__form mb-8" method="POST">

                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active">
                                <h3 class="font-weight-bolder">{{ $page_title }}</h3>
                                <div class="multisteps-form__content ">


                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama Penyewa</label>
                                            <input class="multisteps-form__input form-control" type="text" value="{{ $renter?->name }}" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Nomor Telpon</label>
                                            <input class="multisteps-form__input form-control" type="text" value="{{ $renter?->nomor_telp }}" disabled />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>Nama Gedung</label>
                                            <input class="multisteps-form__input form-control" type="text" value="{{ $rent_data['item_name'] }}" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Harga Gedung</label>
                                            <input class="multisteps-form__input form-control" type="text" value="{{ "RP " .  number_format($rent_data['item_price'] ?? 0, 0, ',', '.') }}" disabled />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12 col-sm-6">
                                            <label>Durasi Penyewaan</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $rent_data['duration'] }}" type="text" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Total Harga</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ "RP " .  number_format($order->total_price, 0, ',', '.') }}" type="text" disabled />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12 col-sm-6">
                                            <label>Mulai Sewa</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $rent_data['start_date'] }}" type="text" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Ahkir Sewa</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $rent_data['end_date'] }}" type="text" disabled />
                                        </div>
                                    </div>

                                    <h5 class="font-weight-bolder mt-3">Informasi Transaksi</h5>

                                    <div class="row mt-2">
                                        <div class="col-12 col-sm-6">
                                            <label>Status Pembayaran</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $order->payment_status }}" type="text" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Transaksi ID</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $order->key }}" type="text" disabled />
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12 col-sm-6">
                                            <label>Total Pembayaran</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ "RP " .  number_format($order->total_payment, 0, ',', '.') }}" type="text" disabled />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>Metode Pembayaran</label>
                                            <input class="multisteps-form__input form-control disabled" value="{{ $order->payment_method ?? 'Data Tidak Tersedia' }}" type="text" disabled />
                                        </div>
                                    </div>

                                    @if (is_array($payment_info) && count($payment_info) > 0)
                                        <h5 class="font-weight-bolder mt-3">Informasi Pembayaran</h5>
                                    @endif

                                    <div class="row mt-2">
                                        @if (is_array($payment_info))
                                            @foreach ($payment_info as $infoK => $infoV)
                                            @if (is_array($infoV) === false && in_array($infoK, $sensor_key) === false )
                                            <div class="col-12">
                                                <label style="text-transform: capitalize">{{ str_replace("_"," ", $infoK) }}</label>
                                                <input class="multisteps-form__input form-control disabled" value="{{ $infoV }}" type="text" disabled />
                                            </div>
                                            @endif
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="button-row d-flex mt-4">
                                        <a class="btn bg-gradient-light mb-0" href="{{route('adm.building.transactions')}}" >Kembali</a>
                                        <div class="ms-auto">
                                            @if ($order->payment_status == 'pending')
                                                <a class="btn bg-gradient-danger mb-0 " href="{{ route('midtrans.trx.cancel', $order->key) }}" onclick="return confirm('Apakah anda yakin ingin membatalkan transaksi ini!!')">Batalkan Transaksi</a>
                                            @endif
                                            @if ($order->payment_status == 'waiting')
                                                <a class="btn bg-gradient-danger mb-0 "  href="{{ route('adm.building.trx.unbooking', $order->key) }}" onclick="return confirm('Apakah anda yakin ingin membatalkan penyewaan ini!!')">Batalkan Penyewaan</a>
                                                <button type="button" class="btn bg-gradient-success mb-0 " onclick="paynow('{{ $order->snap_token }}', () => { location.replace(location.href) })" >Bayar Sekarang</button>
                                            @endif
                                            @if ($order->payment_status == 'success')
                                                <a class="btn bg-gradient-dark mb-0 " href="{{ route('midtrans.trx.refund', $order->key) }}" >Refund Transaksi</a>
                                            @endif
                                            @if ($order->payment_status == 'expired' || $order->payment_status == 'cancel')
                                                <a class="btn bg-gradient-danger mb-0 " onclick="return confirm('Apakah anda yakin ingin menghapus data transaksi ini!!')" href="{{ route('adm.building.trx.delete', $order->key) }}" >Hapus Data Order</a>
                                            @endif
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
