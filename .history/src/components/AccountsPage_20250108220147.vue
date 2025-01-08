<script setup>
import HelloWorld from './components/HelloWorld.vue';
</script>

<template>
    <div class="account-container">
      <header class="header">
        <div class="wrapper">
          <img src="./assets/logo.png" alt="Logo" class="logo" />
        </div>
      </header>
  
      <main class="main">
        <h1>Welcome Back, {{ username }}</h1>
        <p>Access your financial dashboard to manage your expenses, loans, savings, and more.</p>
  
        <div class="options-container">
          <button class="btn login-btn">
            <router-link to="/login" class="no-underline">Login</router-link>
          </button>
          <button class="btn register-btn">
            <router-link to="/register" class="no-underline">Register</router-link>
          </button>
        </div>
      </main>
  
      <footer class="footer">
        <div class="social-icons">
          <a href="https://www.instagram.com" target="_blank" class="icon">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://www.facebook.com" target="_blank" class="icon">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://www.twitter.com" target="_blank" class="icon">
            <i class="fab fa-twitter"></i>
          </a>
        </div>
        <p>&copy; 2025 YourCompany</p>
      </footer>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  defineProps({
    username: {
      type: String,
      default: 'Guest',
    },
  });
  </script>
  
  <style scoped>
  .account-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  .header {
    background-color: #41b883;
    padding: 1rem 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    text-align: center;
  }
  
  .main {
    flex: 1;
    margin-top: 5rem;
    padding: 2rem;
    text-align: center;
  }
  
  .logo {
    display: block;
    margin: 2rem auto;
    width: 150px;
    height: auto;
  }
  
  h1 {
    font-size: 2.5rem;
    color: #34495e;
  }
  
  p {
    font-size: 1.2rem;
    color: #666;
  }
  
  .options-container {
    margin-top: 2rem;
  }
  
  .btn {
    padding: 10px 20px;
    margin: 0.5rem;
    background-color: #4b3161;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .btn a {
    color: white;
    text-decoration: none;
  }
  
  .btn:hover {
    background-color: #1935ec;
  }
  
  .icon {
    color: white;
    font-size: 1.5rem;
    text-decoration: none;
    margin-right: 1rem;
  }
  
  .icon:hover {
    color: #2ecc71;
  }
  </style>
  