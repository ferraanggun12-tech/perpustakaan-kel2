@extends('layouts.app')

@section('content')
<h1>Kategori</h1>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<a href="{{ route('kategori.create') }}">Tambah Kategori</a>

<table>
    <tr><th>No</th><th>Nama</th><th>Aksi</th></tr>
    @forelse ($kategori as $k)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $k->nama }}</td>
            <td>
                <a href="{{ route('kategori.edit', $k) }}">Edit</a>
                <form action="{{ route('kategori.destroy', $k) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="3">Belum ada kategori.</td></tr>
    @endforelse
</table>
@endsection
