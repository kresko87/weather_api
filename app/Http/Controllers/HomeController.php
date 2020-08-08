<?php

namespace App\Http\Controllers;

use App\Services\PlacesBLL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$placesBLL = new PlacesBLL();
        $places = $placesBLL->getAllPlacesFromUser(Auth::id());*/
        return view('home'/*, ['places'=>$places]*/);
    }
}
