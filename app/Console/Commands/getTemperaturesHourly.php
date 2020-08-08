<?php

namespace App\Console\Commands;

use App\Services\CURLService;
use App\Services\OpenWeatherAPI;
use App\Services\TemperatureBLL;
use Illuminate\Console\Command;

class getTemperaturesHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temperatures:getHourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets current temperatures from weather provider and saves them in database';

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
     * @return int
     */
    public function handle()
    {
        $downloadManager = new CURLService();
        $weatherProvider = new OpenWeatherAPI($downloadManager);
        $temperatureBLL = new TemperatureBLL();
        $temperatureBLL->saveCurrentTemepraturesForAllCities($weatherProvider);

        return 0;
    }
}
