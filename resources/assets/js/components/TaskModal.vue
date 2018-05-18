<template>
   <div class="modal" id="taskmodal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Create a Task</h5>
               <button type="button" class="close" data-dismiss="modal" aria-labeled="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <form>
                  <div class="form-group">
                     <label for="title">Title</label>
                     <input type="text" class="form-control" id="title" v-model="task.title" placeholder="Describe the task" contenteditable>
                  </div>
                  <div class="form-group">
                     <label for="description">Additional Description</label>
                     <textarea class="form-control" id="description" v-model="task.description"></textarea>
                  </div>
                  <div class="form-group">
                     <label for="due_date">Due Date: </label>
                     <datepicker v-model="task.due_date" :clear-button="true" format="YYYY-MM-DD" icons-font="fa"></datepicker>         
                  </div>
                  <div class="form-check">
                     <input type="checkbox" class="form-check-input" v-model="task.reminder"> 
                     <label class="form-check-label">I want reminders</label>

                  </div>

               </form>
            </div>
            <div class="modal-footer">
               <button class="btn btn-sm btn-primary" @click="create">Add</button>
            </div>
         </div>
      </div>

   </div>
   
</template>

<script>
import { datepicker } from 'vue-strap'
import $ from 'jquery'
import 'jquery.caret'
import 'at.js'

export default{
   props:['open'],
   components: {
      datepicker
   },
   data(){
      return {
         task:{
            title:'',
            description:'',
            due_date: moment().format('YYYY-MM-DD'),
            type:'Task',
            taskable_type:'none',
            taskable_id:'',
            reminder: false,
            status:'New',
            owner_id: Spark.state.user.id
         }

      }
   },
   methods:{
      create:function(){
         console.log(this.task);
         let self = this;
         axios.post('/api/tasks/create',this.task).then(response => {
            console.log(response);
            //success broadcast the new task to the list  
            self.$eventHub.$emit('task-added', response.data.data)
            //clear the task object
            self.task.title = ''
            self.task.description = ''
            self.task.due_date = new Date().toDateString();
            self.task.taskable_type = 'none'
            self.task.taskable_id = ''
            $('#taskmodal').modal('hide');

            //close modal

         }).catch(error => {
            console.log(error);
         })
      }
   },
   mounted(){
      let self = this
      this.$eventHub.$on('open-task-modal', payload => {
         //open the modal  if(payload.method == 'open'){
            $('#taskmodal').modal('show');
         //set the taskabale type
         self.task.taskable_type = 'App\\' + payload.type;
         self.task.taskable_id = payload.id;
         if(payload.due_date){
            self.task.due_date = payload.due_date;
         }
         if(payload.description){
            self.task.description = payload.description;
         }
      })

      $('#description').atwho({
         at: "@",
         delay: 500,
         callbacks: {
            remoteFilter: function(query, callback){
               axios.get("/api/lookups/agents/" + query).then(response => {
                  console.log(response.data);
                  callback(response.data);
               })
               console.log('called');
            }
         }
      }).atwho({
         at:"#",
         delay: 500,
         callbacks: {
            remoteFilter: function(query, callback){
               axios.get("/api/lookups/companies/" + query).then(response => {
                  console.log(response.data);
                  callback(response.data);
               })
               console.log('called');
            }
         }
      })
   }
}

</script>