<template>
   <div>
      <!-- Company Name, website, addresses --> 
      <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="card">
               <div class="card-body">
                   <div class="row">
                       <div class="col-sm-6 col-md-4">
                           <img src="http://placehold.it/380x500" alt="" class="rounded img-fluid" />
                       </div>
                       <div class="col-sm-6 col-md-8" style="height:100%">
                           <h4>
                               {{company.name}}</h4>
                           <small><cite :title="company.billing_city + ', USA'">{{company.billing_city}}, USA <i class="fa fa-map-pin">
                           </i></cite></small>
                           <p>
                               <i class="fa fa-envelope"></i> email@example.com
                               <br />
                               <i class="fa fa-globe"></i> <a target="_blank" :href="company.website">{{company.website}}</a>
                               <br />
                               Updated {{friendlyUpdateDate}}</p>
                           <!-- Split button -->
                           <div class="btn-group">
                               <button class="btn btn-primary" type="button" aria-haspopup="true" aria-expanded="false">
                                   Log Activity</button>
                               <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                   <span class="sr-only">Toggle Dropdown</span>
                               </button>
                               <div class="dropdown-menu" role="menu">
                                   <a class="dropdown-item" href="#">Send Eval</a>
                                   <a class="dropdown-item" href="#">Log Activity</a>
                                   <a class="dropdown-item" :href="'/quote/for/' + company.id">Create Quote</a>
                                   <a class="dropdown-item" href="#" @click='createContact'>Add Contact</a>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <div class="card-footer">
                  <!-- <button class="btn btn-sm btn-danger float-right" disabled><i class="fa fa-pencil"></i> Edit</button> -->
                  <button class="btn btn-primary btn-sm" @click="claim" v-if="!company.user_id" >Claim</button>
                  <button class="btn btn-primary btn-sm" @click="drop" v-if="company.user_id === currentUser">Drop</button>
                  <span v-if="company.user_id === currentUser" class="float-right">Your Account</span>
                  <span v-if="company.user_id && company.user_id !== currentUser" class="float-right">{{company.user.name}}</span>
                </div>
             </div>
            </div>
        
        <div class="col-xs-12 col-sm-8 col-md-8">
              <task-list :load-tasks="company.tasks" task-scope="Company" :parent-id="company.id"></task-list>
               
               

        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
      <contacts-table :contacts.sync="company.contacts" :company="company" :company-id="company.id"></contacts-table>
      <quotes-list :load-quotes="company.quotes"></quotes-list>
      </div>
      <div class="col-md-12">
         <div class="card card-body">
            <notes-component noteable-type="App\Company" :noteable-id="company.id" :starting-notes="company.notes"></notes-component>
         </div>
         <activity-list :parent="company" :parent-id="company.id"></activity-list>
      </div>
      <create-activity-modal></create-activity-modal>
    </div>
      <alert v-model="successAlert" placement="top-right" duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>{{success.title}}</strong>
            <p>{{success.description}}</p>
      </alert>
   </div>
</template>
<script>
import { alert } from 'vue-strap'
export default {
   props: ['currentCompany'],
   components: {
      alert
   },
   data(){
      return{
         successAlert: false, 
         errorAlert: false,
         success: {
            title:'',
            description:''
         },
         error: {
            title:'',
            description: ''
         },
         company:{},
         currentUser: Spark.state.user.id 
      }
   },
   watch: {
      currentCompany: function(){
        this.company = this.currentCompany;
      }
   },
   computed:{
      friendlyUpdateDate:function(){
         let d = new Date(this.company.updated_at);
         let today = new Date();
         let timeDiff = today.getTime() - d.getTime();
         let diffDays = Math.ceil(timeDiff/ (1000 * 3600 * 24));
         if(diffDays === 0){
            return 'Today';
         } else {
            return diffDays + ' days ago';
         }
      }
   },
   methods: {
      update: function(){

      },
      createContact: function(){
         this.$eventHub.$emit('open-contact-modal', this.company);
      },
      claim: function(){
         let self = this;
         axios.get('/api/companies/claim/' + this.company.id).then(response => {
            console.log(response.data);
            self.company = response.data;
            //Vue.$set(company, response.data);

         }).catch(error => {
            console.log(error.response);
         })
      },
      drop: function(){
        let self = this; 
        axios.get('/api/companies/drop/' + this.company.id).then(response => {
          console.log(response.data);
          self.company = response.data; 
        }).catch(error => {
          console.log(error.response);
        })
      }
   },
   mounted(){
      console.log('Company View has been mounted');
      console.log(this.company);
      let self = this;
      this.$eventHub.$on('contact-created', payload => {
         self.success.title = "Contact Created!";
         self.success.description = "A contact has been created for " + payload.first_name + " " + payload.last_name;
         self.successAlert = true;
      })
   }
}

</script>