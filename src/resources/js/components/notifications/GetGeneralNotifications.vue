<template>
  
</template>
<script>
import { mapActions } from 'vuex';

export default {
  
  created: function() {
    Echo.channel('general-notification')
    .listen('GeneralNotification', (data) => {
      let generalNoti = data.generalNoti;
      generalNoti.watched_in_menu = false;
      generalNoti.watched_detail = false;
      
      this.getNotiRealTime(generalNoti);
      this.showToast(generalNoti);
    }); 
  },

  methods: {
    ...mapActions({
      getNotiRealTime: 'insertNewNotification',
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

      this.$toast.info(notification.content, option);
    }
  }
}
</script>