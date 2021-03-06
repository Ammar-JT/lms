/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;


/*
//I'm just playing with windows object to figure out how to make a notification using
//.. event bus: 
window.noty = function(notification){
    console.log(notification.message) // go to chrome in the console and call noty({ messgae: 'asdf'})
                                      // .. why { message:'asdf'}? cuz logically, it expect an object that one of its properties is message                         
}
*/

//------------------------
//       Event Bus
//------------------------
window.events = new Vue();

window.noty = function(notification){
    window.events.$emit('notification' , notification)
}

//------------------------
//      global method
//------------------------
//this method can be used in any component in vue.js: 
window.handleError = function(error){
    if(error.response.status == 422){
        window.noty({
            message: 'You had validation errors, please try again',
            type: 'danger'
        })
    }
    window.noty({
        message: 'Something went wrong, please refresh the page',
        type: 'danger'
    })
}




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('vue-player', require('./components/Player.vue').default);
Vue.component('vue-noty', require('./components/Noty.vue').default);
Vue.component('vue-login', require('./components/Login.vue').default);
Vue.component('vue-lessons', require('./components/Lessons.vue').default);
Vue.component('vue-stripe', require('./components/Stripe.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
