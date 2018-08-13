<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\WMLBrowser;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Models\Order;
use App\Models\OrderServer;
use Carbon\Carbon;
use DB;
use Log;

class SyncOrders extends Command
{
    const ORDER_PER_PAGE = 10;

    protected $beginDate;
    protected $endDate;

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
            $this->endDate = Carbon::now();
            $this->beginDate = Carbon::now();
            $this->beginDate->subDays($interval);
            $ordersNumber = Order::whereBetween('created_at', [$this->beginDate->toDateTimeString(), $this->endDate->toDateTimeString()])->count();
            
        }

        if ($force) {
            $ordersNumber = Order::count();
        }

        if ($ordersNumber == 0) {
            $this->info(trans('syncOrders.ordersNotFound'));
            return false;
        }

        $pages = round($ordersNumber/self::ORDER_PER_PAGE);
        $this->info(trans('syncOrders.beginSyncOrder', [ 'numOrders' => $ordersNumber, 'numPages' => $pages ]));

        // Start transaction!
        DB::beginTransaction();
        DB::connection('pgsql_server')->beginTransaction();

        for ($i=0; $i < $pages; $i++) { 
            $this->info(trans('syncOrders.infoPage', [ 'page' => $i+1 ]));            
            $orders = $this->getOrdersPaginated($i, $force);
            $this->sendOrdersToServer($orders);
        }

        if (!$force) {
            try {
               $orders = Order::whereBetween('created_at', [$this->beginDate->toDateTimeString(), $this->endDate->toDateTimeString()])->delete();
            } catch (\Exception $ex) {
                $this->info(trans('syncOrders.massDeleteError'));
                DB::rollback();
                DB::connection('pgsql_server')->rollback();        
                return false;            
            }            
        }

        if ($force) {
            try {
                $orders = Order::where('id', '>=', 1)->delete();
            } catch (\Exception $ex) {
                $this->info(trans('syncOrders.massDeleteError'));
                DB::rollback();
                DB::connection('pgsql_server')->rollback();        
                return false;            
            }     
        }

        DB::commit();
        DB::connection('pgsql_server')->commit();

        return true;
    }

    private function sendOrdersToServer($orders)
    {
        foreach ($orders as $order) {
            $newOrder = new OrderServer([
                'pos_code' => $order->pos_code,
                'value' => $order->value
            ]);
            try {
                $newOrder->save();
            } catch (\Exception $ex) {
                $this->info(trans('syncOrders.errorSaveOrderInServer'));
                DB::rollback();
                DB::connection('pgsql_server')->rollback();        
                return false;            
            }
        }
    }

    private function getOrdersPaginated($page, $force)
    {
        if ($force) {
            return Order::offset($page*self::ORDER_PER_PAGE)->limit(self::ORDER_PER_PAGE)->get();
        }
            
        return Order::whereBetween('created_at', [$this->beginDate->toDateTimeString(), $this->endDate->toDateTimeString()])->offset($page*self::ORDER_PER_PAGE)->limit(self::ORDER_PER_PAGE)->get();        
    }
}
