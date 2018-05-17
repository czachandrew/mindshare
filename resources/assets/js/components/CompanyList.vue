<template>
   <div>
      <div class="row">
         <div class="col-md-6">
            <div class="input-group">
               <input type="text" class="form-control" placeholder="Search for Comapny..." v-model="query">
               <span class="input-group-btn">
                  <button class="btn btn-primary" @click="search" type="button">Search</button>
               </span>
            </div>
         </div>
         <div class="col-md-6 row">
          <div class="col-md-4">
          <div class="btn-group" role="group" aria-label="Basic example">
             <button type="button" @click="toggleLimit" :class="[{'btn-primary':isUser,'btn-default':!isUser},'btn']">Assigned</button>
             <button type="button" @click="toggleLimit" :class="[{'btn-primary':!isUser,'btn-default':isUser},'btn']">All</button>
          </div>
        </div>
        <div class="col-md-8">
              <div class="form-group row">
                <label class="col-md-6">
                  Per Page:
                </label>
                <select class="col-md-4" v-model="pagination.per_page" @change="updateResults">
                  <option>5</option>
                  <option>10</option>
                  <option>15</option>
                  <option>25</option>
                </select>
             </div>
           </div>
       </div>
    </div>
      <table class="table">

         <thead>
            <th>Company</th>
            <th>Location</th>
            <th>Last Activity</th>
            <th>Follow Up</th>
            <th>Value</th>
            <th></th>
         </thead>
         <tbody>
            <tr v-for="company in companies">
               <td><a :href="'/company/' + company.id">{{company.name}}</a></td>
               <td>{{company.billing_city}}, {{company.billing_state}}</td>
               <td>
                  <span v-if="company.latestaction.length > 0">
                     {{company.latestaction[0].created_at | moment("calendar") }}
                  </span>
                  <span v-else> None</span>
               </td>
               <td><span v-if="company.followups.length > 0">{{company.followups[0].due_date | moment('calendar')}}</span>
                  <span v-else>Not Scheduled</span></td>
               <td>{{company.historic_value}}</td>
               <td><a :href="'/company/' + company.id" class="btn btn-primary btn-sm">Zoom</a></td>
            </tr>
         </tbody>
      </table>
      <nav aria-label="company-pages">
        <ul class="pagination">
          <li class="page-item" v-if="pagination.current_page > 1">
            <a class="page-link" @click.prevent="prevPage" href="#" tabindex="-1">Previous</a>
          </li>
          <li v-for="n in pagination.last_page" v-if="pagination.total >= pagination.per_page && n < pagination.current_page + 5 && n > pagination.current_page - 1" :class="[{'active':n == pagination.current_page},'page-item']"><a class="page-link" @click.prevent="getPage(n)" href="#">{{n}}</a>
          </li>
          <li class="page-item" v-if="this.pagination.next_page_url">
            <a class="page-link" @click.prevent="nextPage" href="#">Next</a>
          </li>
        </ul>
      </nav>
   </div>
</template>
<script>
export default {
   props:['defaultCompanies', 'paginate'],
   data(){
      return {
         companies:[],
         pagination:{},
         query:'',
         limit: 'user',
      }
   },
   watch: {
      defaultCompanies:function(){
         //console.log(this.companies);

         this.companies = this.defaultCompanies;
      },
      paginate: function(){

      },
      limit: function(){
         console.log('limit has changed');
      }
   },
   computed: {
      isUser(){
         if(this.limit === 'user'){
            return true;
         } else {
            return false;
         }
      },
      pagesNumber() {
        if (!this.pagination.to) {
          return [];
        }
        let from = this.pagination.current_page - this.offset;
        if (from < 1) {
          from = 1;
        }
        let to = from + (this.offset * 2);
        if (to >= this.pagination.last_page) {
          to = this.pagination.last_page;
        }
        let pagesArray = [];
        for (let page = from; page <= to; page++) {
          pagesArray.push(page);
        }
          return pagesArray;
      }
   },
   methods:{
      latest:function(obj){
         console.log("Action");
         if(obj.length > 0){
            console.log('this one should work');
            console.log(obj[0].description)
         } else {
            return ""
         }
         
      },
      toggleLimit: function(){
         console.log('Toggle Time!');
         if(this.limit === 'user') {
            this.limit = '';
         } else {
            this.limit = 'user';
         }

         this.updateResults();
      },
      search: function(){
         console.log('Searching');
         let self = this; 
         axios.post('/api/companies/paginated', {filter: this.query, limit:this.limit, per_page:this.pagination.per_page}).then(response => {
            console.log(response);
            self.companies = response.data.data;
            self.pagination = response.data;
            self.pagination.filter = self.query; 
            self.pagination.limit = self.limit;
         }).catch(error => {
            console.log(error);
         })
      },
      updateResults: function(){
         let self = this;
         axios.post('/api/companies/paginated',{filter: this.query, limit:this.limit, per_page:this.pagination.per_page}).then(response => {
            console.log(response);
            self.companies = response.data.data;
            self.pagination = response.data;
            self.pagination.filter = self.query; 
            self.pagination.limit = self.limit;
         })
      },
      nextPage:function(){
         let self = this;
         console.log("going to the next page");
         console.log(this.pagination);
         axios.post(this.pagination.next_page_url, this.pagination).then(response => {
            console.log(response);
            self.companies = response.data.data;
            self.pagination = response.data;
            self.pagination.filter = self.query; 
            self.pagination.limit = self.limit;
         });
      },
      prevPage:function(){
         let self = this;
         console.log("going to the next page");
         console.log(this.pagination);
         axios.post(this.pagination.prev_page_url, this.pagination).then(response => {
            console.log(response);
            self.companies = response.data.data;
            self.pagination = response.data;
            self.pagination.filter = self.query; 
            self.pagination.limit = self.limit; 
         });
      },
      getPage:function(page){
         let self = this; 
         axios.post(this.pagination.path + '?page=' + page, this.pagination).then(response => {
            console.log(response);
            self.companies = response.data.data;
            self.pagination = response.data;
            self.pagination.filter = self.query; 
            self.pagination.limit = self.limit; 
         })
      }
   },
   mounted(){
      window.Echo.channel('spark-notifications').listen('Laravel\Spark\Events\NotificationCreated', e => {
         console.log(e);
      } )
      console.log('Company list mounted');
      this.updateResults();
   }
}
</script>