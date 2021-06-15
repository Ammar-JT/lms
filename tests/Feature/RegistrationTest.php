<?php

namespace Tests\Feature;

use Tests\TestCase;


use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ConfirmYourEmail;

use Illuminate\Support\Facades\Mail;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_has_a_default_username_after_registration(){

        $this->post('/register', [
            'name' => 'Ammar JT',
            'email' => 'AmmarTashk@gmail.com',
            'password' => 'password'
        ])->assertRedirect(); // we used assertStatus(200) but not useful here cuz if wronge it will redirect you


        $this->assertDatabaseHas('users', [
            'username' => Str::slug('Ammar JT')
        ]);
    }

    public function test_an_email_is_sent_to_newly_registered_users(){
        $this->withoutExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'Ammar JT',
            'email' => 'AmmarTashk@gmail.com',
            'password' => 'password'
        ])->assertRedirect(); //the erro we received is not helpful, so that's why we attach ->assertRedirect();

        Mail::assertSent(ConfirmYourEmail::class);

        
    }


    public function test_a_user_has_a_token_after_registration(){
        $this->withoutExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'Ammar JT',
            'email' => 'AmmarTashk@gmail.com',
            'password' => 'password'
        ])->assertRedirect(); //the erro we received is not helpful, so that's why we attach ->assertRedirect();


        //all the previous steps are the same, next is different: 

        // here we fetch the first user, cuz the testing database is empty
        $user = User::find(1);
        
        // here we make sure the user token is exist and not null: 
        $this->assertNotNull($user->confirm_token);


        // same test but I used the custom function isConfirmed(), which is reusable
        // .. and im going to use it in somewhere else:
        $this->assertFalse($user->isConfirmed());

        
    }
}
