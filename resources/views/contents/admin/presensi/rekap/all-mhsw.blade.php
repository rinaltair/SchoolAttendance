@extends('index')
@section('content')
<div class="title pb-20">
    <h2 class="h3 mb-0">{{ $title }}</h2>
</div>
<div class="card-box mb-30">
    <div class="card-header pd-20 d-flex justify-content-between">
        <div class=" d-flex">
            <h4 class="text-blue h4 mr-5">Tabel Presensi</h4>

{{--            <a href="{{route('admin.presensi.rekap.mahasiswa.pdf', ['mapel' => $mapel->id ] )}}" class="btn btn-sm btn-outline-info">PDF</a>--}}
            <a href="{{route('admin.presensi.rekap.mahasiswa.excel', ['mapel' => $mapel->id ] )}}" class="btn btn-sm btn-outline-primary">Excel</a>
        </div>
        <p>Mapel: {{ $mapel->nama }} | Kelas: {{ $mapel->kelas->nama }}</p>
    </div>
    <div class="card-body">
        <table class="data-table table">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort text-center">No.</th>
                    <th class="text-center">Nama Mahasiswa</th>
                    <th class="text-center datatable-nosort">Rekap Absen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswas as $mahasiswa)
                <tr>
                    <td class="table-plus text-center">{{ $loop->iteration }}</td>
                    <td>{{ $mahasiswa->firstName }} {{ $mahasiswa->lastName }}</td>
                    <td class="text-center">
                        <a href="{{route('admin.presensi.rekap.mahasiswa.detail', ['mapel' => $mapel->id, 'mahasiswa' =>  $mahasiswa->id] )}}" class="btn btn-sm btn-outline-primary">Lihat Rekap Mahasiswa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
