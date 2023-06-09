@extends('layouts.app', [
'page_title' => $page_title,
'page_id' => \Str::random(12),
])

@section('content')

<!-- Start Content -->


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
                        <strong>Berhasil!</strong> {{ $msg }}
                    </div>
                @endif
            </div>
        </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header pb-1">
                    <h5 class="mb-0">Tabel Mobil</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama</th>
                                <th>Type</th>
                                <th>Harga</th>
                                <th>Km</th>
                                <th>Nomor Polisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cars as $car)
                                <tr>
                                    <td class="text-sm font-weight-normal">{{ \Str::limit($car->name, 30, "...") }}</td>
                                    <td class="text-sm font-weight-normal">{{ $car->type }}</td>
                                    <td class="text-sm font-weight-normal">{{ $car->price }}</td>
                                    <td class="text-sm font-weight-normal">{{ $car->km }}</td>
                                    <td class="text-sm font-weight-normal">{{ $car->license_plate }}</td>
                                    {{-- <td class="text-sm font-weight-normal">
                                        <span class="badge badge-sm bg-gradient-success">Aktif</span>
                                    </td> --}}
                                    <td class="text-sm font-weight-normal">
                                        <a class="btn badge badge-success m-0" href="{{ route('editCarPage', $car->id) }}">Ubah</a>
                                        <a class="btn badge badge-danger m-0" onclick="return confirm('Yakin!!, pilih ok untuk menghapus')" href="{{ route('deleteCar', $car->id) }}">Hapus</a>
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

<!-- End Content -->


@endsection
