<template>
  <div id="app">
    <!-- You can keep a global logo or header here if you like -->
    <img alt="Vue logo" src="./assets/logo.png">

    <!-- This is the placeholder where components from your router will be rendered -->
    <router-view />
  </div>
</template>

<script>
// Remove the import for AdminDashboard.vue here,
// as it is handled by Vue Router and rendered via <router-view />.
// import AdminDashboard from '@/components/AdminDashboard.vue'; // This line is removed

export default {
  name: 'App',
  
  components: {
    // Remove AdminDashboard from components registration,
    // as it is not directly used in App.vue's template.
    // AdminDashboard // This line is removed
  }
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
