<template>
   <div>
      <h3>{{parent.name}}</h3>
      <form type="form">
         <div class="form-group">
            <label>Description</label>
            <input class="form-control" v-model="activity.description" />
         </div>
         <div class="form-group">
            <label>Type</label>
            <select class="custom-select" v-model="activity.type">
               <option>call</option>
               <option>email</option>
               <option>quote</option>
            </select>
         </div>
         
      </form>
      <div class="form-group">
         <button class="btn btn-primary" @click="create">Create</button>
      </div>
   </div>
</template>
<script>
export default{
   props:['actType','parentType','parentId', 'actDesc', 'parent'],
   data(){
      return {
         activity: {
            type:'',
            status: 'new',
            description: '', 
            owner_id: Spark.state.user.id,
            activitable_type:'',
            activitable_id:''
         }
      }
   },
   watch: {
      actType: function(){
         this.activity.type = this.actType
      },
      actDesc: function(){
         this.activity.description = this.actDesc
      },
      parentType: function(){
         this.activity.activitable_type = this.parentType
      },
      parentId: function(){
         this.activity.activitable_id = this.parentId
      }
   },
   methods: {
      create: function(){
         let self = this;
         axios.post('/api/activities/create', {activity: this.activity}).then(response => {
            console.log(response);
            self.$eventHub.$emit('activity-created', response.data.activity);
            if(response.data.task !== ''){
               self.$eventHub.$emit('task-added', response.data.task);
            }
         }).catch(error => {
            console.log(error);
         })
      }
   },
   mounted(){

   }
}
</script>