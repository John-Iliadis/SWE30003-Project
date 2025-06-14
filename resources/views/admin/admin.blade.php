<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <!-- Bootstrap CSS first to establish base styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Then custom styles to override as needed -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- Enable the pagination CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-pagination.css') }}">
</head>
<body>
    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main class="py-4">
        <div class="admin-container container">
            @yield('content')
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
