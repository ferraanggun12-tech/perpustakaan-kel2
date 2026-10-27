@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<h1>Edit Buku</h1>

<form action="{{ route('buku.update', $buku) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>Kategori</label>
    <select name="id_kategori">
        @foreach ($kategori as $k)
            <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $buku->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                {{ $k->nama }}
            </option>
        @endforeach
    </select>
    @error('id_kategori')<div class="error">{{ $message }}</div>@enderror

    <label>Judul</label>
    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}">
    @error('judul')<div class="error">{{ $message }}</div>@enderror

    <label>Penulis</label>
    <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}">
    @error('penulis')<div class="error">{{ $message }}</div>@enderror

    <label>Jenis</label>
    <select name="jenis">
        <option value="ebook" {{ old('jenis', $buku->jenis) == 'ebook' ? 'selected' : '' }}>E-book</option>
        <option value="fisik" {{ old('jenis', $buku->jenis) == 'fisik' ? 'selected' : '' }}>Buku fisik</option>
    </select>

    <label>Deskripsi</label>
    <textarea name="deskripsi">{{ old('deskripsi', $buku->deskripsi) }}</textarea>

    <label>Stok</label>
    <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}">
    @error('stok')<div class="error">{{ $message }}</div>@enderror

    <label>Foto sampul</label>
    @if ($buku->gambar_sampul)
        <div><img class="thumb-besar" src="{{ $buku->sampul_url }}" alt="Sampul {{ $buku->judul }}"></div>
        <small>Pilih file baru hanya jika ingin mengganti sampul.</small><br>
    @endif
    <input type="file" name="gambar_sampul" accept="image/*">
    @error('gambar_sampul')<div class="error">{{ $message }}</div>@enderror

    <button type="submit">Update</button>
</form>
@endsection


