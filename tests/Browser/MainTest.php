<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
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
  use DatabaseMigrations;

  public function setUp()
    {
        Parent::setUp();
        $this->user = factory(\App\User::class)->make(['password' => bcrypt('pass123')]) ;
        $this->user->save();
        $this->newUser = factory(\App\User::class)->make(['password' => bcrypt('random123')]);
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
            ->waitUntilMissing('@cubegrid')
            ->type('@loginemail', $this->user->email)
            ->type('@loginpassword', 'pass123')
            ->press('@loginsubmit')
            ->assertPathIs('/home');
        });

    }
    
    /** @test */
    public function users_see_carousel_home_page()
    {
        $this->browse(function (Browser $browser) {
             $browser->loginAs($this->user)
               ->visit(new HomePage)
                ->assertSee('Composição de portfolio')
                  ->assertSee('Cacoma')
                    ->assertSee('Home')
                      ->assertSee('Investimentos')
                        //->assertSee('@navbarlogout')
                         ->assertVue('dusk', 'ok', '@homecarousel')
                          ->assertSee($this->user->name);
        });
    }
    // public function when_users_logout_they_are_redirected_to_
    
    /** @test */
    public function when_users_logout_they_are_redirected_to_welcome_page()
    {
        $this->browse(function (Browser $browser) {
             $browser->loginAs($this->user)
               ->visit(new HomePage)
                ->waitUntilMissing('@cubegrid')
                ->click('@navbarusername')
                 ->click('@navbarlogout')
                 // ->select('@navbarusername', '@navbarlogout')
                  ->assertPathIs('/');
          });
    }
  
  /** @test */
  public function when_user_register_is_in_database()
  {
     
//     $newUser->email = 'new@user.com';
//     $newUser->password = 'newuser123';
//     $table = 'users';
    
    $this->browse(function (Browser $browser) {
      $browser->visit(new WelcomePage)
          ->click('@welcomeregister')
           ->waitUntilMissing('@cubegrid')
            ->type('@registername', $this->newUser->name)
             ->type('@registeremail', $this->newUser->email)
              ->type('@registerpassword', $this->newUser->password)
              ->type('@registerconfirmpassword', $this->newUser->password)
                ->click('@registersubmit')
                 ->waitUntilMissing('@cubegrid')
                  ->assertPathIs('/home');
                    
          });
    
    $this->assertDatabaseHas('users', [
        'name' => $this->newUser->name,
        'email' => $this->newUser->email,
        //'password' => Hash::check(bcrypt('random123')),
        'role_id' => '2',
    ]);
      
  }

    public function tearDown()
  {
      parent::tearDown();

      $this->browse(function (Browser $browser) {
          $browser->driver->manage()->deleteAllCookies();
      });
      // after test is done. Delete test user
      //  $this->user->delete();
  }
}
