<template>
   <div>
      <div class="card card-default">
         <div class="card-header">
            <h4>Your Tasks <button class="btn bnt-sm btn-primary pull-right" @click="createTask">+ Add Task</button></h4>
            
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-12">
               <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="nav-active-tab" data-toggle="tab" href="#nav-active" role="tab" aria-controls="active" aria-selected="true">  Active</a>
                  </li>
                  <li class="nav-item">
                  <a class="nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed" role="tab" aria-controls="completed" aria-selected="false">  Completed&nbsp;</a>
                  </li>
               </ul>
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade active show" id="nav-active" role="tabpanel" aria-expanded="true" aria-labelledby="active-tab">
                     <div class="table-responseive-sm">
                     <table class="table">
                        <tbody>
                           <tr v-for="(task, index) in tasks">
                              <td style="width: 3%;">
                                 <input type="checkbox" @click="completeTask(index, task)" :checked="task.status === 'completed'"/>
                              </td>
                              <td><span v-bind:class="{strikethrough: task.status === 'completed'}">{{task.title}}</span>
                              </td>
                              <td>
                                 {{computedStatus(task.due_date)}}
                              </td>
                              <td style="width:23%;">
                                 <button class="btn btn-primary btn-sm" @click="viewDetails(task)"><span class="fa fa-search"></span></button>
                                 <button @click="deleteTask(index, task.id, 'tasks')" class="btn btn-default btn-sm"><span class="fa fa-trash"></span></button>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  </div>
                  <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="completed-tab" aria-expanded="false">
                     <table class="table">
                        <thead>
                           <tr>
                              <th scope="col"></th>
                              <th scope="col">Task</th>
                              <th scope="col"></th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-if="completed.length > 0" v-for="(task, index) in completed">
                              <td>
                                 <div class="input-group">
                                    <input type="checkbox" @click="completeTask(index, task)"/>
                                 </div>
                              </td>
                              <td><span v-bind:class="{strikethrough: task.status === 'completed'}">{{task.title}}</span></td>
                              <td><button class="btn btn-primary btn-sm">Status</button>
                                 <button @click="deleteTask(index, task.id, 'completed')" class="btn btn-default btn-sm"><span class="fa fa-trash-o"></span></button>
                              </td>
                           </tr>
                           <tr v-else>
                              <td>
                                 No Tasks Scheduled
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div> 
               </div>
               </div>
            </div>
               
            
         </div>
         <task-modal :open="taskModal"></task-modal>
         <task-zoom-modal></task-zoom-modal>
      </div>
   </div>
</template>
<style>
.strikethrough {
   text-decoration: line-through;
}
</style>
<script>
import { tabset, tab, tabs } from 'vue-strap'
   export default {
      props: ['loadTasks','taskScope', 'parentId'],
      components: {
         tab,
         tabset,
         tabs
      },
      data(){
         return {
            tasks:[],
            completed: [],
            filter:'',
            limit:'', 
            sort:'descending',
            taskModal: false,
            zoomTask:{},
            activeTab: 0
         }
      },
      watch: {
         loadTasks: function(){
            //this.tasks = this.loadTasks;
            console.log('Loading tasks');
            this.processTasks(this.loadTasks);
         },
         taskScope: function(){
            if(this.taskScope == 'global'){
               //then let's load the tasks with the component?
            }
         }
      },
      computed:{
         
      },
      methods: {
         computedStatus: function(date){
            let d1 = new Date();
            let d2 = new Date(date);
            let timeDiff = d2.getTime() - d1.getTime();
            let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            if(diffDays === 0){
               return 'Due Today';
            } else if(diffDays < 1){
               return 'Overdue by ' + (diffDays * -1) + ' days';
            } else {
               return diffDays + ' days from now';
            }
         },
         processTasks: function(tasks){
            let self = this;
            if(this.tasks.length > 0){
               //do nothing
            } else {
            tasks.forEach(function(ele){
               //console.log(ele);
               if(ele.status == 'completed'){
                  self.completed.push(ele);
               } else {
                  self.tasks.push(ele);
               }
            })
         }
         },
         getTasks: function(){
            let self = this;
            axios.get('/api/tasks').then(response => {
               console.log(response); 
               self.tasks = response.data.open;
               self.completed = response.data.completed;
            }).catch(error => {
               console.log('Error');
            })
         },
         createTask: function(){
            //open the create task modal
            this.$eventHub.$emit('open-task-modal', {type:this.taskScope, id: this.parentId});
         },
         viewDetails: function(task){
            this.zoomTask = task;
            this.$eventHub.$emit('toggle-task-detail-modal',task);
            this.$eventHub.$emit('notes-for-task-'+task.id)
         },
         deleteTask: function(index, task, list){
            let self = this;
            axios.get('/api/tasks/delete/'+task.id).then(response => {
               console.log(response);
               if(list == 'tasks'){
                  self.tasks.splice(index,1);
               } else if(list == 'completed'){
                  self.completed.splice(index,1);
               }
               self.tasks.splice(index, 1);
            }).catch(error => {
               console.log(error);
            })
         },
         completeTask: function(index, task){
            console.log('Completing this task');
            //submit to api to update task status
            task.status = 'completed'; 
            let self = this; 
            axios.post('/api/tasks/update/' + task.id, task).then(response => {
               console.log(response);
               setTimeout(function() {
                  self.tasks.splice(index, 1);
                  self.completed.push(task);
               },2000)
               
            }).catch(error => {
               console.log(error);
            }) 

            //set a timer to remove after 3-4 seconds 


         },
         deleteTask: function(index, task, list){
            let self = this;
            axios.get('/api/tasks/delete/' + task).then(response => {
               console.log(response);
               self[list].splice(index, 1);
            }).catch(error => {
               console.log(error);
            })
         }
      },
      mounted(){
         //this.$eventHub.$emit('open-modal', 'open');
         console.log('Task List Mounted');
         let self = this
         this.processTasks(this.loadTasks);
         this.$eventHub.$on('task-added', payload  => {
            console.log(payload);
            self.tasks.push(payload);
         })
         //this.getTasks();
      }
   }
</script>