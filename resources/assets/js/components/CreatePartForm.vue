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
      <alert v-model="showAlert" placement="top-right" duration="3000" type="success" width="400px" dismissable>
         <strong> Part Created!</strong>
         <p>{{newPart}} has been added to the database</p>

      </alert>
   </div>
</template>
<script>
import { alert } from 'vue-strap'
export default {
   components: {
      alert
   },
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
         },
         showAlert: false,
         newPart: '',
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
            } else {
               //clear the form 
               self.newPart = self.part.part_number;
               self.part.part_number ='';
               self.part.description = '';
               self.part.suggested_price = ''; 
               self.part.cost = '';
               self.part.floor = '';
               self.part.image = ''; 
               self.showAlert = true;


               //notify user of success
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