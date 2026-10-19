<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Digital SIBINER</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; display: flex; }
        .sidebar { width: 220px; height: 100vh; background: #2c3e50; color: #fff; padding: 20px 0; position: fixed; }
        .sidebar h2 { text-align: center; font-size: 18px; margin-bottom: 30px; }
        .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px 20px; }
        .sidebar a:hover { background: #34495e; }
        .main { margin-left: 220px; padding: 30px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        .alert { background: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .error { color: red; font-size: 13px; }
        form label { display: block; margin-top: 10px; }
        input, select, textarea { width: 300px; padding: 6px; margin-top: 4px; }
        button { margin-top: 15px; padding: 8px 16px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SIBINER Admin</h2>
        <a href="{{ url('/') }}">Dashboard</a>
        <a href="{{ route('kategori.index') }}">Kategori</a>
        <a href="{{ route('buku.index') }}">Kelola Buku</a>
    </div>
    <div class="main">
        @yield('content')
    </div>
</body>
</html>
