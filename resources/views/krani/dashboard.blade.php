<!DOCTYPE html>
<html>
<head>
    <title>Krani Dashboard</title>
</head>
<body>
    <h1>Dashboard Krani</h1>
    <p>Stok Masuk Hari Ini: {{ $data['stokMasukHariIni'] }} kg</p>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body>
</html>