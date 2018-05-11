<template>
   <div class="modal" id="contactModal" tabindex="-1" rold="dailog" :visible="showModal">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Create Contact</h5>
               <button type="button" class="close" data-dismiss="modal" aria-labeled="Close">
                  <span aria-hidden="true"> &times; </span>
               </button>
            </div>
         
         <div class="modal-body">
            <div class="card card-body">
               <contact-form :company="company"></contact-form>
            </div>
         </div>
         </div>
      </div>
   </div>
</template>
<script>
export default{
   data(){
      return {
         company: {},
         showModal: false
      }
   },
   methods: {

   },
   mounted(){
      let self = this;
      this.$eventHub.$on('open-contact-modal', payload => {
         console.log('What up!');
         console.log(payload);
         self.company = payload;
         $('#contactModal').modal('show');
      })
      this.$eventHub.$on('contact-created', payload => {
         $('#contactModal').modal('hide');
      })
   }
}
</script>