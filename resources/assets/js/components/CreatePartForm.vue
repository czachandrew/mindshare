<template>
   <div>
      <form class="form">
         <div class="form-group">
            <label>Part Number</label>
            <input type="text" class="form-control" v-model="part.part_number" />
         </div>
         <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" v-model="part.description" />
         </div>
         <div class="form-group">
            <label>Suggested Price</label>
            <input type="text" class="form-control" v-model="part.suggested_price" />
         </div>
         <div class="form-group">
            <label>Cost</label>
            <input type="text" class="form-control" v-model="part.cost" />
         </div>
         <div class="form-group">
            <label>Floor</label>
            <input type="text" class="form-control" v-model="part.floor" />
         </div>
         <div class="form-group">
            <label>Image</label>
            <input type="file" class="form-control" v-on:change="fileUploaded" /> 
         </div>
         
      </form>
      <button class="btn btn-primary" @click="createPart">Create Part </button>
   </div>
</template>
<script>
export default {
   props:{
      modal:{
         type:Boolean,
         default: false
      }
   },
   data(){
      return{
         part:{
            part_number:'',
            description:'',
            suggested_price:'',
            cost:'',
            floor:'',
            image:'',
            status:'active',
         }
      }
   },
   methods: {
      createPart:function(){
         let self = this;
         axios.post('/api/parts/create/', this.part).then(response => {
            console.log(response)
            if(this.modal === true){
               //this form is in a modal so we need to broadcast the new part
               self.$eventHub.$emit('part-created', response.data);
            }
         }).catch(error => {
            console.log(error);
         })
      },
      fileUploaded:function(){

      }
   }
}
</script>