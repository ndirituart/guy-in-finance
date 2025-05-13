<template>
    <header class="app-header">
      <h1>Welcome to Our Application</h1>
    <img alt="Vue logo" src="./assets/logo.png" class="app-logo">
  <div class="welcome-page">
    <h2>What brings you here today?</h2>
    <button @click="proceed" :class="{ red: isClicked }">PROCEED</button>
    
  </div>
</header>
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
  background-color: rgb(0, 255, 34); /* Orange when hovered before clicked */
}

button:hover {
  background-color: orange; /* Orange on hover */
}

button:active {
  background-color: red; /* Red when the button is actively clicked */
}
</style>
