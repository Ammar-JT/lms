<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/logout', function(){auth()->logout();});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register/confirm', [App\Http\Controllers\ConfirmEmailController::class, 'index'])->name('confirm-email');



//===============================================================
//          What is this project?
//===============================================================
/*

*/




//-------------------------
//      Authentication
//-------------------------
/*
YOU HAVE TO DO THIS IN CMD NOT IN IDE OR GIT BASH: 

Try to make auth from the begining before anything,
this will install the ui for auth and also vue.js:
        composer require laravel/ui --dev
        php artisan ui vue --auth
//BEEN ASKED TO npm run dev????? do this:
        run: npm install
        run: npm run dev
  it will ask you to run mix, but do the same again:
        run: npm run dev


*/



//--------------------------------------
//      Set up the project + Override some auth functions
//--------------------------------------
/*
-first, make a users using the UserFactory (uncomment the databaseSeeder run function) and seed:
                php artisan db:seed

- most of the user authentication function is in AuthenticatesUsers.php, in most cases we're
  .. gonna override a methods form this class, next point i will show you how to do that

- go to AuthenticatesUsers.php (which imported in LoginController.php) and cut authenticated()
  .. and paste it in LoginController to override the method,
  .. and that's how you override a user auth function in laravel

- inside authenticated(),, go and change the loggin system from vue base to ajax base (i did it, go and see it)

- in app.blade.php.. use theSaas theme instead of the default template + modify the welcome.blade scripts


*/


//---------------------------------------------------
//      Use vue js for the login button and form
//---------------------------------------------------
/*
- go to resources/js/components/exampleComponent.vue, and put a modal from theSaaS theme
  .. notice that here laravel deal with vue js in the resource/js folder!
  .. same as the project root folder of any vue js project

- change exampleComponent.vue to Login.vue, and register it in app.js, and put     
                <vue-login></vue-login>
  in layouts/app.blade.php to embeded the vue component in the layout

- make the login button open the model in the Login.vue

- use v-model to  manipulate the values of input ofthe form, and bind it with the script data() in Login.vue

- make computed:{} property with a isValidLoginForm() function that use emailIsValid() regular expression
  ..  method to take care of the validation of the login form

- import axios in the Login component to use ajax so we can deal with http request without
  .. refreshing the page.. and use it!

- make a logout route: 
    Route::get('/logout', function(){
      auth()->logout();
    });

- now login is successful, but it won't redirect you to
  .. another page + current page wont refresh + ui still not set up


*/


//------------------------------------------------------------------------------------------------
//     >>>> npm run watch <<<< : for real time monitoring insted of : >>>> npm run dev <<<<
//------------------------------------------------------------------------------------------------




//---------------------------------------------------
//      refreshing current page after login + 
//---------------------------------------------------
/*
- make an 'ok' response using response()->json in LoginController using the overrided method authenticated()

- in Login.vue .. using the axios method, make the page relode using js if the response was succeful

- if npm run dev desn't update the ui, the problem actually is in the browser, click
      alt + f5

- if you put email+password and click login, we want to disable the button while it's loading
  .. to do that go to the same function isValidLoginForm() in Login.vue and put 
      && !this.loading
  .. and in the begining of attemptLogin() : 
      this.loading = true
  .. and in the data(): 
      loading: false
  
*/



//---------------------------------------------------------------------------------------------------------
//      custom exceptions in laravel (custom error message) + override another AuthenticatesUsers{} function (sendFailedLoginResponse())
//---------------------------------------------------------------------------------------------------------
/*

- Now, if the credintials is correct, login gonna works perfectly,
  .. but if it's wrong then we have to handle the error that been catched by 
  .. the axios function, which also been sent from sendFailedLoginResponse() 
  .. function of the great auth class AuthenticatesUsers{}, so, our mission here is to
      - make an excpetion response in AuthFailedException{} class 
      - overide sendFailedLoginResponse() in loginController  
      - throw  the AuthFailedException{} inside the overrided function

- make app/Exceptions/AuthFailedException.php file ,,, here is the docs for that: 
      https://laravel.com/docs/8.x/errors#renderable-exceptions

- do the following in the render() function: 
          return response()->json([
                'message' => 'These credintials do not match our records'
            ],422); 
  notice we didn't use:
          throw ValidationException::withMessage([])
  .. cuz the validation exception is a string stored in a session, and we don't want that
  .. We want a json repsonse so that we can recieve it and use the message inside it
  .. in our custom login form



- now go to AuthenticatesUsers{} and cut sendFailedLoginResponse() and paste it in LoginController
  .. to override the exception that been recieved when the login failed

- change the throw new ValidationException to throw new AuthFailedException (which is the one you made)

  
*/



//---------------------------------------------------------------------------------------------------------
//      Display errors messages using vue + ajax instead of using laravel
//---------------------------------------------------------------------------------------------------------
/*

- make a data() array in Login.vue called errors:[] 

- push an error to it when the axios catch an error in Login.vue

- display the error messages in Login.vue, using  v-if function + v-for loop through errors + alert


*/


