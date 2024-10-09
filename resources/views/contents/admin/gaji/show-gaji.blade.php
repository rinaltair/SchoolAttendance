@extends('index')
@section('content')
<div class="title pb-20">
    <h2 class="h3 mb-0">{{ $title }}</h2>
</div>
<div class="card-box mb-30">
    <div class="pd-20">
        <div class=" d-flex justify-content-between">
            <h4 class="text-blue h4 mr-5">Tabel Gaji</h4>
            <div class="">
                @if($data != null)
{{--                <a href="{{route('admin.gaji.pdf', ['user' => $user->id, 'bulan' =>  $input['bulan'], 'tahun' =>  $input['tahun']] )}}" class="btn btn-sm btn-outline-info">PDF</a>--}}
                <a href="{{route('admin.gaji.excel', ['user' => $user->id, 'bulan' =>  $input['bulan'], 'tahun' =>  $input['tahun']] )}}" class="btn btn-sm btn-outline-primary">Excel</a>
                @endif
            </div>
        </div>
    </div>
    <div class="pb-2">
        <form class="m-4" action="{{route('admin.gaji.list', ['user' => $user->id])}}" method="get" id="get-periode">
            @csrf
            @error('error')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @enderror
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endforeach
            @endif
            <div class="form-group row mb-4">
                @csrf
                <label class="col-form-label col-12 col-md-2 col-lg-2">Bulan</label>
                <div class="col-sm-12 col-md-4 col-lg-3 mb-2">
                    <select name="bulan" id="bulan" class='form-control' required>
                        <option value="" hidden>Pilih Bulan</option>
                        <option value="1" {{($input != null && $input['bulan'] == '1')? 'selected' : '' }}>Januari</option>
                        <option value="2" {{($input != null && $input['bulan'] == '2')? 'selected' : '' }}>Februari</option>
                        <option value="3" {{($input != null && $input['bulan'] == '3')? 'selected' : '' }}>Maret</option>
                        <option value="4" {{($input != null && $input['bulan'] == '4')? 'selected' : '' }}>April</option>
                        <option value="5" {{($input != null && $input['bulan'] == '5')? 'selected' : '' }}>Mei</option>
                        <option value="6" {{($input != null && $input['bulan'] == '6')? 'selected' : '' }}>Juni</option>
                        <option value="7" {{($input != null && $input['bulan'] == '7')? 'selected' : '' }}>Juli</option>
                        <option value="8" {{($input != null && $input['bulan'] == '8')? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{($input != null && $input['bulan'] == '9')? 'selected' : '' }}>September</option>
                        <option value="10" {{($input != null && $input['bulan'] == '10')? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{($input != null && $input['bulan'] == '11')? 'selected' : '' }}>November</option>
                        <option value="12" {{($input != null && $input['bulan'] == '12')? 'selected' : '' }}>December</option>

                    </select>
                </div>

                <label class=" col-form-label col-12 col-md-2 col-lg-2">Tahun</label>
                <div class="col-sm-12 col-md-4 col-lg-3 mb-2">
                    <select name="tahun" id="tahun" class='form-control' required>
                        @if($input)
                        <option value="{{$input['tahun']}}" hidden selected>{{$input['tahun']}}</option>
                        @endif

                        <option value="" hidden>Pilih Tahun</option>
                        @foreach($tahuns as $tahun)
                        <option value="{{$tahun}}" {{($input != null && $input['tahun'] == $tahun)? 'selected' : '' }}>{{$tahun}}</option>
                        @endforeach
                    </select>
                </div>
                <div class=" d-flex justify-content-end col-12 col-lg-2 mb-2">
                    <button class="btn btn-outline-info" type="submit" id="btn-lihat">Lihat</button>
                </div>
            </div>
        </form>
    </div>
    <div class="pb-2">
        <table class="data-table table stripe nowrap">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort text-center">No.</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Mapel - Kelas</th>
                    <th class="text-center">SKS</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Nominal</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if($data != null)
                @foreach($data['details'] as $detail)
                <tr>
                    <td class="table-plus datatable-nosort text-center">{{$loop->iteration}}</td>
                    <td class="text-start">{{$detail->tanggal}}</td>
                    <td class="text-center">{{$detail->waktu}}</td>
                    <td class="text-start">{{$detail->mapelkelas}}</td>
                    <td class="text-center">{{$detail->sks}}</td>
                    <td class="text-left">{{$detail->tipe}}</td>
                    <td class="text-right">@currency($detail->nominal)</td>
                    <td class="text-left">{{$detail->keterangan}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="pb-2">
        @if($data != null)
        <form class="m-4" action="{{route('admin.gaji.post', ['user' => $user->id])}}" method="post" id="post-total">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-12 col-md-2 col-lg-1">Tambahan</label>
                <div class="d-flex col-sm-12 col-md-4 col-lg-4 mb-2">
                    <label class="col-form-label mr-3">Rp.</label>
                    <input class="form-control" type="number" min=0 name="tambahan" id="tambahan" value="{{ ($simpan == true) ? $data['tambahan']['nominal'] : 0 }}" {{ ($simpan == true) ? 'readonly' : '' }}>
                </div>

                <label class="col-form-label col-12 col-md-2 col-lg-1">Total</label>
                <div class="d-flex col-sm-12 col-md-4 col-lg-4 mb-2">
                    <label class="col-form-label mr-3">Rp.</label>
                    <input class="form-control text-right" type="text" name="totalview" id="totalview" readonly>
                    <input class="form-control text-right" type="number" name="total" id="totalgaji" initial={{$data['total']}} value={{$data['total']}} hidden>
                    <input class="form-control text-right" type="number" name="bulan" id="bulangaji" value={{$input['bulan']}} hidden>
                    <input class="form-control text-right" type="number" name="tahun" id="tahungaji" value={{$input['tahun']}} hidden>
                </div>

                <div class=" d-flex justify-content-end col-12 col-lg-2 mb-2">
                    @if($simpan != true)
                        <button class="btn btn-primary" type="submit" value="Submit">Simpan</button>
                    @endif
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

@if($data != null)
@push('scripts')
<script src="{{ asset('src/scripts/admin-penggajian.js') }}"></script>
@endpush
@endif
