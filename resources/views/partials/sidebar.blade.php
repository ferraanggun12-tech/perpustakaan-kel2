@php
    $user = Auth::user();
    $staf = $user->isStaf();
@endphp
<div class="sidebar">
    <h2>{{ match ($user->peran) { 'admin' => 'SIBINER Admin', 'petugas' => 'SIBINER Petugas', default => 'SIBINER' } }}</h2>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    @if ($user->hasRole('admin'))
        <a href="{{ route('kategori.index') }}">Kategori</a>
    @endif
    <a href="{{ route('buku.index') }}">{{ $staf ? 'Kelola Buku' : 'Daftar Buku' }}</a>
    <a href="{{ route('peminjaman.index') }}">{{ $staf ? 'Peminjaman' : 'Peminjaman Saya' }}</a>

    <div class="sidebar-user">
        <div class="sidebar-user-nama">{{ $user->nama }}</div>
        <small>{{ $user->label_peran }}</small>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
