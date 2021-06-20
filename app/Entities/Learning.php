<?php

namespace App\Entities;

use App\Models\Lesson;
use App\Models\Series;
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



    public function hasCompletedLesson($lesson){
        return in_array(
            $lesson->id,
            $this->getCompletedLessonsForASeries($lesson->series)
        );
    }

    /*
    public function seriesBeingWatched(){
        //format of redis that we follow: 
            //user:user_id:series:series_id
        $keys = Redis::keys("user:{$this->id}:series:*");

        $seriesIds = [];
        foreach($keys as $key):
            $recordArray = explode(':', $key); //this will result an array that has: ['user','user_id','series','series_id']
            $seriesId = $recordArray[3]; // but we want only the series id, so we extract the index 3 value
            //array of series ids: 
            array_push($seriesIds, $seriesId);
        endforeach;

        //convert the array of ids to collection: 
        $seriesCollection = collect($seriesIds);

        //convert the collection of ids to collection of series and return it: 
        return $seriesCollection->map(function($id){
            return Series::find($id);
        });
    }

    */
    //These two methods refactor the commented function:
    public function seriesBeingWatchedIds(){
        //format of redis that we follow: 
            //user:user_id:series:series_id
        $keys = Redis::keys("user:{$this->id}:series:*");

        $seriesIds = [];
        foreach($keys as $key):
            $recordArray = explode(':', $key); //this will result an array that has: ['user','user_id','series','series_id']
            $seriesId = $recordArray[3]; // but we want only the series id, so we extract the index 3 value
            //array of series ids: 
            array_push($seriesIds, $seriesId);
        endforeach;

        return $seriesIds;
    }

    public function seriesBeingWatched(){
        return $seriesCollection = collect($this->seriesBeingWatchedIds())->map(function($id){
            return Series::find($id);
        })->filter();
    }


    public function getTotalNumberOfCompletedLessons(){
        $keys = Redis::keys("user:{$this->id}:series:*");

        $result = 0;
        foreach($keys as $key):
            //this line won't work..
                //$result = $result + count(Redis::smembers($key));
            //.. cuz it gives ths count(Redis::smembers("lmssaas_database_user:1:series:2"))
            //.. which won't work cuz it shd be:  count(Redis::smembers(("user:1:series:2"))

            //so, i replaced it with these three lines: 
            $recordArray = explode(':', $key);
            $seriesId = $recordArray[3];
            $result = $result + count(Redis::smembers("user:{$this->id}:series:{$seriesId}"));
        endforeach;

        return $result;
    }

    public function getNextLessonToWatch($series){
        //end() will gives you the last element of an array: 
        $lessonIds = $this->getCompletedLessonsForASeries($series);

        $lessonId = end($lessonIds);


        $lastWatchedLesson =  Lesson::find($lessonId);

        return $lastWatchedLesson->getNextLesson();
    }
}