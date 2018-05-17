<template>
   <div>
      <table class="table table-sm">
         <thead>
            <th>Quote</th>
            <th>Items</th>
            <th>Good until</th>
            <th>Value</th>
            <th>Status</th>
            <th></th>
         </thead>
         <tbody>
            <tr v-for="quote in quotes">
               <td><a :href="'/quotes/' + quote.id">{{quote.ref_number}}</a></td>
               <td>{{quote.lineitems[0].part.part_number}} <span v-if="quote.lineitems.length > 1"> and {{quote.lineitems.length - 1}} more...</span></td>
               <td>{{quote.good_until}}</td>
               <td>{{quote.value}}</td>
               <td>{{quote.status}}</td>
               <td><a :href="'/quotes/' + quote.id + '/pdf'" class="btn btn-sm btn-primary">Download</a></td>
            </tr>
         </tbody>
      </table>
      <nav aria-label="quote-pages">
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
   props:['loadQuotes'],
   data(){
      return {
         quotes:[],
         pagination: {}

      }
   },
   watch:{
      loadQuotes:function(){
         this.quotes = this.loadQuotes;
      }
   },
   methods:{
      nextPage:function(){
         let self = this;
         this.pagination.filter = this.query;
         axios.post(this.pagination.next_parge_url, this.pagination).then(response => {
            console.log(response);
            self.quotes = response.data.data;
            self.pagination = response.data;
         });
      },
      prevPage:function(){
         let self = this;
         console.log("going to the next page");
         console.log(this.pagination);
         this.pagination.filter = this.query;
         axios.post(this.pagination.prev_page_url, this.pagination).then(response => {
            console.log(response);
            self.quotes = response.data.data;
            self.pagination = response.data; 
         });
      },
      getPage:function(page){
         let self = this; 
         this.pagination.filter = this.query;
         axios.post(this.pagination.path + '?page=' + page, this.pagination).then(response => {
            console.log(response);
            self.quotes = response.data.data;
            self.pagination = response.data; 
         })
      }
   }
}
</script>   