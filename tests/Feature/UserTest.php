<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllUsers()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->get(route('users.all').'?q=test');
        $response->assertViewHas('allUsers');
        $response->assertOk();
    }
}
