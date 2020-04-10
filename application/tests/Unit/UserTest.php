<?php

namespace Tests\Unit;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Services\StudyService;

class UserTest extends TestCase
{
    use  RefreshDatabase;

    use WithoutMiddleware;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_to_see_if_user_can_be_created()
    {



    $manager = $this->app->make(StudyService::class);

        $ox  =$manager->foo();


        $this->assertIsInt($ox);
        $response = $this->post('/api/login');

        $response->assertStatus(401);
        $response->assertUnauthorized();
        //$response->assertViewIs('auth.login');
        $resp = factory(User::class)->create();
        $this->actingAs($resp);
        $this->assertAuthenticated($guard = null);
        $resp = User::first();

        $response = $this->get('/api/user');
        $response->assertStatus(200);
        $arr = array
        (
            'email' => $resp->email,
            'name' => $resp->name,
            'access_level' => $resp->access_level,
           'note' =>  $resp->note
        );

        $response->assertJsonFragment($arr);
        $note = array('note'=> $resp->note);

        $response->assertSeeText($resp->name, $escaped = true);



    }

  /** @test */
  public function mad()
  {
      $this->assertTrue(true);


  }

}
