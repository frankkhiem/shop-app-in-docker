<template>
  <div v-if="show_modal_login" class="modal-login">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <button class="modal-button-exit" @click="closedModalLogin(); error_login=''">X</button>
          <div class="modal-body">
            <div class="modal-nav">
              <div @click="modeLogin" :class="[mode === 'login' ? 'choice':'']">Đăng nhập</div>
              <div @click="modeRegister" :class="[mode === 'register' ? 'choice':'']">Đăng ký</div>
            </div>
            <div class="modal-content">
              <div class="login" v-if="mode === 'login'">
                <h3 class="login-error">{{ error_login }}</h3>
                <div>
                  <label for="email">Email</label>
                  <input id="email" type="text" v-model="form_login.email">
                </div>
                <div>
                  <label for="password">Mật khẩu</label>
                  <input type="password" id="password" v-model="form_login.password">
                </div>
                <div class="login-social">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/480px-Google_%22G%22_Logo.svg.png" alt="Google">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/1200px-Facebook_Logo_%282019%29.png" alt="Facebook">
                </div>
                <button @click="loginSPA()">Xác nhận</button>
              </div>
              <div class="register" v-if="mode === 'register'">
                <div>
                  <label for="name">Tên người dùng</label>
                  <input id="name" type="text" v-model="formRegister.name" autocomplete="off">
                </div>
                <div>
                  <label for="furigana-name">Furigana</label>
                  <input id="furigana-name" type="text" v-model="formRegister.furigana" autocomplete="off">
                </div>
                <div>
                  <label for="email-register">Email</label>
                  <input id="email-register" type="email" v-model="formRegister.email" autocomplete="off">
                </div>
                <div>
                  <label for="password-register">Mật khẩu</label>
                  <input type="password" id="password-register" v-model="formRegister.password" autocomplete="new-password">
                </div>
                <div>
                  <label for="confirm-password">Nhập lại mật khẩu</label>
                  <input type="password" id="confirm-password" v-model="formRegister.password_confirmation" autocomplete="new-password">
                </div>
                <button @click="registerSPA">Đăng ký</button>
              </div>            
            </div>
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
      mode: 'login',
      abc: 0,
      form_login: {
          email: '',
          password: '',
      },
      error_login: '',
      formRegister: {
        name: '',
        furigana: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errorRegister: '',
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

    registerSPA: function() {
      axios.get('/sanctum/csrf-cookie').then(response => {
        axios.post('/api/register', this.formRegister)
        .then(response => {
          console.log(response.data);          
          if (response.data.status === 'fails') {
            alert(response.data.message);
            this.formRegister = {}
          } else if (response.data.status === 'ok') {
            this.$router.go();
          }
        })
        .catch(error => {
          console.log(error);
        });
      });
      // console.log(this.formRegister);
    },

    modeLogin: function() {
      this.mode = 'login';
      this.formRegister = {}
    },

    modeRegister: function() {
      this.mode = 'register';
      this.form_login = {};
    },

    ...mapActions({
      closedModalLogin: 'closedLogin',
    })
  },
}
</script>

<style src="../../../css/css_modal_login.css" scoped>