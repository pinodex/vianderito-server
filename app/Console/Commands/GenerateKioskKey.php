<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateKioskKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosk:keygen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate key for kiosk software';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = str_random(32);

        echo 'Generated Kiosk key: ' . $key;
    }
}
