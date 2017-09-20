<?php 

namespace Olymbytes\H3pha3stus\Test;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Olymbytes\H3pha3stus\Test\Models\City;
use Orchestra\Testbench\Exceptions\Handler;
use Olymbytes\H3pha3stus\Test\Models\Country;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Olymbytes\H3pha3stus\H3pha3stusServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Perform the set up for testing.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

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

    /**
     * Get the environment set up.
     * @param  $app
     * @return void
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['router']->get('/cities', function (Request $request) {
            return City::query()
                ->fieldSearchable($request->all())
                ->selectable($request->all())
                ->filterable($request->all())
                ->sortable($request->all())
                ->get();
        });
    }

    /**
     * Set up the database.
     */
    protected function setUpDatabase()
    {
        $this->createTables('cities', 'countries');

        $this->seedTables();
    }

    /**
     * Get the temp directory path.
     * @return string
     */
    public function getTempDirectory()
    {
        return __DIR__.'/temp';
    }

    /**
     * Create the provided tables.
     * @param   $tables
     */
    protected function createTables(...$tables)
    {
        collect($tables)->each(function ($table) {
            $method = 'create'.studly_case($table).'Table';

            if (!method_exists($this, $method)) {
                throw new \Exception("A method with name: {$method} could not be found.");
            }

            $this->{$method}();
        });
    }

    protected function createCitiesTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')
                ->unsigned();
            $table->string('name')->nullable();
            $table->string('code', 20)->unique();
            $table->timestamps();
        });
    }

    protected function createCountriesTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code', 20);
            $table->timestamps();
        });
    }

    /**
     * Seed the tables.
     */
    protected function seedTables()
    {
        $this->seedCountriesTable();
        $this->seedCitiesTable();
    }

    protected function seedCitiesTable()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('cities')->insert([
            'name' => 'PRATTELN',
            'code' => '2NP',
            'country_id' => $this->findCountryIdByCode('CH'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('cities')->insert([
            'name' => 'VISP',
            'code' => 'B8U',
            'country_id' => $this->findCountryIdByCode('CH'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('cities')->insert([
            'name' => 'SEON',
            'code' => '62R',
            'country_id' => $this->findCountryIdByCode('CH'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('cities')->insert([
            'name' => 'ZURICH',
            'code' => 'ZRH',
            'country_id' => $this->findCountryIdByCode('CH'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('cities')->insert([
            'name' => 'CHAVORNAY',
            'code' => 'KX-',
            'country_id' => $this->findCountryIdByCode('CH'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    protected function seedCountriesTable()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('countries')->insert([
            'name' => 'Switzerland',
            'code' => 'CH',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    protected function findCountryIdByCode($code)
    {
        return Country::where('code', 'CH')->first()->id;
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
        return $this;
    }
    protected function enableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}
