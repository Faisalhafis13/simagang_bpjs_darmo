<!DOCTYPE html>
<html lang="id">

<head>

    {{-- ===========================
    Meta
    =========================== --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ===========================
    Title
    =========================== --}}
    <title>@yield('title', 'SIMAGANG BPJS | BPJS Ketenagakerjaan Surabaya Darmo')</title>

    {{-- ===========================
    Favicon
    =========================== --}}
    {{-- <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}"> --}}

    {{-- ===========================
    Bootstrap
    =========================== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}">

    {{-- CSS Khusus Halaman --}}

    <link rel="stylesheet"
        href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
    @stack('css')

</head>

<body>

    @yield('content')

    {{-- ===========================
    JQuery
    =========================== --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/ajax.js') }}"></script>

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
    @stack('js')

</body>

</html>