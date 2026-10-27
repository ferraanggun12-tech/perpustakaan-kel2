<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['judul', 'penulis', 'dibuat_pada'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : 'judul';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        $buku = Buku::with('kategori')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%");
                });
            })
            ->when(in_array($request->jenis, ['ebook', 'fisik']), function ($query) use ($request) {
                $query->where('jenis', $request->jenis);
            })
            ->when($request->filled('id_kategori'), function ($query) use ($request) {
                $query->where('id_kategori', $request->id_kategori);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $kategori = Kategori::orderBy('nama')->get();

        return view('buku.index', compact('buku', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'jenis'         => 'required|in:ebook,fisik',
            'deskripsi'     => 'nullable|string',
            'stok'          => 'nullable|integer|min:0',
            'gambar_sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar_sampul')) {
            $validated['gambar_sampul'] = Buku::simpanSampul($request->file('gambar_sampul'));
        }

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
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'jenis'         => 'required|in:ebook,fisik',
            'deskripsi'     => 'nullable|string',
            'stok'          => 'nullable|integer|min:0',
            'gambar_sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar_sampul')) {
            if ($buku->gambar_sampul) {
                Buku::hapusSampul($buku->gambar_sampul);
            }
            $validated['gambar_sampul'] = Buku::simpanSampul($request->file('gambar_sampul'));
        }

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->gambar_sampul) {
            Buku::hapusSampul($buku->gambar_sampul);
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}

