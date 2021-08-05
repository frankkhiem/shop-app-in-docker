<template>
  <div>
    <Header/>
    <ModalLogin/>
    <GetNotifications v-if="isLogin"/>
    
    <router-view></router-view>
    
    <!-- Footer cua trang -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <div class="footer-col-1">
            <h3>Web K3MN</h3>
          </div>
          <div class="footer-col-2">
            <h3>Sản phẩm của:</h3>
            <ul>
              <li>Nguyễn Quốc Khánh</li>
              <li>Nguyễn Gia Khiêm</li>
              <li>Nguyễn Huy Mạnh</li>
              <li>Trần Minh Khương</li>
              <li>Nguyễn Văn Nam</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Header from './Header.vue';
  import ModalLogin from './modals/ModalLogin.vue';
  import GetNotifications from './notifications/GetNotificatoins.vue';
  import { mapGetters } from 'vuex';

  export default {
    components: {
      Header,
      ModalLogin,
      GetNotifications,
    },

    computed: {
      ...mapGetters({
        isLogin: 'user_isset'
      }),
    }
  }
</script>

<style src="../../css/css_layout.css" scoped>