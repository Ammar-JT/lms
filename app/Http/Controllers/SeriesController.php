<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CreateSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.series.all')->with('series', Series::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.series.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSeriesRequest $request)
    {
        // validation + upload + create file already made using CreateSeriesRequest:
        //.. notice you used return,, cuz you can't do the redirect from there
        //.. you have to return the redirect,, and here you return it also
        return $request->uploadSeriesImage()
                ->storeSeries();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series)
    {
        
        return view('admin.series.index')->with('series',$series);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Series $series)
    {
        return view('admin.series.edit')->with('series', $series);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSeriesRequest $request, Series $series)
    {
        if($request->hasFile('image')){
            $series->image_url = $request->uploadSeriesImage()->fileName;
        }
        $series->title = $request->title;
        $series->description = $request->description;
        $series->slug = Str::slug($request->title);

        $series->save();

        return redirect()->route('series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
