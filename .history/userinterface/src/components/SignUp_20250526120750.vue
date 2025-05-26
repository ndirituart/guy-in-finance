<template>
  <div class="signup-container">
    <form class="signup-form" @submit.prevent="submitForm">
      <h2>Sign Up</h2>

      <div class="form-group">
        <label>Username</label>
        <input v-model="form.username" type="text" required />
      </div>

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

      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" required />
      </div>

      <div class="form-group">
        <label>Birthday</label>
        <input v-model="form.birthday" type="date" required @change="checkAge" />
        <span v-if="age && age < 14" class="error">Users must be over 14 years old</span>
      </div>

      <div v-if="age >= 18" class="form-group">
        <label>ID Number (optional)</label>
        <input v-model="form.idNumber" type="text" />
      </div>

      <div class="form-group">
        <label>Phone Number</label>
        <input v-model="form.phone" type="tel" required pattern="^\+254\d{9}$" placeholder="+2547XXXXXXXX" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="password-input-group">
          <input :type="passwordFieldType" v-model="form.password" required />
          <i :class="passwordToggleIconClass" @click="togglePasswordVisibility"></i>
        </div>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <div class="password-input-group">
          <input :type="confirmPasswordFieldType" v-model="form.confirmPassword" required />
          <i :class="confirmPasswordToggleIconClass" @click="toggleConfirmPasswordVisibility"></i>
        </div>
        <span v-if="passwordMismatch" class="error">Passwords do not match</span>
      </div>

      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

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
    const errorMessage = ref(''); // For API errors
    const showPassword = ref(false); // For password visibility
    const showConfirmPassword = ref(false); // For confirm password visibility

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

    // Computed properties for password field types and icons
    const passwordFieldType = computed(() => showPassword.value ? 'text' : 'password');
    const passwordToggleIconClass = computed(() => showPassword.value ? 'fas fa-eye-slash' : 'fas fa-eye');

    const confirmPasswordFieldType = computed(() => showConfirmPassword.value ? 'text' : 'password');
    const confirmPasswordToggleIconClass = computed(() => showConfirmPassword.value ? 'fas fa-eye-slash' : 'fas fa-eye');

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };

    const toggleConfirmPasswordVisibility = () => {
      showConfirmPassword.value = !showConfirmPassword.value;
    };

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
      errorMessage.value = ''; // Clear previous errors
      if (!formValid.value) {
        errorMessage.value = 'Please correct the form errors.';
        return;
      }

      try {
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
        }, {
          // Headers for sending JSON and expecting JSON response
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          withCredentials: true // If your API relies on sessions/cookies
        });

        // Assuming your backend responds with a redirect and token on successful registration
        if (response.data.redirect && response.data.token) {
          // Store the token if you need to use it for subsequent authenticated requests
          localStorage.setItem('authToken', response.data.token);
          // Set default Axios header for future requests (optional, based on your auth flow)
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

          alert('Account created successfully!');
          router.push(response.data.redirect); // Redirect to the path provided by the backend (e.g., '/home')
        } else {
            // If the backend doesn't send a token/redirect but indicates success in another way
            alert('Account created successfully, but no direct redirect information received. Redirecting to home.');
            router.push('/home');
        }

      } catch (error) {
        console.error('Signup failed:', error);
        if (error.response) {
          // Handle specific errors from the backend
          console.error('Error response data:', error.response.data);
          errorMessage.value = error.response.data.message || 'Signup failed. Please try again.';
          // You might want to display more specific validation errors if your backend sends them
          if (error.response.data.errors) {
            // Example: If backend sends an 'errors' object with field-specific messages
            let errorDetails = '';
            for (const key in error.response.data.errors) {
              errorDetails += `${key}: ${error.response.data.errors[key].join(', ')}\n`;
            }
            errorMessage.value += '\n' + errorDetails;
          }
        } else if (error.request) {
          // The request was made but no response was received (e.g., network down)
          errorMessage.value = 'Network error. Please check your connection.';
        } else {
          // Something happened in setting up the request that triggered an Error
          errorMessage.value = 'An unexpected error occurred during signup.';
        }
      }
    };

    const goToLogin = () => {
      router.push('/login');
    };

    return {
      form,
      age,
      previewImage,
      errorMessage,
      passwordMismatch,
      formValid,
      passwordFieldType,
      passwordToggleIconClass,
      confirmPasswordFieldType,
      confirmPasswordToggleIconClass,
      checkAge,
      handleFileUpload,
      submitForm,
      goToLogin,
      togglePasswordVisibility,
      toggleConfirmPasswordVisibility,
    };
  },
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'); /* Import Font Awesome */

.signup-container {
  min-height: 100vh;
  background: linear-gradient(to right, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px; /* Add some padding for smaller screens */
  box-sizing: border-box; /* Include padding in element's total width and height */
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
  color: #333;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.3rem;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 0.75rem; /* Slightly more padding */
  border: 1px solid #ddd; /* Lighter border */
  border-radius: 8px;
  box-sizing: border-box; /* Crucial for width: 100% with padding */
}

.full-name {
  display: flex;
  gap: 0.75rem; /* Slightly more space */
  flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

.full-name div {
  flex: 1;
  min-width: 120px; /* Ensure fields don't get too narrow */
}

.error {
  color: #e74c3c; /* A more distinct red */
  font-size: 0.85rem;
  margin-top: 0.2rem;
  display: block; /* Ensures it takes full width */
}

/* New style for password input with eye icon */
.password-input-group {
  position: relative;
  width: 100%;
  display: flex; /* Aligns input and icon */
  align-items: center;
}

.password-input-group input {
  padding-right: 2.5rem; /* Make space for the icon */
  flex-grow: 1; /* Allow input to take available space */
}

.password-input-group i {
  position: absolute;
  right: 1rem;
  cursor: pointer;
  color: #888;
  font-size: 1.1rem;
}

.password-input-group i:hover {
  color: #555;
}

button {
  width: 100%; /* Make buttons full width */
  padding: 0.75rem 1rem; /* Adjust padding */
  font-size: 1.1rem; /* Slightly larger font */
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 8px; /* More rounded corners */
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
  margin-top: 1rem; /* Space above buttons */
}

button:hover {
  background-color: #36a371; /* Darker green on hover */
  transform: translateY(-2px); /* Slight lift effect */
}

button:active {
  background-color: #2e8c61;
  transform: translateY(0);
}

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
  transform: translateY(0);
}

hr {
  border: none;
  border-top: 1px solid #eee;
  margin: 1.5rem 0;
}

p {
  text-align: center;
  color: #666;
  margin-bottom: 0.5rem;
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
  border: 2px solid #eee;
}

/* Error message styling */
.error-message {
  color: #e74c3c;
  background-color: #ffe6e6;
  border: 1px solid #e74c3c;
  padding: 0.75rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  text-align: center;
  font-size: 0.9rem;
}
</style>