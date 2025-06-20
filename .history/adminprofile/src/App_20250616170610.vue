<template>
  <div id="app">
    <!-- You can keep a global logo or header here if you like -->
    <img alt="Vue logo" src="./assets/logo.png">

    <!-- This is the placeholder where components from your router will be rendered -->
    <router-view />
  </div>
</template>

<script>

export default {
  name: 'App',
  // Remove the components registration if they are only used via router-view
  // components: {
  //   AdminDashboard // This line should be removed
  // }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>

