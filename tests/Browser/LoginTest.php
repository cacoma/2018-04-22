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


class LoginTest extends DuskTestCase
{

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

    /** @test
     * @group loginregister
     */
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
                $browser->logout();
        });
    }

    /** @test
     * @group loginregister
     */
  //registra um usuario novo do zero, e localiza no bando de dados
    public function when_user_register_is_in_database()
    {


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
                  ->assertVue('auth.role_id', '2', '@navbaradmin')
                  ->assertPathIs('/home');
                $browser->logout();
        });

        $this->assertDatabaseHas('users', [
        'name' => $this->newUser->name,
        'email' => $this->newUser->email,
        //'password' => Hash::check(bcrypt('random123')),
        'role_id' => '2',
              ]);
    }


  //localiza o email do usuario criado no banco de dados, para enviar email de recuperacao de senha
//   public function when_user_reset_password_reset_works()
//     {
//      $this->browse(function (Browser $browser) {
//             $browser->visit(new Login)
//                ->click('@loginpasswordforgot')
//                  ->assertPathIs('/password/reset')
//                    ->type('@resetemail', $this->user->email)
//                     ->click('@resetsubmit')
//                         ->waitUntilMissing('@cubegrid')
//                           ->assertSee('Enviamos um email com o procedimento para troca de senha!');
//      });
//   }


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
