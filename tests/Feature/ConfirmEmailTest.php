<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmEmailTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_can_confirm_email(){
        $this->withoutExceptionHandling();

        //create user
        $user = User::factory()->create();

        //send email
        //no need, we already tested sending verification email

        //make a get request to the confirm endpoint
        $this->get("/register/confirm/?token=$user->confirm_token")
             ->assertRedirect('/')
             ->assertSessionHas('success', 'Your email has been confirmed.');

        $this->assertTrue($user->fresh()->isConfirmed());

        //assert that the user is confirmed


    }

    public function test_user_is_redirected_if_token_is_wrong(){
        $this->withoutExceptionHandling();

        //create user
        $user = User::factory()->create();

        //make a get request to the confirm endpoint
        $this->get("/register/confirm/?token=WRONG_TOKEN!!")
             ->assertRedirect('/')
             ->assertSessionHas('error', 'Confirmation token not recognised.');

    }
}
