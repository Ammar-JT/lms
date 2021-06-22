<template>
    <div>
        <button class="btn btn-success" @click="subscribe('monthly')">Subscribe to $10 Monthly</button>
        <button class="btn btn-info" @click="subscribe('yearly')"> Subscribe to $100 Yearly</button>
    </div>
</template>

<script>
import Axios from 'axios'
import Swal from "sweetalert"

export default {
    props: ['email'],
    mounted(){
        this.handler = StripeCheckout.configure({
            key: 'pk_test_2VnQL9Cic4hLPeiYtvHellBI',
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            locale: 'auto',
            token(token) {
                console.log(token.id)
                //let's alert user using sweet alert: 
                Swal({test: 'please wait while we subscribe you to a plan...', buttons: false});
                Axios.post('/subscribe', {
                    stripeToken: token.id,
                    plan: window.stripePlan,
                    price: window.amount
                }).then(resp => {
                    console.log(resp)
                    //using sweet alret:
                    Swal({test: 'Successfully subscribed', icon: 'success'})
                    .then(() => {
                        window.location = "";
                    });
                })
                
            }
        }) 
    },
    data(){
        return{
            plan: "",
            amount: 0,
            handler: null
        }
    },
    methods:{
        subscribe(plan){
            if(plan=='monthly'){
                window.stripePlan = 'monthly'
                window.amount = 1000
            }else{
                window.stripePlan = 'yearly'
                window.amount = 10000
            }

            this.handler.open({
                name: 'LmsSaaS',
                description: 'Lms SaaS Subscription',
                amount: this.amount,
                email: this.email
            })

        }

    }

}
</script>
