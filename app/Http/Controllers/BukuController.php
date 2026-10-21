<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $buku = $query->get();

        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'jenis'       => 'required|in:ebook,fisik',
            'deskripsi'   => 'nullable|string',
            'stok'        => 'nullable|integer|min:0',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();
        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'jenis'       => 'required|in:ebook,fisik',
            'deskripsi'   => 'nullable|string',
            'stok'        => 'nullable|integer|min:0',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
