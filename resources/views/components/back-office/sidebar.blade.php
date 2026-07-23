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

        <a href="{{ route('back-office.pengajuan') }}"
           class="menu-item {{ request()->routeIs('back-office.pengajuan') ? 'active' : '' }}">

            <i class="bi bi-folder-fill"></i>

            Data Pengajuan

        </a>

        <a href="{{ route('back-office.peserta') }}"
           class="menu-item {{ request()->routeIs('back-office.peserta') ? 'active' : '' }}">

            <i class="bi bi-people-fill"></i>

            Data Peserta

        </a>

        <a href="{{ route('back-office.perguruan-tinggi') }}"
           class="menu-item {{ request()->routeIs('back-office.perguruan-tinggi') ? 'active' : '' }}">

            <i class="bi bi-building"></i>

            Data Perguruan Tinggi

        </a>

        <a href="{{ route('back-office.logbook') }}"
           class="menu-item {{ request()->routeIs('back-office.logbook') ? 'active' : '' }}">

            <i class="bi bi-journal-text"></i>

            Monitoring Logbook

        </a>

        <a href="{{ route('back-office.mentor') }}"
           class="menu-item {{ request()->routeIs('back-office.mentor') ? 'active' : '' }}">

            <i class="bi bi-person-badge-fill"></i>

            Data Mentor

        </a>

        <a href="{{ route('back-office.role-user') }}"
           class="menu-item {{ request()->routeIs('back-office.role-user') ? 'active' : '' }}">

            <i class="bi bi-people"></i>

            Role User

        </a>

    </div>

    <div class="sidebar-footer">

        <small>

            © {{ date('Y') }} SIMAGANG

        </small>

    </div>

</aside>