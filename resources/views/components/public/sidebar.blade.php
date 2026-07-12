<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebarMenu">

    <div class="offcanvas-header">

        <div>

            <h4 class="fw-bold mb-0">
                SIMAGANG
            </h4>

            <small>BPJS Ketenagakerjaan</small>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body">

        <a href="{{ route('home') }}"
            class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">

            <i class="bi bi-house-door-fill"></i>

            Home

        </a>

        <a href="{{ route('pengajuan') }}"
            class="menu-item {{ request()->routeIs('pengajuan') ? 'active' : '' }}">

            <i class="bi bi-file-earmark-plus-fill"></i>

            Pengajuan Baru

        </a>

        <a href="{{ route('hasil') }}"
            class="menu-item {{ request()->routeIs('hasil') ? 'active' : '' }}">

            <i class="bi bi-search"></i>

            Lihat Hasil

        </a>

        <a href="{{ route('login') }}"
            class="menu-item {{ request()->routeIs('login') ? 'active' : '' }}">

            <i class="bi bi-box-arrow-in-right"></i>

            Login

        </a>

    </div>

</div>