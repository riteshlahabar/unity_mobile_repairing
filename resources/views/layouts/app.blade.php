<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard | Mifty')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- CSS Files -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/icons.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/app.min.css" type="text/css">
</head>

<body>
    <!-- Topbar -->
    @include('partials.topbar')
    
    <!-- Sidebar -->
    @include('partials.sidebar')
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
        </div>
        
        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Scripts -->
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
