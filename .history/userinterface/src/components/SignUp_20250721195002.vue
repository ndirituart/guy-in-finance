<template>
  <div class="profile-edit-container">
    <form class="profile-edit-form" @submit.prevent="updateProfile">
      <h2>Edit Profile</h2>

      <div class="form-group image-upload">
        <label>Profile Image</label>
        <input type="file" @change="handleImageUpload" accept="image/*" />
        <img v-if="profile.profileImageUrl" :src="profile.profileImageUrl" alt="Current Profile" class="current-profile-image" />
        <img v-if="previewImage" :src="previewImage" alt="New Profile Preview" class="preview-image" />
        <p v-else-if="!profile.profileImageUrl && !previewImage">No profile image uploaded yet.</p>
      </div>

      <div class="form-group">
        <label>Username</label>
        <input v-model="profile.username" type="text" disabled /> </div>

      <div class="form-group full-name">
        <div>
          <label>First Name</label>
          <input v-model="profile.firstName" type="text" required />
        </div>
        <div>
          <label>Middle Name</label>
          <input v-model="profile.middleName" type="text" />
        </div>
        <div>
          <label>Last Name</label>
          <input v-model="profile.lastName" type="text" required />
        </div>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="profile.email" type="email" required />
      </div>

      <div class="form-group">
        <label>Birthday</label>
        <input v-model="profile.birthday" type="date" required @change="checkAge" />
        <span v-if="age && age < 14" class="error">Users must be over 14 years old</span>
      </div>

      <div v-if="age >= 18" class="form-group">
        <label>ID Number (optional)</label>
        <input v-model="profile.idNumber" type="text" />
      </div>

      <div class="form-group">
        <label>Phone Number</label>
        <input v-model="profile.phone" type="tel" required pattern="^\+254\d{9}$" placeholder="+2547XXXXXXXX" />
      </div>

      <div class="form-group">
        <label>New Password (leave blank to keep current)</label>
        <input v-model="profile.newPassword" type="password" />
      </div>
      <div class="form-group">
        <label>Confirm New Password</label>
        <input v-model="profile.confirmNewPassword" type="password" />
        <span v-if="newPasswordMismatch" class="error">Passwords do not match</span>
      </div>

      <button type="submit" class="button" :disabled="!formValid">Update Profile</button>
    </form>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'ProfileEdit',
  setup() {
    const profile = ref({
      username: '',
      firstName: '',
      middleName: '',
      lastName: '',
      email: '',
      birthday: '',
      idNumber: '',
      phone: '',
      profileImageUrl: null, // To store the URL of the existing image
      newPassword: '', // For optional password change
      confirmNewPassword: '',
    });

    const selectedFile = ref(null); // To hold the actual File object for upload
    const previewImage = ref(null);  // For new image preview
    const age = ref(null);

    const newPasswordMismatch = computed(() => {
      return profile.value.newPassword && profile.value.newPassword !== profile.value.confirmNewPassword;
    });

    const formValid = computed(() => {
      // Basic validation, enhance as needed
      return (
        !newPasswordMismatch.value &&
        age.value >= 14 && // Assuming age check applies to updates too
        profile.value.phone.startsWith('+254') &&
        (profile.value.newPassword === '' || profile.value.newPassword.length >= 6) // If changing password, must meet criteria
      );
    });

    const checkAge = () => {
      if (!profile.value.birthday) {
        age.value = null;
        return;
      }
      const today = new Date();
      const birthDate = new Date(profile.value.birthday);
      let calculatedAge = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        calculatedAge--;
      }
      age.value = calculatedAge;
    };

    const handleImageUpload = (e) => {
      const file = e.target.files[0];
      if (file) {
        selectedFile.value = file;
        previewImage.value = URL.createObjectURL(file);
      } else {
        selectedFile.value = null;
        previewImage.value = null;
      }
    };

    // --- Fetch User Data on Component Mount ---
    onMounted(async () => {
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          // Handle no token case (e.g., redirect to login)
          console.error("No authentication token found.");
          return;
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await axios.get('http://127.0.0.1:8000/api/user/profile'); // Assuming an API endpoint to fetch user profile

        const userData = response.data.data; // Adjust based on your API response structure

        // Populate the form with existing user data
        profile.value.username = userData.username;
        profile.value.firstName = userData.first_name;
        profile.value.middleName = userData.middle_name;
        profile.value.lastName = userData.last_name;
        profile.value.email = userData.email;
        profile.value.birthday = userData.birthday; // Ensure format matches input type="date" (YYYY-MM-DD)
        profile.value.idNumber = userData.id_number;
        profile.value.phone = userData.phone_number;
        profile.value.profileImageUrl = userData.profile_image_url ? `http://127.0.0.1:8000/storage/${userData.profile_image_url}` : null; // Construct full URL if needed

        checkAge(); // Recalculate age based on fetched birthday

      } catch (error) {
        console.error('Failed to fetch profile data:', error.response?.data || error.message);
        alert('Failed to load profile data: ' + (error.response?.data?.message || error.message));
      }
    });

    // --- Update Profile ---
    const updateProfile = async () => {
      if (!formValid.value) {
        alert("Please correct the form errors.");
        return;
      }

      try {
        const formData = new FormData(); // Use FormData for file uploads

        // Append text fields
        formData.append('_method', 'PUT'); // Laravel expects _method='PUT' for PUT requests with FormData
        formData.append('first_name', profile.value.firstName);
        formData.append('middle_name', profile.value.middleName || ''); // Ensure it's not undefined
        formData.append('last_name', profile.value.lastName);
        formData.append('email', profile.value.email);
        formData.append('birthday', profile.value.birthday);
        formData.append('id_number', profile.value.idNumber || '');
        formData.append('phone_number', profile.value.phone);

        // Append new password if provided
        if (profile.value.newPassword) {
          formData.append('password', profile.value.newPassword);
          formData.append('password_confirmation', profile.value.confirmNewPassword);
        }

        // Append the file if selected
        if (selectedFile.value) {
          formData.append('profile_image', selectedFile.value);
        }

        const token = localStorage.getItem('authToken');
        if (!token) {
          console.error("No authentication token found.");
          return;
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Make the PUT request to update the profile
        const response = await axios.post('http://127.0.0.1:8000/api/user/profile', formData, { // Using POST for FormData with _method=PUT
            headers: {
                'Content-Type': 'multipart/form-data' // Important for file uploads
            }
        });

        alert('Profile updated successfully!');
        // Optionally update the current profile image URL after successful upload
        if (response.data.user && response.data.user.profile_image_url) {
            profile.value.profileImageUrl = `http://127.0.0.1:8000/storage/${response.data.user.profile_image_url}`;
            previewImage.value = null; // Clear preview after successful upload
            selectedFile.value = null; // Clear selected file
        }

        // You might want to refresh the user data in a global store here if you're using Pinia/Vuex
        // For example: userStore.fetchUserProfile();

      } catch (error) {
        console.error('Profile update failed:', error.response?.data || error.message);
        alert('Profile update failed: ' + (error.response?.data?.message || error.message));
      }
    };

    return {
      profile,
      previewImage,
      age,
      newPasswordMismatch,
      formValid,
      checkAge,
      handleImageUpload,
      updateProfile,
    };
  },
};
</script>

<style scoped>
.profile-edit-container {
  min-height: 100vh;
  background: linear-gradient(to right, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-edit-form {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.profile-edit-form h2 {
  text-align: center;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.3rem;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="date"],
.form-group input[type="tel"],
.form-group input[type="password"] {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.full-name {
  display: flex;
  gap: 0.5rem;
}

.full-name div {
  flex: 1;
}

.error {
  color: red;
  font-size: 0.8rem;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983; /* Green by default */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s; 
  width: 100%; 
  margin-top: 1rem; /* Add some spacing */
}

button:hover {
  background-color: orange;
}

button:active {
  background-color: red;
}

.image-upload input[type="file"] {
  padding: 0.5rem 0;
  border: none; 
}

.current-profile-image, .preview-image {
  width: 100px; 
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  margin-top: 0.5rem;
  border: 2px solid #ddd; 
}
.current-profile-image {
    margin-right: 15px; 
}
</style>