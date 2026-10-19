@extends('layouts.app')

@section('content')
<h1>Buku</h1>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<a href="{{ route('buku.create') }}">Tambah Buku</a>

<table>
    <tr><th>No</th><th>Judul</th><th>Penulis</th><th>Jenis</th><th>Kategori</th><th>Aksi</th></tr>
    @forelse ($buku as $b)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->jenis }}</td>
            <td>{{ $b->kategori->nama }}</td>
            <td>
                <a href="{{ route('buku.edit', $b) }}">Edit</a>
                <form action="{{ route('buku.destroy', $b) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6">Belum ada buku.</td></tr>
    @endforelse
</table>
@endsection
