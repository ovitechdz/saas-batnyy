<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Vaccination;
use App\Models\BreedingCycle;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\AnimalRepositoryInterface::class,
            \App\Repositories\AnimalRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $notifications = collect([
                [
                    'type' => 'vaccination',
                    'title' => 'Vaccination Due',
                    'message' => "Sheep 142 (RFID-88219) is due for CD-T Vaccine.",
                    'date' => Carbon::now()->addDays(2),
                    'link' => '#'
                ],
                [
                    'type' => 'pregnancy',
                    'title' => 'Upcoming Lambing',
                    'message' => "Ewe 089 (RFID-99211) is expected to lamb soon.",
                    'date' => Carbon::now()->addDays(5),
                    'link' => '#'
                ],
                [
                    'type' => 'inventory',
                    'title' => 'Low Feed Stock',
                    'message' => "Alfalfa Hay is running low (850 kg left).",
                    'date' => Carbon::now()->subHours(1),
                    'link' => '#'
                ]
            ]);

            $notifications = $notifications->sortBy('date')->values();

            $view->with('notifications', $notifications);
        });
    }
}
