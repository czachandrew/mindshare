<template>
   <div>
      <h5>Change the {{type}} address for {{company.name}}</h5>
      <div class="row" v-if="!makeNew">
         <div class="col-md-12">
            <autocomplete v-model="address.address_1" async-source='/api/address/lookup/' label="Lookup an existing address" channel="newaddress-auto-result">
               <span slot-scope="slotProps">{{slotProps.result.address_1}}<small>
                  {{slotProps.result.address_2}}<br>{{slotProps.result.city}}, {{slotProps.result.state}}
               </small></span>
            </autocomplete><br>
            <button class="btn btn-primary" v-if="addressSelected == false" @click="makeNew = true"><span class="fa fa-plus"></span> New Address</button>
            <div v-if="addressSelected">
               <div class="card">
                  <div class="card-body">
                     <span class="fa fa-trash pull-right" @click="removeAddressSelection"></span>
                     <p>{{address.address_1}}<br>
                     {{address.address_2}}<br>
                     {{address.city}}, {{address.state}}<br>
                     {{address.zip}}</p>
                  </div>

               </div>

            </div>
            <!-- <label>Address 1</label>
            <input type="text" class="form-control" v-model="address.address_1" /> -->
         </div>
      </div>
      <div class="row" v-if="makeNew">
         <div class="col-md-12">
      <form class="form">
         <div class="form-group">
            <label>Address 1</label>
            <input type="text" class="form-control" v-model="address.address_1" />
         </div>
         <div class="form-group">
            <label>Address 2</label>
            <input type="text" class="form-control" v-model="address.address_2" />
         </div>
         <div class="form-group">
            <label>City</label>
            <input type="text" class="form-control" v-model="address.city" /> 
         </div>
         <div class="form-group">
            <label>State</label>
            <input type="text" class="form-control" v-model="address.state" />
         </div>
         <div class="form-group">
            <label>Zip</label>
            <input type="text" class="form-control" v-model="address.zip" />
         </div>
         <div class="form-group">
            <label>Country</label>
            <input type="text" class="form-control" v-model="address.country" />
         </div>
      </form>
      <button class="btn btn-primary" @click="saveAddress">Create Address</button>
   </div>
   </div>
      <button class="btn btn-danger" v-if="makeNew == false" @click="saveAddress">Update</button>
   </div>
</template>
<script>
import autocomplete from './Autocomplete.vue';
export default {
   props:['company', 'type'],
   components: {
      autocomplete
   },
   data(){
      return {
         address: { 
            address_1:'',
            address_2:'',
            city: '',
            state: '',
            zip: '',
            role: this.type,
            country: 'United States',
            company_id: this.company.id
         },
         makeNew: false,
         addressSelected: false, 


      }
   },
   methods: {
      addAddress:function(){
         console.log("I'm adding an address");
         console.log(this.address);
      },
      saveAddress: function(){
         console.log(this.address);
         let self = this;
         //check and see if the address selected thing is true
         //if true we will just have to pass back the address
         if(this.addressSelected == true){
            console.log("I seem to think that an address has been selected");
            self.$eventHub.$emit(self.type + '-address-create', this.address);
            return;
         }
         console.log(this.company);
         this.address.company_id = this.company.id;
         this.address.role = this.type;

         axios.post('/api/companies/' + this.company.id + '/address/new', this.address).then(response => {
            console.log(response); 
            console.log("created")
            if(response.message === 'created') {
               // a new address was create emit the event to update the quote 
            } else if(response.message === 'exists') {
               //resolve the duplicate address
            }
            //broadcast the newly created address
            self.$eventHub.$emit(self.type + '-address-create', response.data);
         }).catch(error => {
            console.log(error)
            //display some kind of error message
         })

      },
      removeAddressSelection: function(){
         this.addressSelected = false;
      }
   },
   mounted(){
      console.log("Here is the company object");
      console.log(this.company)
      let self = this
      this.$eventHub.$on('newaddress-auto-result', payload => {
         console.log(payload);
         self.addressSelected = true;
         this.address = payload;
      });
   }

}
</script>