<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class UpdateStatusCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk Mengupdate status Customer';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $customers = Customer::all();
        foreach ($customers as $customer) {
            $customer->updateStatus();
        }
    }
}
