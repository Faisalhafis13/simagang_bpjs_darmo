<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        URL::forceScheme('https');
        View::composer('components.back-office.sidebar', function ($view) {
            if (!Auth::check()) {
                return;
            }
            $user = Auth::user();
            $menus = Menu::whereHas('roleMenus', function ($query) use ($user) {
                $query->where('role_id', $user->role_id)
                ->where('status', 'active');
            })
            ->orderBy('urutan', 'asc')
            ->get();
            $view->with('menus', $menus);
        });
    }
}