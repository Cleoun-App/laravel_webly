@extends('layouts.app', [
'page_title' => $page_title,
'page_id' => \Str::random(12),
])

@section('content')

<!-- Start Content -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
               <!--progress bar-->
               <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" title="a" disabled>
                                <span>Form Penyewaan</span>
                            </button>
                            <button class="multisteps-form__progress-btn" disabled type="button" title="Address">Form Pembayaran</button>
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
                        @livewire('dashboard.rent-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Content -->


@endsection
