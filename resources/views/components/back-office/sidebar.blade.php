{{-- SIDEBAR --}}
<aside class="sidebar" id="backOfficeSidebar">

    <div class="sidebar-header">

        <img
            src="{{ asset('assets/images/bpjslogo.jpg') }}"
            alt="Logo"
        >

        <div>
            <h5>SIMAGANG</h5>
        </div>

        {{-- Tombol close khusus mobile --}}
        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose"
            aria-label="Tutup sidebar"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>


    <div class="sidebar-menu">

        @foreach($menus as $menu)

            <a
                href="{{ route($menu->route) }}"
                class="menu-item {{ request()->routeIs($menu->route) ? 'active' : '' }}"
            >
                {{ $menu->name }}
            </a>

        @endforeach

    </div>


    <div class="sidebar-footer">

        <small>
            © {{ date('Y') }} SIMAGANG
        </small>

    </div>

</aside>


{{-- BACKDROP MOBILE --}}
<div
    class="sidebar-backdrop"
    id="sidebarBackdrop"
></div>