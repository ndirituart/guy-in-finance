<script setup>
import { defineProps } from 'vue';
import logo from '/src/assets/logo.png';
</script>

<template>
  <div class="container">
    <!-- Main Content -->
    <main class="main">
      <img src="/src/assets/logo.png" alt="Logo" class="logo" />
      <h1>Hi, {{ username }}</h1>
      <p>Welcome to your personal finance dashboard!</p>
    </main>
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
</style>
