<template>
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="card card-shadowed p-50 w-400 mb-0" style="max-width: 100%">
                <h5 class="text-uppercase text-center">Login</h5>
                <br><br>
                <form>
                    <ul class="list-group " v-if="errors.length>0">
                        <!-- :key="errors.indexOf(error)" <<<< just to remove the error -->
                        <li class="list-group-item" v-for="error in errors" :key="errors.indexOf(error)">
                            <div class="alert alert-danger">
                                {{error}}
                            </div>
                        </li>
                    </ul>
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" v-model="password" placeholder="Password">
                    </div>

                    <div class="form-group flexbox py-10">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" v-model="remember" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Remember me</span>
                        </label>

                        <a class="text-muted hover-primary fs-13" href="#">Forgot password?</a>
                    </div>

                    <div class="form-group">
                        <!-- 
                            :disabled="!isValidLoginForm" means bind the disable property to the function !isValidLoginForm() 
                            .. so if the values is invalid the function will return false and the property disabled will be true,
                            .. which means user can't submit the form
                        -->
                        <button :disabled="!isValidLoginForm" @click="attemptLogin" class="btn btn-bold btn-block btn-primary"  type="button">Login</button>
                    </div>
                </form>
                <p class="text-center text-muted fs-13 mt-20">Don't have an account? <a href="page-register.html">Sign up</a></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                email: '',
                password: '',
                remember: true,
                loading: false,
                errors: []
            }
        },
        methods:{
            emailIsValid()   {  
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email))  {  
                    return true
                } else { 
                    return false 
                }
            },
            attemptLogin(){
                //this means if user has unsuccessful login more than one time, 
                //..the list of messages won't be redundant: 
                this.errors = []
                
                //if true, then the login button will be disabled
                this.loading = true

                //this like any ajax function, will send a request >>>> axios.post('/',{}) <<<<,
                //..then listen to the respone >>>> .then(resp=>{}) <<<< that came from the server
                //.. then it will catch an error if exist
                axios.post('/login',{
                    email: this.email, password: this.password, remember: this.remember
                }).then(resp => {
                    console.log(resp)
                    location.reload()
                }).catch(error => {
                    this.loading = false
                    console.log(error)

                    if(error.response.status == 422){
                        this.errors.push("we couldn't verify your account details")
                    }else{ 
                        //422 means everthings went right, but the credintials of the user is not correct,
                        //.. that's why we putted else for the other cases: 
                        this.errors.push("Something went wrong, please refresh the page and try again")


                    }
                })
            }
        },
        computed:{
            isValidLoginForm(){
                return this.emailIsValid() && this.password && !this.loading
            }
        }
    }
</script>
