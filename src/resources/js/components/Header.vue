<template>
  <div class="header">
    <div class="container">
      <div class="navbar">
        <div class="logo">
          <a href="/home"><img src="../images/Logo.png" width="150px"></a>
        </div>
        <nav>
          <ul>
            <li><router-link to="/home">Trang chủ</router-link></li>
            <li><router-link to="/products">Sản phẩm</router-link></li>
            <li v-if="set_user == false"><a href="#" @click.prevent="showModalLogin">Đăng nhập</a></li>
            <li>
              <form @submit="search($event)">
                <input type="text" v-model="keyword" placeholder="Tìm kiếm sản phẩm..." />
                <button type="submit"><i class="fas fa-search"></i>>></button>
              </form>
            </li>
          </ul>
        </nav>
        <router-link to="/cart" v-if="set_user == true"><img src="../images/Cart.png" width="30px" height="30px"></router-link>
        <div class="container_notification_menu" @click.prevent="showNotificationMenu">
          <div class="numbers_new_noti pointer" v-if="numberNewNotis > 0">{{ numberNewNotis }}</div>
          <img class="pointer" src="../images/notification.png" width="22px" height="22px">

          <NotificationMenu v-if="showNotification"/>
  
        </div>
        <div class="account">
            <img v-if="set_user == true && user[0].avatar == null" class="avatar" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1CfdSF5Sdj53VRzQtJe8dgcoDLSyH5tK_sGgyhlfs91uiPe4FAg0u_nsBPDIGovorvso&usqp=CAU" width="30px" height="30px" @click="show_acc = !show_acc">
                  <img v-if="set_user == true && user[0].avatar != null" class="avatar" :src="'/uploads/avatar/' + user[0].avatar" width="30px" height="30px" @click="show_acc = !show_acc">
            <div class="dropdown-menu" v-show="show_acc == true">
                  <p v-if="user[0] != null"><b>{{ user[0].name }}</b></p>
                <hr>
                <router-link to="/profile">Hồ sơ</router-link>
                <router-link to="/listorders">Lịch sử mua hàng</router-link>
                <a href="#" @click="logout($event)">Đăng xuất</a>
            </div>
        </div>
      </div>
    </div>
  </div>  
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import NotificationMenu from './NotificationMenu.vue';

    export default {
        components: {
          NotificationMenu,
        },

        data() {
            return {
                keyword: '',
                show_acc: false,
                showNotification: false,
            }
        },

        mounted() {

        },

        computed: {
          ...mapGetters({
            user: 'info_user',
            numberNewNotis: 'numberNewNotis',
          }),

          set_user: function() {
            if( this.user.length == 0 ) {
              return false;
            }
            return true;
          }
        },

        methods: {
          search(event) {
            event.preventDefault();
            if (this.keyword == '') {
              return;
            }
            this.$router.push('/products/search/' + this.keyword);
            this.keyword = '';
          },

          async logout(event) {
              event.preventDefault();
              axios.get('/api/logout')
                  .then(response => {
                    window.location.href = '/';
                  })
                  .catch(function(){
                    console.log('Loi dang xuat');
                  });
			    },

          showNotificationMenu: function() {
            this.showNotification = !this.showNotification;
            this.clickNotificationsMenu();
          },

          closeNotificationMenu: function() {
            if( this.showNotification ) {
              this.showNotification = false;
            }
            return
          },

          ...mapActions({
            showModalLogin: 'showLogin',
            clickNotificationsMenu: 'clickMenuNotifications',
          })
        },        

        watch:{
          $route (to, from){
              this.show_acc = false;
          },
        } 
    }
</script>

<style src="../../css/css_layout.css" scoped>