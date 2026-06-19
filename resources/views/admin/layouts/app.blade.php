<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - ELEDJI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #F9F9F9;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #1C1C1C;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 20px;
            border-bottom: 1px solid #2d2d2d;
            text-align: center;
        }
        .sidebar-logo .logo {
            width: 50px;
            height: 50px;
            background: #C0392B;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            font-size: 12px;
        }
        .sidebar-logo .name {
            font-size: 14px;
            font-weight: 600;
        }
        .sidebar-menu {
            padding: 15px 0;
        }
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #9CA3AF;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            gap: 12px;
        }
        .menu-item:hover {
            background: #2d2d2d;
            color: white;
        }
        .menu-item.active {
            background: #8B1A1A;
            color: white;
        }
        .menu-item i {
            width: 20px;
            text-align: center;
        }
        .menu-badge {
            margin-left: auto;
            background: #C0392B;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 32px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1A1A1A;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #C0392B;
            color: white;
        }
        .btn-primary:hover {
            background: #a32f23;
        }
        .btn-secondary {
            background: #6B7280;
            color: white;
        }
        .btn-success {
            background: #10B981;
            color: white;
        }
        .btn-danger {
            background: #EF4444;
            color: white;
        }
        .btn-outline {
            border: 1px solid #D1D5DB;
            background: white;
            color: #374151;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            text-align: left;
            padding: 12px 16px;
            background: #F3F4F6;
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
        }
        .table td {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }
        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }
        .badge-danger {
            background: #FEE2E2;
            color: #991B1B;
        }
        .badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        .form-input:focus {
            outline: none;
            border-color: #C0392B;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
        }
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
        }
        .alert-danger {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        @include('admin.layouts.sidebar')
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>