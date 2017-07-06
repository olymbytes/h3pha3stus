<?php 

namespace Spatie\LaravelBlink\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Olymbytes\H3pha3stus\H3pha3stusServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            H3pha3stusServiceProvider::class,
        ];
    }
}