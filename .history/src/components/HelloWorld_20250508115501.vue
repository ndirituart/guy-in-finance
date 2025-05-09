<template>
  <div class="welcome-page">
    <img src="path/to/logo.png" alt="Logo" class="logo" />
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
      this.$router.push({ name: 'NextPage' });
    }
  }
}
</script>