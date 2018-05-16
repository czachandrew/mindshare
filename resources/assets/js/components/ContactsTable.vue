<template>
   <div class="card">
      <div class="card-body">
   <table class="table">
      <thead>
         <th>Name</th>
         <th>Title</th>
         <th>Phone</th>
         <th>Email</th>
         <th></th>
      </thead>
      <tbody>
         <tr v-for="(contact,index) in contacts">
            <td>
               <input type="text" class="form-control" v-if="contact.edit" v-model="contact.first_name" placeholder="First Name..." /> 
               <input type="text" class="form-control" v-if="contact.edit" v-model="contact.last_name" placeholder="Last Name..." />
               <span v-else>{{contact.first_name}} {{contact.last_name}}</span>
            </td>
            <td>
               <input type="text" class="form-control" v-if="contact.edit" v-model="contact.title" /> 
               <span v-else>{{contact.title}}</span>
            </td>
            <td>
               <input type="text" class="form-control" v-if="contact.edit" v-model="contact.phone" /> 
               <span v-else>{{contact.phone}}</span>
            </td>
            <td>
               <input type="text" class="form-control" v-if="contact.edit" v-model="contact.email" /> 
               <span v-else>{{contact.email}}</span>
            </td>
            <td>
               <button v-if="!contact.edit" class="btn btn-sm btn-success" @click="logCall(contact)"><i class="fa fa-phone"></i></button>
               <button v-if="!contact.edit" class="btn btn-sm btn-primary" @click="logEmail(contact)"><i class="fa fa-envelope"></i></button>
               <button v-if="!contact.edit" class="btn btn-sm btn-default" @click="edit(contact)"><i class="fa fa-pencil"></i></button>
               <button v-if="contact.edit" class="btn btn-sm btn-success" @click="update(contact)"><i class="fa fa-thumbs-o-up"></i></button>
               <button v-if="contact.edit" class="btn btn-sm btn-danger" @click="cancelEdit(contact)"><i class="fa fa-close"></i></button>

            </td>
         </tr>
      </tbody>

   </table>
</div>
<div class="card-footer">
<button class="btn btn-sm btn-primary float-right" @click="addContact"><i class="fa fa-plus"></i> New</button>
   </div>
   <contact-modal></contact-modal>
   <alert v-model="showAlert" placement="top-right" duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Contact Updated!</strong>
            <p>{{alertMessage}}</p>
   </alert>
</div>
</template>
<script>
import { alert } from 'vue-strap'
export default {
   props:['companyId', 'company'],
   components: {
      alert
   },
   data(){
      return {
         contacts:[],
         editingId:'',
         revertContact:{},
         showAlert:false,
         alertMessage: '',
      }
   },
   watch: {
      company:function(){
         this.contacts = this.company.contacts
      }
   },
   methods:{
      addContact:function(){
         console.log('Adding Contact');
         this.$eventHub.$emit('open-contact-modal', this.company);
      },
      logCall:function(contact){
         let obj = {
            parent: this.company,
            desc: 'Made a call to ' + contact.first_name + " " + contact.last_name + " at " + this.company.name, 
            type: 'call',
            parentType: 'App\\Company',
         }
         this.$eventHub.$emit('open-activity-modal', obj);
      },
      logEmail:function(contact){
         let obj = {
            parent: this.company,
            desc: 'Emailed ' + contact.first_name + " " + contact.last_name + " at " + this.company.name, 
            type: 'email',
            parentType: 'App\\Company',
         }
         this.$eventHub.$emit('open-activity-modal', obj);
      },
      cancelEdit: function(contact){
         
         contact = this.revertContact;
         contact.edit = false; 
         this.editingId = '';
         this.revertContact = {};
      },
      edit: function(contact){
         console.log("Company edit");
         this.editingId = contact.id; 
         this.revertContact = contact;
         Vue.set(contact, 'edit', true);
         contact.edit = true;
      },
      update:function(contact){
         let self = this; 
         axios.post('/api/contacts/update/' + contact.id, contact).then(response => {
            console.log('Contact Updated');
            self.alertMessage = response.data.first_name + ' ' + response.data.last_name + ' has been updated!';
            self.showAlert = true;

         }).catch(error => {
            //display error message alert
            contact = self.revertContact;
         })
         this.editingId = '';
         this.reverContact = {};
         contact.edit = false;
      }
   },
   mounted(){
      let self = this;
      this.$eventHub.$on('contact-created', payload => {
         self.contacts.push(payload);
      })
   }
}
</script>