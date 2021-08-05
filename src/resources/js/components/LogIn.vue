<template>
  <div>
    <form @submit="login($event)">
      <label for="">Email</label>
      <input type="text" name="email" v-model="form_login.email">
      <br>
      <label for="">Password</label>
      <input type="text" name="password" v-model="form_login.password">
      <br>
      <button type="submit">Login</button>
    </form>
    <button @click="logout">Logout</button>
  </div>
</template>

<script>
export const axiosClient = axios.create({
  withCredentials: true, // required to handle the CSRF token
});

export default {
  data: function() {
    return {
      form_login: {
        email: '',
        password: '',
      }
    }
  },

  methods: {
    login: function(event) {
      event.preventDefault();
      axios.get('/sanctum/csrf-cookie').then(response => {
        axios.post('/api/login', this.form_login)
          .then(function (response) {
            console.log(response.data);
          })
          .catch(function (error) {
            console.log(error);
          });
      });
    },

    logout: function() {
      axios.get('/api/logout')
        .then(function (response) {
          console.log(response.data);
        })
        .catch(function (error) {
          console.log(error);
        })
    }
  }
}
</script>