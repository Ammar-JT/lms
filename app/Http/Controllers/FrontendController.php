<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function welcome(){
        return view('welcome')->with('series', Series::all());
    }   


    public function series(Series $series){
        return view('series')->with('series', $series);
    }

    /**
     * Show all the series
     *
     * @return view
     */
    public function showAllSeries() {
        return view('all-series')->with('series', Series::all());
    }
}
