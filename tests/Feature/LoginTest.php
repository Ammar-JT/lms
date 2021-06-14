<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_receives_correct_message_when_passing_in_wrong_credentials(){
        $user = User::factory()->create();
        
        //do the magic hacking stuff: 
        // why postJson? cuz the axios is a json post request also.... i think ya3ni:
        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ])->assertStatus(422)
        ->assertJson([
            'message' => 'These credintials do not match our records'
        ]);
    }


    public function test_correct_response_after_user_logs_in(){
        $user = User::factory()->create();
        //do the magic hacking stuff: 
        // why postJson? cuz the axios is a json post request also.... i think ya3ni:
        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->assertStatus(200)
        ->assertJson([
            'status' => 'ok'
        ])->assertSessionHas('success', 'Successfully logged in'); // ('session key', 'session value')

    }
}
