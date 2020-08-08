<?php

namespace App\Http\Controllers;

use App\Services\CURLService;
use App\Services\OpenWeatherAPI;
use App\Services\PlacesBLL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacesController extends Controller
{
    /** add city to user
     * @param Request $request
     * @return array
     */
    public function getCityByNameAndAddItToUser(Request $request){
        $out = array(
            'city'=>null,
            'user_has_place'=>null
        );
        $downloadManager = new CURLService();
        $placesProvider = new OpenWeatherAPI($downloadManager);
        $placesBLL = new PlacesBLL();
        if(isset($request->cityName)){
            $out['city'] = $placesBLL->addPlaceByNameIfNotExist($placesProvider, $request->cityName);
        }
        if($out['city']!=null){
            $out['user_has_place'] = $placesBLL->addPlaceToUser($out['city'], Auth::id());
        }
        return $out;
    }

    /**
     * @param null $userId
     * @return mixed
     */
    public function getAllCitiesFromUser($userId = null){
        if($userId === null){
            $userId = Auth::id();
        }
        $placesBLL = new PlacesBLL();
        $places = $placesBLL->getAllPlacesFromUser($userId);
        return $places;
    }


}
