<template>
    <div>
        <div :data-vimeo-id="lesson.video_id" data-vimeo-width="720" v-if="lesson" id="handstick"></div>
    </div>
</template>


<script>
import Player from '@vimeo/player'
import Swal from 'sweetalert'
export default {
    props: [
        'defaul_lesson', // this value been received from watch.blade.php, it's a json object
        'next_lesson_url'
        ],

    data(){
        return {
            lesson: JSON.parse(this.defaul_lesson) // here we parse the json object to an object
        }
    },
    methods:{
        displayVideoEndedAlert(){
            Swal('Yaaay! You completed this lesson!')
            .then(() => {
                window.location = this.next_lesson_url
            })
        }
    },

    mounted(){
        const player = new Player('handstick')

        player.on('ended', () => {
            this.displayVideoEndedAlert();
        })
    },


}
</script>