<?php

namespace Tests\Console\Commands;

use App\Console\Commands\SyncOrders;
use App\Models\Order;
use App\Models\OrderServer;
use Mockery;
use Tests\TestCase;
use Carbon\Carbon;
use Artisan;


class SyncOrdersTest extends TestCase
{
    public function testSyncOrderWithForce()
    {
        factory(Order::class, 3)->create();

        $orders = Order::count();
        $this->assertEquals($orders, 3);

        $this->artisan('order:sync', ['force' => 1]);

        $orders = Order::count();
        $this->assertEquals($orders, 0);
    }

    public function testSyncOrderWithoutForce()
    {
        factory(Order::class, 3)->create();
        factory(Order::class, 3)->create(['created_at' => '2010-01-01 12:00:00']);

        $orders = Order::count();
        $this->assertEquals($orders, 6);

        $this->artisan('order:sync');

        $orders = Order::count();
        $this->assertEquals($orders, 3);
    }

    public function testErrorNoOrders()
    {
        $this->assertEquals(0, $this->artisan('order:sync'));
    }
}
