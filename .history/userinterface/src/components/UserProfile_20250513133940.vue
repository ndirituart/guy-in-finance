<template>
  <div class="user-profile-page">
    <!-- Header with Profile Image -->
    <header class="header">
      <img
        src="@/assets/profile.jpg"
        alt="Profile"
        class="profile-image"
      />
      <h1>User Profile</h1>
    </header>

    <!-- User Details -->
    <div class="user-details">
      <label>Username:</label>
      <input v-model="username" type="text" />

      <div class="name-group">
        <div class="name-field">
          <label>First Name:</label>
          <input v-model="firstName" type="text" />
        </div>
        <div class="name-field">
          <label>Middle Name:</label>
          <input v-model="middleName" type="text" />
        </div>
        <div class="name-field">
          <label>Last Name:</label>
          <input v-model="lastName" type="text" />
        </div>
      </div>

      <label>Email Address:</label>
      <input v-model="email" type="email" />

      <label>Phone Number:</label>
      <input v-model="phone" type="tel" />

      <label>Birthday:</label>
      <input v-model="birthday" type="date" />

      <label>Password:</label>
      <input :value="hashedPassword" type="password" disabled />
      <button class="change-password">Change Password</button>
    </div>

    <!-- Bottom Buttons -->
    <div class="actions">
      <button class="logout" @click="logout">LOGOUT</button>
      <button class="signup" @click="goToSignup">Create New Profile</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserProfile',
  data() {
    return {
      username: 'Pashie🦋',
      firstName: 'Patience',
      middleName: 'Njeri',
      lastName: 'Ndiritu',
      email: 'ndiritupatience002@gmail.com',
      phone: '0712345678',
      birthday: '1995-06-15',
      hashedPassword: '************', // Placeholder for actual hash
    };
  },
  methods: {
    logout() {
      // Clear profile data
      this.username = '';
      this.firstName = '';
      this.middleName = '';
      this.lastName = '';
      this.email = '';
      this.phone = '';
      this.birthday = '';
      this.hashedPassword = '';
      alert('Logged out!');
    },
    goToSignup() {
      this.$router.push('/signup');
    },
  },
};
</script>

<style scoped>
.user-profile-page {
  font-family: Arial, sans-serif;
  max-width: 700px;
  margin: 30px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 10px;
}

.header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
}

.profile-image {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}

.user-details {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.user-details label {
  font-weight: bold;
  margin-top: 10px;
}

input {
  padding: 8px;
  border: 1px solid #aaa;
  border-radius: 5px;
  width: 100%;
}

.name-group {
  display: flex;
  gap: 10px;
}

.name-field {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.change-password {
  margin-top: 10px;
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 8px;
  border-radius: 5px;
  cursor: pointer;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
}

.logout,
.signup {
  padding: 10px 20px;
  font-size: 16px;
  color: white;
  background-color: #28a745;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  width: 48%;
}

.logout:hover,
.signup:hover {
  background-color: #218838;
}
</style>
