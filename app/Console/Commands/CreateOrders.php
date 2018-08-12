<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Order;
use Log;

class CreateOrders extends Command
{

    /**
     * @var string
     */
    protected $signature = 'order:create {qtd : Number of orders to be created}';

    /**
     * @var string
     */
    protected $description = 'Create fake orders to test.';

    /**
     * Actions to be executed when run the command
     *
     * @return mixed
     */
    public function handle()
    {
        $number = $this->argument('qtd');

        if (!is_numeric($number)) {
            $this->error(trans('createOrders.notNumber'));
            Log::error(trans('createOrders.notNumber'));            
            return false;
        }

        $this->info(trans('createOrders.beginCreation'));
        
        for ($i=0; $i < $number; $i++) { 
            $this->createOrder();
        }

        $this->info(trans('createOrders.endCreation'));
    }

    private function createOrder()
    {
        $order = new Order([
            'pos_code' => str_random(10),
            'value' => mt_rand(10, 100)
        ]);

        try {
           $order->save();
        } catch (\Exception $ex) {
            throw new \UnexpectedValueException(
                trans('createOrders.errorCreation')." Error: ".$ex
            );
            return false;            
        }
    }
        
}
