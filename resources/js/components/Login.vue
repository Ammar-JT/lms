<template>
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="card card-shadowed p-50 w-400 mb-0" style="max-width: 100%">
                <h5 class="text-uppercase text-center">Login</h5>
                <br><br>
                <form>
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
                remember: true
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
                //this like any ajax function, will send a request >>>> axios.post('/',{}) <<<<,
                //..then listen to the respone >>>> .then(resp=>{}) <<<< that came from the server
                //.. then it will catch an error if exist

               
            }
        },
        computed:{
            isValidLoginForm(){
                return this.emailIsValid() && this.password
            }
        }
    }
</script>
