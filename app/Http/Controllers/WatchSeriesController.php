<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Series;
use Illuminate\Http\Request;

class WatchSeriesController extends Controller
{
    public function index(Series $series){
        $user = auth()->user();
        if($user->hasStartedSeries($series)){
            return redirect()->route('series.watch', [
                'series' => $series->slug,
                'lesson' => $user->getNextLessonToWatch($series)
            ]);
        }
        return redirect()->route('series.watch', [
            'series' => $series->slug,
            'lesson' => $series->lessons->first()->id
        ]);
    }

    public function showLesson(Series $series, Lesson $lesson){
        
        if($lesson->premium && !auth()->user()->subscribed('yearly') && !auth()->user()->subscribed('monthly')){
            return redirect('subscribe');
        }
        

        return view('watch', [
            'series' => $series,
            'lesson' => $lesson
        ]);
    }

    public function completeLesson(Lesson $lesson){
        auth()->user()->completeLesson($lesson);

        return response()->json([
            'status' => 'ok'
        ]);
    }

}
