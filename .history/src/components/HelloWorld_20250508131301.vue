<template>
  <div class="welcome-page">
    <h2>What brings you here today?</h2>
    <button @click="proceed" :class="{ red: isClicked }">PROCEED</button>
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
    proceed() {
      this.isClicked = true;
      this.$router.push('/home');
    }
  }
};
</script>
