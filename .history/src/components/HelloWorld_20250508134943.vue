<template>
  <div class="welcome-page">
    <header class="header">
      <h1>Welcome to Our Application</h1>
    </header>
    <!-- Add your logo here -->
    <img src="@/assets/logo.png" alt="Logo" class="logo" /> <!-- Adjust the path as necessary -->

    <h2>What brings you here today?</h2>
    <router-link to="/home" class="link">
      <button @click="isClicked = true" :class="{ red: isClicked }">PROCEED</button>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data() {
    return {
      isClicked: false
    };
  }
};
</script>

<style scoped>
.welcome-page {
  text-align: center;
  margin-top: 50px;
}

.header {
  background-color: #007BFF; /* Blue header */
  color: white;
  padding: 20px;
}

.logo {
  display: block;
  margin: 20px auto; /* Center the logo */
  max-width: 100px; /* Adjust size as needed */
}

.link {
  text-decoration: none; /* Remove underline from link */
}

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s; /* Smooth transition */
}

button.red {
  background-color: red; /* Change color when clicked */
}

button:hover {
  background-color: #36a372; /* Change color on hover */
}
</style>