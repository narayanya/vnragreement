<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class VarietyPageTest extends TestCase
{
    public function test_variety_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/master/variety');

        $response->assertOk();
        $response->assertSee('Variety');
    }
}
