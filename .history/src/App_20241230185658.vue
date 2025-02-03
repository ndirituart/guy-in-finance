<template>
  <header>
    <div class="wrapper">
      <HelloWorld msg="Welcome to your financial dashboard!" />
      <img src="./assets/logo.png" alt="Logo" class="logo" />
    </div>
  </header>
</template>

<script setup>
import HelloWorld from './components/HelloWorld.vue';
</script>

<style scoped>
header {
  line-height: 1.5;
  text-align: center;
}

.logo {
  display: block;
  margin: 2rem auto;
  width: 150px;
  height: auto;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    align-items: center;
    justify-content: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin-right: 2rem;
  }

  .wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>
