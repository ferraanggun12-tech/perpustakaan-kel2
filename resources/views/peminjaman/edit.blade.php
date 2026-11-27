@extends('layouts.app')

@section('title', 'Ubah Status Peminjaman')

@section('content')
<h1>Ubah Status Peminjaman</h1>

<p><strong>Peminjam:</strong> {{ $peminjaman->pengguna->nama }}</p>
<p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>

<form action="{{ route('peminjaman.update', $peminjaman) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Status (hanya transisi yang valid)</label>
    <select name="status">
        @foreach ($opsi as $nilai)
            <option value="{{ $nilai }}" @selected(old('status', $peminjaman->status) == $nilai)>{{ ucfirst($nilai) }}</option>
        @endforeach
    </select>
    @error('status')<div class="error">{{ $message }}</div>@enderror

    <label>Batas Waktu</label>
    <input type="date" name="batas_waktu" value="{{ old('batas_waktu', $peminjaman->batas_waktu) }}">
    @error('batas_waktu')<div class="error">{{ $message }}</div>@enderror

    <label>Catatan</label>
    <textarea name="catatan">{{ old('catatan', $peminjaman->catatan) }}</textarea>

    <button type="submit">Update</button>
</form>
@endsection
