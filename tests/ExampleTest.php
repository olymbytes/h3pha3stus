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
    function it_can_filter_cities()
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

    /** @test */
    function it_can_sort_cities_in_descending_order()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', '/cities', [
            'sort' => 'code|desc',
        ]);

        $response->assertStatus(200);

        $cities = $response->decodeResponseJson();
        $highestValue = $cities[0]['code'];
        foreach ($cities as $city) {
            $this->assertTrue($city['code'] <= $highestValue);
        }
    }

    /** @test */
    function it_can_sort_cities_in_ascending_order()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', '/cities', [
            'sort' => 'code|asc',
        ]);

        $response->assertStatus(200);

        $cities = $response->decodeResponseJson();
        $lowestValue = $cities[0]['code'];
        foreach ($cities as $city) {
            $this->assertTrue($city['code'] >= $lowestValue);
        }
    }
}
