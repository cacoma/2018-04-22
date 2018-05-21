<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\WelcomePage;
use Tests\Browser\Pages\HomePage;

class MainTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
  //use DatabaseMigrations;

  public function setUp()
    {
        Parent::setUp();
        $this->user = factory(\App\User::class)->make(['password' => bcrypt('pass123')]) ;
        $this->user->save();
        // para passar pelo htaccess do apache
        $this->browse(function (Browser $browser) {
                $browser->visit('https://cacoma:pascal@cacoma.tk');
         });
    }

      public function test_ve_nome_cacoma_pagina_principal()
        {
            $this->browse(function (Browser $browser) {
                    $browser->visit(new WelcomePage)
                        ->assertSee('Cacoma');
            });
        }

    /** @test */
    // teste para logar no sistema com o usuario criado acima e que está sendo redirecionado para a pagina home
    public function when_users_login_successfully_they_are_redirected_to_home()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
            ->type('@email', $this->user->email)
            ->type('password', 'pass123')
            ->press('Login')
            ->assertPathIs('/home');
        });

    }

    public function users_see_carousel_home_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                ->assertSee('Composição de portfolio');
        });
    }
    // public function when_users_logout_they_are_redirected_to_


    public function tearDown()
  {
      parent::tearDown();

      $this->browse(function (Browser $browser) {
          $browser->driver->manage()->deleteAllCookies();
      });
      // after test is done. Delete test user
        $this->user->delete();
  }
}
