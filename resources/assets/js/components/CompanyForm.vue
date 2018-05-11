<template>
   <div>
      <form class="form">
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                  <label class="control-label" for="company-name">Name</label>
                  <input type="text" class="form-control" v-model="company.name" id="company-name" placeholder="Enter the name of the company" required />
               </div>
               <div class="form-group">
                  <label class="control-label" for="company-website">Website</label>
                  <input type="text" class="form-control" v-model="company.website" id="company-website" placeholder="www.example.com"/>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label" for="shipping-address-1">Shipping Address 1</label>
                  <input type="text" class="form-control" v-model="company.shipping_address_1" id="shipping-address-1" placeholder="123 Main st." />
               </div>
               <div class="form-group">
                  <label class="control-label" for="shipping-address-2">Shipping Address 2</label>
                  <input type="text" class="form-control" v-model="company.shipping_address_2" id="shipping-address-2" placeholder="Suite 123" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="shipping-address-city">Shipping City</label>
                  <input type="text" class="form-control" v-model="company.shipping_city" id="shipping-address-city" placeholder="Chicago" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="shipping_state">Shipping State</label>
                  <input type="text" class="form-control" v-model="company.shipping_state" id="shipping_state" placeholder="IL" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="shipping_zip">Shipping Zip</label>
                  <input type="text" class="form-control" v-model="company.shipping_zip" id="shipping_zip" placeholder="55555" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="shipping_country">Shipping Country</label>
                  <input type="text" class="form-control" v-model="company.shipping_country" id="shipping_country" placeholder="United States" />
               </div>

            </div>
            <div class="col-md-6">
               <div class="form-check">
                  <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" @click="copyAddress" v-model="sameAddresses" /> Same as shipping address
               </label>
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing-address-1">Billing Address 1</label>
                  <input type="text" class="form-control" v-model="company.billing_address_1" id="billing-address-1" placeholder="123 Main st." />
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing-address-2">Billing Address 2</label>
                  <input type="text" class="form-control" v-model="company.billing_address_2" id="billing-address-2" placeholder="Suite 123" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing-address-city">Billing City</label>
                  <input type="text" class="form-control" v-model="company.billing_city" id="billing-address-city" placeholder="Chicago" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing_state">Billing State</label>
                  <input type="text" class="form-control" v-model="company.billing_state" id="billing_state" placeholder="IL" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing_zip">Billing Zip</label>
                  <input type="text" class="form-control" v-model="company.billing_zip" id="billing_zip" placeholder="55555" />
               </div>
               <div class="form-group">
                  <label class="control-label" for="billing_country">Billing Country</label>
                  <input type="text" class="form-control" v-model="company.billing_country" id="billing_country" placeholder="United States" />
               </div>

            </div>
         </div>
         


      </form>
      <div class="row">
            <div class="col-md-12">
               <button class="btn btn-primary" @click="create">Create</button>
               <!-- <button class="btn btn-primary" @click="testAlert">Alert</button> --> 
            </div>
         </div>
         <alert v-model="showAlert" placement="top-right" duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Company Created!</strong>
            <p>You successfully created <span class="font-weight:bold;">{{justMadeName}}</span></p>
         </alert>
   </div>
</template>
<script>
import { alert } from 'vue-strap'
export default{
   components: {
      alert
   },
   data(){
      return {
         company: {
            name:'',
            website:'',
            shipping_address_1: '',
            shipping_address_2: '',
            shipping_city:'',
            shipping_state: '',
            shipping_zip: '',
            shipping_country:'United States',
            billing_address_1: '',
            billing_address_2: '',
            billing_city:'',
            billing_state: '',
            billing_zip: '',
            billing_country:'United States',

         },
         sameAddresses: false,
         justMadeName:'',
         showAlert: false
      }
   },
   methods: {
      create:function(){
         let self = this; 
         axios.post('/api/companies/create', this.company).then(response => {
            console.log('Company Created');
            console.log(response);
            self.justMadeName = response.data.name;
            self.showAlert = true;

            //fire an alert

         }).catch(error => {
            console.log(error);
         })
      },
      testAlert:function(){
         this.showAlert = true;
      },
      copyAddress: function(){
         if(this.sameAddresses == false){
            //copy the address
            this.company.billing_address_1 = this.company.shipping_address_1;
            this.company.billing_address_2 = this.company.shipping_address_2; 
            this.company.billing_city = this.company.shipping_city;
            this.company.billing_state = this.company.shipping_state;
            this.company.billing_zip = this.company.shipping_zip;
            this.company.billing_country = this.company.shipping_country;
         } else {
            //clear the billing address
         }
      }
   },
   mounted(){
      console.log('Company Form has been loaded');
   }
}
</script>