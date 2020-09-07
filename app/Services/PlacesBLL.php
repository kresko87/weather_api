<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 05/08/2020
 * Time: 21:09
 */

namespace App\Services;


use App\Interfaces\PlacesProvider;
use App\Models\Place;
use App\Models\UserHasPlace;

class PlacesBLL{

    public function __construct(){}

    /** adds place if it doesnt exist; returns place
     * @param PlacesProvider $pp
     * @param string $city
     * @return Place|null
     */
    public function addPlaceByNameIfNotExist(PlacesProvider $pp, string $city) : ?Place{
        $place = Place::where('name',$city)->first();
        if($place===null){
            $place = $pp->returnPlaceByName($city);
            if($place!=null){
                $place->save();
            }
        }
        return $place;
    }

    /** adds place to user
     * @param Place $place
     * @param int $userId
     * @return UserHasPlace
     */
    public function addPlaceToUser(Place $place, int $userId) : UserHasPlace{
        $uhp = UserHasPlace::where('id_user',$userId)->where('id_place',$place->id)->first();
        if($uhp===null){
            $uhp = new UserHasPlace();
            $uhp->id_user = $userId;
            $uhp->id_place = $place->id;
            $uhp->save();
        }
        return $uhp;
    }

    /** returns all places from user
     * @param int $idUser
     * @return mixed
     */
    public function getAllPlacesFromUser(int $idUser){
        return UserHasPlace::where('id_user',$idUser)->with('place')->get();
    }

}