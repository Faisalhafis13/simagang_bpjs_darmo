<nav class="bo-navbar">

    <div class="bo-navbar-left">

        {{-- Hamburger khusus mobile --}}
        <button
            type="button"
            class="sidebar-toggle"
            id="sidebarToggle"
            aria-label="Buka sidebar"
            aria-expanded="false"
        >
            <i class="bi bi-list"></i>
        </button>


        <h5 class="mb-0 fw-bold">

            @yield('title')

        </h5>

    </div>


    <div class="d-flex align-items-center gap-3">

        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button class="btn btn-danger btn-sm">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</nav>