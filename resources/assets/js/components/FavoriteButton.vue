<template>
   <div class="click" @click="toggleFavorite">
      <span id="favspan" class="fa fa-star-o"></span>
      <div class="ring"></div>
      <div class="ring2"></div>
      <p class="info">Added to favorites!</p>
   </div>
</template>
<style scoped>
   @import url(//fonts.googleapis.com/css?family=Open+Sans:600,400&subset=latin,cyrillic);

h4 {
   text-align: center;
   font-family: 'Open Sans', sans-serif;
   font-weight: 400;
   opacity: 0.7;
}

.click {
   font-size: 28px;
   color: rgba(0,0,0,.5);
   width: 28px;
   height: 28px;
   margin: 0 auto;
   margin-top: 20px;
   position: relative;
   cursor: pointer;
}

body {
   padding-top: 20px;
}

.click span {
   margin-left: 4px;
   margin-top: 3px;
   z-index: 999;
   position: absolute;
}

span:hover {
   opacity: 0.8;
}

span:active {
   transform: scale(0.93,0.93) translateY(2px)
}

.ring, .ring2 {
   opacity: 0;
   background: grey;
   width: 1px;
   height: 1px;
   position: absolute;
   top: 19px;
   left: 18px;
   border-radius: 50%;
   cursor: pointer;
}

.active span, .active-2 span {
   color: #F5CC27 !important;
}

.active-2 .ring {
   width: 58px !important;
   height: 58px !important;
   top: -10px !important;
   left: -10px !important;
   position: absolute;
   border-radius: 50%;
   opacity: 1 !important;
}

.active-2 .ring {
   background: #F5CC27 !important;
}

.active-2 .ring2 {
   background: #f6f8f9 !important;
}

.active-3 .ring2 {
   width: 60px !important;
   height: 60px !important;
   top: -11px !important;
   left: -11px !important;
   position: absolute;
   border-radius: 50%;
   opacity: 1 !important;
}

.info {
   font-family: 'Open Sans', sans-serif;
   font-size: 12px;
   font-weight: 600;
   text-transform: uppercase;
   white-space: nowrap;
   color: grey;
   position: relative;
   top: 30px;
   left: -46px;
   opacity: 0;
   transition: all 0.3s ease;
}

.info-tog {
   color: #F5CC27;
   position: relative;
   top: 45px;
   opacity: 1;
}

* {
   transition: all .32s ease;
}
</style>
<script>
   export default {
      props:['favtype','typeid','favorite'], 
      data(){
         return{
            user:Spark.state.user.id,
            status: 'notfaved',
            newFav: {
               favoritable_type:'',
               favoritable_id: '', 
               user_id: Spark.state.user.id,
               id: ''
            }
         };
      },
      watch: {
         favtype: function(){
            console.log('Watcher is going');
            this.newFav.favoritable_type = this.favtype;
            console.log(this.newFav);
         },
         typeid: function(){
            this.newFav.favoritable_id = this.typeid;
         },
         favorite: function(){
            if(this.favorite.length >= 1){
            if(this.favorite[0].id){
               this.status = 'faved';
               this.newFav.id = this.favorite[0].id;
               $('.click').addClass('active')
               $('.click').addClass('active-2')
               setTimeout(function() {
                  $('#favspan').addClass('fa-star')
                  $('#favspan').removeClass('fa-star-o')
               }, 150)
               setTimeout(function() {
                  $('.click').addClass('active-3')
               }, 150)
               //$('.info').addClass('info-tog')
               //setTimeout(function(){
                 // $('.info').removeClass('info-tog')
               //},1000)
            }
         }
      }
      },
      methods: {
         toggleFavorite: function(){
            if(this.status == 'faved'){
               this.unfavoriteThis();
            } else {
               this.favoriteThis();
            }
         },
         favoriteThis: function(){
            //process the favoriting 
            this.newFav.favoritable_type = this.favtype;
            axios.post('/api/favorites/create', this.newFav).then(response => {
               console.log(response);
               $('.click').addClass('active')
               $('.click').addClass('active-2')
               setTimeout(function() {
                  $('#favspan').addClass('fa-star')
                  $('#favspan').removeClass('fa-star-o')
               }, 150)
               setTimeout(function() {
                  $('.click').addClass('active-3')
               }, 150)
               $('.info').addClass('info-tog')
               setTimeout(function(){
                  $('.info').removeClass('info-tog')
               },1000)
               this.status = 'faved';
            }).catch(error => {
               console.log(error);
            });
            
         },
         unfavoriteThis: function(){
            console.log('Unfavoriting');
            axios.post('/api/favorites/remove/' + this.newFav.id, this.newFav).then(response => {
               console.log(response);
               $('.click').removeClass('active')
            setTimeout(function() {
               $('.click').removeClass('active-2')
            }, 30)
            $('.click').removeClass('active-3')
            setTimeout(function() {
               $('#favspan').removeClass('fa-star')
               $('#favspan').addClass('fa-star-o')
            }, 15)

            this.status = 'notfaved';
            }).catch(error => {
               console.log(error.response);
            });
            
         }
      },
      mounted(){

      }
   }
</script>