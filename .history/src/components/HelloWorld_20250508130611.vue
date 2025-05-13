<template>
  <div class="welcome-page">
    <h2>What brings you here today?</h2>
    <router-link to="/home" class="link">
      <button @click="handleClick" :class="{ red: isClicked }">PROCEED</button>
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
  },
  methods: {
    handleClick() {
      this.isClicked = true; // Change button color
    }
  }
};
</script>

<style scoped>
.welcome-page {
  text-align: center;
  margin-top: 50px;
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