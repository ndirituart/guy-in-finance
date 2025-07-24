<template>
  <div class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <section class="user-section">
      <h2>All Registered Users</h2>

      <table v-if="users.length" class="user-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>PIN</th>
            <th>Birthday</th>
            <th>ID Number</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in users" :key="user.id">
            <td>{{ index + 1 }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.phone_number }}</td>
            <td>{{ user.PIN ? '🔐 Set' : '—' }}</td>
            <td>{{ user.birthday }}</td>
            <td>{{ user.id_number }}</td>
          </tr>
        </tbody>
      </table>

      <p v-else>No users found.</p>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref([])

const loadUsers = async () => {
  try {
    const token = localStorage.getItem('token') // Or your auth method
    const response = await axios.get('http://127.0.0.1:8000/api/admin/users', {
      headers: { Authorization: `Bearer ${token}` }
    })
    users.value = response.data.data
  } catch (error) {
    console.error('Error loading users:', error)
  }
}

onMounted(() => {
  loadUsers()
})
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
  font-family: Arial, sans-serif;
}

.user-section h2 {
  margin-top: 20px;
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 16px;
  background: white;
}

.user-table th,
.user-table td {
  padding: 10px;
  border: 1px solid #ccc;
  text-align: left;
}

.user-table th {
  background-color: #007BFF;
  color: white;
}
</style>
