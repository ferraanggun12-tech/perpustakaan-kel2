@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
@php $admin = Auth::user()->isAdmin(); @endphp
<h1>{{ $admin ? 'Kelola Buku' : 'Daftar Buku' }}</h1>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

@if ($admin)
    <a href="{{ route('buku.create') }}">Tambah Buku</a>
@endif

<form class="filter-form" method="GET" action="{{ route('buku.index') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul / penulis">

    <select name="jenis">
        <option value="">Semua jenis</option>
        <option value="ebook" @selected(request('jenis') === 'ebook')>E-book</option>
        <option value="fisik" @selected(request('jenis') === 'fisik')>Buku fisik</option>
    </select>

    <select name="id_kategori">
        <option value="">Semua kategori</option>
        @foreach ($kategori as $k)
            <option value="{{ $k->id_kategori }}" @selected(request('id_kategori') == $k->id_kategori)>{{ $k->nama }}</option>
        @endforeach
    </select>

    <select name="sort">
        <option value="judul" @selected(request('sort') === 'judul')>Urut: Judul</option>
        <option value="penulis" @selected(request('sort') === 'penulis')>Urut: Penulis</option>
        <option value="dibuat_pada" @selected(request('sort') === 'dibuat_pada')>Urut: Tanggal input</option>
    </select>

    <select name="direction">
        <option value="asc" @selected(request('direction') !== 'desc')>A-Z / Terlama</option>
        <option value="desc" @selected(request('direction') === 'desc')>Z-A / Terbaru</option>
    </select>

    <button type="submit">Terapkan</button>
    <a href="{{ route('buku.index') }}">Reset</a>
</form>

@if (request('search'))
    <p class="info">Hasil pencarian: <strong>{{ request('search') }}</strong></p>
@endif

<p class="info">Menampilkan {{ $buku->count() }} dari {{ $buku->total() }} data.</p>

<table>
    <tr>
        <th>No</th><th>Sampul</th><th>Judul</th><th>Penulis</th><th>Jenis</th><th>Kategori</th>
        @if ($admin)<th>Aksi</th>@endif
    </tr>
    @forelse ($buku as $b)
        <tr>
            <td>{{ $buku->firstItem() + $loop->index }}</td>
            <td><img class="thumb" src="{{ $b->sampul_url }}" alt="Sampul {{ $b->judul }}"></td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->jenis }}</td>
            <td>{{ $b->kategori->nama }}</td>
            @if ($admin)
                <td>
                    <a href="{{ route('buku.edit', $b) }}">Edit</a>
                    <form action="{{ route('buku.destroy', $b) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                    </form>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="{{ $admin ? 7 : 6 }}">
                @if (request()->hasAny(['search', 'jenis', 'id_kategori']))
                    Tidak ada buku yang cocok dengan pencarian atau filter. <a href="{{ route('buku.index') }}">Reset filter</a>
                @else
                    Belum ada buku.
                @endif
            </td>
        </tr>
    @endforelse
</table>

{{ $buku->links() }}
@endsection
