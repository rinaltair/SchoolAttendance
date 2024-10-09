@extends('index')
@section('content')
<div class="title pb-20">
    <h2 class="h3 mb-0">{{ $title }}</h2>
</div>
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Tabel Pilih Presensi</h4>
    </div>
    <div class="pb-20">
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <table class="data-table table stripe hover nowrap">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort text-center">No.</th>
                    <th class="text-center">Kelas</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Mapel</th>
                    <th class="text-center">Dosen</th>
                    <th class="text-center">Ket.</th>
                    <th class="text-center">SKS</th>
                    <th class="text-center datatable-nosort">Lanjutkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pertemuans as $pertemuan)
                <tr>
                    <td class="table-plus text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $pertemuan->mapel->kelas->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($pertemuan->tanggal . ' ' . $pertemuan->waktu)->format('l, j F Y H:i') }}</td>
                    <td>{{ $pertemuan->mapel->kode }} - {{ $pertemuan->mapel->nama }}</td>
                    <td>{{ $pertemuan->mapel->dosen->firstName }} {{ $pertemuan->mapel->dosen->lastName }}</td>
                    <td class="text-center">
                        <h6><span class=" badge badge-pill {{ ($pertemuan->keterangan == 'masuk') ? 'badge-success' : 'badge-danger' }} text-uppercase" style="font-weight: 600;">{{ $pertemuan->keterangan }}</span></h6>
                    </td>
                    <td class="text-center">{{ $pertemuan->sks }}</td>
                    <td class="text-center">
                        <a href="{{route('dosen.presensi.pertemuan', ['mapel' => $pertemuan->mapel->id, 'pertemuan' => $pertemuan->id] )}}" class="btn btn-sm btn-outline-primary">Pilih</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
