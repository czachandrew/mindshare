<template>
   <div>
   <div class="form-group">
      <label>Upload Companies File</label>
      <input type="file" ref="companyfile" name="companyfile" class="file-input form-control" @change="addCompanyFile" />
   </div>
   <div class="form-group">
      <button class="btn btn-primary" @click="submitForm">Upload</button>
      <span v-if="isLoading">Loading.....</span>
   </div>
   <div class="form-group">
      <label>Upload Contacts File</label>
      <input type="file" ref="contactsfile" name="contactsfile" class="file-input form-control" @change="addContactsFile" />
   </div>
   <div class="form-group">
      <button class="btn btn-primary" @click="submitContactFile">Upload</button>
      <span v-if="contactFileLoading">Loading...</span>
   </div>
   <h4>Assign Reps</h4>
   <div class="form-group">
      <label>Select a user</label>
      <select class="form-control" v-model="assignUser">
         <option v-for="user in users" :value="user.id">
            {{user.name}}
         </option>
      </select>
   </div> 
   <div class="form-group">
      <label>Upload a list of customer numbers you want to assign</label>
      <input type="file" ref="assignfile" name="assignfile" class="file-input form-control" @change="addAssignFile" />
   </div>
   <div class="form-group">
      <button class="btn btn-primary" @click="submitAssignFile">Assign</button>
      <span v-if="assignLoading">Assigning....</span> 
   </div>
</div>
</template>
<script>
export default {
   props:['users'],
   data(){
      return {
         fileName:'',
         attachment:{},
         isLoading: false,
         formData:{},
         contactFileLoading: false,
         assignLoading: false,
         assignUser:'',
         assignColumn:'id_customer_cdw',
         assignError:{
            status:false,
            value: 'You must tell me the column title'
         }
      }
   },
   methods: {
      addCompanyFile:function(){
         this.attachment = this.$refs.companyfile.files[0];
         console.log(this.attachment);
      },
      addContactsFile: function(){
         this.attachment = this.$refs.contactsfile.files[0];
         console.log(this.attachment);
      },
      addAssignFile: function(){
         this.attachment = this.$refs.assignfile.files[0];
         console.log(this.attachment);
      },
      submitAssignFile: function(){
         if(this.assignColumn === ''){
            //gotta have a value here
            this.assignError.status = true; 
            return
         }else {
            console.log(this.assignUser);
            this.formData = new FormData();
            this.formData.append('col', this.assignColumn);
            this.formData.append('user', this.assignUser);
            this.formData.append('file', this.attachment);
            this.assignLoading = true; 
            let self = this;
            axios.post('/companies/assign', this.formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
               console.log(response);
               self.assignLoading = false;
            }).catch(error => {
               console.log(error.response);
               self.assignLoading = false;
            })
         }
      }, 
      submitForm:function(){
         this.formData = new FormData();
         console.log(this.attachment);
         this.formData.append('name', this.fileName);
         this.formData.append('file', this.attachment);
         console.log(this.formData);
         this.isLoading = true; 
         let self = this

         axios.post('/companies/import', this.formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
               console.log(response);
               self.isLoading = false;
         }).catch(error => {
            console.log(error.response);
            self.isLoading = false;
         })
      },
      submitContactFile:function(){
         this.formData = new FormData();
         this.formData.append('name', this.fileName);
         this.formData.append('file', this.attachment);
         this.contactFileLoading = true;
         let self = this; 
         console.log("Submitting a contact file");

         axios.post('/contacts/import', this.formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            console.log(response);
            self.contactFileLoading = false;
         }).catch(error => {
            self.contactFileLoading = false;
            console.log(error.response);

         })
      }
   }
}
</script>