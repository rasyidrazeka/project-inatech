<?php

namespace App\Http\Controllers;

use App\Models\KualitasAirModel;
use App\Models\FaseKolamModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KualitasAirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Data Kualitas Air',
            'paragraph' => 'Berikut ini merupakan data kualitas air yang terinput ke dalam sistem',
            'list' => [
                ['label' => 'Home', 'url' => route('kualitasair.index')],
                ['label' => 'kualitasAir'],
            ]
        ];
        $activeMenu = 'kualitasair';
        $fase_kolam = FaseKolamModel::all();
        return view('kualitasair.index',['breadcrumb' =>$breadcrumb, 'activeMenu' => $activeMenu, 'fase_kolam' => $fase_kolam]);
    }

    // menampilkan data table    
    public function list(Request $request)
    {
        $kualitasairs = KualitasAirModel::select('id_kualitas_air', 'kd_kualitas_air', 'tanggal_cek', 'waktu_cek', 'pH','salinitas','DO', 'suhu', 'kejernihan_air', 'warna_air', 'catatan','id_fase_tambak', 'created_at', 'updated_at')->with('faseKolam'); 
        return DataTables::of($kualitasairs)
        ->make(true);
    }


    public function create(){
        $breadcrumb = (object) [
            'title' => 'Tambah Data Kualitas Air',
            'paragraph' => 'Berikut ini merupakan form tambah data kualitas air yang terinput ke dalam sistem',
            'list' => [
                ['label' => 'Home', 'url' => route('dashboard.index')],
                ['label' => 'Kualitas Air', 'url' => route('kualitasair.index')],
                ['label' => 'Tambah'],
            ]
    ];
    $activeMenu = 'kualitasAir';
    $fase_kolam = FaseKolamModel::all();
    return view('kualitasair.create',['breadcrumb' =>$breadcrumb, 'activeMenu' => $activeMenu, 'fase_kolam' => $fase_kolam]);
}

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'kd_kualitas_air' => 'required|string|max:255|unique:kualitas_air,kd_kualitas_air',
        'tanggal_cek' => 'required|date',
        'waktu_cek' => 'required',
        'pH' => 'required|integer',
        'salinitas' => 'required|integer',
        'DO' => 'required|integer',
        'suhu' => 'required|integer',
        'kejernihan_air' => 'required|string',
        'warna_air' => 'required|string',
        'catatan' => 'required|string',
        'id_fase_tambak' => 'required',
    ]);

        // Simpan data ke dalam database
        $kualitasairs = new KualitasAirModel();
        $kualitasairs->kd_kualitas_air = $request->kd_kualitas_air;
        $kualitasairs->tanggal_cek = $request->tanggal_cek;
        $kualitasairs->waktu_cek = $request->waktu_cek;
        $kualitasairs->pH = $request->pH;
        $kualitasairs->salinitas = $request->salinitas;
        $kualitasairs->DO = $request->DO;
        $kualitasairs->suhu = $request->suhu;
        $kualitasairs->kejernihan_air = $request->kejernihan_air;
        $kualitasairs->warna_air = $request->warna_air;
        $kualitasairs->catatan = $request->catatan;
        $kualitasairs->id_fase_tambak = $request->id_fase_tambak;

    $kualitasairs->save();

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('kualitasair.index');
}

public function show($id)
{
    $kualitasairs = KualitasAirModel::with('faseKolam')->find($id); // Ambil data tambak dengan relasi fase
    if (!$kualitasairs) {
        return response()->json(['error' => 'Tambak tidak ditemukan.'], 404);
    }

    // Render view dengan data tambak
    $view = view('kualitasair.show', compact('kualitasairs'))->render();
    return response()->json(['html' => $view]);
}

public function edit(string $id){
    $kualitasairs = KualitasAirModel::find($id);
    $faseKolam = FaseKolamModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit Data Kualitas Air',
        'paragraph' => 'Berikut ini merupakan form edit data kualitas air yang terinput ke dalam sistem',
        'list' => [
            ['label' => 'Home', 'url' => route('dashboard.index')],
            ['label' => 'Kualitas Air', 'url' => route('kualitasair.index')],
            ['label' => 'Edit'],
        ]
    ];
    $activeMenu = 'kualitasAir';

    return view('kualitasair.edit', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'kualitasairs' => $kualitasairs, 'faseKolam' => $faseKolam]);
}

public function update(Request $request, string $id){
    $request->validate([
        'kd_kualitas_air' => 'required|string|max:255|unique:kualitas_air,kd_kualitas_air',
        'tanggal_cek' => 'required|date',
        'waktu_cek' => 'required',
        'pH' => 'required|integer',
        'salinitas' => 'required|integer',
        'DO' => 'required|integer',
        'suhu' => 'required|integer',
        'kejernihan_air' => 'required|string',
        'warna_air' => 'required|string',
        'catatan' => 'required|string',
        'id_fase_tambak' => 'required',
    ]);

    $kualitasairs = KualitasAirModel::findOrFail($id);
    
    $updateData = [
        'kd_kualitas_air' => $request->kd_kualitas_air,
        'tanggal_cek' => $request->tanggal_cek,
        'waktu_cek' => $request->waktu_cek,
        'pH' => $request->pH,
        'salinitas' => $request->salinitas,
        'DO' => $request->DO,
        'suhu' => $request->suhu,
        'kejernihan_air' => $request->kejernihan_air,
        'warna_air' => $request->warna_air,
        'catatan' => $request->catatan,
        'id_fase_tambak' => $request->id_fase_tambak,
    ];
    
    $kualitasairs->update($updateData);
    return redirect()->route('kualitasair.index');
}

public function destroy($id) {
    $kualitasairs = KualitasAirModel::findOrFail($id);
    // AncoModel::destroy($id);
    $kualitasairs->delete();
    return redirect()->route('kualitasair.index');
}

}