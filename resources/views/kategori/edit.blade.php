@extends('layouts.app')

@section('content')
<h1>Edit Kategori</h1>

<form action="{{ route('kategori.update', $kategori) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nama kategori</label>
    <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}">
    @error('nama')<div class="error">{{ $message }}</div>@enderror

    <button type="submit">Update</button>
</form>
@endsection
