<template>
   <div>
      <label>{{label}}</label>
      <input type="text" placeholder="Lookup Term" v-model="query" v-on:keyup="autoComplete" autocomplete="off" class="form-control">
      <div class="panel-footer" v-if="results.length">
         <ul class="list-group">
            <li class="list-group-item" v-for="result in results" @click="selectItem(result)" v-on:mouseover="mouseOver">
               <slot v-bind:result="result">{{result}}</slot>
               <!-- <span>{{ result.name }}</span> -->
            </li>
         </ul>
      </div>
   </div>
</template>
<script>
export default {
   props:['label','asyncSource','channel','default'],
   data(){
      return{ 
         query:'',
         results:[],
         showDropdown: false
      }
   },
   methods: {
      autoComplete(){
         console.log("going");
         this.results = []; 
         if(this.query.length > 2) {
            axios.get(this.asyncSource + this.query).then(response => {
               console.log(response);
               this.results = response.data;
            }).catch(error => {
               console.log(error);
            })
         }
      },
      mouseOver(){

      },
      selectItem(item){
         console.log(item)
         this.$eventHub.$emit(this.channel, item);
         this.query = item.name;
         this.results = [];
      },
      isActive(index){
         return this.current = index;
      }
   }
}
</script>