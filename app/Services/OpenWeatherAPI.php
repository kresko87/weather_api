<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 16:21
 */

namespace App\Services;


use App\Interfaces\DownloadManager;
use App\Interfaces\PlacesProvider;
use App\Interfaces\WeatherProvider;
use App\Models\Place;
use App\Models\Temperature;


class OpenWeatherAPI implements WeatherProvider, PlacesProvider {

    private $downloadManager;

    public function __construct(DownloadManager $dm){
        $this->downloadManager = $dm;
    }

    /** makes api call to Open weather, maps content to Temperature and returns it
     * @param Place $city
     * @return Temperature|null
     * @throws \Exception
     */
    public function getWeatherForecastForCity(Place $city) : ?Temperature{
        $out = null;
        //makes api call
        $response = json_decode(
            $this->downloadManager->getContent($this->buildWeatherURL($city->lon, $city->lat))
        );
        //check if data is returned
        if($response!=null){
            if($response->hourly!=null && count($response->hourly)>12){
                $current = $response->hourly[0];
                $forecast = $response->hourly[12];  //contains weather prediction in 12 hours
                $currentDateTime = (new \DateTime('@'.$current->dt))->setTimezone(new \DateTimeZone('Europe/Ljubljana'));

                $prevTemp = null;
                //<editor-fold desc="search for previous temperature in database to save it in current record">
                $previousDateTime = (new \DateTime( '@'.(intval($current->dt)-43200)))->setTimezone(new \DateTimeZone('Europe/Ljubljana'));  //12h*60min*60s
                $prevTempObj = Temperature::where('datetime',$previousDateTime->format('Y-m-d H').':00:00')
                    ->where('id_place',$city->id)->first();
                if($prevTempObj!=null){
                    $prevTemp = $prevTempObj->temperature_in_12h;
                }
                //</editor-fold>

                //set the forecast temperature in next 12 h
                $toBeTemp = $forecast->temp;

                //creating temperature object
                $out = new Temperature();
                $out->id_place = $city->id;
                $out->datetime = $currentDateTime->format('Y-m-d H').':00:00';
                $out->temperature = $current->temp;
                $out->temperature_in_12h = $toBeTemp;
                $out->temperature_predicted_in_past = $prevTemp;
            }
        }
        return $out;
    }

    /** makes api call and returns place object
     * @param string $city
     * @return Place|null
     */
    public function returnPlaceByName(string $city): ?Place{
        $place = null;
        $response = $this->downloadManager->getContent($this->buildPlaceUrl($city));
        $decoded = json_decode($response);
        if($decoded->cod && $decoded->cod==200){
            $place = new Place();
            $place->name = $decoded->name;
            $place->city_id = $decoded->id;
            $place->lon = $decoded->coord->lon ?? 0;
            $place->lat = $decoded->coord->lat ?? 0;
        }
        return $place;
    }

    private function buildWeatherURL($lon, $lat):string{
        return "https://api.openweathermap.org/data/2.5/onecall?lat=".urlencode($lat)."&lon=".
            urlencode($lon)."&exclude=current,minutely,daily&units=metric&appid=".env('OPEN_WEATHER_API_KEY');
    }

    private function buildPlaceUrl(string $cityName) : string{
        return 'api.openweathermap.org/data/2.5/weather?q='.urlencode($cityName).
            ',si&appid='.env('OPEN_WEATHER_API_KEY');
    }

}