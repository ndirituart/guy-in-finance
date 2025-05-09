<template>
  <div>
    <!-- Header Section -->
    <header class="app-header">
      <h1>Welcome to Our Application</h1>
    </header>
    
    <!-- Logo Section -->
    <img alt="Vue logo" src="/src/" class="app-logo">

    <!-- Welcome Page Section -->
    <div class="welcome-page">
      <h2>What brings you here today?</h2>
      <button @click="proceed" :class="{ red: isClicked, orange: !isClicked }">PROCEED</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data() {
    return {
      isClicked: false
    };
  },
  methods: {
    proceed() {
      this.isClicked = true;
      this.$router.push('/home');
    }
  }
};
</script>

<style scoped>
/* Header Styling */
.app-header {
  text-align: center;
  margin-top: 20px;
}

.app-logo {
  width: 150px; /* Customize size */
  height: 150px; /* Make logo circular if desired */
  margin-top: 20px;
}

/* Welcome Page Styling */
.welcome-page {
  text-align: center;
  margin-top: 50px;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983; /* Green by default */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s; /* Smooth transition for background color */
}

button.red {
  background-color: red; /* Red when clicked */
}

button.orange {
  background-color: orange; /* Orange when hovered before clicked */
}

button:hover {
  background-color: orange; /* Orange on hover */
}

button:active {
  background-color: red; /* Red when the button is actively clicked */
}
</style>
