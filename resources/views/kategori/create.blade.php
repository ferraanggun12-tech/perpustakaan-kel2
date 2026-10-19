@extends('layouts.app')

@section('content')
<h1>Tambah Kategori</h1>

<form action="{{ route('kategori.store') }}" method="POST">
    @csrf
    <label>Nama kategori</label>
    <input type="text" name="nama" value="{{ old('nama') }}">
    @error('nama')<div class="error">{{ $message }}</div>@enderror

    <button type="submit">Simpan</button>
</form>
@endsection
