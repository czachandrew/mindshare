<template>
   <div class="modal" id="activityModal" tabindex="-1" rold="dailog" >
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
               <activity-form :parent="parent" :parent-type="parentType" :parent-id="parent.id" :act-desc="desc" :act-type="type" ></activity-form>
            </div>
         </div>
         </div>
      </div>
   </div>
</template>
<script>
export default {
   data(){
      return {
         parent:{},
         desc:'',
         type: '',
         parentType:''
      }
   },
   mounted(){
      let self = this
      this.$eventHub.$on('open-activity-modal', payload => {
         self.parent = payload.parent
         self.desc = payload.desc
         self.type = payload.type
         self.parentType = payload.parentType
         $('#activityModal').modal('show');
      })

      this.$eventHub.$on('activity-created', payload => {
         $('#activityModal').modal('hide');
      })
   }
}
</script>