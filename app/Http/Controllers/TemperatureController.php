<?php

namespace App\Http\Controllers;

use App\Services\CURLService;
use App\Services\OpenWeatherAPI;
use App\Services\TemperatureBLL;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    //created just for testing purpose
    /*
    public function saveTemperatureForAllCities(){
        $curlBLL = new CURLService();
        $weatherApi = new OpenWeatherAPI($curlBLL);
        $temperatureBLL = new TemperatureBLL();
        $temperatureBLL->saveCurrentTemepraturesForAllCities($weatherApi);
    }*/

    public function getTemperaturesForParameters(Request $r){
        $out = array();
        $temperatureBLL = new TemperatureBLL();
        $out = $temperatureBLL->returnTemperaturesForParameters($r->cityId, $r->dateFrom, $r->dateTo);
        return $out;
    }

}
