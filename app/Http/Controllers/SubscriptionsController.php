<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function showSubscriptionForm(){
        return view('subscribe');
    }

    public function subscribe(){
        //return auth()->user()->newSubscription('default', request('plan'))->create(request('price'));

        return auth()->user()->subscriptions()->create([
            'name' => request('plan'),
            'stripe_id' => Str::random(18),
            'stripe_status' => 'active',
            'quantity' => 1
        ]);
    }

    public function change(){
        $this->validate(request(), [
            'plan' => 'required'
        ]);
        
        $user = auth()->user();
        $userPlan = $user->subscriptions()->first();

        if(request('plan') === $userPlan->name){
            return redirect()->back();
        }

        $userPlan->name = request('plan');

        $userPlan->save();
        

        return redirect()->back();
    }

    
}
