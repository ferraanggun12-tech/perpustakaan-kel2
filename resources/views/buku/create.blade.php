@extends('layouts.app')

@section('content')
<h1>Tambah Buku</h1>

<form action="{{ route('buku.store') }}" method="POST">
    @csrf
    <label>Kategori</label>
    <select name="id_kategori">
        <option value="">-- pilih kategori --</option>
        @foreach ($kategori as $k)
            <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                {{ $k->nama }}
            </option>
        @endforeach
    </select>
    @error('id_kategori')<div class="error">{{ $message }}</div>@enderror

    <label>Judul</label>
    <input type="text" name="judul" value="{{ old('judul') }}">
    @error('judul')<div class="error">{{ $message }}</div>@enderror

    <label>Penulis</label>
    <input type="text" name="penulis" value="{{ old('penulis') }}">
    @error('penulis')<div class="error">{{ $message }}</div>@enderror

    <label>Jenis</label>
    <select name="jenis">
        <option value="ebook" {{ old('jenis') == 'ebook' ? 'selected' : '' }}>E-book</option>
        <option value="fisik" {{ old('jenis') == 'fisik' ? 'selected' : '' }}>Buku fisik</option>
    </select>
    @error('jenis')<div class="error">{{ $message }}</div>@enderror

    <label>Deskripsi</label>
    <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>

    <label>Stok (khusus buku fisik)</label>
    <input type="number" name="stok" value="{{ old('stok') }}">

    <button type="submit">Simpan</button>
</form>
@endsection
