<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'SIMAGANG')
    </title>


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    {{-- Custom CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}"
    >


    {{-- DataTables --}}
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css"
    >


    @stack('css')

</head>


<body class="back-office">


    {{-- ===================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ===================================================== --}}

    @include('components.back-office.sidebar')


    {{-- ===================================================== --}}
    {{-- MAIN --}}
    {{-- ===================================================== --}}

    <div class="main">


        {{-- NAVBAR --}}

        @include('components.back-office.navbar')


        {{-- CONTENT --}}

        <main class="content">

            @yield('content')

        </main>


    </div>


    {{-- ===================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- ===================================================== --}}


    {{-- jQuery --}}
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>


    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
    </script>


    {{-- DataTables --}}
    <script
        src="https://cdn.datatables.net/2.3.2/js/dataTables.js">
    </script>

    <script
        src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js">
    </script>


    {{-- ===================================================== --}}
    {{-- SWEETALERT2 --}}
    {{-- ===================================================== --}}

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>


    {{-- ===================================================== --}}
    {{-- GLOBAL LOGIN SUCCESS --}}
    {{-- ===================================================== --}}

    @if(session('login_success'))

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    Swal.fire({

                        icon: 'success',

                        title: 'Login Berhasil',

                        text: @json(session('login_success')),

                        timer: 2000,

                        timerProgressBar: true,

                        showConfirmButton: false

                    });

                }
            );

        </script>

    @endif

<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('backOfficeSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const close = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');


    if (!sidebar || !toggle || !backdrop) {
        return;
    }


    function openSidebar() {

        sidebar.classList.add('show');

        backdrop.classList.add('show');

        toggle.setAttribute('aria-expanded', 'true');

        document.body.classList.add('sidebar-open');
    }


    function closeSidebar() {

        sidebar.classList.remove('show');

        backdrop.classList.remove('show');

        toggle.setAttribute('aria-expanded', 'false');

        document.body.classList.remove('sidebar-open');
    }


    toggle.addEventListener('click', function () {

        if (sidebar.classList.contains('show')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });


    if (close) {

        close.addEventListener('click', function () {

            closeSidebar();

        });

    }


    backdrop.addEventListener('click', function () {

        closeSidebar();

    });


    /* Tutup sidebar setelah klik menu di mobile */

    sidebar.querySelectorAll('.menu-item').forEach(function (item) {

        item.addEventListener('click', function () {

            if (window.innerWidth <= 768) {

                closeSidebar();

            }

        });

    });


    /* ESC untuk menutup sidebar */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeSidebar();

        }

    });


    /* Reset ketika kembali ke desktop */

    window.addEventListener('resize', function () {

        if (window.innerWidth > 768) {

            closeSidebar();

        }

    });

});

</script>

    @stack('js')

</body>

</html>
