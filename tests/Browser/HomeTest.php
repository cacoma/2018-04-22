<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\HomePage;

class HomeTest extends DuskTestCase
{
  
          use DatabaseMigrations;
  
    
    public function setUp()
        {
            Parent::setUp();
            $this->user = factory(\App\User::class)->make(['password' => bcrypt('pass123')]) ;
            $this->user->save();
            $this->broker = factory(\App\Broker::class)->create() ;
            //$this->broker->save();
            $this->stock = factory(\App\Stock::class)->create() ;
            //$this->stock->save();
            $this->invest = factory(\App\Invest::class)->create() ;
            //$this->invest->save();
            $this->admin = factory(\App\User::class)->make([
              'password' => bcrypt('admin123'),
              'role_id' => '1',
            ]) ;
            $this->admin->save();
            $this->newUser = factory(\App\User::class)->make(['password' => bcrypt('random123')]);
            // para passar pelo htaccess do apache
            $this->browse(function (Browser $browser) {
                $browser->visit('https://cacoma:pascal@cacoma.tk');
            });
        }
  
    /** @test 
     * @group homepage
     */
    public function users_see_carousel_home_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
               ->visit(new HomePage)
                ->assertSee('Composição de portfolio')
                  ->assertSee('Cacoma')
                    ->assertSee('Home')
                      ->assertSee('Investimentos')
                      ->assertDontSee('Cadastros')
                        ->click('@navbaradmininvestimentos')
                        ->assertSee('Indexar')
                         ->assertVue('dusk', 'ok', '@homecarousel')
                         ->assertVue('auth.role_id', '2', '@navbaradmin')
                          ->assertSee($this->user->name);
                $browser->logout();
        });
    }
  
    /** @test 
     * @group homepage
     */
  //admin tem todas as opcoes na tela home
    public function admin_home_has_all_objects()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
               ->visit(new HomePage)
                ->waitUntilMissing('@cubegrid')
                ->assertSee('Composição de portfolio')
                  ->assertSee('Cacoma')
                    ->assertSee('Home')
                      ->assertSee('Cadastros')
                      ->assertSee('Cotações')
                         ->assertVue('dusk', 'ok', '@homecarousel')
                          ->assertVue('auth.role_id', '1', '@navbaradmin')
                          ->assertSee($this->admin->name)
                            ->assertPathIs('/home');
                $browser->logout();
        });

    }
    
      /** @test 
     * @group homepage
     */
  //usuario sao deslogados se clicarem em logout na tela principal
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
                $browser->logout();
        });
    }
  
  
      public function tearDown()
    {
        parent::tearDown();

        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
        });
    }
}
