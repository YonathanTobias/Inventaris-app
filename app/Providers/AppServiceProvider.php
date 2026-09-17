<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;

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
        View::composer('layouts.app', function ($view) {
            try {
                $pendingAsetCount = 0;
                $pendingRuanganCount = 0;

                if (Schema::hasTable('peminjamans')) {
                    $pendingAsetCount = Peminjaman::where('status', 'Menunggu')->count();
                }

                if (Schema::hasTable('peminjaman_ruangans')) {
                    $pendingRuanganCount = PeminjamanRuangan::where('status', 'Menunggu')->count();
                }

                $view->with([
                    'pendingAsetCount' => $pendingAsetCount,
                    'pendingRuanganCount' => $pendingRuanganCount,
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'pendingAsetCount' => 0,
                    'pendingRuanganCount' => 0,
                ]);
            }
        });
    }
}
