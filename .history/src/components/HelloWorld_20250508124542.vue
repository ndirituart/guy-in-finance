<template>
  <div class="welcome-page">
    <h2>What brings you here today?</h2>
    <router-link to="/home">
      <button @click="isClicked = true" :class="{ red: isClicked }">PROCEED</button>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'WelcomePage',
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

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button.red {
  background-color: red;
}

button:hover {
  background-color: #36a372;
}
</style>
