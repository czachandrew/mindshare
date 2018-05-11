<template>
   <div>
      <form class="form">
         <div class="row">
            <div class="col-md-6">
         <div class="form-group">
            <label class="control-label" for="firstName">First Name</label>
            <input type="text" class="form-control" v-model="contact.first_name" id="firstName" placeholder="Enter a first name" />
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            <label class="control-label" for="lastName">Last Name</label>
            <input type="text" class="form-control" v-model="contact.last_name" id="lastName" placeholder="Contact last name" />
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
      <autocomplete label="Company" asyncSource="companies/lookup/" channel="contact-company-result" v-if="!company">
         <span slot-scope="slotProps">{{slotProps.result.name}}</span>
      </autocomplete>
      <div class="form-group" v-else>
         <label class="control-label">Company</label>
         <input class="form-control" v-model="company.name" disabled />
      </div>
         <div class="form-group">
            <label class="control-label" for="email">Email</label>
            <input type="email" class="form-control" v-model="contact.email" id="email" placeholder="Contact email" />
         </div>
         <div class="form-group">
            <label class="control-label" for="phone">Phone</label>
            <input type="phone" class="form-control" v-model="contact.phone" id="phone" placeholder="Phone number" />
         </div>
         <div class="form-group">
            <label class="control-label" for="mobile">Mobile</label>
            <input type="phone" class="form-control" v-model="contact.mobile" id="mobile" placeholder="Mobile phone" />
         </div>
         <div class="form-group">
            <label class="control-label" for="title">Title</label>
            <input type="text" class="form-control" v-model="contact.title" id="title" placeholder="Job Title" />
         </div>
         
      </div>
   </div>
      </form>
      <button class="btn btn-primary" @click="create">Create Contact</button>
   </div>
</template>
<style>

</style>

<script>
import { typeahead } from 'vue-strap'
import autocomplete from './Autocomplete.vue'

export default {
   props:['company'],
   components: {
      typeahead,
      autocomplete
   },
   data(){
      return {
         contact: {
            first_name:'',
            last_name:'',
            email:'',
            phone:'',
            mobile:'',
            title:'',
            company_id:''
         },
         resultTemplate: '<p class="dropdown-item">{{item}}</p>',
         testData: ['Iowa','Ohio','Washington'],
         lookup:''
      }
   },
   methods: {
      initialState: function(){
            this.contact.first_name=''
            this.contact.last_name=''
            this.contact.email=''
            this.contact.phone=''
            this.contact.mobile=''
            this.contact.title=''
            this.contact.company_id=''
      },
      getResponse: function(response){
         console.log(response);
         return response.data;
      },
      onHit:function(item){
         console.log(item);
         return item.name;
      },
      render:function(items, vue){

      },
      create:function(){
         console.log(this.contact);
         if(this.company){
            this.contact.company_id = this.company.id;
         }
         let self = this;
         axios.post('/api/contacts/create', this.contact).then(response => {
            console.log(response);
            self.$eventHub.$emit('contact-created', response.data);
            this.initialState()
         }).catch(error => {
            console.log(error);
         })
      },
      evalDrop: function(){
         console.log(this.lookup);
      }
   },
   mounted(){
      let self = this;
      this.$eventHub.$on('contact-company-result', payload => {
         console.log(payload);
         self.contact.company_id = payload.id;

      })
   }
}
</script>