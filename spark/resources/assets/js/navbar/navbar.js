module.exports = {
    props: [
        'user', 'teams', 'currentTeam',
        'unreadAnnouncementsCount', 'unreadNotificationsCount'
    ],


    computed:{
        notificationsCount(){
            return this.unreadAnnouncementsCount + this.unreadNotificationsCount;
        }
    },


    methods: {
         /**
          * Show the user's notifications.
          */
         showNotifications() {
            Bus.$emit('showNotifications');
        },


        /**
         * Show the customer support e-mail form.
         */
        showSupportForm() {
            Bus.$emit('showSupportForm');
        },
        listen(){
          
        }
    },
    mounted(){
      console.log("NAVABR");
      window.Echo.channel('spark-notifications').listen('NotificationCreated', e => {
            console.log(e);
            console.log('notification');
          })
      this.listen();
    }
};
