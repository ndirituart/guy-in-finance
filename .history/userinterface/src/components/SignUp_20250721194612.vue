<template>
  <div class="signup-container">
    <form class="signup-form" @submit.prevent="submitForm">
      <h2>Sign Up</h2>

      <!-- Profile Image (optional, currently commented out) -->
      
      <div class="form-group image-upload">
        <label>Profile Image</label>
        <input type="file" @change="handleFileUpload" accept="image/*" />
        <img v-if="previewImage" :src="previewImage" alt="Profile Preview" class="preview-image" />
      </div>
     

      <!-- Username -->
      <div class="form-group">
        <label>Username</label>
        <input v-model="form.username" type="text" required />
      </div>

      <!-- Full Name -->
      <div class="form-group full-name">
        <div>
          <label>First Name</label>
          <input v-model="form.firstName" type="text" required />
        </div>
        <div>
          <label>Middle Name</label>
          <input v-model="form.middleName" type="text" />
        </div>
        <div>
          <label>Last Name</label>
          <input v-model="form.lastName" type="text" required />
        </div>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" required />
      </div>

      <!-- Birthday -->
      <div class="form-group">
        <label>Birthday</label>
        <input v-model="form.birthday" type="date" required @change="checkAge" />
        <span v-if="age && age < 14" class="error">Users must be over 14 years old</span>
      </div>

      <!-- ID Number -->
      <div v-if="age >= 18" class="form-group">
        <label>ID Number (optional)</label>
        <input v-model="form.idNumber" type="text" />
      </div>

      <!-- Phone -->
      <div class="form-group">
        <label>Phone Number</label>
        <input v-model="form.phone" type="tel" required pattern="^\+254\d{9}$" placeholder="+2547XXXXXXXX" />
      </div>

      <!-- Passwords -->
      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" required />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input v-model="form.confirmPassword" type="password" required />
        <span v-if="passwordMismatch" class="error">Passwords do not match</span>
      </div>

      <!-- Submit -->
      <button type="submit" class="button" :disabled="!formValid">SIGN UP</button>

      <hr />
      <p>Do you have an account?</p>
      <button type="button" class="button" @click="goToLogin">LOGIN</button>
    </form>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'SignUp',
  setup() {
    const router = useRouter();

    const form = ref({
      username: '',
      firstName: '',
      middleName: '',
      lastName: '',
      email: '',
      birthday: '',
      idNumber: '',
      phone: '',
      password: '',
      confirmPassword: '',
    });

    const age = ref(null);
    const previewImage = ref(null);

    const passwordMismatch = computed(() => {
      return form.value.password !== form.value.confirmPassword;
    });

    const formValid = computed(() => {
      return (
        !passwordMismatch.value &&
        age.value >= 14 &&
        form.value.phone.startsWith('+254') &&
        form.value.password.length >= 6
      );
    });

    const checkAge = () => {
      const today = new Date();
      const birthDate = new Date(form.value.birthday);
      let calculatedAge = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        calculatedAge--;
      }
      age.value = calculatedAge;
    };

    const handleFileUpload = (e) => {
      const file = e.target.files[0];
      if (file) {
        previewImage.value = URL.createObjectURL(file);
      }
    };

    const submitForm = async () => {
      if (!formValid.value) return;

      try {
        // The endpoint where we register from
        const response = await axios.post('http://127.0.0.1:8000/api/register', {
          username: form.value.username,
          first_name: form.value.firstName,
          middle_name: form.value.middleName,
          last_name: form.value.lastName,
          email: form.value.email,
          birthday: form.value.birthday,
          id_number: form.value.idNumber,
          phone_number: form.value.phone,
          password: form.value.password,
          password_confirmation: form.value.confirmPassword,
        });

        // Assuming your Laravel backend returns a user object and/or token on successful registration
        const userId = response.data.user.User_id; // Use User_id as per your migration
        const authToken = response.data.token; // Assuming your backend returns a token

        // Store the authentication token (e.g., in localStorage)
        localStorage.setItem('authToken', authToken);

        // Set the default Authorization header for future requests
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        // Redirect to the PIN entry page, passing the user ID
        router.push({ name: 'SetPin', query: { userId: userId } });

      } catch (error) {
        console.error('Signup failed:', error.response?.data || error.message);
        alert('Signup failed: ' + (error.response?.data?.message || 'error.message'));
      }
    };

    // Function to navigate to the login page
    const goToLogin = () => {
      router.push('/login');
    };

    return {
      form,
      age,
      previewImage,
      passwordMismatch,
      formValid,
      checkAge,
      handleFileUpload,
      submitForm,
      goToLogin,
    };
  },
};
</script>

<style scoped>
.signup-container {
  min-height: 100vh;
  background: linear-gradient(to right, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
}

.signup-form {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.signup-form h2 {
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

.form-group input {
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
  transition: background-color 0.3s; /* Smooth transition for background color */
}

button.red {
  background-color: red; /* Red when clicked */
}

button.orange {
  background-color: rgb(0, 255, 85); /* Orange when hovered before clicked */
}

button:hover {
  background-color: orange; /* Orange on hover */
}

button:active {
  background-color: red;
}

.image-upload input {
  padding: 0.5rem 0;
}

.preview-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  margin-top: 0.5rem;
}
</style>
