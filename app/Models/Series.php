<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    //by this, you remove mass assignment protection, cuz it's not dangorous and has not cretical data: 
    protected $guarded = [];
    

    //doing this will make the series holding the lesson with it even if you didn't
    //.. call the lessons, you can see that clearly when you dd($series)
    //.. try with $with once, and comment $with and try dd($series) again:
    protected $with = ['lessons'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(){
        return 'slug';
    }

    public function lessons(){
        return $this->hasMany(Lesson::class);
    }


    //accessor, like the one in java, set and get methods: 
    public function getImagePathAttribute(){
        return asset('storage/' . $this->image_url);
    }

    public function getOrderedLessons(){
        return $this->lessons()->orderBy('episode_number', 'asc')->get();
    }
}
