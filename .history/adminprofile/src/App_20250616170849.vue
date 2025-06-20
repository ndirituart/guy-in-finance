<template>
  <div id="app">
    <!-- You can keep a global logo or header here if you like -->
    <img alt="Vue logo" src="./assets/logo.png">

    <!-- This is the placeholder where components from your router will be rendered -->
    <router-view />
  </div>
</template>

<script>
// Import AdminDashboardHome.vue (assuming this is your AdminDashboard)
// The path is relative to App.vue, which is typically in src/
import AdminDashboard from '@co';

export default {
  name: 'App',
  
  components: {
    // Register AdminDashboard here so it can be used in this component's template
    AdminDashboard 
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
