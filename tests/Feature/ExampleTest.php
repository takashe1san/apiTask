<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_add_ads()
    {
        $response = $this->post('api/ads/add', [
            'name' => 'Ads from test',
            'description' => 'ads description',
            'category' => 1
        ]);

        $response->assertOk();
    }
}
