<nav class="bo-navbar">

    <div>

        <h5 class="mb-0 fw-bold">

            @yield('title')

        </h5>

    </div>

    <div class="d-flex align-items-center gap-3">

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button class="btn btn-danger btn-sm">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </button>

        </form>

    </div>

</nav>