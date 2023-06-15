@extends('layouts.app', [
'page_title' => $page_title,
'page_id' => \Str::random(12),
])

@section('content')

<div class="container-fluid py-4">

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

            @if ($msg = session()->get('info'))
            <div class="alert alert-warning text-light" role="alert">
                <strong>Info!</strong> {{ $msg }}
            </div>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header pb-1">
                    <h5 class="mb-0">{{ $page_title }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>Transaksi ID</th>
                                <th>Tgl Sewa</th>
                                <th>Periode</th>
                                <th>Total Transaksi</th>
                                <th>Penyewa</th>
                                <th>Status Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                @php
                                 $payment_status = $log->payment_status;
                                @endphp
                                <tr>
                                    <td class="text-sm font-weight-normal">{{ $log->log_id }}</td>
                                    <td class="text-sm font-weight-normal">{{ $log->start_date }}</td>
                                    <td class="text-sm font-weight-normal">{{ $log->duration . ' Hari' }}</td>
                                    <td class="text-sm font-weight-normal">{{ "RP " .  number_format($log->total_payment ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-sm font-weight-normal">
                                        {{ $log->user->name }}
                                    </td>
                                    <td class="text-sm font-weight-normal">
                                        @if (strtolower($payment_status) == 'success')
                                            <span class="badge badge-success m-0">Berhasil</span>
                                        @endif
                                        @if (strtolower($payment_status) == 'error')
                                            <span class="badge badge-danger m-0">Error</span>
                                        @endif
                                        @if (strtolower($payment_status) == 'cancel')
                                            <span class="badge badge-dark m-0" style="color: whitesmoke">Dibatalkan</span>
                                        @endif
                                        @if (strtolower($payment_status) == 'waiting')
                                            <span class="badge badge-warning m-0">Waiting</span>
                                        @endif
                                        @if (strtolower($payment_status) == 'expired')
                                            <span class="badge badge-danger m-0">Expired</span>
                                        @endif
                                        @if (strtolower($payment_status) == 'pending')
                                            <span class="badge badge-warning m-0">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-sm font-weight-normal">
                                        <a class="btn badge badge-info m-0" href="{{ route('adm.canteen.log.detail', $log->id) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer pt-3  ">
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

@endsection
