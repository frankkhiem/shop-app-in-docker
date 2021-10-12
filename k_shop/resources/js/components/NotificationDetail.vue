<template>
  <div>
    <h1>{{ notification.content }}</h1>
    <button @click="showNoti">Show Noti</button>
  </div>
</template>

<script>
import {mapActions} from 'vuex';

export default {
  data: function() {
    return {
      notification: {},
    }
  },

  mounted: function() {
    this.getNotificationDetail();
  },

  methods: {
    getNotificationDetail: function() {
      axios.get('/api/notification/' + this.$route.params.id)
          .then(response => {
            this.notification = response.data;
            // console.log(this.notification);
            this.watchedDetailNoti(this.notification.id);
          })
          .catch(function(){
            console.log('Loi lay thong bao......');
          });
    },

    showNoti: function() {
      this.$toast("I'm a toast!", {
        transition: "Vue-Toastification__bounce",
        maxToasts: 16,
        newestOnTop: true,
        position: "bottom-center",
        timeout: 4000,
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
      });
    },

    ...mapActions({
      watchedDetailNoti: 'watchedDetailNotification'
    }),
  },

  watch: {
    $route() {
      this.getNotificationDetail()
    }
  }

}
</script>