<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CoreLocationTest extends TestCase
{
    public function test_core_location_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/core/location');

        $response->assertOk();
        $response->assertSee('Core Location');
    }
}
