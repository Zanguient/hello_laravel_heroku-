<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;


class ExampleTest extends TestCase
{
   use  RefreshDatabase;
   use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {


        $resp = factory(User::class)->make();
        $this->actingAs($resp);
        $resp = User::first();

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/login', ['email' => $resp->email, 'password' => $resp->password]);

        $response = $this->get('/api/user');
        $response->assertStatus(200);
//       $response = $this->get('/api/pull');
//
//        $response->assertStatus(200);
    }
}
