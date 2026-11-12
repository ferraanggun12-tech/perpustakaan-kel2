@php $admin = Auth::user()->isAdmin(); @endphp
<div class="sidebar">
    <h2>{{ $admin ? 'SIBINER Admin' : 'SIBINER' }}</h2>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    @if ($admin)
        <a href="{{ route('kategori.index') }}">Kategori</a>
    @endif
    <a href="{{ route('buku.index') }}">{{ $admin ? 'Kelola Buku' : 'Daftar Buku' }}</a>
    <a href="{{ route('peminjaman.index') }}">{{ $admin ? 'Peminjaman' : 'Peminjaman Saya' }}</a>

    <div class="sidebar-user">
        <div class="sidebar-user-nama">{{ Auth::user()->nama }}</div>
        <small>{{ ucfirst(Auth::user()->peran) }}</small>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
