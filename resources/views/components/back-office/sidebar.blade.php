<aside class="sidebar">

    <div class="sidebar-header">

        <img src="{{ asset('assets/images/bpjslogo.jpg') }}"
             alt="Logo">

        <div>

            <h5>SIMAGANG</h5>

        </div>

    </div>

<div class="sidebar-menu">

@foreach($menus as $menu)

<a href="{{ route($menu->route) }}"
   class="menu-item {{ request()->routeIs($menu->route) ? 'active' : '' }}">

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