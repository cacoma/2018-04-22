<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_invest_have_a_stock()
    {
        $invest = factory('App\Invest')->create();

        $this->assertInstanceOf('App\Stock', $invest->stock);
    }
}
