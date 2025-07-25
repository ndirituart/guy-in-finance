<template>
  <div id="app">
   
    <router-view />

    <!--for navigation between pages back to home page-->
     <footer class="main-footer">
    <nav class="footer-nav">
      <router-link to="/" class="nav-item">
        <span class="icon">🏠</span>
        <span class="text">Home</span>
      </router-link>

      <router-link to="/family-friends" class="nav-item">
        <span class="icon">👨‍👩‍👧‍👦</span>
        <span class="text">Family & Friends</span>
      </router-link>

      <router-link to="/bills" class="nav-item">
        <span class="icon">💧⚡</span>
        <span class="text">Bills</span>
      </router-link>

      <router-link to="/daily-expenses" class="nav-item">
        <span class="icon">🚌🛒</span>
        <span class="text">Daily Expenses</span>
      </router-link>

      <router-link to="/user-savings" class="nav-item">
        <span class="icon">💰</span>
        <span class="text">Savings</span>
      </router-link>
    </nav>
  </footer>
  </div>
</template>
<script>
export default {
  name: 'App'
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

/*for the footer */
</style>
