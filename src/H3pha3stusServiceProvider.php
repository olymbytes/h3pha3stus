<?php 

namespace Olymbytes\H3pha3stus;

use Illuminate\Support\ServiceProvider;
use Olymbytes\H3pha3stus\Commands\SortCommand;
use Olymbytes\H3pha3stus\Commands\FilterCommand;

class H3pha3stusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/h3pha3stus.php' => config_path('h3pha3stus.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/h3pha3stus.php', 'h3pha3stus');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            FilterCommand::class,
            SortCommand::class,
        ]);
    }
}
