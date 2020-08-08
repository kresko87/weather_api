<?php

namespace App\Console\Commands;

use App\Models\Place;
use App\Models\Temperature;
use Illuminate\Console\Command;

class fillRandomTemperatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temperatures:fillRandomData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random temperature data to all cities in july 2020';

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
        $places = Place::all();
        $createArray = array();

        foreach ($places as $place){
            $startTemp = 25;
            $startTemp+=rand(-3,5);
            for($i=1;$i<32;$i++){
                $startTemp2=$startTemp+(rand(-300,300)*0.01);
                $startTempPredict = $startTemp2;
                for($j=0;$j<24;$j++){
                    $startTemp2+= (($j<17 && $j>6)?rand(0,200)*0.01:rand(-200,0)*0.01);
                    $startTempPredict = $startTemp2+(rand(-150,150)*0.01);
                    $createArray[]=array(
                        'id_place'=>$place->id,
                        'datetime'=>'2020-07-'.$i.' '.substr('00'.$j,-2).':00:00',
                        'temperature'=>$startTemp2,
                        'temperature_predicted_in_past'=>$startTempPredict,
                    );
                }
            }
        }
        $t = Temperature::insert($createArray);


        return 0;
    }
}
