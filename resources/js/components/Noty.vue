<template>
    <div class="alert  alert-noty" :class="typeOfMessage" v-if="notification">
        <p class="text-center">{{notification.message}}</p>
    </div>
</template>



<script>
export default {
    created(){ // i think this function means when the app created, so it works with booting.. i think!

        window.events.$on('notification', (payload) => {
            console.log('notification received')
            this.notification = payload
            setTimeout(() => {
                this.notification = null
            }, 3000)
        })
    },
    data(){
        return{
            notification: null
        }
    },
    computed: {
        typeOfMessage(){
            return 'alert-' + this.notification.type
        }
    }
}
</script>




<style>
    alert-noty{
        position: fixed;
        right: 20px;

        bottom: 40px;
        z-index: 1;

    }

</style>

