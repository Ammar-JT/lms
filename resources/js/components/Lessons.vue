<template>
    <div class="container" style="color: black; font-weight: bold;">
            
        <h1 class="text-center">
            <button class="btn btn-primary" @click="createNewLesson">
                Create New Lesson
            </button>
        </h1>
        <ul class="list-group d-flex">
            <li class="list-group-item d-flex justify-content-between" v-for="lesson in lessons" :key="lessons.indexOf(lesson)">
                <p>{{ lesson.title }}</p> 
                <p>
                    <button class="btn btn-primary btn-xs" @click="editLesson(lesson)">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-xs" @click="deleteLesson(lesson.id, key)">
                        Delete
                    </button>
                </p>
            </li>
        </ul>
        <CreateLesson></CreateLesson>
    </div>
</template>



<script>
import axios from 'axios'
import CreateLesson from './children/CreateLesson.vue'
export default {
    props: [
        'default_lessons',
        'series_id'
    ],
    mounted(){
        this.$on('lesson_created', (lesson) =>{
            window.noty({
                message: 'Lesson Created Successfully',
                type: 'success'
            })

            //push the new lesson into the list of lesson of vue: 
            this.lessons.push(lesson)

        }),

        this.$on('lesson_updated', (lesson) => {
            window.noty({
                message: 'Lesson Updated Successfully',
                type: 'success'
            })
                            //this js function is a loop+if: 
                            //this.lessons.findIndex( lesson => { here put condition })
                            // if the condition is true stop the loop:
            let lessonIndex = this.lessons.findIndex( l => {
                return lesson.id == l.id
            })// << why do we need this? to find the index of the element if it exist in the array


            //don't use this, use splice: 
            //this.lessons[lessonIndex] = lesson
            //after we find the index, let's replace the updated lesson with the old lesson: 
            this.lessons.splice(lessonIndex, 1, lesson)
        })
    },
    components:{
        CreateLesson,

    },
    data(){
        return{
            lessons: JSON.parse(this.default_lessons)
        }
    },
    methods:{
        createNewLesson(){
            //you will emit this event to a child this time,
            //.. the child is CreateLesson.vue, and will be received there in mounted(){}
            this.$emit('create_new_lesson', this.series_id) 
        },
        deleteLesson(id, key){
            if(confirm('Are you sure you wanna delete?')){
                axios.delete('/admin/' + this.series_id + '/lessons/' + id)
                     .then(resp => {
                        window.noty({
                            message: 'Lesson deleted Successfully',
                            type: 'success'
                        })
                        this.lessons.splice(key,1)
                     }).catch(error => {
                        window.handleError(error)                         
                     })
            }
        },
        editLesson(lesson){
            //donno why i cant pass this.series_id, {...} is an object
            //this.$emit('edit_lesson', {lesson, this.series_id})

            let seriesId = this.series_id
            this.$emit('edit_lesson', {lesson, seriesId})
        }
    }
    
}
</script>