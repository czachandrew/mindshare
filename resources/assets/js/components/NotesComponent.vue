<template>
   <div class='container'>
      <div class="row">
         <div class="col-md-12">
            <div class="form-group">
               <label for="newNote">Add a Note</label>
               <!-- <at-ta v-model="newNote.content" :members="users" name-key="name">
                  <textarea class="form-control" rows="3"></textarea>
               </at-ta> --> 
              <textarea class="form-control" rows="3" v-model="newNote.content" name="note" :id="'note' + noteableId + marker"></textarea>
            </div>
            <div class="form-group">
               <button class="btn btn-primary" @click="create">Add Note</button>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card" v-for="note in startingNotes">
               <div class="card-body" >
                  <small class="text-muted float-right">{{note.created_at}}</small>
                  <img :src="note.author.photo_url" style="width: 50px; height: auto; margin-right: 10px;" class="rounded-circle float-left" /> <a href="#">{{note.author.name}}</a> said - <br>
                  {{note.content}}
               </div>
            </div>
         </div>
      </div>
   </div>
</template>
<script>
//import At from 'vue-at';
//import AtTa from 'vue-at/dist/vue-at-textarea';
import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery.caret';
import 'at.js';
export default {
   props:['noteableType','noteableId', 'startingNotes', 'marker'],
   data(){
      return {
         newNote:{
            content:'',
            author_id: Spark.state.user.id,
            noteable_id:'',
            noteable_type : ''
         },
         notes:[],
         users:[]
      }
   },
   watch: {
      startingNotes: function(){
         this.notes = this.startingNotes;
      },
      noteableId: function(){
         this.newNote.noteable_id = this.noteableId;
      }
   },
   methods:{
      members:function(q){
         console.log('triggered');
         return []
      },
      create:function(){
         this.newNote.noteable_type = this.noteableType;
         this.newNote.noteable_id = this.noteableId;
         console.log(this.newNote);
         axios.post('/api/notes/create', this.newNote).then(response => {
            console.log(response);
            this.startingNotes.push(response.data);
            this.newNote.content = '';
         }).catch(error => {
            console.log(error);
         })
      },
      add:function(){
         this.newNote.noteable_type = this.noteableType;
         this.newNote.noteable_id = this.noteableId;
         console.log(this.newNote);
         this.startingNotes.push(Vue.util.extend({}, this.newNote));
         this.newNote.content = '';
      },
      setNotes:function(){
         console.log("Setting the notes");
         console.log(this.startingNotes);
         if(this.startingNotes){
            this.notes.push(this.startingNotes);
         }
      }
   },
   mounted(){

      let self = this;
      this.$eventHub.$on('opening-task', payload => {
         self.notes.push(payload);
      })

      $('#note' + this.noteableId + this.marker).atwho({
         at: "@",
         delay: 150,
         callbacks: {
            remoteFilter: function(query, callback){
               axios.get("/api/lookups/agents/" + query).then(response => {
                  console.log(response.data);
                  callback(response.data);
                  //when a use is a selected, send a notification with an associated note
                  //
               })
               console.log('called');
            }
         }
      }).atwho({
         at:"#",
         delay: 750,
         //we need to remove spaces from the result
         //insertTpl: '<a href="/company/${id}">${atwho-at}${name}</a>',
         callbacks: {
            remoteFilter: function(query, callback){
               axios.get("/api/lookups/companies/" + query).then(response => {
                  console.log(response.data);
                  callback(response.data);
               })
               console.log('called');
            }
            }
         })
   }
}
</script>