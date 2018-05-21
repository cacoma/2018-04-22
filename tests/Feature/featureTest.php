<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class featureTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_welcome_page_exists()
    {
        $this->get('/')
            ->assertSee('Cacoma')
              ->assertStatus(200);
    }
  
  public function test_login_exists()
    {
        $this->get('/login')
          ->assertSee('Login')
                ->assertStatus(200); //deveria ser 200
    }

    public function test_home_page_exists()
    {
        $this->get('/home')
          ->assertSee('portfolio');
    }
    
}
