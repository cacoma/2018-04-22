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
use Tests\Browser\Pages\Invests;
use Tests\Browser\Pages\Users;
use Tests\Browser\Pages\Brokers;
use Tests\Browser\Pages\Stocks;
use Carbon\Carbon;

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

  /** @test */
    public function welcome_page_has_cacoma()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new WelcomePage)
                        ->assertSee('Cacoma');
        });
    }

    /** @test */
    public function when_users_click_invest_index_they_see_all_invests()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
               ->visit(new HomePage)
             ->click('@navbaradmininvestimentos')
              ->click('@navbaradmininvestimentosindexar')
                ->waitUntilMissing('@cubegrid')
                ->assertSee('There are no records to show.') //usuario ainda nao tem nenhum investimento cadastrado
                  ->assertPathIs('/invests');
                $browser->logout();
        });
    }

    /** @test */
    public function when_users_access_user_index_they_dont_see_all_users()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
               ->visit('/users')
                ->waitUntilMissing('@cubegrid')
               //->waitFor('@flash')
               //->pause(1000)
               //->assertVue('title', 'Aviso', '@flash')
                ->assertSee('Acesso negado')
                //->assertDontSee('Nenhum investimento foi encontrado.') //usuario ainda nao tem nenhum investimento cadastrado
                  ->assertPathIs('/home');
                $browser->logout();
        });
    }

    public function when_users_access_brokers_index_they_dont_see_all_brokers()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
               ->visit('/brokers')
                ->waitUntilMissing('@cubegrid')
               //->waitFor('@flash')
               //->pause(1000)
               //->assertVue('title', 'Aviso', '@flash')
                ->assertSee('Acesso negado')
                //->assertDontSee('Nenhum investimento foi encontrado.') //usuario ainda nao tem nenhum investimento cadastrado
                  ->assertPathIs('/home');
                $browser->logout();
        });
    }

    public function when_users_access_stocks_index_they_dont_see_all_stocks()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
               ->visit('/stocks')
                ->waitUntilMissing('@cubegrid')
               //->waitFor('@flash')
               //->pause(1000)
               //->assertVue('title', 'Aviso', '@flash')
                ->assertSee('Acesso negado')
                //->assertDontSee('Nenhum investimento foi encontrado.') //usuario ainda nao tem nenhum investimento cadastrado
                  ->assertPathIs('/home');
                $browser->logout();
        });
    }



    //admin tests
    /** @test */
    public function admin_exists_and_is_administrator()
    {

        $this->assertDatabaseHas('users', [
        'name' => $this->admin->name,
        'email' => $this->admin->email,
        //'password' => Hash::check(bcrypt('random123')),
        'role_id' => '1',
    ]);
    }

    /** @test */
    public function admin_can_visit_all_indexes()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
               ->visit(new Brokers)
                  ->waitUntilMissing('@cubegrid')
                    ->assertsee($this->broker->cnpj)
                      ->visit(new HomePage)
                        ->visit(new Invests)
                          ->waitUntilMissing('@cubegrid')
                            ->assertsee($this->invest->symbol)
                              ->visit(new Stocks)
                                ->waitUntilMissing('@cubegrid')
                                  ->assertsee($this->stock->symbol)
                                    ->visit(new Users)
                                      ->waitUntilMissing('@cubegrid')
                                        ->assertsee($this->user->email);
                $browser->logout();
            // ->waitUntilMissing('@cubegrid')
                                                  //->assertPathIs('/users');
        });
    }


    /** @test */
    public function admin_can_add_broker()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
            ->visit(new Brokers)
              ->click('@indexcreate')
                ->waitUntilMissing('@cubegrid')
                  ->assertPathIs('/brokers/create')
                    ->assertSee('Corretoras')
                      //->click('@name')
                        ->type('name', 'Corretora teste')
                          //->click('@cnpj');
                            ->type('cnpj', '34897345000100')
                              ->click('@createsubmit')
                                ->waitUntilMissing('@cubegrid')
                                  ->waitFor('@flash')
                                  ->assertSee('A corretora foi inserida.')
                                    //->assertVue('dusk', 'ok', '@flash')
                                  ->visit(new Brokers)
                                    ->assertSee('Corretora teste')
                                      ->assertSee('34.897.345/0001-00');
                $browser->logout();
        });
        $this->assertDatabaseHas('brokers', [
          'name' => 'Corretora teste',
          'cnpj' => '34.897.345/0001-00',
      ]);
    }

  /** @test */
   public function admin_can_add_stock()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
            ->visit(new Stocks)
              ->click('@indexcreate')
                ->waitUntilMissing('@cubegrid')
                  ->assertPathIs('/stocks/create')
                    ->assertSee('Ações')
                      //->click('@name')
                        ->type('symbol', 'test2.sa')
                          //->click('@cnpj');
                            ->type('type', 'Ordinaria')
                              ->click('@createsubmit')
                                ->waitUntilMissing('@cubegrid')
                                   ->waitFor('@flash')
                                  ->assertSee('A ação foi inserida.')
                                  ->assertVisible('@flash')
                                  ->visit(new Stocks)
                                   ->waitUntilMissing('@cubegrid')
                                    ->assertSee(strtoupper('test2.sa'))
                                      ->assertSee(strtoupper('Ordinaria'));
                $browser->logout();
        });
        $this->assertDatabaseHas('stocks', [
          'symbol' => strtoupper('test2.sa'),
          'type' => strtoupper('Ordinaria'),
      ]);
    }

    /** @test */
   public function user_can_add_invest()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
            ->visit(new Invests)
              ->click('@indexcreate')
                ->waitUntilMissing('@cubegrid')
                  ->assertPathIs('/invests/create');
              $browser->visit('/invests/create')
              ->waitUntilMissing('@cubegrid')
                   ->click('@Ações ')
                    ->assertSee('Ações')
                        ->type('symbol', $this->stock->symbol)
                          //->click('@cnpj');
                           ->click('@datepicker')
                            ->assertSee(Carbon::now()->month)
                            ->assertSee(Carbon::now()->year)
                            ->type('date_invest', '01/01/2018')
                               ->type('broker_name', $this->broker->name)
                                ->type('price', '13,35')
                                ->type('quant', '100')
                                ->type('broker_fee', '0,99')
                                  ->click('@createtypestocktotal')
                                 //->assertSeeIn('@createtypestocktotal', '1335.99')
                                  ->assertVue('total', '1335.99', '@createinveststypestocks')
                                    //press('Inserir')
                                    //->click('#createtypestockform button[type=submit]');
                                    ->click('@createtypestocksubmit')
                                      ->waitUntilMissing('@cubegrid');
                                        //waitFor('.flash');
                                        //->waitFor('O investimento foi inserido.')
                                          //$browser->assertSourceHas('flash')
                                            //->assertSee('O investimento foi inserido.');
                                          //->assertVue('title', 'Aviso', '@flash')
                                    $browser->visit('/invests')
                                              ->waitUntilMissing('@cubegrid')
                                              ->assertSee(strtoupper($this->stock->symbol))
                                                ->assertSee('13,35')
                                                  ->assertSee('100')
                                                  ->assertSee($this->broker->name);
                $browser->logout();
        });
        $this->assertDatabaseHas('invests', [
          'symbol' => strtoupper($this->stock->symbol),
          'type' => 'stock',
          'price' => '13.35',
          'quant' => '100.00',
          'broker_fee' => '0.99',
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
