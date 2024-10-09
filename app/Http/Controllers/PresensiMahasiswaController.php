<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mapel;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use App\Models\Presensi;
use DateTime;
use Illuminate\Support\Facades\Auth;

class PresensiMahasiswaController extends Controller
{
    public function showMapel()
    {
        $mapels = Auth::user()->mahasiswa->kelas->mapels;
        for ($i = 0; $i < count($mapels); $i++) {
            $pertemuans = $mapels[$i]->pertemuans;
            $sks_count = 0;
            foreach ($pertemuans as $pertemuan) {
                if ($pertemuan->keterangan == "masuk") {
                    $sks_count += $pertemuan->sks;
                }
            }
            $mapels[$i]->sks_count =  $sks_count;
        }
        return view('contents.mahasiswa.presensi.all-mapel-presensi', [
            'title' => 'Pilih Mapel',
            'mapels' => $mapels,
        ]);
    }

    //menampilkan tanggal pertemuan mapel
    public function showTgl(Mapel $mapel)
    {
        return view('contents.mahasiswa.presensi.detail-mapel-presensi', [
            'title' => 'Pilih Tanggal Presensi',
            'pertemuans' => $mapel->pertemuans,
        ]);
    }

    //manmpilkan absensi pada pertemuan tsb
    public function showPresensi(Mapel $mapel, Pertemuan $pertemuan)
    {
        $presensi_dosen = Presensi::select()->where('pertemuan_id', $pertemuan->id)->where('level', 'dosen')->first();
        if ($presensi_dosen) {

            //kalo absen masuk
            if ($pertemuan->keterangan == "masuk") {
                //batesnya sampe mapel selesai

                $limit = (Pertemuan::select("waktu")
                    ->where('mapel_id', $pertemuan->mapel->id)
                    ->where('tanggal', $pertemuan->tanggal)
                    ->where('keterangan', 'keluar')
                    ->first()
                )["waktu"];

                $limit = ($limit === null) ? '23:59:59' : $limit;
                //kalo keluar
            } else if ($pertemuan->keterangan == "keluar") {
                //limitnya 30 menit setelah keluar
                $limit = date('H:i:s', strtotime("$pertemuan->waktu + 30 minutes"));
                // dd($limit);
            }

            if (
                //kalo bukan hari ini atau
                $pertemuan->tanggal != date('Y-m-d') ||
                // lewat dari jam segini
                date('H:i:s') >= $limit
            ) {
                $telat = true;
            } else {
                $telat = false;
            }
            return view('contents.mahasiswa.presensi.create-mapel-presensi', [
                'title' => 'Presensi',
                'mapel' => $mapel,
                'materi' => $presensi_dosen->materi,
                'pertemuan' => $pertemuan,
                'mahasiswas' => $mapel->kelas->mahasiswas,
                'telat' => $telat,
                'presensi' => Presensi::select()->where('pertemuan_id', $pertemuan->id)->where('level', 'mahasiswa')->get(),
                'absensis' => Absensi::all()
            ]);
        } else {
            return back()->with('error', 'Absen belum dibuka, Pastikan dosen telah absen!');
        }
    }

    //input data absensi
    public function inputAbsensi(Request $request)
    {
        $now = new DateTime('now');

        $materi = Pertemuan::select()->where('id', $request->pertemuan)->first()->presensi->first()->materi_id;

        foreach ($request->presensi as $person) {
            Presensi::updateOrInsert([
                'pertemuan_id' => request('pertemuan'),
                'user_id' => $person['mahasiswa'],
                'level' => 'mahasiswa',
                'materi_id' => $materi,
            ], [
                'waktu_absen' => $now->format('Y-m-d H:i:s'),
                'absensi_id' => $person['kehadiran'],
            ]);
        }
        return redirect()->route('mahasiswa.presensi.detail',  ['mapel' => request("mapel")]);
    }
}
