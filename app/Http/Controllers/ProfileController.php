<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(User $user){
        return view('profile')
                ->with('user', $user)
                ->with('series', $user->seriesBeingWatched());
    }

    public function update(User $user, Request $request){
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        return redirect()->back();
    }
}
