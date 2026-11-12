@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<h1>SIBINER</h1>
<p>Masuk ke Perpustakaan Digital</p>

@if (session('status'))
    <div class="alert">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" autofocus>
    @error('email')<div class="error">{{ $message }}</div>@enderror

    <label>Password</label>
    <input type="password" name="password">
    @error('password')<div class="error">{{ $message }}</div>@enderror

    <button type="submit">Login</button>
</form>
@endsection
