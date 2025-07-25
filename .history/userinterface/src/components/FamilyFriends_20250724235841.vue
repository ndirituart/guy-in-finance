<template>
  <div class="page-content"> <h1>Family & Friends Page</h1>
    <p>Content for managing family and friends related transactions.</p>
    <button @click="$router.push('/')">Go to Home</button>
  </div>
</template>
<script>
export default { name: 'FamilyFriends' }
</script>
<style scoped>
.page-content {
  padding: 20px;
  text-align: center;
}
button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
}
button:hover {
  background-color: #0056b3;
}
</style>