<template>
   <div>
      <table class="table">
         <thead>
            <th>Company</th>
            <th>Location</th>
            <th>Last Activity</th>
            <th>Follow Up</th>
            <th>Value</th>
            <th></th>
         </thead>
         <tbody>
            <tr v-for="company in companies">
               <td><a :href="'/company/' + company.id">{{company.name}}</a></td>
               <td>{{company.billing_city}}, {{company.billing_state}}</td>
               <td>
                  <span v-if="company.latestaction.length > 0">
                     {{company.latestaction[0].created_at | moment("calendar") }}
                  </span>
                  <span v-else> None</span>
               </td>
               <td><span v-if="company.followups.length > 0">{{company.followups[0].due_date | moment('calendar')}}</span>
                  <span v-else>Not Scheduled</span></td>
               <td>$0.00</td>
               <td><a :href="'/company/' + company.id" class="btn btn-primary btn-sm">Zoom</a></td>
            </tr>
         </tbody>
      </table>
   </div>
</template>
<script>
export default {
   props:['companies'],
   data(){
      return {

      }
   },
   watch: {
      companies:function(){
         console.log(this.companies);
      }
   },
   methods:{
      latest:function(obj){
         console.log("Action");
         if(obj.length > 0){
            console.log('this one should work');
            console.log(obj[0].description)
         } else {
            return ""
         }
         
      }
   },
   mounted(){
      window.Echo.channel('spark-notifications').listen('Laravel\Spark\Events\NotificationCreated', e => {
         console.log(e);
      } )
   }
}
</script>