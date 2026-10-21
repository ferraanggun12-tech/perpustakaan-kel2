@extends('layouts.app')

@section('content')
<h1>Ajukan Peminjaman</h1>

<form action="{{ route('peminjaman.store') }}" method="POST">
    @csrf
    <label>Buku</label>
    <select name="id_buku">
        <option value="">-- pilih buku --</option>
        @foreach ($buku as $b)
            <option value="{{ $b->id_buku }}" {{ old('id_buku') == $b->id_buku ? 'selected' : '' }}>
                {{ $b->judul }} ({{ $b->jenis }})
            </option>
        @endforeach
    </select>
    @error('id_buku')<div class="error">{{ $message }}</div>@enderror

    <label>Catatan (opsional)</label>
    <textarea name="catatan">{{ old('catatan') }}</textarea>

    <button type="submit">Ajukan</button>
</form>
@endsection
