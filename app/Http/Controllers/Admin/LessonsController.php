<?php
//you have to update the namespace cuz you move this controller one to \Admin: 
namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Series;
use Illuminate\Http\Request;
use App\Http\Requests\CreateLessonRequest;
use App\Http\Requests\UpdateLessonRequest;

//you have to import the controller cuz it's not in the same directory cuz you move this one to \Admin: 
use App\Http\Controllers\Controller;

class LessonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Series $series,CreateLessonRequest $request)
    {
        return $series->lessons()->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series, $id)
    {
        dd($series . ' ' . $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Series $series, Lesson $lesson, UpdateLessonRequest $request)
    {
        $lesson->update($request->all());

        return $lesson->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series,Lesson $lesson)
    {
        $lesson->delete();

        return response()->json(['status' => 'ok'], 200);
    }
}
