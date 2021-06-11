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
//      custom exceptions in laravel (custom error) + override another AuthenticatesUsers{} function (sendFailedLoginResponse())
//---------------------------------------------------------------------------------------------------------
/*

- Now, if the credintials is correct, login gonna works perfectly,
  .. but if it's wrong then we have to handle the error that been catched by 
  .. the axios function, which also been sent from sendFailedLoginResponse() 
  .. function of the great auth class AuthenticatesUsers{}, so, our mission here is to
      - make an excpetion response AuthFailedException{} class 
      - overide sendFailedLoginResponse() in loginController  
      - throw  the AuthFailedException{} inside the overrided function

- make app/Exceptions/AuthFailedException.php file ,,, here is the docs for that: 
      https://laravel.com/docs/8.x/errors#renderable-exceptions

- do the following in the render() function: 
      return response()->json([
            'message' => 'These credintials do not match our records'
        ],422); 



- now go to AuthenticatesUsers{} and cut sendFailedLoginResponse() and paste it in LoginController
  .. to override the exception that been recieved when the login failed

- change the throw new ValidationException to throw new AuthFailedException (which is the one you made)

  
*/



//---------------------------------------------------------------------------------------------------------
//      Display errors using vue + ajax instead of using laravel
//---------------------------------------------------------------------------------------------------------
/*

- make a data() array in Login.vue called errors:[] 

- push an error to it when the axios catch an error in Login.vue

- display the error messages in Login.vue, using  v-if function + v-for loop through errors + alert








  
*/



