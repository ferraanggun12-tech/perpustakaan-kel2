@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="banner"></div>
<div class="dash-title">DASHBOARD</div>

<div class="stats">
    <div class="stat c-yellow">
        <div><strong>{{ $jumlahBuku }}</strong><span>Total buku</span></div>
        <div class="ico">
            <svg width="30" height="30" viewBox="0 0 100 100" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="50,10 96,21 55,45 9,35" fill="#333"/>
                <polygon points="45,27 54,21 68,24 60,30" fill="#f39c12"/>
                <polygon points="36,46 43,46 43,63 39.5,59 36,62" fill="#f39c12"/>
                <g stroke="#333" stroke-width="4">
                    <path d="M33 53 L14 48 Q6 46 6 40 Q6 35 12 35"/>
                    <path d="M45 57 L55 59 L95 36"/>
                    <path d="M12 50 Q6 52 6 57 Q6 63 14 64 L55 74 L95 50"/>
                    <path d="M12 66 Q6 68 6 73 Q6 79 14 80 L55 88 L95 65"/>
                </g>
            </svg>
        </div>
    </div>
    <div class="stat c-blue">
        <div><strong>{{ $jumlahEbook }}</strong><span>E-book</span></div>
        <div class="ico">
            <svg width="28" height="28" viewBox="0 0 48 48">
                <path d="M12 3 H28 L41 16 V40 a5 5 0 0 1 -5 5 H12 a5 5 0 0 1 -5 -5 V8 a5 5 0 0 1 5 -5z" fill="#04123a"/>
                <path d="M28 3 L41 16 H33 a5 5 0 0 1 -5 -5z" fill="#c5d8f8"/>
                <rect x="12" y="9" width="12" height="2.2" rx="1.1" fill="#c5d8f8"/>
                <rect x="12" y="14" width="15" height="2.2" rx="1.1" fill="#c5d8f8"/>
                <rect x="12" y="19" width="18" height="2.2" rx="1.1" fill="#c5d8f8"/>
                <rect x="17" y="40" width="14" height="2" rx="1" fill="#c5d8f8"/>
            </svg>
        </div>
    </div>
    <div class="stat c-beige">
        <div><strong>{{ $jumlahFisik }}</strong><span>Buku fisik</span></div>
        <div class="ico">
            <svg width="30" height="30" viewBox="0 0 48 48" stroke-linejoin="round">
                <path d="M4 13 V38 C13 36 20 39 24 44 C28 39 35 36 44 38 V13 C36 14 29 16 24 20 C19 16 12 14 4 13z" fill="#111"/>
                <path d="M24 17 C20 11 12 9 7 10 V34 C14 34 20 36 24 40 Z" fill="#fff" stroke="#111" stroke-width="1.8"/>
                <path d="M24 17 C28 11 36 9 41 10 V34 C34 34 28 36 24 40 Z" fill="#fff" stroke="#111" stroke-width="1.8"/>
            </svg>
        </div>
    </div>
    <div class="stat c-green">
        <div><strong>{{ $jumlahKategori }}</strong><span>Kategori</span></div>
        <div class="ico">
            <svg width="30" height="30" viewBox="0 0 48 48" stroke="#3a66db" stroke-width="4.5" stroke-linejoin="round">
                <rect x="5" y="5" width="16" height="16" rx="6" fill="#d2d9e3"/>
                <rect x="27" y="5" width="16" height="16" rx="6" fill="#45a5f5"/>
                <rect x="5" y="27" width="16" height="16" rx="6" fill="#45a5f5"/>
                <rect x="27" y="27" width="16" height="16" rx="6" fill="#d2d9e3"/>
            </svg>
        </div>
    </div>
    <div class="stat c-lilac">
        <div><strong>{{ $jumlahPeminjaman }}</strong><span>Total peminjaman</span></div>
        <div class="ico">
            <svg width="30" height="30" viewBox="0 0 48 48" fill="#fff" stroke="#111" stroke-width="2.4" stroke-linejoin="round" stroke-linecap="round">
                <rect x="15" y="9" width="17" height="30"/>
                <path d="M32 9 V39" stroke-width="4"/>
                <rect x="5" y="5" width="16" height="9" rx="4.5"/>
                <rect x="27" y="34" width="16" height="9" rx="4.5"/>
            </svg>
        </div>
    </div>
</div>

<div class="panels">
    <div class="panel wide">
        <h3>TABEL</h3>
        <p class="sub">Pengajuan peminjaman terbaru ({{ $menunggu }} menunggu verifikasi)</p>
        <table>
            <tr><th>No</th><th>Pengguna</th><th>Buku</th><th>Tanggal</th><th>Status</th></tr>
            @forelse ($terbaru as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->pengguna->nama }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->diajukan_pada)->translatedFormat('d M Y') }}</td>
                    <td><span class="badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada pengajuan peminjaman.</td></tr>
            @endforelse
        </table>
        <a class="btn" href="{{ route('peminjaman.index') }}">Lihat Semua</a>
    </div>

    <div class="panel side">
        <h3>GRAFIK</h3>
        <p class="sub">Pengajuan peminjaman 6 bulan terakhir</p>
        <div class="chart">
            @foreach ($grafik as $g)
                <div class="bar-wrap">
                    {{ $g['jumlah'] }}
                    <div class="bar" style="height: {{ ($g['jumlah'] / $maks) * 85 }}%"></div>
                </div>
            @endforeach
        </div>
        <div class="chart-label">
            @foreach ($grafik as $g)
                <span>{{ $g['label'] }}</span>
            @endforeach
        </div>
    </div>
</div>
@endsection