//---------------------------------------------------------------------------------------------------------
//          LoginTest (Why? cuz we customize the login functions it!)
//---------------------------------------------------------------------------------------------------------
/*

- make a new test: 
      php artisan make:test LoginTest
- make the function: 
      test_a_user_receives_correct_message_when_passing_in_wrong_credentials()
- fill it up with the magical testing function

- now, testing won't work until you use RefreshDatabase trait, so use it!

- but the problem here that if we use it, it will refresh the db entirly!!
  .. to prevent that we will use a db connection for the testing only,
  .. to do that you have to modify phpunit file, put this: 
        <server name="DB_CONNECTION" value="testing"/>
        <server name="DB_DATABASE" value=":memory:"/>
  then go to config/database.php and do this: 
        'testing' => [
            'driver' => 'sqlite',
            'database' => database_path('testing.sqlite'),
            'prefix' => ''
        ],
  and don't forget to make the database اصلا:
        touch database/testing.sqlite

- Now, do: 
    ./vendor/bin/phpunit
  or you can do only the LoginTest: 
    ./vendor/bin/phpunit --filter LoginTest

- Successful!!!

---------

- We made a test to assert that the user receive an error message if login no successful.
  .. now we want to make sure the respone is correct if the user logged in successfully: 
        test_correct_response_after_user_logs_in()
- fill it with the test functions

- to make test for it only just do: 
      ./vendor/bin/phpunit --filter test_correct_response_after_user_logs_in

*/


//---------------------------------------------------------------------------------------------------------
//              Customize Regiseration with Test Driven Development
//---------------------------------------------------------------------------------------------------------
/*

- copy the registeration template to yours and modify it so it contains only: name + email + pass

- in registerContrller{} when controller receive the registration, the user must created with 
  .. username also,, we will do that using the name text, so we will make slug of the name to make username

- make a test: 
      php artisan make:test RegistrationTest
  make the function: 
      test_a_user_has_a_default_username_after_registration()
  fill it.

- Do the test, it will gives you an error cuz username is not sumbitted in the controller,

- Fix it with Str::slug in the RegisterController + in User Factory 
  and in the user migration, add a unique username column

- don't forget use RefreshDatabase; in the RegistrationTest, now run the test!!

- Successful!!!

------------------------
- we want to test_an_email_is_sent_to_newly_registered_users(), so make this function in the RegistrationTest

- fill it, then create an email: 
      php artisan make:mail ConfirmYourEmail --markdown="emails.confirm-your-email"

- now import this ConfirmYourEmail in the RegisterController and in the RegistraionTest

- im very tired and i don't understood anything do the test, but do the test: 

- Successful!!
*/




//---------------------------------------------------------------------------------------------------------
//        Customize Regiseration with Test Driven Development: Test if the use is verified or not
//                      Laravel 8: don't use this, use 'email_verified_at' instead.s
//---------------------------------------------------------------------------------------------------------
/*
- In Laravel 8  don't use this, cuz in laravel 8 there is 'email_verified_at' column
  ..and you can use it instead of this, read the document to know how to use it.

- make a function : 
      test_a_user_has_a_token_after_registration()

- fill it up, and do the test:
    ./vendor/bin/phpunit --filter test_a_user_has_a_token_after_registration
    
- YOU IDIOT!!!!! {{{{{{{{ REMEMBER TOKEN is not for account confirmation, it's for remembering the accout in loggin (remember me) Idiot!!!!}}}}}}}}
  .. now, turn everything back, and follow the lesson as it is!

- Success!!  'confirm_token' filled with a random token and test_a_user_has_a_token_after_registration succeed!

- You can see the confirm-your-email.blade.php when return it in a route: 
      Route::get('/', function () {
          return new App\Mail\ConfirmYourEmail();
      });

*/



//------------------------------------------------------------------------------------------------------------------------------------
//        Customize Regiseration with Test Driven Development: test_a_user_can_confirm_email + Confirm Mail for real using mail trap
//                      Laravel 8: don't use this, use 'email_verified_at' instead.s
//------------------------------------------------------------------------------------------------------------------------------------
/*
- This Test make sure that the user can confirm his email using the link: 
      php artisan make:test ConfirmEmailTest{}

- make, and fill it:
      test_a_user_can_confirm_email()

- filling that up, you need to make a route and controller: 
      Route::get('/register/confirm', [App\Http\Controllers\ConfirmEmailController::class, 'index']);
      php artisan make:controller ConfirmEmailController

- after filling them up, make a confirm() function in the User model

- also the User Factory, add: 
      'confirm_token' => Str::random(25),


- Test Will fail if you don't use the fresh method:
      $this->assertTrue($user->fresh()->isConfirmed());
  instead of: 
      $this->assertTrue($user->isConfirmed());
  Cuz if you change the model record it will still have the old version, so you have to refresh that using fresh()


- make a new method: 
      test_user_is_redirected_if_token_is_wrong
  and fill it up.

- put a route and the token in: 
    confirm-your-email.blade.php
  but you have to receive the $user first to get $user->token 

- So, Send the user from Mail/ConfirmYourEmail

*/



//---------------------------------------------------------------------------------------------------------
//             Series of LMS SaaS
//---------------------------------------------------------------------------------------------------------
/*

- let's make series model with migrations:
    php artisan make:model Series -m


*/


