<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Core\Notification;
use App\Models\Core\Transaction;
use Carbon\Carbon;

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
        Paginator::useBootstrapFour();
        
        View::composer('*', function ($view) {
            if (Schema::hasTable('notifications')) {
                $unreadCount = Notification::query()->where('is_read', false)->count();
                $latestNotifications = Notification::query()->latest()->take(5)->get();
                $view->with('unreadCount', $unreadCount)->with('latestNotifications', $latestNotifications);
            }

            // Share Today's Earnings for Driver Portal
            $driverId = request()->route('id');
            if ($driverId && Schema::hasTable('transactions')) {
                $todayEarnings = Transaction::query()->where('driver_id', $driverId)
                    ->whereDate('created_at', Carbon::today())
                    ->sum('driver_amount');
                $view->with('todayEarningsGlobal', $todayEarnings);
            }
        });
    }

}
