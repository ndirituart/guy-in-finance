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
  <div>
    <!-- Navigation Header -->
    <header class="header">
      <nav class="nav">
        <ul>
          <li><a href="/src/components/MyExpenses.vue">MyExpenses</a></li>
          <li><a href="/src/components/LoansPage.vue">Loans</a></li>
          <li><a href="/src/components/SavingsPage.vue">Savings</a></li>
          <li><a href="/src/components/RelationsPage.vue">Relations</a></li>
        <li><a href="/src/components/Azq">Account</a></li>
          <li><a href="#login">Login</a></li>
          <li><a href="#register">Register</a></li>
        </ul>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="main">
      <img src="/src/assets/Logo.png" alt="Logo" class="logo" />
      <h1>Hi, {{ username }}</h1>
      <p>Welcome to your personal finance dashboard!</p>
    </main>
  </div>
</template>

<style scoped>
/* Header Styles */
.header {
  background-color: #41b883;
  padding: 1rem 0;
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
  text-align: center;
  padding: 2rem;
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
</style>
