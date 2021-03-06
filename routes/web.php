<?php

use App\Models\Series;
use Illuminate\Support\Facades\Redis;
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

/*
//just learning redis
Route::get('/', function(){
  //key: value // string, set that: 
    //Redis::set('friend', 'momo');
    //now see the value: 
    //dd(Redis::get('friend'));

  //key: value // list
    //Redis::lpush('frameworks', ['vuejs','laravel','nodejs']);
    //see the value of that list (lrange = list range, put a range or put 0, -1 so you can get all)
    //dd(Redis::lrange('frameworks', 0, -1)); // the list doesn't care if element is duplicated, unlike the set

  //key: value // set, the set does care if element is duplicated, unlike the list
  //..so all the values are unique on it: 
    //Redis::sadd('fronted-frameworks', ['angular, vuejs, react']);
    //dd(Redis::smembers('fronted-frameworks'));
});
*/
Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register/confirm', [App\Http\Controllers\ConfirmEmailController::class, 'index'])->name('confirm-email');


Route::get('/', [App\Http\Controllers\FrontendController::class, 'welcome']);
Route::get('/series/{series}', [App\Http\Controllers\FrontendController::class, 'series'])->name('series');
Route::get('/series', [App\Http\Controllers\FrontendController::class, 'showAllSeries'])->name('all-series');

Route::get('/logout', function(){
      auth()->logout();
      return redirect('/');
});

