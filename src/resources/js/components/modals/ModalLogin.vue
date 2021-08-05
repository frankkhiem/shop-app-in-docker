<template>
  <div v-if="show_modal_login" class="modal-login">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <button class="modal-button-exit" @click="closedModalLogin(); error_login=''">X</button>
          <div class="modal-body">
            <h2>Đăng nhập</h2>
            <h3 class="login-error">{{ error_login }}</h3>
            <div>
              <label for="email">Email</label>
              <input id="email" type="text" v-model="form_login.email">
            </div>
            <br>
            <div>
              <label for="password">Mật khẩu</label>
              <input type="password" id="password" v-model="form_login.password">
            </div>
            <br>
            <button @click="loginSPA()">Xác nhận</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'

export default {
  data: function() {
    return {
      form_login: {
          email: '',
          password: '',
      },
      error_login: '',
    }
  },

  computed: {
    ...mapGetters({
      show_modal_login: 'stateModalLogin',
    })
  },

  methods: {
    loginSPA() {
      axios.get('/sanctum/csrf-cookie').then(response => {
        axios.post('/api/login', this.form_login)
        .then(response => {
          console.log(response.data);
          this.$router.go();
        })
        .catch(error => {
          console.log(error.toString());
          this.error_login = 'Đăng nhập thất bại!';
          this.form_login = {};
        });
      });               
    },

    ...mapActions({
      closedModalLogin: 'closedLogin',
    })
  },
}
</script>

<style src="../../../css/css_modal_login.css" scoped>