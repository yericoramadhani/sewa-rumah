<?php

namespace App\Http\Controllers;

use App\Models\LapanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LapanganController extends Controller
{
    public function tambah(Request $request)
    {
       
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'ukuran' => 'required',
            'harga' => 'required',
            'status' => 'required',
            'tipe' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $fileName = null;

        // Handle upload gambar`
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar_rumah'), $fileName);
        }

        $lapangan = LapanganModel::create([
            'rumah' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ukuran' => $request->ukuran,
            'harga' => $request->harga,
            'status' => $request->status,
            'type' => $request->tipe,
            'gambar' => $fileName ? 'gambar_rumah/' . $fileName : null
        ]);

        if ($lapangan) {
            echo('berhasil');
            return redirect()->route('lapangan')->with('berhasil_tambah', true);
        } else {
            echo('gagal');
            return redirect()->route('lapangan')->with('gagal_tambah', true);
        }
    }

    public function delete(Request $request, $id)
    {
        $lapangan = LapanganModel::findOrFail($id);

        // Hapus gambar jika ada
        if ($lapangan->gambar && file_exists(public_path($lapangan->gambar))) {
            unlink(public_path($lapangan->gambar));
        }

        $lapangan->delete();

        return redirect()->route('lapangan')->with('berhasil_hapus', true);
    }

    public function edit(Request $request, $id)
    {
        // Validasi input edit
        //  dd($request->all());
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'ukuran' => 'required',
            'harga' => 'required',
            'status' => 'required',
            // 'tipe' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $lapangan = LapanganModel::findOrFail($id);

        $lapangan->rumah = $request->nama;
        $lapangan->deskripsi = $request->deskripsi;
        $lapangan->ukuran = $request->ukuran;
        $lapangan->harga= $request->harga;
        $lapangan->status = $request->status;
        $lapangan->type = $request->tipe;

        // Update gambar jika ada upload baru
        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar && file_exists(public_path($lapangan->gambar))) {
                unlink(public_path($lapangan->gambar));
            }

            $file = $request->file('gambar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar_rumah'), $fileName);

            $lapangan->gambar = 'gambar_rumah/' . $fileName;
        }

        $lapangan->save();

        return redirect()->route('lapangan')->with('berhasil_edit', true);
    }
}