Route::middleware('auth')->group(function(){
      

      Route::get('/watch-series/{series}', [App\Http\Controllers\WatchSeriesController::class, 'index'])->name('series.learning');
      Route::get('/series/{series}/lesson/{lesson}', [App\Http\Controllers\WatchSeriesController::class, 'showLesson'])->name('series.watch');
      Route::post('/series/complete-lesson/{lesson}', [App\Http\Controllers\WatchSeriesController::class, 'completeLesson'])->name('series.complete.lesson');
      Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
      
      Route::put('/profile/{user}/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

      Route::get('/subscribe', [App\Http\Controllers\SubscriptionsController::class, 'showSubscriptionForm'])->name('subscription');
      Route::post('/subscribe', [App\Http\Controllers\SubscriptionsController::class, 'subscribe']);
      Route::post('/subscription/change', [App\Http\Controllers\SubscriptionsController::class, 'change'])->name('subscriptions.change');

});





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
  and don't forget to make the database ????????:
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
//               Series Create of LMS SaaS
//---------------------------------------------------------------------------------------------------------
/*

- let's make series model with migrations:
    php artisan make:model Series -m

- fill the migration and remove the mass assignment protection from the model

- make factory for this:
    php artisan make:factory SeriesFactory


------------

- let's make the ui for creating series from the theme block-contact + page-portfolio ..in:
    views/admin/series/create.blade.php 

-make route group for admin with the prefix admin: 
    Route::prefix('admin')->group(function(){
      Route::resource('series);
    });

- make the SeriesController and put it in the route group: 
    php artisan make:controller SeriesController --resource
  and return the view you made in create()

- use the store() function, and make validation request: 
    php artisan make:request CreateSeriesRequest

- here is a function we will use to store the image from this library: //vendor\laravel\framework\src\Illuminate\Http\UploadedFile.php
      which is >>> $request->file('image')->storePubliclyAs() <<< save to a directory + name of the file

- done
*/


//---------------------------------------------------------------------------------------------------------
//              Series Create Test
//---------------------------------------------------------------------------------------------------------
/*

- do: 
      php artisan make:test CreateSeriesTest
- all the details in the test file CreateSeriesTest.php

- notice that the image uploading been handled by the Controller like a real image, 
  .. but we force the storage to be fake.. so it been uploaded to that fake storage
- Also Notice!!! this fake storage will not work with php link (the one you use for uploading image cuz the Storage class not working in shared hosting)
  .. it only works with Storage and the model functions like storePubliclyAs()..... i think ya3ni

--------

- make a title test: 


- if you had this error: 
    Integrity constraint violation: 19 NOT NULL constraint failed: series.title
  it means the controller tried to make title with null value, which is not allowed

- To prevent that, just make validation in the CreateSeriesRequest for title > requireds

*/


//---------------------------------------------------------------------------------------------------------
//              Refactor SeriesController@Create: move the logic to the Request Class!!
//---------------------------------------------------------------------------------------------------------
/*

- move all the logic of the controller into the request class

- in the CreateSeriesRequest.php make these methods: 
      uploadSeriesImage()
      storeSeries()
  and then use it in SeriesController{}

- put the validations in CreateSeriesRequest, and test it in CreateSeriesTest



*/


//---------------------------------------------------------------------------------------------------------
//              Admin Middleware
//---------------------------------------------------------------------------------------------------------
/*

- create the middleware: 
    php artisan make:middleware Administrator
- register the middleware in the kernel.php: routeMiddleWare: 
    'admin' => \App\Http\Middleware\Administrator::class

- create a custom config file to store the admins names: 
    /config/lms

- make the middleware logic
- make a function isAdmin() in the User model

- now attach the middleware to the admin group of routes

- sign up with admin@admin.com and try to visit: 
    /admin/series/create
  Success, try to visit it with another user: 
  Failed and redirected to home!

----------

*/

//---------------------------------------------------------------------------------------------------------
//              Testing Admin Middleware
//              pushing user email to the config('lms.administrators') to make them admins
//---------------------------------------------------------------------------------------------------------
/*

- let's test this middleware, in CreateSeriesTest: 
      test_only_admins_can_create_series()

- Succeed

- BUT!!! IT WILL FAILS ALL THE OTHER /admin/... tests!!!
  ..because user can do anything in that /admin/... unless he is an admin,

-So, to get over that we have to make new user for everytest and push this
  .. user to the config('lms.administrators')
  .. how can we push to a config??? like this: 
         Config::push('lms.administrators', $user->email);
  You do that for any user you want to make him an admin during the runtime
  .. then you need to logged the user in ofcourse

-After doing that, put it in a function: 
      loginAdmin()
 and use it for every test that needed it


*/

//---------------------------------------------------------------------------------------------------------
//              Routing: use slug instead of primary key for the single series
//---------------------------------------------------------------------------------------------------------
/*

- copy this method from Model class that all you model extends it: 
      getRouteKeyName()
  and paste it in Series model so you can override
  public function getRouteKeyName(){
      return 'slug';            <<<<            instead of $this->getKeyName();
  }

*/



//---------------------------------------------------------------------------------------------------------
//                      Lessons
//---------------------------------------------------------------------------------------------------------
/*

- make model for it: 
      php artisan make:model Lesson -m
  fill the migration

- remove the protection from mass assignment in the model

- make an index.blade.php (not show) to display the single lesson

- make a factory:
    php artisan make:factory LessonFactory --model="Lesson"

- now refresh youe database:
      php artisan migrate:refresh
  and use tinker to make 5 lessons: 
      php artisan tinker
  then: 
      App\Models\Lesson::factory()->count(5)->create();
  now get all series and copy one of the series's slug in ur url: 
       App\Models\Series::all()

- Now we want to put a one to many relationship between Series and lessons,
  ..that can be done in the series model

      public function lessons(){
        return $this->hasMany(Lesson::class);
      }
  Also put this property up there in the same model: 
        protected $with = ['lessons'];
  ..so the model series always loaded with lessons

-

*/


//---------------------------------------------------------------------------------------------------------
//                      Lessons's view using Vue
//---------------------------------------------------------------------------------------------------------
/*

- make a new component called Lessons.vue
- register it in app.js:
      Vue.component('vue-lessons', require('./components/Lessons.vue').default);
  do: 
      npm run watch

- amount the vue component in series/index.blade.php: 
      <vue-lessons></vue-lessons>
  now assign the series to a vue variable (in Lesson.vue we will parse the objects to json objects): 
      <vue-lessons default_lessons="{{$series->lessons}}"></vue-lessons>

- in Lessons.vue assign the variable to the props: 
      props: [
        'default_lesson'
      ],

- too many things to explain, take a look at  Lessons.vue and trace the code
- the modal that pop up when you want to create new lesson should be in:
     children/CreatedLesson.vue
  and then register it the components property of Lessons.vue

- don't forget that the courese use and older version of vue.js, so when you import
  .. component inside a component, you do like this:
        import CreateLesson from './children/CreateLesson.vue'
        export default {
            props: [
                'default_lessons'
            ],
            components:{
                CreateLesson,W
            },
  .. unlike the way he used

- We also gonna use axios to handle request in ajax


---------


*/

//---------------------------------------------------------------------------------------------------------
//                      Explicit Route Model Binding
//---------------------------------------------------------------------------------------------------------
/*

- Here in laravel, register the route of the lessons here in web.php:

- Implicit route binding (???????????? ???????????? ???????? ?????????? ?????? ??????????)  is when you bind the model name with the model it self: 
            Route::resource('{series}/lessons',App\Http\Controllers\LessonsController::class);
  Now, you can in LessonsController@show do 
            show(Lesson $lesson){}
  and deal with $lesson as a model, this is called implicit binding


- Explicit route binding (???????????? ???????? ???????????? ???????????????? ?????????? ??????????????)  is a customise route binding,
  .. you have to explain to laravel how it should understand the passed parameter
  .. you do that in RouteService Provider: 
            Route::model('series_by_id', Series::class);
            Route::bind('series_by_id', function($value){
                return Series::findOrFail($value);
            });
  Domain will be like: 
            http://lms/admin/4/lessons/4
  .. notice we didn't pass (/series/4/lessons/4)
  Go to RouteServiceProvider for more details

------
- make lessons crud controller: 
            php artisan make:controller LessonsController --resource
  and fill the store(): 
            return $series->lessons()->create($request->all());

*/



//---------------------------------------------------------------------------------------------------------
//                      create lesson logic in Vue views
//---------------------------------------------------------------------------------------------------------
/*

- put an event listiner property in CreateLesson.vue, in the button tag: 
        <button type="button" class="btn btn-primary" @click="createLesson">Save lesson</button>
- put the method createLesson() in method: in the script of CreateLesson.vue

- now you have to have the series id in the Vue filse, to do that you have to pass it form /series/index: 
            <vue-lessons default_lessons="{{$series->lessons}}" series_id={{$series->id}}></vue-lessons>

- and in Lessons.vue, receive the series id as props: 
            props: [
                'default_lessons',
                'series_id'
            ],
  and in the methods:{} pass it to the child
            methods:{
                createNewLesson(){
                    this.$emit('create_new_lesson', this.series_id) <<<< pass it in the para
                }
              }
  
  

- and in CreateLesson.vue: 
          mounted(){
              this.$parent.$on('create_new_lesson', (seriesId)=>{ <<<<<<< here, pass the series id
                  this.seriesId = seriesId;
                  console.log('hello parent, we are creating the lesson')
                  //here we use jquery + bootstrap: 
                  $('#createLesson').modal()
              })
            }

  and in the same file: 
          data(){
            return{
              seriesId: ''
            }
          }

- in CreateLesson.vue  do the axios post requests, go check it,
  .. This shit doesn't work, i donno why, it wasted my time: 
          Axios.post('/admin/${this.seriesId}/lessons', {
  so i replaced it with this: 
          Axios.post("/admin/" + this.seriesId +"/lessons", {
  and it works!!!

*/


//---------------------------------------------------------------------------------------------------------
//                      create lesson logic in Vue views: A Test for it!
//---------------------------------------------------------------------------------------------------------
/*

- do: 
      php artisan make:test CreateLessonsTest

- make this function with its tests: 
      test_a_user_can_create_lessons()

- make this test: 
      ./vendor/bin/phpunit --filter test_a_user_can_create_lessons

- don't forget:     use RefreshDatabase;

- change 200 to 201, cuz it means it created successfuly, not just the page request succeed!

- go and see the test.


- Do another test: 
            test_a_title_is_required_to_create_a_lesson()
  and fill it, this is some of the stuff: 
            $this->postJson("/admin/$series->id/lessons", $lesson)
                  ->assertSessionHasErrors('title');

- put a valiation for creat lesson in custom request
            php artisan make:request CreateLessonRequest
  dont forget to set the authorize() to return true in the CreateLessonRequest

- do the test: 
            ./vendor/bin/phpunit --filter test_a_title_is_required_to_create_a_lesson
  Error, why? cuz you use: 
            postJson()
  ..and this make json api enviroment, and test json only, so this assertion is not suitable: 
            ->assertSessionHasErrors('title');
  Cuz it is postJson() not post(), so what the solution? use this assertion: 
            ->assertStatus(422)
  Succeed!!

- now do the same function but for description: 
            test_a_description_is_required_to_create_a_lesson()


*/




//---------------------------------------------------------------------------------------------------------
//                      lesson created successfully: 
//---------------------------------------------------------------------------------------------------------
/*

- go to CreateLesson.vue, the repsonse for creating will give you a message,
  .. you have to $emit to pass it up to the parent:
            ).then(reps =>{
              this.$parent.$emit('lesson_created', resp.data) << notice it's this.$parent.$emit not this.$emit.... if you do it without parent then you have to refresh the page to see the lessons updated
            }).catch

- now to listen to this event, you have to mount it in the parent (Lesson.vue): 
            mounted(){
              this.$on('lesson_created', (lesson) =>{
                this.lessons.push(lesson)
              }
            }
- look at CreateLesson.vue and Lessons.vue, cuz they are the only files changed here

*/



//---------------------------------------------------------------------------------------------------------
//                      Deleting Lessons: 
//---------------------------------------------------------------------------------------------------------
/*

- fill the destroy function in LessonsController{} 

- make function for delete in Lesson.vue attach with delete button:
        <button class="btn btn-danger btn-xs" @click="deleteLesson(lesson.id, key)">
            Delete
        </button> 
  obviously you have to create the function in method:{} :
    deleteLesson(){
      if(confirm('Are you sure you wanna delete?')){
        ax
      }
    }

- to Make the dom update the array of lesson, you have to pass the index key of that array
  .. when been catched from the loop of lessons.


- now put this in the axios .then() 
        this.lessons.splice(key,1)

*/


//---------------------------------------------------------------------------------------------------------
//                      Update Lessons: 
//---------------------------------------------------------------------------------------------------------
/*

- fill the update() in LessonsController{} 

- make update request: 
      php artisan make:request UpdateLessonRequest


------
Edit: 

- Now in Lessons.vue make a click event for editLesson(lesson): 
        <button class="btn btn-primary btn-xs" @click="editLesson(lesson)">
- make the function editLesson(lesson), and $emit the lesson you wanna update to 
  ..the child CreateLesson.vue to display the edit: 
        editLesson(lesson){
          this.$emit('edit_lesson', lesson)
        }

- now in the child CreateLesson.vue, in mount(): 
        this.$parent.$on('edit_lesson', (lesson) => {
            this.editingMode = true
        })
  notice you active the editingMode variable

- fill the same function with what you want to be displayed in the modal form: 
        this.$parent.$on('edit_lesson', (lesson) => {
              this.editingMode = true

              this.title = lesson.title
              this.description = lesson.description
              this.video_id = lesson.video_id
              this.episode_number = lesson.episode_number
          })

- now, use the editingMode in with buttons to change the text with v-if + v-else: 
        <button type="button" class="btn btn-primary" @click="updateLesson" v-if="editing">Save lesson</button>
        <button type="button" class="btn btn-primary" @click="createLesson" v-else>Edit lesson</button>



-------
Update: 


- pass the series_id as payload just like you did with create lesson

- in CreateLesson.vue create the updateLesson() method using axios, go and see it.
- refactore CreateLesson and make a class for lesson that have constructor to put the property on it: 
              //--------------------
              // create Lesson Class
              //--------------------
  replaced everything you need to replace + replace the v-model="title" to v-model="lesson.title" and so on

- make the put request using axios.put() and send the edited data, go and see it 

- in axios.put().then(....), close the modal+ update the DOM

- now in Lessons.vue listen to the event, in mounted()
    {find the index of the updated + replace the old lesson with the updated lesson}

*/



//---------------------------------------------------------------------------------------------------------
//                      Custom Notification
//---------------------------------------------------------------------------------------------------------
/*

- Best way to display a notification in anywhere is to register it in js event bus in our window object
  .. to understand that, just visit this video, im too tired to explain: 
      https://www.udemy.com/course/the-ultimate-advanced-laravel-pro-course-incl-vuejs-2/learn/lecture/8449626?start=30#content
  For me, I understand the event bus from this perfect video: 
      https://www.youtube.com/watch?v=jzh4zQcfB0o

- make window.events = new Vue(); in app.js << a vue instance stored in a window variable object called events

- also make it window.events.$emit that emit! 

- make Noty.vue component that have window.events.$on() that listen  + register it in app.js (vue.js)

- now mount the noty component in the app.blade.php bottom

- etc etc etc .......


- now if you want to notify, just use: 
      window.noty({
          message: 'Put you message here',
          type: "success" <<<<<< if error put danger, if success put success
      })
  you can do that after any event, like when you create a new lesson or delete


- So, to have a global variable or global object in vue.js, you can use an event bus
  .. so the component can commuincate with the the global listener,

- Or Also you can use a global method in the app.js also using window.
  .. we will customize a function in the app.js and call it:
          window.handleError() 
  .. Go and see it!



*/



//---------------------------------------------------------------------------------------------------------
//                        Update Series Test
//---------------------------------------------------------------------------------------------------------
/*

- do:
      php artisan make:test UpdateSeriesTest

- make: 
      test_a_user_can_update_a_series()

- these test class and function are very similar to the CreateSeriesTest

- success!!


- make this also: 
      test_an_image_is_not_required_to_update_a_series()
      

*/


//---------------------------------------------------------------------------------------------------------
//                        Parent Series Request
//---------------------------------------------------------------------------------------------------------
/*

- there are many duplicated functinos in update and create series request,
  .. we want to make a parent series request to inherinit the function to the childs: 
        php artisan make:request SeriesRequest

- now move uploadSeriesImage() from UpdateSeriesRequest{} to SeriesRequest{} 
- >>> UpdateSeriesRequest extends SeriesRequest <<< instead of >> UpdateSeriesRequest extends FormRequest
 

- Do the previous two steps to CreateSeriesRequest{} also.. 

- now do this to make sure that all works fine: 
        ./vendor/bin/phpunit

- Now move all the logic from SeriesController@update() to UpdateSeriesRequest@updateSeries()


*/



//---------------------------------------------------------------------------------------------------------
//                      Clean the webiste:
//---------------------------------------------------------------------------------------------------------
/*

- In this part of the tutorial, nothings new, all known: 
      1- set up the nav bar links
      2- CRUD functions for the series
      3- UdpateSeriesRequest 

- A semi new thing, route group file (like web.php and api.php): 
      - create a route group file
      - import it in Kernel.php in the $middlewareGroups array
      - fill the array with a 'web' so it has all the middleware of 'web' group
      - and put its own middleware which is 'admin' middleware
      - in the boot() of RouteServiceProvider.php put: 
            Route::prefix('admin')
                ->middleware('admin')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
      - Now move the admin controllers in 'Controllers\Admin'
      - update Controller importing
      - update the name space
*/




//---------------------------------------------------------------------------------------------------------
//                      FrontendController + Diplaying images
//---------------------------------------------------------------------------------------------------------
/*

- make this: 
      php artisan make:controller FrontendController
- the '/' route, make it route to this controller 

- copy the front end code from the github and paste it to the feature section of welcome.blade

- do this to link the private storage folder with the public: 
      php artisan storage:link

- refactor the tests for image uploading and testing in: 
      CreateSeriesTest
      UpdateSeriesTest
      SeriesRequest
  to have: 
      /public/series/...
  instead of 
      /series/...

------------


*/



//---------------------------------------------------------------------------------------------------------
//                      Unit Test for Series + accessor (like the accessor in java, setter and getter)
//---------------------------------------------------------------------------------------------------------
/*

- make this: 
      php artisan make:test SeriesTest --unit

- go and see it

- we made an accessor: a getter in the model Series.php

- use the newlly created accessor in welcome.php
*/



//---------------------------------------------------------------------------------------------------------
//                      Redis: using Predis
//---------------------------------------------------------------------------------------------------------
/*

- Redis is a fast tool makes you store data in memory instead of the harddriver, just like sqlite

- install predis using composer: 
      composer require predis/predis

- in .env, put: 
      REDIS_CLIENT=predis

- in config/database, put or change to: 
      'client' => env('REDIS_CLIENT', 'predis'),

- installation video: 
      https://www.youtube.com/watch?v=188Fy-oCw4w&ab_channel=ProgrammingKnowledge2

- install redis like the video using this file for windows (not offical): 
      https://github.com/microsoftarchive/redis/releases

- open redis server

---

- try redis in a route: 
      Route::get('/redis', function(){

      });
- do the tutorial with kati frantz: 
        https://www.udemy.com/course/the-ultimate-advanced-laravel-pro-course-incl-vuejs-2/learn/lecture/8449646?start=90#content



        
-----
- this is the documents of Predis, which is a library that use redis api: 
      https://github.com/predis/predis
- to learn redis commands: 
      https://try.redis.io/


*/


//---------------------------------------------------------------------------------------------------------
//                      Making a Trait in Laravel!
//---------------------------------------------------------------------------------------------------------
/*

- let's make a trait, make: 
      app/Entities/Learning.php 

- make the trait, and put all the extra functions you made in last lesson on it
  .. go and see the trait Learning.php

- after that, use it in the User model: 
      use HasFactory, Notifiable, Learning;

- and that's it!!

*/



//---------------------------------------------------------------------------------------------------------
//                      User unit test
//---------------------------------------------------------------------------------------------------------
/*

- do: 
      php artisan make:test UserTest --unit

- fill it up with tests that using redis, and do the test: 
      ./vendor/bin/phpunit --filter test_a_user_can_complete_a_lesson


- Now, create a function in Test/TestCase{} called: 
      flushRedis()
  .. to refresh redis database


- make another test and fill it up, then test it 
      ./vendor/bin/phpunit --filter test_can_get_percentage_completed_for_series_for_a_user

- make a function in User model: 
      test_can_get_percentage_completed_for_series_for_a_user()
  make another one: 
      getNumberOfCompletedLessonsForASeries()
  and this second function, make an assertion for it in
      UserTest@test_a_user_can_complete_a_lesson()

- make also another function: 
      test_can_know_if_a_user_has_started_a_series()
      

- make this in Learinig.php trait: 
      1- hasStartedSeries()
  and make this function in the Learning trait: 
      2- getCompletedLessons()
  and also this one cuz it relay on it: 
      3- getCompletedLessonsForASeries()
  and refactor the old function: 
      0- getNumberOfCompletedLessonsForASeries()
  so it uses the new method number 3


-Make this function, it's the most complicated, so go and see it: 
      test_can_get_completed_lessons_for_a_series()

-See the related functions in Learning.php trait: 
      getCompletedLessonsForASeries()
      getCompletedLessons()

*/







//---------------------------------------------------------------------------------------------------------
//                      Single Series View + Custom Blade Directive for hasStartedSeries()
//---------------------------------------------------------------------------------------------------------
/*

-  make a new route: 
      Route::get('/series/{series}', [App\Http\Controllers\FrontendController::class, 'series'])->name('series');

- make the series.blade.php 

- clean it up.. 

- you know this @auth @else @endauth ?? you can customize one that does your own function!
  .. go to: 
      AppServiceProvider.php@boot()
  use the blade facade: 
      Blade::if();
  Go and see it!!!


- now use it in series.blade.php: 
      @hasStartedSeries
  

*/

//---------------------------------------------------------------------------------------------------------
//                      Watch Series
//---------------------------------------------------------------------------------------------------------
/*

- make a new route: 
      Route::get('/watch-series/{series}', [App\Http\Controllers\WatchSeriesController::class, 'index']);

- make a controller for that: 
      php artisan make:controller WatchSeriesController
  and create the @index() inside it

- now create the route: 


*/





//---------------------------------------------------------------------------------------------------------
//                      Vimeo
//---------------------------------------------------------------------------------------------------------
/*

- Let's use vimeo: 
      https://github.com/vimeo/player.js/

- install it: 
      npm install @vimeo/player

- create a component to display vimeo content: 
      components/Player.vue

- fill the Player.vue, go and see it


-----
- we want to pass the id of the video from controller to > watch.blade to > Player.vue
  .. passe the series in watch.blade: 
            <vue-player defaul_lesson="{{$lesson}}"></vue-player>
  .. then receive it using props() of Player.vue as a json object
  .. then in data() parse it to lesson object again
  .. then in html of Player.vue get bind it with the tag
            <div :data-vimeo-id="lesson.video_id" data-vimeo-width="640" v-if="lesson" id="handstick"></div>




*/


//---------------------------------------------------------------------------------------------------------
//                      Get ordered lessons for series + test that
//---------------------------------------------------------------------------------------------------------
/*

- make a function in series test: 
      test_can_get_ordered_lessons_for_a_series()

- fill it

- make a function in Series.php model: 
      getOrderedLessons()

- Done!


*/


//---------------------------------------------------------------------------------------------------------
//                      Next and previous button (test driven dev approach)
//---------------------------------------------------------------------------------------------------------
/*

- make test, cuz we are test driven dev approach, so test then dev: 
      php artisan make:test LessonTest --unit

- fill it up

- make getNextLesson() and getPrevLesson() in Lesson.php model

- test all of that

-------

- later we did an improvement to these functions, getNextLesson() and getPrevLesson() 
  .. if you are in the first lesson then the prev won't be null, istead it will be the first also
  .. same thing for the next.
- So go and edit LessonTest.php

- and then edit getNextLesson() in Lesson.php, so it never return null


*/


//---------------------------------------------------------------------------------------------------------
//                      display list of lesson in watch.blade.php : go and see it 
//---------------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------------
//                      Vide ended notifiaction + sweetalert
//---------------------------------------------------------------------------------------------------------
/*
- this for sweet notificaition: 
      https://sweetalert.js.org/guides/
-install it:
      npm install sweetalert --save

- in vimeo, from docs, get the function that inform you the video ended: 
      https://github.com/vimeo/player.js/#ended  

- in Player.vue put the function player.on('ended',....) in mount() 

- import the sweetalert
- when the video ended the displayVideoEndedAlert() will be called and alert  will be triggered

- after the alert triggered, if the user click on the next notifiaction it move him to next

- but we don't have the next lesson yet, you should pass it from watch.blade > Player 
  .. (same way we pass values in Vimeo section, go check it up there)

-

*/


//---------------------------------------------------------------------------------------------------------
//                      Debug Laravel
//---------------------------------------------------------------------------------------------------------
/*
- a better way to debug laravl is to use laravel debugbar: 
      https://github.com/barryvdh/laravel-debugbar

- install it: 
      composer require barryvdh/laravel-debugbar --dev

- open: 
      http://lms/series/first-series/lesson/1

- and at the bottom at the debuger, look at the queries section and find the duplicated queries,

- Now solve that in watch.blade.php

- solve the nextLesson prop that been passed to vue with if elese in watch.blade
  .. now in vue also use if else in displayVideoEndedAlert()

*/



//---------------------------------------------------------------------------------------------------------
//                      Feature test for complete lessons functionality
//---------------------------------------------------------------------------------------------------------
/*
- make a test: 
      php artisan make:test WatchSeriesTest

- make a test function: 
      test_a_user_can_complete_a_series()

- make also this function but in UserTest.php unit test: 
      test_can_check_if_user_has_completed_lesson()

- .....


*/



//---------------------------------------------------------------------------------------------------------
//                      Display active lesson (using if else) and Completed lesson (using ajax)
//---------------------------------------------------------------------------------------------------------
/*
- do you job in watch.blade.php for: active lesson

- Use ajax in Player.vue to trigger when lesson completed

- make completeLesson() method in Player.vue using axios

-
*/




//---------------------------------------------------------------------------------------------------------
//                      Display active lesson (using if else) and Completed lesson (using ajax)
//---------------------------------------------------------------------------------------------------------
/*
- make a function in UserTest unit test: 
      test_can_get_all_series_being_watched_by_user()

- in learning
*/


//---------------------------------------------------------------------------------------------------------
//                      The rest of functions of Learning.php and UserTest.php
//---------------------------------------------------------------------------------------------------------
/*
- ........

*/



//---------------------------------------------------------------------------------------------------------
//                      Displaying user profile
//---------------------------------------------------------------------------------------------------------
/*
- make route for the profile: 
      Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'index']);
- make controller: 
      php artisan make:controller ProfileController
- over ride the function getRouteKeyName() in User.php so you can get the user by username not id in routing

- set up the profile.blade.php

- add ->filter(); in the function seriesBeingWatched() of the customized trait Learning.php
  ..this will filter the returning value to exclude the null values and records


*/


//---------------------------------------------------------------------------------------------------------
//                      Custom blade admin directive
//---------------------------------------------------------------------------------------------------------
/*
- before talking bout this topic, I have to put all the lessons in a middleware auth, 
  .. cuz obvously if you are not logged in, you can't see the course content: 
  .. so just an auth middleware: 
      Route::middleware('auth')->group(function{...});

- Now, the navbar display "create user" for any authed user, we only want that for the admin,
  .. to do that we will use Custom blade admin directive, so we can use @admin >> @endadmin 
  .. in any blade

- .. go to: 
      AppServiceProvider.php@boot()
  use the blade facade: 
      Blade::if();
  Go and see it!!!


- now you can use this in any blade: 
      @admin

- now use it for navbar and for what you need.

*/


//---------------------------------------------------------------------------------------------------------
//                      Test the next lesson user should watch + frontend
//---------------------------------------------------------------------------------------------------------
/*
- make this function in UserTest: 
      test_can_get_lesson_to_be_watched_by_user()

- create this function in learning: 
      getNextLessonToWatch()

--------
- I editted some stuf in LessonTest.php and getNextLesson(), so we could make next things right

- in WatchSeriesController.php@index, user should not redirected to first lesson,
  .. but to the next lesson after the last lesson he watched, go fix it!

- I also modify navbar and add all series link for guest and user (not just admin)

*/




//---------------------------------------------------------------------------------------------------------
//                       Cashier: for Saas subscribtions and billing 
//---------------------------------------------------------------------------------------------------------
/*
- Here we reached to the core idea of saas, a subscribtion and billing, all that can be done
  .. with cashier!

- this is the docs: 
      https://laravel.com/docs/8.x/billing

- install it: 
      composer require laravel/cashier

- do this to see migrations and you can edit it if you want: 
      php artisan vendor:publish --tag="cashier-migrations"

- add this to User.php: 
      use Billable;

- set up the these keys in .env: 
      STRIPE_KEY=your-stripe-key
      STRIPE_SECRET=your-stripe-secret

- by default the currency is usd, i think you can change it in .env, unfortunatally there is no saudi riyal: 
      CASHIER_CURRENCY=AED

- migrate!


- make account on stripe, you already did: 
      https://dashboard.stripe.com/

- make subscribtion plans, in the new version of stirpe there is no plans,
  .. so you can make a product and put prices for the product,
  .. so make a products and name them premium and standard and put prices


- create a new component: Stripe.vue, and register it in app.js

- create a view: subscribe.blade.php

- make a route.

- there is no documentation for stripe checkout form that you're using now, cuz it's an old one,
  .. so, the only way to understand the code is from kati's videos: 
            https://www.udemy.com/course/the-ultimate-advanced-laravel-pro-course-incl-vuejs-2/learn/lecture/8576566#content

- the form form checkout has an email empty field, but we don't want that, we want it to be auto filled,
  .. cuz we already has an email from auth.. so pass it from subscribe.blade >> Stripe.js


- Because of Stripe updates, all document has changed for Cashier and Stripe have no 'plan' anymore,
  .. but there is some ways to trick that like making product and then put price for it,
  .. and make a subscription for that product. But I'm not willing to that, I will make the app
  .. comunicate with stripe only to make payment, after the customer pays i will not comunicate with
  .. stripe to register the subscription on their side, instead i will register the subscription
  .. only in our app, why? cuz i'm not willing to learn a technology that i won't use (Stripe has no support for SR only AED)
  .. So, most probably in a real project I will use Paytabs not stripe, that's why i'm doing these tricks.

*/


//---------------------------------------------------------------------------------------------------------
//                       Cashier: for Saas subscribtions and billing 
//---------------------------------------------------------------------------------------------------------
/*
- make controller: 
      php artisan make:controller SubscriptionsController

- make routes, one for subscribe form and the other one for creating subscription in db

- make functions for that routes in the controller 
*/


//---------------------------------------------------------------------------------------------------------
//                       Test Subscription
//---------------------------------------------------------------------------------------------------------
/*
- make a feature test:  
      php artisan make:test SubscriptionTest

- make fake subscription, so it won't comunicate with stripe at all, but it would insert a record
  .. in subscription table, make this function for that: 
            fakeSubscribe()

- make this function: 
      test_a_user_without_a_plan_can_watch_free_lessons()

- now run the test: 
      ./vendor/bin/phpunit --filter test_a_user_without_a_plan_can_watch_free_lessons

- modify the prev function to be:
      test_a_user_without_a_plan_can_watch_premium_lessons()
  go and see the code

- now, in WatchSeriesContrller@showLesson(), put the condition for premium using: 
      $user->subscribed('yearly')
  that's for one plan (or product in the new stripe), if you want it for all plans: 
      $user->subscribedToProduct($products, subscription = 'default) << not working, donno why


- make a second function in SubscriptionTest: 
      test_a_user_on_any_plan_can_watch_all_lessons()

*/


//---------------------------------------------------------------------------------------------------------
//                       CreateLesson.vue: premium lesson check box
//---------------------------------------------------------------------------------------------------------
/*
- make a checkboxx for premium in CreateLesson.vue
- bind it with data
- now go to LessonsController@store+@update: ok, it's already handling all the inputs with all()

- now refresh db so it includes the new column "premium" 

*/





//---------------------------------------------------------------------------------------------------------
//                       CreateLesson.vue: premium lesson check box
//---------------------------------------------------------------------------------------------------------
/*
- make a checkboxx for premium in CreateLesson.vue
- bind it with data
- now go to LessonsController@store+@update: ok, it's already handling all the inputs with all()

- now refresh db so it includes the new column "premium" 

*/


//---------------------------------------------------------------------------------------------------------
//                       Stripe.vue: make subscribing looks better: use sweet alert in Stripe.vue to alert user when he register
//---------------------------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------------------------
//                       User Profile
//---------------------------------------------------------------------------------------------------------
/*
- put this propery in User.php: 
      protected $with = ['subscriptions']

- copy the prfile.blade.php view from kati's github

- make a route: 
      Route::post('/subscription/change')

- make that route's function in the controller SubscriptionsController@change()


*/


//---------------------------------------------------------------------------------------------------------
//                       Make notification better
//---------------------------------------------------------------------------------------------------------
/*
- remove the template in Noty.vue
- use sweet alert instead of the template

*/


//---------------------------------------------------------------------------------------------------------
//                       Queueing laravel emails
//---------------------------------------------------------------------------------------------------------
/*
- in ConfirmYourEmail.php, just implements ShouldQueue

- update the queue driver in .env: 
      QUEUE_DRIVER=database
  instead of 
      QUEUE_DRIVER=sync
  so it will process the queued jobs in db instead of processing it in real time (sync)

- now make a queue table: 
      php artisan queue:table
- migrate!

- set up a queue worker: 
      php artisan queue:work

*/



//---------------------------------------------------------------------------------------------------------
//                       Shared hosting is bullshit
//---------------------------------------------------------------------------------------------------------
/*
- Why? cuz you need an active CMDs to make you app works perfectly,
  .. until now you have to run 3 services: 
      1- npm run watch 
      2- redis server
      3- php artisan queue:work

- in shared hosting, your website will be static, and data won't proccess 
  .. unless you made an http request, nothing will be proccessed in the 
  .. background.


*/





















