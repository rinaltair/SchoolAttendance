<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use App\Models\Presensi;
use DateTime;
use Illuminate\Support\Facades\Auth;

class PresensiDosenController extends Controller
{
    //menampilkan seluruh mata pelajaran untuk kelas
    public function showMapel()
    {
        $mapels = Auth::user()->dosen->mapels;
        for ($i = 0; $i < count($mapels); $i++) {
            $pertemuans = $mapels[$i]->pertemuans;
            $presensi_count = 0;
            $sks_count = 0;

            foreach ($pertemuans as $pertemuan) {
                if ($pertemuan->keterangan == "masuk") {
                    $presensi = $pertemuan->presensi->whereIn('level', ['dosen', 'asdos'])->first();
                    if ($presensi && $presensi->absensi_id == 2) {
                        $presensi_count++;
                    };
                    $sks_count += $pertemuan->sks;
                }
            }
            $mapels[$i]->presensi_count =  $presensi_count;
            $mapels[$i]->sks_count =  $sks_count;
        }

        return view('contents.dosen.presensi.all-mapel-presensi', [
            'title' => 'Pilih Mapel',
            'mapels' => $mapels,
        ]);
    }

    //menampilkan tanggal pertemuan mapel
    public function showTgl(Mapel $mapel)
    {
        return view('contents.dosen.presensi.detail-mapel-presensi', [
            'title' => 'Pilih Tanggal Presensi',
            'pertemuans' => $mapel->pertemuans,
        ]);
    }

    //manmpilkan absensi pada pertemuan tsb
    public function showPresensi(Mapel $mapel, Pertemuan $pertemuan)
    {
//        dd($pertemuan);
        if ($pertemuan->keterangan == "keluar") {
            $pertemuanMasuk = Pertemuan::select()->where('mapel_id', $pertemuan->mapel_id)->where('tanggal', $pertemuan->tanggal)->where('keterangan', 'masuk')->first();
            if ($pertemuanMasuk->presensi->count() == 0){
                return back()->with('error', 'Absen belum dibuka, Pastikan presensi masuk telah diisi!');
            };
        }
        $presensi = Presensi::select()->where('pertemuan_id', $pertemuan->id)->whereIn('level', ['dosen', 'asdos'])->get();
        $presensiDosen = $presensi->where('level', 'dosen')->first();
        $presensiAsdos = $presensi->where('level', 'asdos')->where('absensi_id', 2)->first();

        if (
            //kalo bukan hari ini atau
            $pertemuan->tanggal != date('Y-m-d')
        ) {
            $telat = true;
        } else {
            $telat = false;
        }

        return view('contents.dosen.presensi.create-mapel-presensi', [
            'title' => 'Presensi',
            'mapel' => $mapel,
            'pertemuan' => $pertemuan,
            'dosen' => Auth::user()->dosen,
            'telat' => $telat,
            'presensiDosen' => $presensiDosen,
            'presensiAsdos' => $presensiAsdos,
            'absensis' => Absensi::all(),
            'materis' => Materi::select()->where('mapel_id', $mapel->id)->get(),
        ]);
    }

    //input data absensi
    public function inputAbsensi(Request $request)
    {
        $pertemuan = Pertemuan::find(request("pertemuan"));
        if ($pertemuan->keterangan == "keluar") {
            $pertemuan->waktu = now()->format('H:i');
            $pertemuan->save();

            //ambil materi dari masuk
            $pertemuan = Pertemuan::select('id')
                ->where('mapel_id', $pertemuan->mapel->id)
                ->where('tanggal', $pertemuan->tanggal)
                ->where('keterangan', "masuk")
                ->first();

            $materi = Presensi::select("materi_id")
                ->where('pertemuan_id', $pertemuan->id)
                ->where('level', 'dosen')
                ->first();

            $materi_id = $materi->materi->id;
        } else {
            $materi_id = request('materi');

            $pertemuan->waktu = request('waktu');
            $pertemuan->save();
        }

        $now = new DateTime('now');

        $person = $request->presensi;

        Presensi::updateOrInsert([
            'pertemuan_id' => request('pertemuan'),
            'user_id' => $person['dosen'],
            'level' => 'dosen',
        ], [
            'materi_id' => $materi_id,
            'waktu_absen' => $now->format('Y-m-d H:i:s'),
            'absensi_id' => $person['kehadiran'],
        ]);
        return redirect()->route('dosen.presensi.detail',  ['mapel' => request("mapel")]);
    }
}
