<template>
   <div>
      <div class="form-group">
         <label>Import Parts Sheet</label>
         <input type="file" ref="file" name="file" class="file-input form-control" @change="addFile" />
         <button class="btn btn-primary" @click="upload"> Upload File</button>
         <span v-if="isLoading">Loading ... </span>
      </div>
      <div class="form-group">
         <input type="text" class="form-control" @v-model="query" placeholder="Search..." />
         <button class="btn btn-primary" @click="get">Search</button>
      </div>
      <table class="table table-sm">
         <thead>
            <th>Part Number</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Floor</th>
            <th>Suggested</th>
            <th></th>
         </thead>
         <tbody>
            <tr v-for="(part, index) in parts">
               <td>
                  <input v-if="part.edit" type="text" class="form-control" v-model="part.part_number" />
                  <span v-else>{{part.part_number}}</span>
               </td>
               <td>
                  <input v-if="part.edit" type="text" class="form-control" v-model="part.description" />
                  <span v-else>{{part.description}}</span>
               </td>
               <td>
                  <input v-if="part.edit" type="text" class="form-control" v-model="part.cost" />
                  <span v-else>{{part.cost}}</span>
               </td>
               <td>
                  <input v-if="part.edit" type="text" class="form-control" v-model="part.floor" />
                  <span v-else>{{part.floor}}</span>
               </td>
               <td>
                  <input v-if="part.edit" type="text" class="form-control" v-model="part.suggested_price" />
                  <span v-else>{{part.suggested_price}}</span>
               </td>
               <td>
                  <button class="btn btn-sm btn-primary" @click="edit(part)" v-if="!part.edit">Edit</button>
                  <!-- <button class="btn btn-sm btn-danger" @click="remove(part)" v-if="!part.edit"><i class="fa fa-trash"></i></button> -->
                  <button class="btn btn-sm btn-primary" @click="update(part)" v-if="part.edit">Save</button>
                  <button class="btn btn-sm btn-danger" @click="cancelEdit(part)" v-if="part.edit"><i class="fa fa-close"></i></button>
               </td>
            </tr>
         </tbody>
      </table>
      <nav aria-label="part-pages">
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
   data(){
      return{
         parts:[],
         editPartId:0,
         revertingPart:{},
         pagination:{},
         query:'',
         attachment:{},
         isLoading:false,
         formData:{}
      }
   },
   methods: {
      edit:function(part){
         this.editPartId = part.id;
         this.revertingPart = part;
         Vue.set(part, 'edit', true);
      },
      update:function(part){
         let self = this;
         axios.post('/api/parts/update/' + part.id, part).then(response => {
            console.log("Part Updated");

         }).catch(error => {
            console.log(error.response);
            part = self.revertingPart;
         })

         this.editPartId = '';
         this.revertingPart = {};
         part.edit = false;

      },
      cancelEdit:function(part){
         part = this.revertingPart;
         part.edit = false; 
         this.editPartId = '';
         this.revertingPart = {};
      },
      remove:function(){

      },
      add:function(){

      },
      get:function(){
         let self = this;
         console.log("here is the filter");
         console.log
         axios.post('/api/parts', {filter: self.query}).then(response => {
            console.log(response);
            self.parts = response.data.data;
            self.pagination = response.data;
         }).catch(error => {
            console.log(error.response);
         })
      },
      addFile:function(){
         this.attachment = this.$refs.file.files[0];
      },
      upload:function(){
         this.formData = new FormData();
         this.formData.append('name',this.fileName);
         this.formData.append('file',this.attachment);
         this.isLoading = true; 
         let self = this; 
         axios.post('/api/parts/import', this.formData, {headers:{'Content-Type': 'multipart/form-data'}}).then(response => {
            console.log(response);
            self.isLoading = false;
            self.get();
         }).catch(error => {
            console.log(error.response);
         })
      },
      nextPage:function(){
         let self = this;
         this.pagination.filter = this.query;
         axios.post(this.pagination.next_parge_url, this.pagination).then(response => {
            console.log(response);
            self.parts = response.data.data;
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
            self.parts = response.data.data;
            self.pagination = response.data; 
         });
      },
      getPage:function(page){
         let self = this; 
         this.pagination.filter = this.query;
         axios.post(this.pagination.path + '?page=' + page, this.pagination).then(response => {
            console.log(response);
            self.parts = response.data.data;
            self.pagination = response.data; 
         })
      }
   },
   mounted(){
      this.get();
   }
}
</script>