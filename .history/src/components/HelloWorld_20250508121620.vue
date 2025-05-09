<template>
  <div class="welcome-page">
   
    <h2>What brings you here today?</h2>
    <button @click="proceed">PROCEED</button>
  </div>
</template>

<script>
export default {
  name: 'WelcomePage',
  methods: {
    proceed() {
      // Logic to navigate to the next page
      this.$router.push({ name: 'HomePage' });
    }
  }
}
</script>

<style scoped>
.welcome-page {
  text-align: center;
  margin-top: 50px;
}

.logo {
  width: 150px; /* Set the width */
  height: 150px; /* Set the height to the same value for a perfect circle */
  border-radius: 50%; /* Make the image circular */
  object-fit: cover; /* Ensure the image covers the area without distortion */
  margin-bottom: 20px;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #36a372;
}
</style>N