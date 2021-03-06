<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Models\Student;
use App\Models\Internship;
use App\Observers\Student as StudentObserver;
use App\Observers\Internship as InternshipObserver;
use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Student::observe(StudentObserver::class);
        Internship::observe(InternshipObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {

        $this->app->singleton('HKarlstrom\OpenApiReader\OpenApiReader', function ($app) {

            return new OpenApiReader($app['config']['openapi']['schema_file_path']);

        });

    }

}
