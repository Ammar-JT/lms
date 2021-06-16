<template>
    <div class="container">
        <ul class="list-group">
            <h1 class="text-center">
                <button class="btn btn-primary" @click="createNewLesson">
                    Create New Lesson
                </button>
            </h1>
            <li class="list-group-item" v-for="lesson in formattedLessons" :key="formattedLessons.indexOf(lesson)">
                <b>{{lesson.title}}</b>
            </li>
        </ul>
        <CreateLesson></CreateLesson>
    </div>
</template>



<script>
import CreateLesson from './children/CreateLesson.vue'
export default {
    props: [
        'default_lessons',
        'series_id'
    ],
    components:{
        CreateLesson,

    },
    data(){
        return{
            lessons: this.default_lessons
        }
    },
    computed:{
        formattedLessons(){
            return JSON.parse(this.lessons)
        }
    },
    methods:{
        createNewLesson(){
            //you will emit this event to a child this time,
            //.. the child is CreateLesson.vue, and will be received there in mounted(){}
            this.$emit('create_new_lesson', this.series_id) 
        }
    }
    
}
</script>