<template>
   <div class="modal" id="zoomModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"> Task Details </h5>
               <button type="button" class="close" data-dismiss="modal" aria-labeled="Close">
                  <span aria-hidden="true"> &times; </span>
               </button>
            </div>   
            <div class="modal-body">
               <div class="card">
                  <div class="card-body">
                     
                        <div class="form-check">
                           <input type="checkbox" class="form-check-input" v-model="task.completed" />
                           <label class="form-check-label">
                              <h3>{{task.title}}</h3>
               
                              <p>{{task.description}}</p>
                           </label>

                        </div>
                     
                  </div>
                  <div class="card-footer">
                     Task Due: {{task.due_date | moment('calendar')}} <span :class="[statusStyle ,'float-right']">{{computedStatus}}</span>
                  </div>
               </div>
               
               <notes-component :noteable-id="task.id" noteable-type="App\Task" v-bind:starting-notes="task.notes"></notes-component>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" @click="update">Update</button>
            </div>
         </div>
      </div>
   </div>
</template>
<script> 
export default {
   data(){
      return {
         task:{},

      }
   },
   computed: {
      computedStatus:function(){
         let d1 = new Date();
         let d2 = new Date(this.task.due_date);
         let timeDiff = d2.getTime() - d1.getTime();
         let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
         console.log(diffDays);
         if(diffDays === 0){
            return 'Due Today';
         } else if(diffDays < 1){
            return 'Overdue by ' + (diffDays * -1) + ' days';
         } else {
            return diffDays + ' days from now';
         }
      },
      statusStyle:function(){
         if(this.computedStatus === 'Due Today'){
            return 'text-success';
         } else if(this.computedStatus.includes("Overdue")){
            return 'text-danger';
         } else {
            return 'text-success';
         }
      }
   },
   methods: {
      update:function(){

      },

   },
   mounted(){
      let self = this;
      this.$eventHub.$on('toggle-task-detail-modal', payload => {
         console.log(payload);
         self.task = payload;
         $('#zoomModal').modal('show');
      })

   }
}
</script>