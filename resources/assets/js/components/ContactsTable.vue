<template>
   <div class="card">
      <div class="card-body">
   <table class="table table-sm">
      <thead>
         <th>Name</th>
         <th>Phone</th>
         <th>Email</th>
         <th></th>
      </thead>
      <tbody>
         <tr v-for="contact in contacts">
            <td>{{contact.first_name}} {{contact.last_name}}</td>
            <td>{{contact.phone}}</td>
            <td>{{contact.email}}</td>
            <td>
               <button class="btn btn-sm btn-success" @click="logCall(contact)"><i class="fa fa-phone"></i></button>
               <button class="btn btn-sm btn-primary" @click="logEmail(contact)"><i class="fa fa-envelope"></i></button>

            </td>
         </tr>
      </tbody>

   </table>
</div>
<div class="card-footer">
<button class="btn btn-sm btn-primary float-right" @click="addContact"><i class="fa fa-plus"></i> New</button>
   </div>
   <contact-modal></contact-modal>
</div>
</template>
<script>
export default {
   props:['companyId', 'company'],
   data(){
      return {
         contacts:[]
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