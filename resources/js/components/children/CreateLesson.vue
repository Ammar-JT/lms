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
                            <input type="text" class="form-control" v-model="title" placeholder="title">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" v-model="video_id" placeholder="vimeo video id">
                        </div>

                        <div class="form-group">
                            <input type="number" class="form-control" v-model="episode_number" placeholder="Episode Number">
                        </div>

                        <div class="form-group">
                            <textarea v-model="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="createLesson">Save lesson</button>
                    </div>
                </div>
            </div>
          </div>
</template>



<script>
import Axios from 'axios'
export default {
    mounted(){
        //receive from the $emit of the parent Lessons.vue
        this.$parent.$on('create_new_lesson', (seriesId)=>{
            this.seriesId = seriesId;
            console.log('hello parent, we are creating the lesson')
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
            seriesId: ''
        }
    },
    methods:{
        createLesson(){
            Axios.post("/admin/" + this.seriesId +"/lessons", {
                title: this.title,
                description: this.description,
                episode_number: this.episode_number,
                video_id: this.video_id
            }).then(resp => {
                console.log(resp)
            }).catch(resp => {
                console.log(resp)
            })
        }
    }
}
</script>
