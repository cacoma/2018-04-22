<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrokerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }

    public function test_invest_have_a_broker()
    {
        $invest = factory('App\Invest')->create();

        $this->assertInstanceOf('App\Broker', $invest->broker);
    }

    // public function test_broker_edit_page_exists()
    // {
    //     $broker = factory('App\Broker')->create();
    //     $this->assertDirectoryExists('https://cacoma.tk/brokers/' . $broker->id . '/edit');
    // }

}
