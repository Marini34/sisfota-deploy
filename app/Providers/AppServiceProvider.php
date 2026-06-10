<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;

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
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        //
        View::composer('*', function ($view) {

            $notifs = collect();

            if (Auth::check()) {
                $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->value('id');

                if ($dosenId) {
                    $notifs = Notifikasi::where('dosen_id', $dosenId)
                        ->latest()
                        // ->take(5) // tampilkan 5 terbaru
                        ->get();
                }
            }

            $view->with('notifikasis', $notifs);
        });
    }
}
