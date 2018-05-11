<template>
   <div>
   <table class="table table-sm">
      <thead>
         <th>
            Activity
         </th>
         <th>Type</th>
         <th>User</th>
         <th>Followup</th>
         <th>Completed On</th>
      </thead>
      <tbody>
         <tr v-for="activity in activities">
            <td>{{activity.description}}</td>
            <td><i :class="activityIcon"></i> {{activity.type}}</td>
            <td>{{activity.owner.name}}</td>
            <td>
               <span v-if="activity.tasks.length > 0">{{activity.tasks[0].due_date | moment('calendar') }}
               </span>
               <span v-else>Not Scheduled</span>
            </td>
            <td>{{activity.created_at | moment("calendar")}}</td>
         </tr>
      </tbody>
   </table>
</div>
</template>
<style>

</style>
<script>
export default{
   props:['parent', 'parent_id'],
   data(){
      return {
         activities:[]
      }
   },
   watch: {
      parent: function(){
         this.activities = this.parent.activities;
      }
   },
   methods:{
      activityIcon: function(type){
         if(type === 'call') {
            return 'fa fa-phone'
         } else if (type === 'email') {
            return 'fa fa-envelope'
         }
      },
      details: function(){

      }
   },
   mounted(){
      this.$eventHub.$on('activity-created', payload => {
         console.log(payload);
         this.activities.push(payload);
      })
   }
}
</script>