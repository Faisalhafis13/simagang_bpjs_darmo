<div
    class="offcanvas offcanvas-end public-sidebar"
    tabindex="-1"
    id="sidebarMenu"
    aria-labelledby="sidebarMenuLabel"
>

{{-- Sidebar Header --}}
<div class="offcanvas-header">

    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">
            <i class="bi bi-buildings"></i>
        </div>

        <div>
            <h4
                class="fw-bold mb-0"
                id="sidebarMenuLabel"
            >
                SIMAGANG
            </h4>

            <small>
                BPJS Ketenagakerjaan
            </small>
        </div>

    </div>

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="offcanvas"
        aria-label="Tutup menu"
    ></button>

</div>

{{-- Navigation --}}
<div class="offcanvas-body">

    <div class="sidebar-menu-label">
        MENU UTAMA
    </div>

    <nav>

        <a
            href="{{ route('home') }}"
            class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}"
        >
            <span class="menu-icon">
                <i class="bi bi-house-door-fill"></i>
            </span>

            <span>
                Home
            </span>
        </a>

        <a
            href="{{ route('pengajuan') }}"
            class="menu-item {{ request()->routeIs('pengajuan') ? 'active' : '' }}"
        >
            <span class="menu-icon">
                <i class="bi bi-file-earmark-plus-fill"></i>
            </span>

            <span>
                Pengajuan Baru
            </span>
        </a>

        <a
            href="{{ route('hasil') }}"
            class="menu-item {{ request()->routeIs('hasil') ? 'active' : '' }}"
        >
            <span class="menu-icon">
                <i class="bi bi-search"></i>
            </span>

            <span>
                Lihat Hasil
            </span>
        </a>

        <a
            href="{{ route('login') }}"
            class="menu-item {{ request()->routeIs('login') ? 'active' : '' }}"
        >
            <span class="menu-icon">
                <i class="bi bi-box-arrow-in-right"></i>
            </span>

            <span>
                Login 
            </span>
        </a>

    </nav>

</div>

{{-- Sidebar Footer --}}
<div class="sidebar-public-footer">

    <i class="bi bi-shield-check"></i>

    <span>
        Sistem Informasi Magang
    </span>

</div>

</div>
