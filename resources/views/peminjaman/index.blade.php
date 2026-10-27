@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
<h1>Verifikasi Peminjaman</h1>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<a href="{{ route('peminjaman.create') }}">Ajukan Peminjaman</a>

<table>
    <tr>
        <th>Pengguna</th>
        <th>Buku</th>
        <th>Jenis</th>
        <th>Tanggal ajuan</th>
        <th>Status</th>
        <th>Batas waktu</th>
        <th>Tanggal kembali</th>
        <th>Aksi</th>
    </tr>
    @forelse ($peminjaman as $p)
        @php
            $terlambat = $p->status == 'dipinjam' && $p->batas_waktu && \Carbon\Carbon::parse($p->batas_waktu)->endOfDay()->isPast();
        @endphp
        <tr>
            <td>{{ $p->pengguna->nama }}</td>
            <td>{{ $p->buku->judul }}</td>
            <td>{{ $p->buku->jenis == 'ebook' ? 'E-book' : 'Buku fisik' }}</td>
            <td>{{ \Carbon\Carbon::parse($p->diajukan_pada)->translatedFormat('d M Y') }}</td>
            <td><span class="badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
            <td>
                @if ($p->batas_waktu)
                    {{ \Carbon\Carbon::parse($p->batas_waktu)->translatedFormat('d M Y') }}
                    @if ($terlambat)
                        <span class="terlambat">Terlambat</span>
                    @endif
                @else
                    -
                @endif
            </td>
            <td>
                {{ $p->dikembalikan_pada ? \Carbon\Carbon::parse($p->dikembalikan_pada)->translatedFormat('d M Y') : '-' }}
            </td>
            <td>
                @if ($p->status == 'diajukan')
                    <form action="{{ route('peminjaman.update', $p) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit">Setujui</button>
                    </form>
                    <form action="{{ route('peminjaman.update', $p) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit">Tolak</button>
                    </form>
                @elseif ($p->status == 'dipinjam')
                    <form action="{{ route('peminjaman.update', $p) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" onclick="return confirm('Tandai buku ini sudah dikembalikan?')">Tandai dikembalikan</button>
                    </form>
                    <a href="{{ route('peminjaman.edit', $p) }}">Ubah</a>
                @else
                    <a href="{{ route('peminjaman.edit', $p) }}">Ubah</a>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="8">Belum ada pengajuan peminjaman.</td></tr>
    @endforelse
</table>
@endsection
