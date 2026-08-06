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

        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->role->name === 'Admin') {

            $menus = Menu::orderBy('urutan','asc')->get();

        } else {

            $menus = Menu::whereHas('roleMenus', function ($q) use ($user) {

                $q->where('role_id', $user->role_id)
                  ->where('status', 'active');

            })
            ->orderBy('urutan','asc')
            ->get();

        }

        $view->with('menus', $menus);

    });
}}