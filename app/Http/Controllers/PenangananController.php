<?php

namespace App\Http\Controllers;

use App\Models\PenangananModel;
use App\Models\FaseKolamModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenangananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Data Penanganan',
            'paragraph' => 'Berikut ini merupakan data penanganan yang terinput ke dalam sistem',
            'list' => [
                ['label' => 'Home', 'url' => route('penanganan.index')],
                ['label' => 'penanganan'],
            ]
        ];
        $activeMenu = 'penanganan';
        $fase_kolam = FaseKolamModel::all();
        return view('penanganan.index',['breadcrumb' =>$breadcrumb, 'activeMenu' => $activeMenu, 'fase_kolam' => $fase_kolam]);
    }

    // menampilkan data table    
    public function list(Request $request)
    {
        $penanganans = PenangananModel::select('id_penanganan', 'kd_penanganan', 'tanggal_cek', 'waktu_cek', 'pemberian_mineral','pemberian_vitamin','pemberian_obat', 'penambahan_air', 'pengurangan_air', 'catatan','id_fase_tambak', 'created_at', 'updated_at')->with('faseKolam'); 
        return DataTables::of($penanganans)
        ->make(true);
    }


    public function create(){
        $breadcrumb = (object) [
            'title' => 'Tambah Data Penanganan',
            'paragraph' => 'Berikut ini merupakan form tambah data penanganan yang terinput ke dalam sistem',
            'list' => [
                ['label' => 'Home', 'url' => route('dashboard.index')],
                ['label' => 'penanganan', 'url' => route('penanganan.index')],
                ['label' => 'Tambah'],
            ]
    ];
    $activeMenu = 'penanganan';
    $fase_kolam = FaseKolamModel::all();
    return view('penanganan.create',['breadcrumb' =>$breadcrumb, 'activeMenu' => $activeMenu, 'fase_kolam' => $fase_kolam]);
}

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'kd_penanganan' => 'required|string|max:255|unique:penanganan,kd_penanganan',
        'tanggal_cek' => 'required|date',
        'waktu_cek' => 'required',
        'pemberian_mineral' => 'required|integer',
        'pemberian_vitamin' => 'required|integer',
        'pemberian_obat' => 'required|integer',
        'penambahan_air' => 'required|integer',
        'pengurangan_air' => 'required|integer',
        'catatan' => 'required|string',
        'id_fase_tambak' => 'required',
    ]);

    // Simpan data ke dalam database
    $penanganans = new PenangananModel();
    $penanganans->kd_penanganan = $request->kd_penanganan;
    $penanganans->tanggal_cek = $request->tanggal_cek;
    $penanganans->waktu_cek = $request->waktu_cek;
    $penanganans->pemberian_mineral = $request->pemberian_mineral;
    $penanganans->pemberian_vitamin = $request->pemberian_vitamin;
    $penanganans->pemberian_obat = $request->pemberian_obat;
    $penanganans->penambahan_air = $request->penambahan_air;
    $penanganans->pengurangan_air = $request->pengurangan_air;
    $penanganans->catatan = $request->catatan;
    $penanganans->id_fase_tambak = $request->id_fase_tambak;

    $penanganans->save();

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('penanganan.index')->with('success', 'Data penangan berhasil ditambahkan');
}

public function show($id)
{
    $penanganans = PenangananModel::with('faseKolam')->find($id); // Ambil data tambak dengan relasi fase
    if (!$penanganans) {
        return response()->json(['error' => 'Penanganan tidak ditemukan.'], 404);
    }

    // Render view dengan data tambak
    $view = view('penanganan.show', compact('penanganans'))->render();
    return response()->json(['html' => $view]);
}

public function edit(string $id){
    $penanganans = PenangananModel::find($id);
    $faseKolam = FaseKolamModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit Data Penanganan',
        'paragraph' => 'Berikut ini merupakan form edit data Penanganan yang terinput ke dalam sistem',
        'list' => [
            ['label' => 'Home', 'url' => route('dashboard.index')],
            ['label' => 'Penanganan', 'url' => route('penanganan.index')],
            ['label' => 'Edit'],
        ]
    ];
    $activeMenu = 'penanganan';

    return view('penanganan.edit', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'penanganans' => $penanganans, 'faseKolam' => $faseKolam]);
}

public function update(Request $request, string $id){
    $request->validate([
        'kd_penanganan' => 'required|string|max:255|unique:penanganan,kd_penanganan',
        'tanggal_cek' => 'required|date',
        'waktu_cek' => 'required',
        'pemberian_mineral' => 'required|integer',
        'pemberian_vitamin' => 'required|integer',
        'pemberian_obat' => 'required|integer',
        'penambahan_air' => 'required|integer',
        'pengurangan_air' => 'required|integer',
        'catatan' => 'required|string',
        'id_fase_tambak' => 'required',
    ]);

    $penanganans = PenangananModel::findOrFail($id);
    
    $updateData = [
        'kd_penanganan' => $request->kd_penanganan,
        'tanggal_cek' => $request->tanggal_cek,
        'waktu_cek' => $request->waktu_cek,
        'pemberian_mineral' => $request->pemberian_mineral,
        'pemberian_vitamin' => $request->pemberian_vitamin,
        'pemberian_obat' => $request->pemberian_obat,
        'penambahan_air' => $request->penambahan_air,
        'pengurangan_air' => $request->pengurangan_air,
        'catatan' => $request->catatan,
        'id_fase_tambak' => $request->id_fase_tambak, 
    ];
    
    $penanganans->update($updateData);
    return redirect()->route('penanganan.index');
}

public function destroy($id) {
    $penanganans = PenangananModel::findOrFail($id);
    // AncoModel::destroy($id);
    $penanganans->delete();
    return redirect()->route('penanganan.index');
}

}