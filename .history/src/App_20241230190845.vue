<template>
  <header>
    <div class="wrapper">
      <nav class="nav">
        <ul>
          <li><a href="/myexpenses">My Expenses</a></li>
          <li><a href="/loans">Loans</a></li>
          <li><a href="/savings">Savings</a></li>
          <li><a href="/relations">Relations</a></li>
          <li><a href="/accounts">Account</a></li>
          <!-- <li><a href="/login">Login</a></li> -->
          <!-- <li><a href="/register">Register</a></li> -->
        </ul>
      </nav>
      <img src="./assets/logo.png" alt="Logo" class="logo" />
    </div>
  </header>

  <main>
    <router-view></router-view>
  </main>

  <footer>
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
</template>

<script setup>
import { defineProps } from 'vue';
</script>

<style scoped>
header {
  line-height: 1.5;
  text-align: center;
}

.logo {
  display: block;
  margin: 2rem auto;
  width: 150px;
  height: auto;
}

.nav ul {
  display: flex;
  justify-content: center;
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.nav li {
  margin: 0 1rem;
}

.nav a {
  text-decoration: none;
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
}

.footer {
  background-color: #41b883;
  color: white;
  text-align: center;
  padding: 1rem 0;
  position: fixed;
  bottom: 0;
  width: 100%;
  z-index: 1000;
}

.social-icons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.icon {
  color: white;
  font-size: 1.5rem;
  text-decoration: none;
}

.icon:hover {
  color: #2ecc71;
}
</style>
