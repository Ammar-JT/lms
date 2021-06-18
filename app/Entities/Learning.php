<?php

namespace App\Entities;

use App\Models\Lesson;
use Illuminate\Support\Facades\Redis;

trait Learning {
    public function completeLesson(Lesson $lesson){
        //we will use a set cuz the lessons id's shouldn't be duplicated: 
        Redis::sadd("user:{$this->id}:series:{$lesson->series_id}", $lesson->id);
    }

    public function percentageCompletedForSeries($series){
        //get the total
        $numberOfLessonInSeries = $series->lessons->count();

        $numberOfCompleteLesson = $this->getNumberOfCompletedLessonsForASeries($series);

        return ($numberOfCompleteLesson / $numberOfLessonInSeries) * 100;

    }

    public function getCompletedLessonsForASeries($series){
        return Redis::smembers("user:{$this->id}:series:{$series->id}");
    }

    public function getNumberOfCompletedLessonsForASeries($series){
        return count($this->getCompletedLessonsForASeries($series));
    }

    public function hasStartedSeries($series){
        return $this->getNumberOfCompletedLessonsForASeries($series) > 0;
    }


    
    

    /*
    //i did this the hardway: 
    public function getCompletedLessons($series){
        //same as getCompletedLessonsForASeries() but we want to return it in type collection: 
        //.. so we will use the function actually: 
        $completedLessons = $this->getCompletedLessonsForASeries($series);

        //obviously will return a collectioOfLessonsIds
        $collectioOfLessonsIds = collect($completedLessons);

        //obviously will return a collectionOfLessonsObjects
        //map method will loop through the collection and return the logic for every iteration
        //.. the logic here will return an object of lesson using the lessonId:
        $collectionOfLessonsObjects = $collectioOfLessonsIds->map(function($lessonId){
            return Lesson::find($lessonId);
        });

        return $collectionOfLessonsObjects;

        //you can summerize the previous like this:
         
        //return collect($completedLessons)->map(function($lessonId){
        //    return Lesson::find($lessonId);
        //});
        
    }
    */

    //i did this function the hard way then i comment it up there, 
    //.. here is the easy smart way:
    public function getCompletedLessons($series) {
        // 1, 2, 4
        return Lesson::whereIn('id', 
            $this->getCompletedLessonsForASeries($series)
        )->get();
    }
}