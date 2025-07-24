<template>
  <div class="user-profile-page">
    <!-- Header with Profile Image -->
    <header class="header">
      <img
        :src="profileImageSrc"
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

      <!-- Displaying PIN (masked) -->
      <label>PIN:</label>
      <input :value="maskedPin" type="password" disabled />
      <button class="change-password">Change PIN</button> <!-- Renamed for PIN -->

      <label>Password:</label>
      <input value="************" type="password" disabled /> <!-- Placeholder for actual hash -->
      <button class="change-password">Change Password</button>
    </div>

    <!-- Message Display -->
    <div v-if="message" :class="['message-box', messageType]">
      {{ message }}
    </div>

    <!-- Bottom Buttons -->
    <div class="actions">
      <button class="button" @click="updateProfile">SAVE CHANGES</button>
      <button class="button" @click="logout">LOGOUT</button>
      <button class="button" @click="goToSignup">Create New Profile</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'; 
import { useRouter } from 'vue-router'; 
export default {
  name: 'UserProfile',
  data() {
    return {
      username: '',
      firstName: '',
      middleName: '',
      lastName: '',
      email: '',
      phone: '',
      birthday: '',
      maskedPin: '****', // Display masked PIN
      profileImageSrc: require('@/assets/Profile.jpeg'), // Default image
      message: '', // For displaying success/error messages
      messageType: '', // 'success' or 'error'
    };
  },
  setup() {
    const router = useRouter(); 
    return { router }; 
  },
  async mounted() {
    // Call fetchUserProfile when the component is mounted
    await this.fetchUserProfile();
  },
  methods: {
    async fetchUserProfile() {
      try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
          this.displayMessage('You are not logged in. Please log in to view your profile.', 'error');
          this.router.push('/login'); // Redirect to login if no token
          return;
        }

        //Authorization header for this request
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        // Make API call to fetch user data
        const response = await axios.get('http://127.0.0.1:8000/api/user/profile'); // New endpoint

        const userData = response.data.user; // Assuming your backend returns { user: {...} }

        // Populate data properties with fetched user data
        this.username = userData.username || '';
        this.firstName = userData.first_name || '';
        this.middleName = userData.middle_name || '';
        this.lastName = userData.last_name || '';
        this.email = userData.email || '';
        this.phone = userData.phone_number || '';
        this.birthday = userData.birthday ? new Date(userData.birthday).toISOString().split('T')[0] : ''; // Format date for input

        // PIN is usually not returned directly for security, but if it was, we'd mask it.
        // For now, we'll just show '****' as it's not directly editable here.
        // If you want to allow changing PIN, you'd need a separate flow.
        this.maskedPin = '****'; // Always masked

        this.displayMessage('Profile data loaded successfully.', 'success');

      } catch (error) {
        console.error('Failed to fetch user profile:', error.response?.data || error.message);
        this.displayMessage('Failed to load profile data. Please try again.', 'error');
        // If the token is invalid or expired, redirect to login
        if (error.response?.status === 401 || error.response?.status === 403) {
          localStorage.removeItem('authToken'); // Clear invalid token
          this.router.push('/login');
        }
      }
    },

    async updateProfile() {
      try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
          this.displayMessage('You are not logged in. Please log in to update your profile.', 'error');
          this.router.push('/login');
          return;
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        // Data to send for update
        const updateData = {
          username: this.username,
          first_name: this.firstName,
          middle_name: this.middleName,
          last_name: this.lastName,
          email: this.email,
          phone_number: this.phone,
          birthday: this.birthday,
          // PIN and password changes should be separate for security
        };

        // Make API call to update user data
        const response = await axios.put('http://127.0.0.1:8000/api/user/profile', updateData); // New endpoint for update

        this.displayMessage(response.data.message || 'Profile updated successfully!', 'success');

      } catch (error) {
        console.error('Failed to update user profile:', error.response?.data || error.message);
        this.displayMessage(error.response?.data?.message || 'Failed to update profile. Please try again.', 'error');
      }
    },

    async logout() {
      try {
        const authToken = localStorage.getItem('authToken');
        if (authToken) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
          await axios.post('http://127.0.0.1:8000/api/logout');
        }
      } catch (error) {
        console.error('Logout failed on backend:', error);
        // Even if backend logout fails, clear local token
      } finally {
        localStorage.removeItem('authToken'); // Always clear token from local storage
        axios.defaults.headers.common['Authorization'] = ''; // Clear axios default header
        this.displayMessage('Logged out successfully!', 'success');
        this.router.push('/login'); // Redirect to login page after logout
      }
    },

    goToSignup() {
      this.router.push('/signup');
    },

    displayMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 3000); // Message disappears after 3 seconds
    },
  },
};
</script>

<style scoped>
.user-profile-page {
  font-family: 'Inter', sans-serif; /* Using Inter font */
  max-width: 700px;
  margin: 30px auto;
  padding: 25px;
  border: 1px solid #e0e6ed;
  border-radius: 12px; /* Rounded corners */
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
  padding-bottom: 15px;
  border-bottom: 1px solid #f0f2f5;
}

.profile-image {
  width: 90px; /* Slightly larger */
  height: 90px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #007bff; /* Accent border */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header h1 {
  color: #007bff;
  font-size: 2.2em;
  margin: 0;
}

.user-details {
  display: flex;
  flex-direction: column;
  gap: 18px; /* More space between fields */
}

.user-details label {
  font-weight: 600;
  margin-top: 5px;
  color: #333;
  font-size: 0.95em;
}

input {
  padding: 10px 12px;
  border: 1px solid #cce0f0; /* Lighter border */
  border-radius: 8px; /* Rounded corners */
  width: 100%;
  font-size: 1em;
  box-sizing: border-box; /* Ensures padding doesn't add to width */
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
  outline: none;
}

.name-group {
  display: flex;
  gap: 15px; /* More space between name fields */
  flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

.name-field {
  flex: 1;
  min-width: 150px; /* Ensure fields don't get too small */
  display: flex;
  flex-direction: column;
}

.change-password {
  margin-top: 10px;
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 8px; /* Rounded corners */
  cursor: pointer;
  font-size: 0.9em;
  align-self: flex-start; /* Align to left */
  transition: background-color 0.3s ease;
}

.change-password:hover {
  background-color: #5a6268;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-top: 40px;
  gap: 15px; /* Space between buttons */
  flex-wrap: wrap; /* Allow wrapping */
}

.button {
  padding: 12px 25px;
  font-size: 1.1em;
  background-color: #007bff; /* Primary blue */
  color: white;
  border: none;
  border-radius: 8px; /* Rounded corners */
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.1s ease;
  flex: 1; /* Make buttons take equal space */
  min-width: 150px; /* Ensure buttons don't get too small */
}

.button:hover {
  background-color: #0056b3;
  transform: translateY(-1px);
}

.button:active {
  background-color: #004085;
  transform: translateY(0);
}

/* Specific styles for message box */
.message-box {
  padding: 12px;
  border-radius: 8px;
  margin-top: 20px;
  font-weight: bold;
  text-align: center;
  animation: slideIn 0.3s ease-out;
}

.message-box.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message-box.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

@keyframes slideIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .user-profile-page {
    margin: 15px;
    padding: 15px;
  }
  .header {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
  .name-group {
    flex-direction: column;
    gap: 10px;
  }
  .actions {
    flex-direction: column;
    gap: 10px;
  }
  .button {
    width: 100%;
  }
}
</style>