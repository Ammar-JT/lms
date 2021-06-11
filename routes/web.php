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

- go to AuthenticatesUsers.php (which imported in LoginController.php) and cut authenticated()
  .. and paste it in LoginController to override the method
- with that been set, you change the loggin system from vue base to ajax base

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



//---------------------------------------------------
//      refreshing current page after login + 
//---------------------------------------------------
/*
- make an 'ok' response using response()->json in LoginController using the overrided method authenticated()

- in Login.vue .. using the axios method, make the page relode using js if the response was succeful


*/



