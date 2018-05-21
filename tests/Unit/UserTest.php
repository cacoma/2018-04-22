<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testBasicTest()
    // {
    //     $this->assertTrue(true);
    // }

    public function test_invest_have_a_user()
    {
        $invest = factory('App\Invest')->create();

        $this->assertInstanceOf('App\User', $invest->user);
    }

    // public function test_path()
    // {
    //     $user = factory('App\User')->create();
    //     $this->assertDirectoryExists('/users/' . $user->id);
    // }
  

}
