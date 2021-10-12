<template>
  <div class="notification_menu">
    <div v-if="loading">
      <h2>Loading.........</h2>
    </div>
    <div v-if="!loading">
      <div 
        class="notification_item" 
        v-for="(item, index) in notifications" 
        :key="index" 
        :class="item.watched_detail ? 'watched' : 'not_watched'"
        @click="goToDetail(item)"
      >
        <p>Thông báo: {{ item.content }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {

  data: function() {
    return {
      loading: false,
    }
  },

  computed: {
    ...mapGetters({
      notifications: 'notifications'
    })
  },

  mounted: function() {

  },

  methods: {
    goToDetail: function(notification) {
      var path = '/notification/' + notification.id + '/detail';
      if (this.$route.path !== path) this.$router.push(path);
    },

  }

}
</script>

<style src="../../css/css_notification.css" scoped>