<template>
  
</template>
<script>
import {mapActions, mapGetters} from 'vuex'

export default {
    computed: {
    ...mapGetters({
      user: 'info_user'
    })
  },

  created: function() {
    this.getListNotis();

    Echo.leave('notification_realtime_for_user_' + this.user[0].id);
   
    Echo.private('notification_realtime_for_user_' + this.user[0].id)
        .listen('NewNotification', (data) => {
          const newNoti = {
            id: data.notification.id,
            type: data.notification.type,
            content: data.notification.content,
            watched_in_menu: data.statusNotification.watched_in_menu,
            watched_detail: data.statusNotification.watched_detail,
            created_at: data.notification.created_at,
            updated_at: data.notification.updated_at
          };
          console.log(newNoti);
          this.getNotiRealTime(newNoti);
          this.showToast(newNoti)
        }); 
  },

  methods: {
    ...mapActions({
      getListNotis: 'fetchNotifications',
      getNotiRealTime: 'insertNewNotification'
    }),

    showToast: function(notification) {
      let option = {
        transition: "Vue-Toastification__bounce",
        maxToasts: 16,
        newestOnTop: true,
        position: "bottom-center",
        timeout: 5000,
        closeOnClick: true,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
        draggable: true,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: "button",
        icon: true,
        rtl: false
      };

      this.$toast.success(notification.content, option);
    }
  }
}
</script>