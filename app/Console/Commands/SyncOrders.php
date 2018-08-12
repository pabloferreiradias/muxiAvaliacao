<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\WMLBrowser;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Models\Orders;
use App\Models\OrdersServer;
use Carbon\Carbon;

class SyncOrders extends Command
{
    const ORDER_PER_PAGE = 10;

    /**
     * @var string
     */
    protected $signature = 'order:sync {force? : Force synconization of all orders, put 0 for NO and 1 for YES}';

    /**
     * @var string
     */
    protected $description = 'Synchronize transactions with the server.';

    /**
     * Actions to be executed when run the command
     * @return mixed
     */
    public function handle()
    {
        $this->output = new ConsoleOutput;

        $force = $this->argument('force');

        $ordersNumber = 0;

        if (!$force) {
            $interval = env('INTERVAL_DAYS', 1);
            $endDate = Carbon::now();            
            $beginDate = $endDate->subDays($interval);
            $ordersNumber = Orders::whereBetween('created_at', [$beginDate->toDateTimeString(), $endDate->toDateTimeString()])->count();
            
        }

        if ($force) {
            $ordersNumber = Orders::count();
        }

        if ($ordersNumber == 0) {
            $this->info(trans('syncOrders.ordersNotFound'));
            return false;
        }

        $pages = round($ordersNumber/self::ORDER_PER_PAGE);
        $this->info(trans('syncOrders.beginSyncOrder', [ 'numPages' => $pages ]));

        for ($i=0; $i < $pages; $i++) { 
            $this->info(trans('syncOrders.infoPage', [ 'page' => $i+1 ]));            
            $orders = Orders::offset($i*self::ORDER_PER_PAGE)->limit(self::ORDER_PER_PAGE)->get();
            $this->sendOrdersToServer($orders);
        }

    }

    private function sendOrdersToServer($orders)
    {
        foreach ($orders as $order) {
            $newOrder = new OrdersServer([
                'pos_code' => $order->pos_code,
                'value' => $order->value
            ]);
            $newOrder->save();
        }
    }
}
