<?php

namespace App\Console;

use App\Console\Commands\AddCountry;
use App\Console\Commands\AddLocation;
use App\Console\Commands\AddSubLocation;
use App\Console\Commands\ConfigureOpenApiSchema;
use App\Console\Commands\ListCountries;
use App\Console\Commands\ListLocations;
use App\Console\Commands\ListSubLocations;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ConfigureOpenApiSchema::class,
        AddCountry::class,
        ListCountries::class,
        AddLocation::class,
        ListLocations::class,
        AddSubLocation::class,
        ListSubLocations::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }

}
