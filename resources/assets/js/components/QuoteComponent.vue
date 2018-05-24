<template>
   <div>
    <div class="row">
        <div class="col-lg-12">
            <div class="">
              <button class="btn btn-primary btn-lg float-right" v-if="!isSaved && startState == 'create'" @click="createQuote">Save</button>
              <button class="btn btn-primary btn-lg float-right" v-if="!isSaved && startState == 'edit'" @click="updateQuote">Update</button>
              <button class="btn btn-primary btn-lg float-right" v-if="isSaved && startState == 'edit'" @click="isSaved = !isSaved">Edit</button>
              <a v-if="isSaved" class="btn btn-success btn-lg float-right" :href="'/quotes/' + quote.id +'/pdf'" target="blank">Download PDF</a>
                
                <h2 class="col-md-6"> <i class="fa fa-search-plus icon"></i> Quote <span v-if="company.name">for {{company.name}}</span></h2>

                
            </div>
            <hr>
            <div class="row">
               <div class="col-lg-12" v-if="!loadCompany">
                  <autocomplete async-source='/api/customers/lookup/' label="Customer" channel="company-auto-result">
                     <span slot-scope="slotProps">{{slotProps.result.name}}<br>
                     {{slotProps.result.type}}
                     </span>
                  </autocomplete>
               </div>
            </div>
            <div class="container" id="printQuote">
            <div class="row">
              <div class="col-md-6">
                <h2 v-if="isSaved || startState == 'edit'" style="padding-bottom: 10px;">Quote # {{quote.ref_number}}</h2>
              </div>
              <div class="col-md-6">
                <div class="form-group row" v-if="!isSaved">
                  <label class="col-sm-2 col-form-label">
                    Status:
                  </label>
                  <div class="col-sm-10">
                    <select class="form-control" v-model="quote.status">
                      <option>New</option>
                      <option>Sent to Customer</option>
                      <option>Cancelled</option>
                      <option>Closed</option>
                      <option>Won</option>
                    </select>
                  </div>
                  
                </div>
                <h2 v-else>Status: {{quote.status}}</h2>
               
              </div>
                <div class="col-lg-3">
                    <div class="card height">
                        <div class="card-header">Billing Details</div>
                        <div class="card-body">
                            {{quote.billing_address_1}}<br>
                            {{quote.billing_address_2}}<br>
                            {{quote.billing_city}}<br>
                            {{quote.billing_state}}<br>
                            <strong>{{quote.billing_zip}}</strong><br>
                            <button v-if="!isSaved" class="btn btn-primary btn-sm" @click="toggleAddressModal('billing')">Change Billing Address</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card  height">
                        <div class="card-header">Reseller Information</div>
                        <div class="card-body">
                            <strong>Reseller:</strong> Not Selected<br>
                            <strong>Rep:</strong> Not Selected<br>
                            <strong>Exp Date:</strong> {{quote.good_until | moment("calendar")}}<br>
                            <span v-if="isSaved"><strong>Quote #:</strong> {{quote.ref_number}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card  height">
                        <div class="card-header">Order Preferences</div>
                        <div class="card-body">
                            <strong>Shipping Method:</strong> 
                            <select v-if="!isSaved" class="form-control" v-model="quote.shipping_method">
                              <option value="Best Air">Best Air</option>
                              <option value="Best Ground">Best Ground</option>
                              <option value="Next Day Air">Next Day Air</option>
                            </select><span v-else>{{quote.shipping_method}}</span><br>
                            <strong>Garauntee Date:</strong>
                            <datepicker v-if="!isSaved" v-model="quote.good_until" :clear-button="true" format="yyyy-MM-dd" icons-font="fa" placeholder="Select a date"></datepicker>
                            <span v-else>{{quote.good_until}}</span>
                            <br>
                            <strong>Availability:</strong> Immediate<br>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 float-xs-right">
                    <div class="card  height">
                        <div class="card-header">Shipping Address</div>
                        <div class="card-body">
                            {{quote.shipping_address_1}}<br>
                            {{quote.shipping_address_2}}<br>
                            {{quote.shipping_city}}<br>
                            {{quote.shipping_state}}<br>
                            <strong>{{quote.shipping_zip}}</strong><br>
                            <button v-if="!isSaved" class="btn btn-primary btn-sm" @click="toggleAddressModal('shipping')">Change Shipping Address</button>
                        </div>
                    </div>
                </div>
            </div>
        
  
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h3 class="text-xs-center"><strong>Order summary</strong></h3>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <td><strong>Item Name</strong></td>
                                    <td class="text-xs-center"><strong>Item Price</strong></td>
                                    <td class="text-xs-center"><strong>Item Quantity</strong></td>
                                    <td class="text-xs-right"><strong>Total</strong></td>
                                    <td class="text-xs-right"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in lineitems">
                                    <td>
                                       <input v-if="item.edit === true" class="form-control" v-model="item.part_number" />
                                       <span v-else>
                                          {{item.part.part_number}}<br>
                                          <small>{{item.part.description}}</small>
                                       </span>
                                    </td>
                                    <td class="text-xs-center">
                                       {{item.sale_price}}
                                    </td>
                                    <td class="text-xs-center">
                                       {{item.quantity}}
                                    </td>
                                    <td class="text-xs-right">
                                       {{(item.sale_price * item.quantity).toFixed(2)}}
                                    </td>
                                    <td class="text-xs-right">
                                       <button class="btn btn-primary btn-sm" @click="item.edit = true">Edit</button>
                                       <button class="btn btn-danger btn-sm" @click="removeItem(index)"><span class="fa fa-trash"></span></button>
                                    </td> <!-- Here is where we put the edit/remove buttons --> 
                                </tr>
                                <tr v-if="!isSaved">
                                    <td>
                                      <input type="text" class="form-control" placeholder="Enter a part number" v-on:keyup="autocomplete" v-model="partQuery" autocomplete="off" />
                                       <div class="panel-footer" v-if="partSuggestions.length > 0">
                                          <ul class="list-group">
                                             <li v-bind:class="[{ active: highlighted}, 'list-group-item']" v-for="part in partSuggestions" @click="selectPart(part)" v-on:mouseover="hightlighted = true" v-on:mouseleave="highlighted = false">
                                                {{part.part_number}}
                                             </li>
                                             <li class="list-group-item" v-if="partSuggestions.length < 5">
                                                <a href="#" @click="togglePartModal">Create New Part</a>
                                             </li>
                                          </ul>
                                       </div>
                                    </td>
                                    <td class="text-xs-center">
                                      <div :class="[{'form-group-danger': priceError !== ''},'form-group']">
                                      <input type="text" v-model="newItem.sale_price" @change="validatePrice(newItem.sale_price)" :class="[{'form-control-danger':priceError.status},'form-control form-control-danger']" ref="price" />
                                      <span class="danger-text" v-if="priceError.status">{{priceError.message}}</span>
                                      </div>
                                    </td>
                                    <td class="text-xs-center"><input type="integer" class="form-control" v-model="newItem.quantity" /></td>
                                    <td class="text-xs-right">{{(newItem.quantity * newItem.sale_price).toFixed(2)}}</td>
                                    <td class="text-xs-right"><button class="btn btn-primary btn-sm" @click="createLine"><span class="fa fa-plus"></span></button></td>
                                </tr>
                                <tr>
                                    <td class="highrow"></td>
                                    <td class="highrow"></td>
                                    <td class="highrow text-xs-center"><strong>Subtotal</strong></td>
                                    <td class="highrow text-xs-right">${{subTotal}}</td>
                                </tr>
                                <tr>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-xs-center"><strong>Shipping</strong></td>
                                    <td class="emptyrow text-xs-right">${{shippingCost}}</td>
                                </tr>
                                <tr>
                                    <td class="emptyrow"><i class="fa fa-barcode iconbig"></i></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-xs-center"><strong>Total</strong></td>
                                    <td class="emptyrow text-xs-right">${{total}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
    <notes-component v-if="isSaved" noteable-type="App\Quote" :noteable-id="quote.id" :starting-notes="quote.notes"></notes-component>
    <modal v-model="show" @ok="show = false">
      <new-address :company="company" :type="newAddressType"></new-address>
    </modal>
    <modal v-model="showPartModal" @ok="showPartModal = false" @cancel="showPartModal = false" title="New Part">
      <create-part-form :modal="true"></create-part-form>
    </modal>
  </div>
</div>
</div>
</template>
<style>
.height {
    min-height: 200px;
}

.icon {
    font-size: 47px;
    color: #5CB85C;
}

.iconbig {
    font-size: 77px;
    color: #5CB85C;
}

.table > tbody > tr > .emptyrow {
    border-top: none;
}

.table > thead > tr > .emptyrow {
    border-bottom: none;
}

.table > tbody > tr > .highrow {
    border-top: 3px solid;
}
</style>
<script>
import { modal } from 'vue-strap';
import { datepicker } from 'vue-strap';
import autocomplete from './Autocomplete.vue';

export default {
   props:['loadCompany', 'loadQuote', 'startState'],
   components: {
      modal,
      datepicker,
      autocomplete
   },
   data(){
      return {
         show: false,
         company:'',
         newAddressType:'',
         newItem: {
            part_id:'',
            quantity: 1, 
            sale_price: 0.00,
            lineable_type: 'quote',
            lineable_id: '',
            part:{}
         },
         quote: {
            id:'',
            title:'',
            good_until: moment().add(1,'months').format('YYYY-MM-DD'),
            shipping_address_id:'',
            billing_address_id:'',
            billing_address_1:'',
            billing_address_2: '',
            billing_city:'',
            billing_state: '',
            billing_zip:'',
            shipping_method:'Best Ground',
            shipping_cost:10,
            shipping_address_1:'',
            shipping_address_2:'',
            shipping_city:'',
            shipping_state:'',
            shipping_zip:'',
            company_id:'',
            owner_id: Spark.state.user.id,
            value: 0,
            ref_number:'',
            status: 'New'
         },
         shippingCosts: {
            'Best Ground': 10,
            'Best Air': 20,
            'Next Day Air': 30
         },
         lineitems:[],
         showPartSuggestions: false,
         partSuggestions: [],
         partQuery:'',
         priceError:{
          status: false, 
          message:''
         },
         highlighted: false,
         showPartModal:false,
         isSaved: false,
         submitType: 'create'
      };
   },
   watch:{
    loadCompany: function(){
      this.company = this.loadCompany;
      if(this.startState === 'create'){
        console.log(this.company);
        this.quote.company_id = this.company.id;
        this.quote.title = "Quote for " + this.company.name;
        let addresses = this.createAddressObjects();
        console.log('Here is the addresses object');
        console.log(addresses);
        this.setBillingAddress(addresses.billingAddress);
        this.setShippingAddress(addresses.shippingAddress);
      }
    },
    loadQuote: function(){
      if(this.startState == 'edit'){
        this.loadCreatedQuote(this.loadQuote);
      }
    }
   },
   computed:{
      subTotal: function() {
            if (!this.lineitems) {
                return Number(0);
            }

            let total = 0;

            this.lineitems.forEach(function(item){
               total += Number(item.sale_price) * Number(item.quantity);
            });

            return total.toFixed(2);
        },
        shippingCost: function(){
         return this.shippingCosts[this.quote.shipping_method];
        },
        total: function(){
         return (Number(this.subTotal) + Number(this.shippingCost)).toFixed(2);
        }
   },
   methods: {
    loadCreatedQuote: function(quote){
      //load an already saved quote
      console.log("I'm loading a quote");
      console.log(quote);
      this.quote = quote; 
      this.lineitems = quote.lineitems; 
      this.company = quote.company;
      this.setBillingAddress(quote.billing_address);
      this.setShippingAddress(quote.shipping_address);
      this.submitType = 'update';
      this.isSaved = true;

    },
    updateQuote: function(){
      let self = this;
      this.quote.value = this.total;
      axios.post('/api/quotes/update/' + this.quote.id, {quote: this.quote, lineitems: this.lineitems}).then(response => {
        console.log(response.data);
        self.isSaved = true;
      }).catch(error => {
        console.log(error);
      });
    },
    createQuote: function(){
      console.log(this.quote); 
      this.quote.value = this.total;
      let self = this;

      console.log(this.lineitems);
      axios.post('/api/quotes/create', {quote: this.quote, lineitems: this.lineitems}).then(response => {
        console.log(response);
        self.loadCreatedQuote(response.data);
        //redirect to the quote view 
        window.location.replace('/quotes/' + response.data.id);
        //self.isSaved = true;
      }).catch(error => {
        console.log(error);
      });
    },
    downloadQuote: function(){
      console.log("download function");
      axios.get('/api/quotes/' + this.quote.id + '/pdf').then(response => {
        console.log(response);
      }).catch(error => {
        console.log(error);
      });
      
    },
      setBillingAddress: function(address){
         this.quote.billing_address_1 = address.address_1;
         this.quote.billing_address_2 = address.address_2;
         this.quote.billing_city = address.city;
         this.quote.billing_state = address.state;
         this.quote.billing_zip = address.zip;
         this.quote.billing_address_id = address.id;
            
      },
      setShippingAddress: function(address){
         console.log('setting the shipping address');
         console.log(address);
         this.quote.shipping_address_1 = address.address_1;
         this.quote.shipping_address_2 = address.address_2;
         this.quote.shipping_city = address.city;
         this.quote.shipping_state = address.state;
         this.quote.shipping_zip = address.zip;
         this.quote.shipping_address_id = address.id;
      },
      createAddressObjects: function(company){
         console.log("Creating the address object");
         let shippingAddress = {}; 
         let billingAddress = {};
         let object = {};
         shippingAddress.address_1 = this.company.primary_shipping.address_1;
         shippingAddress.address_2 = this.company.primary_shipping.address_2;
         shippingAddress.city = this.company.primary_shipping.city; 
         shippingAddress.state = this.company.primary_shipping.state;
         shippingAddress.zip = this.company.primary_shipping.zip;
         shippingAddress.id = this.company.primary_shipping.id;
         billingAddress.address_1 = this.company.primary_billing.address_1;
         billingAddress.address_2 = this.company.primary_billing.address_2;
         billingAddress.city = this.company.primary_billing.city;
         billingAddress.state = this.company.primary_billing.state;
         billingAddress.zip = this.company.primary_billing.zip;
         billingAddress.id = this.company.primary_billing.id;
         object.shippingAddress = shippingAddress;
         object.billingAddress = billingAddress;
         console.log("Here is the address object");
         console.log(object);
         return object;

      },
      toggleAddressModal: function(type){
         //set the type of new address
         console.log(type);
         this.newAddressType = type;
         this.show = !this.show;
      },
      validatePrice: function(price){
        if(price < Number(this.newItem.part.floor)){
          this.priceError.status = true;
          this.priceError.message = 'Price is below floor';
          //focus on field and mark error
          this.$refs.price.focus();
        } else {
          this.priceError.status = false;
        }
      },
      resetNewItem: function(){
         this.newItem.part_number = '';
         this.newItem.sale_price = ''; 
         this.newItem.part = {};
         this.newItem.quantity = 1; 
      },
      createLine: function(){
         //verifty that all the required fields are filled out 
         console.log("adding this item");
         let test = Vue.util.extend({}, this.newItem);

         console.log(test);
         this.quote.value += Number(this.newItem.sale_price) * Number(this.newItem.quantity);
         
         if(this.newItem.part_id !== ''){
            //there are no issues create the line item
            this.lineitems.push(Vue.util.extend({}, this.newItem));
            this.partQuery = "";
            
         } else {
            console.log('error');
         }
         this.resetNewItem();
      },
      removeItem: function(key){
        if(this.lineitems[key].id){
          //need to delete this on the server 
          axios.get('/api/lines/remove/' + this.lineitems[key].id).then(response => {
            console.log(response);
          }).catch(error => {
            console.log(error.response);
          });
        }
         this.lineitems.splice(key, 1);
      },
      togglePartModal:function(){
         this.showPartModal = !this.showPartModal;
      },
      save: function(){
        this.isSaved = true;
      },
      autocomplete:function(){
         this.partSuggestions = [];
         if(this.partQuery.length > 2) {
            axios.get('/api/parts/lookup/' + this.partQuery).then(response =>{
               this.partSuggestions = response.data;
               console.log(response);
            }).catch(error => {
               console.log(error);
            });
         }

      },
      selectPart:function(item){
         console.log(item);
         this.partQuery = item.part_number;
         this.newItem.part_id = item.id;
         this.newItem.quantity = 1; 
         this.newItem.sale_price = item.suggested_price;
         this.newItem.part = item;
         this.partSuggestions = [];
      },
      mouseOver:function(key){

      }
   },
   mounted(){
      console.log('Quote template has been loaded');
      //if company is not null set the company 
      
      let self = this;
      this.$eventHub.$on('company-auto-result', payload => {
         console.log(payload);
         if(payload.type === 'company'){
            self.company = payload.object;
            self.quote.company_id = self.company.id;
            let addresses = self.createAddressObjects();
            console.log('Here is the addresses object');
            console.log(addresses);
            self.setBillingAddress(addresses.billingAddress);
            self.setShippingAddress(addresses.shippingAddress);
         } else if(payload.type === 'rep') {
            self.company = payload.object;
            self.quote.rep_id = payload.object.id;
            self.company.name = payload.name + ' from ' + self.company.name;
            self.setBillingAddress(payload.object.billing_address);
            self.setShippingAddress(payload.object.shipping_address);
         }
         //set quote addresses
         
      });

      this.$eventHub.$on('shipping-address-auto-result', payload => {
         console.log("set the shipping address");
         console.log(payload);
         self.setShippingAddress(payload);
      });

      this.$eventHub.$on('billing-address-auto-result', payload => {
         console.log("set the billing address");
         console.log(payload);
         self.setBillingAddress(payload);
      });

      this.$eventHub.$on('shipping-address-create', payload => {
         self.setShippingAddress(payload);
         this.show = false;
      });

      this.$eventHub.$on('part-created', payload => {
         //let's set the active part
         self.selectPart(payload);
         self.showPartModal = false;
      });

      this.$eventHub.$on('billing-address-create', payload => {
         self.setBillingAddress(payload);
         this.show = false; 
      });
      //else we'll show the company lookup fie
   }
}
</script> 