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
    public function test_pagina_principal_carrega()
    {
        $this->get('/')
            ->assertSee('Cacoma')
              ->assertStatus(200);
    }
  
  public function test_home_carrega()
    {
        $this->get('/home')
          ->assertSee('Login');
        //$response->assertStatus(302); //deveria ser 200
    }
}
