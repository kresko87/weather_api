<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 20:55
 */
namespace App\Interfaces;

use App\Models\Place;

interface PlacesProvider{
    public function returnPlaceByName(string $place) : ?Place;
}