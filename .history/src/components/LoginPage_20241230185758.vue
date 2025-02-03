<template>
    <div class="login-container">
      <!-- Add the 'msg' class to make the text pink -->
      <h1 class="msg">{{ msg }}</h1> 
      <form @submit.prevent="login">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" v-model="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" required>
        </div>
        <button type="submit">Login</button>
      </form>
      <p v-if="message" :class="{'error-message': isError}">{{ message }}</p>
      <div class="register-container">
        <p>Are you not a member?</p>
        <button><router-link to="/signup" class="no-underline text-black">Register</router-link></button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  
  export default {
    name: 'LoginPage',
    props: {
      msg: {
        type: String,
        default: 'Welcome to Zara!'
      }
    },
    data() {
      return {
        username: '',
        password: '',
        message: '',
        isError: false
      };
    },
    methods: {
    async login() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/login', {
          email: this.username, // Assuming email is the same as username for login
          password: this.password
        });
  
        // Handle successful login response
        localStorage.setItem('token', response.data.token); // Save token to localStorage or Vuex store
        this.message = response.data.message;
        this.isError = false;
  
        // Redirect to the dashboard or another page
        this.$router.push('/dashboard');
      } catch (error) {
        // Handle login error
        this.message = error.response ? error.response.data.message : error.message;
        this.isError = true;
      }
    }
  }
  
  }
  
  </script>
  
  <style scoped>
  .login-container {
    max-width: 300px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    text-align: center;
  }
  .form-group {
    margin-bottom: 15px;
  }
  .form-group label {
    display: block;
    margin-bottom: 5px;
  }
  .form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  button {
    padding: 10px 20px;
    background-color: #4b3161;
    color: rgb(255, 255, 255);
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  button a {
    color: white;
    text-decoration: none;
    background-color: #4b3161;
  }
  button:hover {
    background-color: #1935ec;
  }
  button:active {
    background-color: #5b1ed6;
  }
  
  .register-container {
    margin-top: 20px;
  }
  
  .msg {
    color: rgb(249, 23, 140); /* Make the text pink */
  }
  </style>
  