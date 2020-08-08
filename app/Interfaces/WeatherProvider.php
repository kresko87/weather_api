<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 16:11
 */

namespace App\Interfaces;


use App\Models\Place;
use App\Models\Temperature;

interface WeatherProvider{
    public function getWeatherForecastForCity(Place $city) : ?Temperature;
}