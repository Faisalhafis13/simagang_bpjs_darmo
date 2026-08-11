<header class="header">

<div class="container">
    <div class="header-wrapper">

        <a
            href="{{ route('home') }}"
            class="logo"
            aria-label="SIMAGANG BPJS Ketenagakerjaan"
        >

            <div class="logo-image">
                <img
                    src="{{ asset('assets/images/bpjslogo.jpg') }}"
                    alt="Logo BPJS Ketenagakerjaan"
                >
            </div>

            <div class="logo-text">
                <span class="logo-title">
                    SIMAGANG
                </span>

                <span class="logo-subtitle">
                    BPJS Ketenagakerjaan
                </span>
            </div>

        </a>

        <button
            type="button"
            class="menu-button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu"
            aria-controls="sidebarMenu"
            aria-label="Buka menu navigasi"
        >
            <i class="bi bi-list"></i>
        </button>

    </div>
</div>

</header>
