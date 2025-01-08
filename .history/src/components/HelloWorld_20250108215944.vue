<script setup>
import logo from '/src/assets/logo.png';

defineProps({
  username: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <div class="container">
    <!-- Navigation Header -->
    <header class="header">
      <nav class="nav">
  <ul>
    <li><router-link to="/expenses">MyExpenses</router-link></li>
    <li><router-link to="/loans">Loans</router-link></li>
    <li><router-link to="/savings">Savings</router-link></li>
    <li><router-link to="/relations">Relations</router-link></li>
    <li><router-link to="/account">Account</router-link></li>
    <li><router-link to="/login">Login</router-link></li>
    <li><router-link to="/register">Register</router-link></li>
  </ul>
</nav>

    </header>

    <!-- Main Content -->
    <main class="main">
      <img src="/src/assets/logo.png" alt="Logo" class="logo" />
      <h1>Hi, {{ username }}</h1>
      <p>Welcome to your personal finance dashboard!</p>
    </main>

    <!-- Footer -->
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
      <p>; 2025 GuyInFinance</p>
    </footer>
  </div>
</template>

<style scoped>
/* General Container */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Header Styles */
/* Header Styles */
.header {
  background-color: #41b883;
  padding: 1rem 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  text-align: center; /* Optional for centered content */
}

/* Footer Styles */
.footer {
  background-color: #34495e; /* Example background color */
  padding: 1rem;
  position: fixed;
  bottom: 0;
  width: 100%;
  text-align: center;
  z-index: 1000;
}

.main {
  margin: 4rem 1rem 6rem; /* Adjust the margins for content spacing */
  padding: 2rem;
  min-height: calc(100vh - 2rem); /* Ensures content area height */
  overflow-y: auto; /* Allows scrolling */
  text-align: center;
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

/* Main Content Styles */
.main {
  flex: 1;
  margin-top: 5rem; /* Adjust for fixed header */
  margin-bottom: 5rem; /* Adjust for fixed footer */
  padding: 2rem;
  text-align: center;
  overflow-y: auto;
  flex-grow: 1; /* Ensure full height */
}

.logo {
  width: 150px;
  height: auto;
  margin: 1rem auto;
}

h1 {
  font-size: 2.5rem;
  color: #34495e;
}

p {
  font-size: 1.2rem;
  color: #666;
}

/* Footer Styles */
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
