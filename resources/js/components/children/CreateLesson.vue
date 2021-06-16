<template>
    <div class="modal fade" id="createLesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create new Lesson</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="lesson.title" placeholder="title">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" v-model="lesson.video_id" placeholder="vimeo video id">
                        </div>

                        <div class="form-group">
                            <input type="number" class="form-control" v-model="lesson.episode_number" placeholder="Episode Number">
                        </div>

                        <div class="form-group">
                            <textarea v-model="lesson.description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="updateLesson" v-if="editingMode">Save lesson</button>
                        <button type="button" class="btn btn-primary" @click="createLesson" v-else>Edit lesson</button>

                    </div>
                </div>
            </div>
          </div>
</template>



<script>
import Axios from 'axios'
//--------------------
// create Lesson Class
//--------------------
class Lesson {
    constructor(lesson){
            this.title = lesson.title || ""
            this.description = lesson.description || ""
            this.video_id = lesson.video_id || ""
            this.episode_number = lesson.episode_number || ""
    }
}
export default {
    mounted(){
        //receive from the $emit of the parent Lessons.vue
        this.$parent.$on('create_new_lesson', (seriesId)=>{
            this.editingMode = false
            this.seriesId = seriesId;

            //{} means empty object: 
            this.lesson = new Lesson({})

            console.log('hello parent, we are creating the lesson')
            //here we use jquery + bootstrap: 
            $('#createLesson').modal()
        }),


        this.$parent.$on('edit_lesson', ({lesson, seriesId}) => {
            this.editingMode = true
            this.seriesId = seriesId
            this.lessonId = lesson.id 

            //{} means empty object: 
            this.lesson = new Lesson(lesson)

 

            //here we use jquery + bootstrap: 
            $('#createLesson').modal()
        })
    },
    data(){
        return{
            title: '',
            description: '',
            episode_number: '',
            video_id: '',
            lesson: {},

            seriesId: '',
            editingMode: false,
            lessonId: null
            
        }
    },
    methods:{
        createLesson(){
            Axios.post("/admin/" + this.seriesId +"/lessons", this.lesson
            ).then(resp => {
                this.$parent.$emit('lesson_created', resp.data)
                //close the modal using jquery: 
                $('#createLesson').modal('hide')
            }).catch(error => {
                window.handleError(error)
            })
        }, 

        updateLesson(){
            console.log('updating')
            Axios.put("/admin/" + this.seriesId + "/lessons/" + this.lessonId , this.lesson
                ).then(resp => {
                    console.log(resp)

                    this.$parent.$emit('lesson_updated', resp.data)
                    $('#createLesson').modal('hide')

                }).catch(error =>{
                    window.handleError(error)
                })

        }
    }
}
</script>
