<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_without_a_plan_can_watch_premium_lessons(){
        //$this->withoutExceptionHandling();
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create([ 'premium' => 1]);
        $lesson2 = Lesson::factory()->create([ 'premium' => 0]);

        $this->actingAs($user);

        $this->get('/series/' . $lesson->series->slug . '/lesson/' . $lesson->id)
             ->assertRedirect('/subscribe');

        $this->get('/series/' . $lesson2->series->slug . '/lesson/' . $lesson2->id)
        ->assertViewIs('watch');

        //$this->fakeSubscribe($user);
        //dd($user->subscribed('yearly'));
    }

    public function test_a_user_on_any_plan_can_watch_all_lessons(){
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create([ 'premium' => 1]);
        $lesson2 = Lesson::factory()->create([ 'premium' => 0]);

        $this->actingAs($user);
        $this->fakeSubscribe($user);
        //dd($user->subscribed('yearly') || $user->subscribed('monthly'));

        $this->get('/series/' . $lesson->series->slug . '/lesson/' . $lesson->id)
            ->assertViewIs('watch');

        $this->get('/series/' . $lesson2->series->slug . '/lesson/' . $lesson2->id)
            ->assertViewIs('watch');



    }

    public function fakeSubscribe($user){
        //subscriptions 
        $user->subscriptions()->create([
            'name' => 'yearly',
            'stripe_id' => Str::random(18),
            'stripe_status' => 'active',
            'quantity' => 1
        ]);
        
    }
}
