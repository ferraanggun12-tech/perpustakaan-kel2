@extends('layouts.app')

@section('content')
<h1>Ubah Status Peminjaman</h1>

<p><strong>Peminjam:</strong> {{ $peminjaman->pengguna->nama }}</p>
<p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>

<form action="{{ route('peminjaman.update', $peminjaman) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Status</label>
    <select name="status">
        <option value="diajukan" {{ old('status', $peminjaman->status) == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
        <option value="disetujui" {{ old('status', $peminjaman->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="ditolak" {{ old('status', $peminjaman->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="selesai" {{ old('status', $peminjaman->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
