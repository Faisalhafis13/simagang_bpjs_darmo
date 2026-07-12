<aside class="sidebar">

    <div class="sidebar-header">

        <img src="{{ asset('assets/images/bpjslogo.jpg') }}"
             alt="Logo">

        <div>

            <h5>SIMAGANG</h5>

            <small>BPJS Ketenagakerjaan</small>

        </div>

    </div>

    <div class="sidebar-menu">

        <a href="{{ route('back-office.dashboard') }}"
           class="menu-item {{ request()->routeIs('back-office.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2-fill"></i>

            Dashboard

        </a>

        <div class="menu-title">

            Pengaturan

        </div>

        <a href="#"
           class="menu-item">

            <i class="bi bi-people-fill"></i>

            Role User

        </a>

        <a href="#"
           class="menu-item">

            <i class="bi bi-shield-lock-fill"></i>

            Role Menu

        </a>

    </div>

    <div class="sidebar-footer">

        <small>

            © {{ date('Y') }} SIMAGANG

        </small>

    </div>

</aside>