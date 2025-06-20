<template>
  <div id="app">
    <!-- You can keep a global logo or header here if you like -->
    <!-- <img alt="Vue logo" src="./assets/logo.png"> -->
    
    <!-- This is the placeholder where components from your router will be rendered -->
    <router-view />
  </div>
</template>

<script>
// No specific page components are imported or registered directly in App.vue,
// as Vue Router handles their rendering via <router-view />.

export default {
  name: 'App',
  
  components: {
    // This object should be empty if App.vue only acts as a router shell.
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
