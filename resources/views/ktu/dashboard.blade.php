<!DOCTYPE html>
<html>
<head>
    <title>KTU Dashboard</title>
</head>
<body>
    <h1>Dashboard KTU</h1>
    <p>Pending Approval: {{ $data['pendingApproval'] }}</p>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body>
</html>