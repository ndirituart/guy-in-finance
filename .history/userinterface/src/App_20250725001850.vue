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
.main-footer {
  position: fixed; /* Fixes the footer at the bottom of the viewport */
  bottom: 0;
  left: 0;
  width: 100%;
  background-color:#007BFF; /* Dark background for visibility */
  color: white;
  padding: 10px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2); /* Shadow for depth */
  z-index: 1000; /* Ensures it stays on top of other content */
}

.footer-nav {
  display: flex;
  justify-content: space-around; /* Distributes items evenly */
  align-items: center;
  max-width: 1200px; /* Limit width for larger screens */
  margin: 0 auto; /* Center the nav items */
}

.nav-item {
  display: flex;
  flex-direction: column; /* Stack icon and text */
  align-items: center;
  text-decoration: none; /* Remove underline */
  color: white; /* Default text color */
  font-size: 0.85em; /* Smaller text for compactness */
  padding: 5px 10px;
  border-radius: 5px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-item:hover {
  background-color: #555; /* Slightly lighter on hover */
}

/* Active link style */
.nav-item.router-link-exact-active {
  color: #87ceeb; /* Light blue for active link */
  font-weight: bold;
}

.icon {
  font-size: 1.8em; /* Larger icons */
  margin-bottom: 3px; /* Space between icon and text */
}

.text {
  white-space: nowrap; /* Prevents text from wrapping */
}

/* Media queries for responsiveness */
@media (max-width: 600px) {
  .nav-item {
    padding: 5px 5px; /* Less padding on small screens */
    font-size: 0.75em;
  }
  .icon {
    font-size: 1.5em; /* Smaller icons on small screens */
  }
}

/* Ensure content above footer */
body {
  padding-bottom: 70px; /* Add padding to the bottom of the body to prevent footer from overlapping content */
}
</style>
