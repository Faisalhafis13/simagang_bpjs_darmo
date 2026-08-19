<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.back-office.sidebar', function ($view) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan user sudah login
            |--------------------------------------------------------------------------
            */

            if (!Auth::check()) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Ambil user yang sedang login
            |--------------------------------------------------------------------------
            */

            $user = Auth::user();


            /*
            |--------------------------------------------------------------------------
            | Ambil menu berdasarkan Role Menu
            |--------------------------------------------------------------------------
            |
            | Semua role, termasuk Admin, mengikuti pengaturan Role Menu.
            |
            | Menu hanya ditampilkan apabila:
            | - role_id sesuai dengan role user
            | - status RoleMenu = active
            |
            */

            $menus = Menu::whereHas('roleMenus', function ($query) use ($user) {

                $query->where('role_id', $user->role_id)
                      ->where('status', 'active');

            })
            ->orderBy('urutan', 'asc')
            ->get();


            /*
            |--------------------------------------------------------------------------
            | Kirim menu ke sidebar
            |--------------------------------------------------------------------------
            */

            $view->with('menus', $menus);

        });
    }
}
