<?php 

namespace Olymbytes\H3pha3stus\Test;

use Olymbytes\H3pha3stus\Test\Models\City;
use Olymbytes\H3pha3stus\Test\Models\Country;

class ExampleTest extends TestCase
{
    /** @test */
    function it_has_seeded_database()
    {
        $this->assertCount(1, Country::all());
        $this->assertCount(5, City::all());
    }

    /** @test */
    function it_can_access_cities_endpoint()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', '/cities', [
            'filter' => [
                ['key' => 'code', 'value' => '2NP'],
            ],
        ]);

        $response->assertStatus(200);

        $this->assertCount(1, $response->decodeResponseJson());
    }
}