<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','SIMAGANG')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

    @stack('css')

</head>

<body class="back-office">
<div class="wrapper">

    @include('components.back-office.sidebar')

    <div class="main">

        @include('components.back-office.navbar')

        <main class="content">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>