<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 07/08/2020
 * Time: 20:47
 */

namespace App\Services;


use App\Interfaces\DownloadManager;
use App\Interfaces\WeatherProvider;
use App\Models\Place;
use App\Models\Temperature;
use Carbon\Carbon;

class TemperatureBLL{

    public function __construct(){}

    /** Saves current Temperature for all cities in database
     * @param WeatherProvider $wp
     */
    public function saveCurrentTemepraturesForAllCities(WeatherProvider $wp){
        $places = Place::all();
        foreach ($places as $place){
            $temp = $wp->getWeatherForecastForCity($place);
            $temp->save();
        }
    }

    /** Returns temperatures for requested city and dates
     * @param $cityId
     * @param $dateFrom
     * @param $dateTo
     * @return array of temperatures or null
     */
    public function returnTemperaturesForParameters($cityId, $dateFrom, $dateTo){
        //$dateFromC = Carbon::createFromFormat("Y-m-d H:i:s", $dateFrom);
        //$dateToC = Carbon::createFromFormat("Y-m-d H:i:s", $dateTo);
        return Temperature::where('id_place',$cityId)
            ->where('datetime', '>=', $dateFrom)
            ->where('datetime', '<=', $dateTo)
            ->groupBy('id_place', 'datetime')
            ->orderBy('datetime','asc')->get();
    }

}